<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function landing() {
        return view('landing', [
            'statBahan' => DB::table('master_bahan')->count(),
            'statMenu' => DB::table('paket_menu')->count(),
            'statUser' => DB::table('users')->count()
        ]);
    }

    public function index() {
        if (!session()->has('user_id')) return redirect('/login.php');

        $standarPath = public_path('standar_gizi.json');
        if (!file_exists($standarPath)) {
            $standarGizi = [
                "Kelas Ringan" => ["energi" => 1400, "protein" => 25, "lemak" => 50, "karbo" => 220, "serat" => 20], 
                "Kelas Berat" => ["energi" => 2375, "protein" => 70, "lemak" => 78, "karbo" => 350, "serat" => 33]
            ];
            file_put_contents($standarPath, json_encode($standarGizi));
        } else {
            $standarGizi = json_decode(file_get_contents($standarPath), true);
        }

        return view('dashboard', [
            'role' => session('role'),
            'standarGizi' => $standarGizi,
            'master_bahan' => DB::table('master_bahan')->orderByDesc('id')->get()->map(fn($item) => (array)$item)->toArray(),
            'countBahan' => DB::table('master_bahan')->count(),
            'countMenu' => DB::table('paket_menu')->count(),
            'dataLine' => array_reverse(DB::table('paket_menu')->select('nama_paket', 'total_energi')->orderByDesc('id')->limit(7)->get()->map(fn($i)=>(array)$i)->toArray()),
            'dataPie' => (array) DB::table('paket_menu')->selectRaw('AVG(total_protein) as avg_p, AVG(total_lemak) as avg_l, AVG(total_karbohidrat) as avg_k')->first(),
            'allMenus' => DB::table('paket_menu')->orderByDesc('tanggal_menu')->orderByDesc('id')->get()->map(fn($i)=>(array)$i)->toArray()
        ]);
    }

    public function getDetailMenu(Request $request) {
        $id = $request->query('id', 0);
        $details = DB::table('detail_menu as dm')->join('master_bahan as mb', 'dm.bahan_id', '=', 'mb.id')->where('dm.paket_id', $id)->select('dm.takaran_gram', 'mb.nama_bahan', 'mb.energi')->get();
        if ($details->count() > 0) {
            echo '<table style="width:100%; border-collapse:collapse;"><tr style="background:#f8fafc"><th style="padding:10px">Bahan</th><th style="padding:10px">Takaran</th><th style="padding:10px">Energi (Kkal)</th></tr>';
            foreach ($details as $d) {
                echo '<tr><td style="padding:10px; border-bottom:1px solid #f1f5f9">' . htmlspecialchars($d->nama_bahan) . '</td><td style="padding:10px; border-bottom:1px solid #f1f5f9">' . $d->takaran_gram . ' g</td><td style="padding:10px; border-bottom:1px solid #f1f5f9">' . number_format(($d->takaran_gram / 100) * $d->energi, 1) . '</td></tr>';
            }
            echo '</table>';
        } else { echo '<p>Resep tidak ditemukan.</p>'; }
    }

    public function simpanMenu(Request $request) {
        DB::beginTransaction();
        try {
            $totE = 0; $totP = 0; $totL = 0; $totK = 0; $totS = 0;
            $detail_data = [];
            foreach ($request->bahan_id as $index => $id) {
                $gram = (float)($request->takaran_gram[$index]);
                if ($id <= 0 || $gram <= 0) continue;
                $b = DB::table('master_bahan')->where('id', $id)->first();
                if ($b) {
                    $factor = $gram / 100;
                    $totE += $factor * $b->energi; $totP += $factor * $b->protein; $totL += $factor * $b->lemak; $totK += $factor * $b->karbohidrat; $totS += $factor * $b->serat;
                    $detail_data[] = ['bahan_id' => $id, 'gram' => $gram];
                }
            }
            $paket_id = DB::table('paket_menu')->insertGetId([
                'nama_paket' => trim($request->nama_paket), 'tanggal_menu' => $request->tanggal_menu, 'kategori_sasaran' => $request->kategori_sasaran,
                'total_energi' => $totE, 'total_protein' => $totP, 'total_lemak' => $totL, 'total_karbohidrat' => $totK, 'total_serat' => $totS, 'status_menu' => 'Aktif'
            ]);
            foreach ($detail_data as $dt) {
                DB::table('detail_menu')->insert(['paket_id' => $paket_id, 'bahan_id' => $dt['bahan_id'], 'takaran_gram' => $dt['gram']]);
            }
            DB::commit();
            echo "<script>alert('Menu Berhasil Disimpan!'); window.location.href = 'index.php';</script>";
        } catch (\Exception $e) { DB::rollBack(); die("<script>alert('Gagal disimpan'); window.history.back();</script>"); }
    }

    public function simpanBahanManual(Request $request) {
        DB::table('master_bahan')->insert(['nama_bahan' => trim($request->nama_bahan), 'kategori' => $request->kategori, 'energi' => (float)$request->energi, 'protein' => (float)$request->protein, 'lemak' => (float)$request->lemak, 'karbohidrat' => (float)$request->karbohidrat, 'serat' => (float)$request->serat]);
        echo "<script>alert('Bahan berhasil ditambahkan!'); window.location.href='index.php';</script>";
    }

    public function editBahan(Request $request) {
        DB::table('master_bahan')->where('id', $request->id)->update(['nama_bahan' => $request->nama_bahan, 'kategori' => $request->kategori, 'energi' => $request->energi, 'protein' => $request->protein, 'lemak' => $request->lemak, 'karbohidrat' => $request->karbohidrat, 'serat' => $request->serat ?? 0]);
        echo "<script>alert('Data bahan berhasil diperbarui!'); window.location.href='index.php';</script>";
    }

    public function hapusBahan(Request $request) {
        DB::table('master_bahan')->where('id', $request->query('id'))->delete();
        echo "<script>alert('Bahan berhasil dihapus!'); window.location.href='index.php';</script>";
    }

    public function hapusMenu(Request $request) {
        DB::table('paket_menu')->where('id', $request->query('id'))->delete();
        echo "<script>alert('Paket Menu berhasil dihapus!'); window.location.href='index.php';</script>";
    }

    public function cetakPdf(Request $request) {
        $id = $request->query('id', 0);
        
        $menu = (array) DB::table('paket_menu')->where('id', $id)->first();
        if (!$menu) die("Menu tidak ditemukan.");

        $details = DB::table('detail_menu as dm')
            ->join('master_bahan as mb', 'dm.bahan_id', '=', 'mb.id')
            ->where('dm.paket_id', $id)
            ->select('dm.takaran_gram', 'mb.*')
            ->get()
            ->map(fn($i) => (array)$i)
            ->toArray();

        $summary = []; 
        foreach ($details as $d) { 
            $summary[$d['kategori']][] = $d['nama_bahan']; 
        }

        return view('cetak_pdf', compact('menu', 'details', 'summary'));
    }

    public function exportExcel(Request $request) {
        $id = $request->query('id', 0);
        
        $menu = (array) DB::table('paket_menu')->where('id', $id)->first();
        if (!$menu) die("Menu tidak ditemukan.");

        $details = DB::table('detail_menu as dm')
            ->join('master_bahan as mb', 'dm.bahan_id', '=', 'mb.id')
            ->where('dm.paket_id', $id)
            ->select('dm.takaran_gram', 'mb.*')
            ->get()
            ->map(fn($i) => (array)$i)
            ->toArray();

        $summary = []; 
        foreach ($details as $d) { 
            $summary[$d['kategori']][] = $d['nama_bahan']; 
        }

        $filename = "Laporan_Gizi_" . str_replace(' ', '_', $menu['nama_paket']) . "_" . date('Ymd') . ".xls";

        return response()
            ->view('export_excel', compact('menu', 'details', 'summary'))
            ->header('Content-Type', 'application/vnd.ms-excel')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function uploadJson(Request $request) {
        if ($request->hasFile('jsonFile')) {
            $request->file('jsonFile')->move(public_path('uploads'), 'data_master.json');
            return redirect('/import_db.php');
        }
        echo "<script>alert('Gagal upload file.'); window.history.back();</script>";
    }

    public function importDb() {
        $filePath = public_path('uploads/data_master.json');
        if (!file_exists($filePath)) die("File JSON tidak ditemukan.");
        $dataArray = json_decode(file_get_contents($filePath), true);
        $berhasil = 0; $gagal = 0;
        DB::statement("SET FOREIGN_KEY_CHECKS = 0;"); DB::table('master_bahan')->truncate(); DB::statement("SET FOREIGN_KEY_CHECKS = 1;");
        foreach ($dataArray as $row) {
            $nama = trim($row['Nama Bahan'] ?? '');
            if (empty($nama)) { $gagal++; continue; }
            DB::table('master_bahan')->insert(['nama_bahan' => $nama, 'kategori' => trim($row['Kategori'] ?? '-'), 'energi' => (float)($row['Energi'] ?? 0), 'protein' => (float)($row['Protein'] ?? 0), 'lemak' => (float)($row['Lemak'] ?? 0), 'karbohidrat' => (float)($row['Karbohidrat'] ?? 0), 'serat' => (float)($row['Serat'] ?? 0)]);
            $berhasil++;
        }
        if(file_exists($filePath)) unlink($filePath);
        echo "<script>alert('Impor Selesai! Berhasil: $berhasil data.'); window.location.href = 'index.php';</script>";
    }
}