<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Membaca file CSV/XLSX bahan makanan dan memvalidasi setiap barisnya.
 * Tidak menyimpan apa pun ke database; penyimpanan dilakukan oleh controller.
 */
class BahanFileImporter
{
    const KATEGORI = ['Karbohidrat', 'Protein Hewani', 'Protein Nabati', 'Sayur', 'Buah', 'Lemak'];
    const FIELDS = ['nama_bahan', 'kategori', 'energi', 'protein', 'lemak', 'karbohidrat'];
    const NUMERIC = ['energi', 'protein', 'lemak', 'karbohidrat'];
    const LABELS = ['nama_bahan' => 'Nama Bahan', 'kategori' => 'Kategori', 'energi' => 'Energi', 'protein' => 'Protein', 'lemak' => 'Lemak', 'karbohidrat' => 'Karbohidrat'];
    const ALIASES = ['karbo' => 'karbohidrat'];
    const MAX_ROWS = 5000;
    const MAX_XLSX_UNCOMPRESSED = 50 * 1024 * 1024;

    /** Pesan kesalahan tingkat file (bukan per baris). */
    public ?string $fileError = null;
    public ?string $sheetName = null;

    /**
     * @return array{rows: array, summary: array}|null  null jika file tidak bisa dibaca ($fileError terisi)
     */
    public function process(string $path, string $ext): ?array
    {
        $raw = $ext === 'xlsx' ? $this->readXlsx($path) : $this->readCsv($path);
        if ($raw === null) return null;

        // Baris pertama yang tidak kosong adalah header.
        $header = null;
        $dataRows = [];
        foreach ($raw as $r) {
            if ($this->isEmptyRow($r['cells'])) continue;
            if ($header === null) { $header = $r; continue; }
            $dataRows[] = $r;
        }
        if ($header === null) return $this->fail('File kosong. Pastikan file berisi header dan data bahan.');
        if (count($dataRows) === 0) return $this->fail('File hanya berisi header, belum ada data bahan.');
        if (count($dataRows) > self::MAX_ROWS) {
            return $this->fail('Jumlah data melebihi batas ' . number_format(self::MAX_ROWS, 0, ',', '.') . ' baris (terbaca ' . number_format(count($dataRows), 0, ',', '.') . ' baris). Bagi file menjadi beberapa bagian.');
        }

        $map = [];
        foreach ($header['cells'] as $col => $cell) {
            $key = $this->normalizeHeader((string) $cell['v']);
            $key = self::ALIASES[$key] ?? $key;
            if (!in_array($key, self::FIELDS, true)) continue; // kolom tambahan (termasuk Serat) diabaikan
            if (isset($map[$key])) return $this->fail('Kolom "' . self::LABELS[$key] . '" muncul lebih dari satu kali pada header.');
            $map[$key] = $col;
        }
        $missing = array_diff(self::FIELDS, array_keys($map));
        if ($missing) {
            return $this->fail('Kolom wajib tidak ditemukan: ' . implode(', ', array_map(fn($f) => self::LABELS[$f], $missing)) . '.');
        }

        $limits = $this->columnLimits();
        $existing = [];
        foreach (DB::table('master_bahan')->pluck('nama_bahan') as $n) {
            $existing[mb_strtolower(trim((string) $n))] = true;
        }

        $rows = [];
        $seen = [];
        $sum = ['total' => 0, 'valid' => 0, 'invalid' => 0, 'duplikat' => 0];
        foreach ($dataRows as $r) {
            $errors = [];
            $duplicate = false;
            $values = [];
            $formula = [];
            foreach (self::FIELDS as $f) {
                $cell = $r['cells'][$map[$f]] ?? ['v' => '', 'f' => false];
                if ($cell['f']) {
                    $errors[] = self::LABELS[$f] . ' berisi rumus; isi dengan nilai langsung.';
                    $values[$f] = '';
                    $formula[$f] = true;
                    continue;
                }
                $values[$f] = trim((string) $cell['v']);
            }

            if ($values['nama_bahan'] === '') {
                $errors[] = 'Nama Bahan wajib diisi.';
            } elseif (mb_strlen($values['nama_bahan']) > $limits['nama_bahan']) {
                $errors[] = 'Nama Bahan maksimal ' . $limits['nama_bahan'] . ' karakter.';
            }

            if ($values['kategori'] === '') {
                $errors[] = 'Kategori wajib diisi.';
            } else {
                $match = null;
                foreach (self::KATEGORI as $k) {
                    if (mb_strtolower($k) === mb_strtolower($values['kategori'])) $match = $k;
                }
                if ($match === null) $errors[] = 'Kategori "' . $values['kategori'] . '" tidak dikenal. Pilihan: ' . implode(', ', self::KATEGORI) . '.';
                else $values['kategori'] = $match;
            }

            foreach (self::NUMERIC as $f) {
                if (isset($formula[$f])) continue;
                [$num, $err] = $this->parseNumber($values[$f], self::LABELS[$f], $limits[$f], $r['xlsxNumeric'][$map[$f]] ?? false);
                if ($err) $errors[] = $err; else $values[$f] = $num;
            }

            if ($values['nama_bahan'] !== '') {
                $key = mb_strtolower($values['nama_bahan']);
                if (isset($existing[$key])) {
                    $duplicate = true;
                    $errors[] = 'Bahan "' . $values['nama_bahan'] . '" sudah ada di database.';
                }
                if (isset($seen[$key])) {
                    $duplicate = true;
                    $errors[] = 'Nama Bahan sama dengan baris ' . $seen[$key] . ' pada file.';
                } else {
                    $seen[$key] = $r['line'];
                }
            }

            $sum['total']++;
            if ($duplicate) $sum['duplikat']++;
            if ($errors) $sum['invalid']++; else $sum['valid']++;
            $rows[] = ['baris' => $r['line'], 'data' => $values, 'valid' => !$errors, 'duplikat' => $duplicate, 'errors' => $errors];
        }

        return ['rows' => $rows, 'summary' => $sum];
    }

    private function fail(string $msg): ?array
    {
        $this->fileError = $msg;
        return null;
    }

    private function isEmptyRow(array $cells): bool
    {
        foreach ($cells as $c) {
            if (trim((string) $c['v']) !== '') return false;
        }
        return true;
    }

    private function normalizeHeader(string $h): string
    {
        $h = preg_replace('/^\xEF\xBB\xBF/', '', $h);
        $h = mb_strtolower(trim($h));
        return preg_replace('/[\s_]+/u', '_', $h);
    }

    /**
     * Mengubah teks angka menjadi nilai float. Titik atau koma tunggal dianggap pemisah desimal.
     * Format dengan beberapa pemisah atau yang menyerupai pemisah ribuan dilaporkan sebagai ambigu.
     * @return array{0: ?float, 1: ?string}
     */
    private function parseNumber(string $v, string $label, array $limit, bool $fromXlsxNumber): array
    {
        if ($v === '') return [null, $label . ' wajib diisi.'];

        if ($fromXlsxNumber) {
            // Nilai numerik asli XLSX selalu memakai titik dan bisa berbentuk eksponen.
            if (!is_numeric($v)) return [null, $label . ' bukan angka yang valid.'];
            $num = (float) $v;
            $normalized = rtrim(rtrim(sprintf('%.10F', $num), '0'), '.');
        } else {
            if (preg_match('/^-/', $v)) return [null, $label . ' tidak boleh bernilai negatif.'];
            if (!preg_match('/^\d+([.,]\d+)?$/', $v)) {
                return [null, $label . ' "' . $v . '" bukan angka yang valid. Gunakan angka dengan satu pemisah desimal (contoh: 12.5 atau 12,5) tanpa pemisah ribuan.'];
            }
            if (preg_match('/^[1-9]\d{0,2}[.,]\d{3}$/', $v)) {
                return [null, $label . ' "' . $v . '" ambigu (bisa berarti ribuan atau desimal). Tulis tanpa pemisah ribuan, atau gunakan jumlah angka desimal selain tiga.'];
            }
            $normalized = str_replace(',', '.', $v);
            $num = (float) $normalized;
        }

        if ($num < 0) return [null, $label . ' tidak boleh bernilai negatif.'];
        if ($limit['scale'] !== null) {
            $dec = str_contains($normalized, '.') ? strlen(substr($normalized, strpos($normalized, '.') + 1)) : 0;
            if ($dec > $limit['scale']) return [null, $label . ' maksimal ' . $limit['scale'] . ' angka di belakang koma.'];
        }
        if ($limit['max'] !== null && $num > $limit['max']) {
            return [null, $label . ' terlalu besar (maksimal ' . $limit['max'] . ').'];
        }
        return [$num, null];
    }

    /** Membaca batas panjang/presisi kolom dari struktur tabel yang ada. */
    private function columnLimits(): array
    {
        $limits = ['nama_bahan' => 255];
        foreach (self::NUMERIC as $f) $limits[$f] = ['scale' => null, 'max' => null];
        try {
            foreach (Schema::getColumns('master_bahan') as $col) {
                $type = strtolower($col['type'] ?? '');
                if ($col['name'] === 'nama_bahan' && preg_match('/char\((\d+)\)/', $type, $m)) {
                    $limits['nama_bahan'] = (int) $m[1];
                }
                if (in_array($col['name'], self::NUMERIC, true) && preg_match('/(decimal|numeric)\((\d+),\s*(\d+)\)/', $type, $m)) {
                    $p = (int) $m[2]; $s = (int) $m[3];
                    $limits[$col['name']] = ['scale' => $s, 'max' => (float) (str_repeat('9', max($p - $s, 0) ?: 1) . ($s ? '.' . str_repeat('9', $s) : ''))];
                }
            }
        } catch (\Throwable $e) {
            // Struktur tidak terbaca: gunakan batas bawaan.
        }
        return $limits;
    }

    /* ---------------------------- CSV ---------------------------- */

    private function readCsv(string $path): ?array
    {
        $content = file_get_contents($path);
        if ($content === false) return $this->fail('File tidak dapat dibaca.');
        if (str_starts_with($content, "PK\x03\x04") || str_contains($content, "\0")) {
            return $this->fail('Isi file bukan CSV teks. Pastikan file disimpan sebagai CSV (UTF-8).');
        }
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content);
        if (!mb_check_encoding($content, 'UTF-8')) {
            return $this->fail('File CSV harus menggunakan encoding UTF-8. Simpan ulang file sebagai "CSV UTF-8".');
        }

        // Tentukan pemisah dari baris header (di luar tanda kutip).
        $firstLine = strtok($content, "\r\n") ?: '';
        $plain = preg_replace('/"[^"]*"/', '', $firstLine);
        $delimiter = substr_count($plain, ';') > substr_count($plain, ',') ? ';' : ',';

        $fh = fopen('php://temp', 'r+');
        fwrite($fh, $content);
        rewind($fh);
        $rows = [];
        $line = 0;
        $filled = 0;
        while (($data = fgetcsv($fh, 0, $delimiter, '"', '')) !== false) {
            $line++;
            if ($data === [null]) continue;
            $cells = [];
            foreach ($data as $i => $v) $cells[$i] = ['v' => (string) $v, 'f' => false];
            $rows[] = ['line' => $line, 'cells' => $cells];
            if (!$this->isEmptyRow($cells) && ++$filled > self::MAX_ROWS + 1) break; // header + batas data
        }
        fclose($fh);
        if ($filled > self::MAX_ROWS + 1) {
            return $this->fail('Jumlah data melebihi batas ' . number_format(self::MAX_ROWS, 0, ',', '.') . ' baris. Bagi file menjadi beberapa bagian.');
        }
        return $rows;
    }

    /* ---------------------------- XLSX ---------------------------- */

    private function readXlsx(string $path): ?array
    {
        if (!class_exists(\ZipArchive::class)) return $this->fail('Server belum mendukung pembacaan file XLSX. Gunakan file CSV.');
        $head = file_get_contents($path, false, null, 0, 4);
        $zip = new \ZipArchive();
        if ($head !== "PK\x03\x04" || $zip->open($path, \ZipArchive::RDONLY) !== true) {
            return $this->fail('Isi file bukan XLSX yang valid. Simpan ulang file sebagai "Excel Workbook (.xlsx)".');
        }
        try {
            $total = 0;
            for ($i = 0; $i < $zip->numFiles; $i++) $total += $zip->statIndex($i)['size'] ?? 0;
            if ($total > self::MAX_XLSX_UNCOMPRESSED) return $this->fail('Isi file XLSX terlalu besar untuk diproses.');

            $workbook = $this->xml($zip, 'xl/workbook.xml');
            $rels = $this->xml($zip, 'xl/_rels/workbook.xml.rels');
            if (!$workbook || !$rels) return $this->fail('Isi file bukan XLSX yang valid. Simpan ulang file sebagai "Excel Workbook (.xlsx)".');

            $workbook->registerXPathNamespace('m', 'http://schemas.openxmlformats.org/spreadsheetml/2006/main');
            $sheets = $workbook->xpath('//m:sheets/m:sheet');
            if (!$sheets) return $this->fail('File XLSX tidak memiliki worksheet.');
            $first = $sheets[0];
            $this->sheetName = (string) $first['name'];
            $rid = (string) $first->attributes('http://schemas.openxmlformats.org/officeDocument/2006/relationships')['id'];

            $target = null;
            foreach ($rels->children() as $rel) {
                if ((string) $rel['Id'] === $rid) $target = (string) $rel['Target'];
            }
            if ($target === null) return $this->fail('Worksheet pertama tidak dapat dibaca.');
            $target = str_starts_with($target, '/') ? ltrim($target, '/') : 'xl/' . $target;

            $shared = [];
            $ss = $this->xml($zip, 'xl/sharedStrings.xml');
            if ($ss) {
                foreach ($ss->si as $si) $shared[] = $this->richText($si);
            }

            $sheet = $this->xml($zip, $target);
            if (!$sheet || !isset($sheet->sheetData)) return $this->fail('Worksheet pertama tidak dapat dibaca.');

            $rows = [];
            $filled = 0;
            $lineGuess = 0;
            foreach ($sheet->sheetData->row as $row) {
                $line = isset($row['r']) ? (int) $row['r'] : $lineGuess + 1;
                $lineGuess = $line;
                $cells = [];
                $numeric = [];
                $colGuess = -1;
                foreach ($row->c as $c) {
                    $col = isset($c['r']) ? $this->colIndex((string) $c['r']) : $colGuess + 1;
                    $colGuess = $col;
                    $t = (string) ($c['t'] ?? 'n');
                    $isFormula = isset($c->f);
                    if ($t === 's') $v = $shared[(int) $c->v] ?? '';
                    elseif ($t === 'inlineStr') $v = isset($c->is) ? $this->richText($c->is) : '';
                    else $v = (string) ($c->v ?? '');
                    $cells[$col] = ['v' => $v, 'f' => $isFormula];
                    if ($t === 'n' && !$isFormula && $v !== '') $numeric[$col] = true;
                }
                $rows[] = ['line' => $line, 'cells' => $cells, 'xlsxNumeric' => $numeric];
                if (!$this->isEmptyRow($cells) && ++$filled > self::MAX_ROWS + 1) {
                    return $this->fail('Jumlah data melebihi batas ' . number_format(self::MAX_ROWS, 0, ',', '.') . ' baris. Bagi file menjadi beberapa bagian.');
                }
            }
            return $rows;
        } finally {
            $zip->close();
        }
    }

    private function xml(\ZipArchive $zip, string $name): ?\SimpleXMLElement
    {
        $data = $zip->getFromName($name);
        if ($data === false) return null;
        $prev = libxml_use_internal_errors(true);
        $xml = simplexml_load_string($data, \SimpleXMLElement::class, LIBXML_NONET | LIBXML_COMPACT);
        libxml_clear_errors();
        libxml_use_internal_errors($prev);
        return $xml ?: null;
    }

    private function richText(\SimpleXMLElement $el): string
    {
        if (isset($el->t)) return (string) $el->t;
        $out = '';
        foreach ($el->r as $r) $out .= (string) $r->t;
        return $out;
    }

    private function colIndex(string $ref): int
    {
        $letters = preg_replace('/\d+/', '', strtoupper($ref));
        $n = 0;
        foreach (str_split($letters) as $ch) $n = $n * 26 + (ord($ch) - 64);
        return $n - 1;
    }
}
