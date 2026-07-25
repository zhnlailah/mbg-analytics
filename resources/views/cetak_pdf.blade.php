<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Harian Ahli Gizi - <?= htmlspecialchars($menu['nama_paket']) ?></title>
    <style>
        body { 
            font-family: "Times New Roman", Times, serif; 
            padding: 40px; 
            background-color: #fff; 
            color: #000;
            line-height: 1.4;
        }
        .container { max-width: 800px; margin: auto; }
        .title { text-align: center; font-weight: bold; font-size: 14pt; text-decoration: underline; margin-bottom: 20px; }
        
        .header-info { margin-bottom: 20px; font-size: 11pt; }
        .header-info table { border: none; }
        .header-info td { vertical-align: top; padding: 2px 5px; }

        .data-table { width: 100%; border-collapse: collapse; margin-top: 10px; font-size: 10pt; }
        .data-table th, .data-table td { border: 1px solid #000; padding: 6px; text-align: center; }
        .data-table th { background-color: #f2f2f2; }
        .text-left { text-align: left !important; }
        
        .footer-sign { margin-top: 50px; width: 100%; }
        .footer-sign td { width: 50%; text-align: center; vertical-align: top; font-size: 11pt; }
        .signature-space { height: 80px; }

        .no-print-btn {
            position: fixed; top: 20px; right: 20px;
            padding: 10px 20px; background: #4f46e5; color: white;
            border: none; border-radius: 5px; cursor: pointer; font-weight: bold;
        }

        @media print {
            .no-print-btn { display: none; }
            body { padding: 0; }
            @page { margin: 1cm; }
        }
    </style>
</head>
<body>

    <button class="no-print-btn" onclick="window.print()">🖨️ CETAK / SIMPAN PDF</button>

    <div class="container">
        <div class="title">LAPORAN HARIAN AHLI GIZI</div>

        <div class="header-info">
            <table>
                <tr>
                    <td>Hari, Tanggal</td>
                    <td>:</td>
                    <td><?= date('l, d F Y', strtotime($menu['tanggal_menu'])) ?></td>
                </tr>
                <tr>
                    <td>Jumlah Porsi</td>
                    <td>:</td>
                    <td>1 Porsi</td>
                </tr>
                <tr>
                    <td>Menu</td>
                    <td>:</td>
                    <td>
                        <strong>Karbohidrat :</strong> <?= implode(', ', $summary['Karbohidrat'] ?? ['-']) ?><br>
                        <strong>Protein Hewani :</strong> <?= implode(', ', $summary['Protein Hewani'] ?? ['-']) ?><br>
                        <strong>Protein Nabati :</strong> <?= implode(', ', $summary['Protein Nabati'] ?? ['-']) ?><br>
                        <strong>Sayur :</strong> <?= implode(', ', $summary['Sayur'] ?? ['-']) ?><br>
                        <strong>Buah :</strong> <?= implode(', ', $summary['Buah'] ?? ['-']) ?>
                    </td>
                </tr>
            </table>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th width="20%">Kategori</th>
                    <th width="30%">Menu</th>
                    <th>Gram</th>
                    <th>Energi (kkal)</th>
                    <th>Protein (g)</th>
                    <th>Lemak (g)</th>
                    <th>Karbo (g)</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $first = true;
                foreach ($details as $row): 
                    $f = $row['takaran_gram'] / 100;
                ?>
                <tr>
                    <?php if($first): ?>
                        <td rowspan="<?= count($details) ?>">
                            <strong>Porsi <?= $menu['kategori_sasaran'] ?></strong><br>
                            (<?= ($menu['kategori_sasaran'] == 'Kelas Berat') ? 'SMA dan POSYANDU' : 'TK' ?>)
                        </td>
                    <?php $first = false; endif; ?>
                    
                    <td class="text-left"><?= htmlspecialchars($row['nama_bahan']) ?></td>
                    <td><?= $row['takaran_gram'] ?></td>
                    <td><?= number_format($row['energi'] * $f, 1) ?></td>
                    <td><?= number_format($row['protein'] * $f, 1) ?></td>
                    <td><?= number_format($row['lemak'] * $f, 1) ?></td>
                    <td><?= number_format($row['karbohidrat'] * $f, 1) ?></td>
                </tr>
                <?php endforeach; ?>
                
                <tr style="font-weight: bold; background: #f9f9f9;">
                    <td colspan="2">Total Kandungan Gizi</td>
                    <td>-</td>
                    <td><?= number_format($menu['total_energi'], 1) ?></td>
                    <td><?= number_format($menu['total_protein'], 1) ?></td>
                    <td><?= number_format($menu['total_lemak'], 1) ?></td>
                    <td><?= number_format($menu['total_karbohidrat'], 1) ?></td>
                </tr>
            </tbody>
        </table>

        <table class="footer-sign">
            <tr>
                <td>
                    Menyusun<br>Ahli Gizi
                    <div class="signature-space"></div>
                    <strong><u>Salsabila Sakinah, S.Gz.</u></strong>
                </td>
                <td>
                    Menyetujui<br>Kepala SPPG
                    <div class="signature-space"></div>
                    <strong><u>Irsyad Rahmadi, S.Tr.A.P.</u></strong>
                </td>
            </tr>
        </table>
    </div>

    <script>
        // Memicu dialog print otomatis
        window.onload = function() {
            // window.print(); // Aktifkan jika ingin langsung muncul dialog print
        };
    </script>
</body>
</html>