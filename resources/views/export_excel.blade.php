<html xmlns:x="urn:schemas-microsoft-com:office:excel">
<head>
    <meta http-equiv="content-type" content="application/vnd.ms-excel; charset=UTF-8">
    <style>
        .title { font-size: 14pt; font-weight: bold; text-align: center; }
        .label { font-weight: bold; }
        .border { border: 1px solid #000; }
        .header-table td { vertical-align: top; }
        th { background-color: #d1d5db; border: 1px solid #000; }
        td { border: 1px solid #000; vertical-align: middle; }
        .text-center { text-align: center; }
        .num { mso-number-format:"\#\,\#\#0\.0"; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="7" class="title">LAPORAN HARIAN AHLI GIZI</td>
        </tr>
        <tr><td colspan="7"></td></tr> <tr>
            <td class="label">Hari, Tanggal</td>
            <td>: <?= date('l, d F Y', strtotime($menu['tanggal_menu'])) ?></td>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td class="label">Jumlah Porsi</td>
            <td>: 1 Porsi</td>
            <td colspan="5"></td>
        </tr>
        <tr>
            <td class="label" valign="top">Menu</td>
            <td colspan="6">
                Karbohidrat: <?= implode(', ', $summary['Karbohidrat'] ?? ['-']) ?><br>
                Protein Hewani: <?= implode(', ', $summary['Protein Hewani'] ?? ['-']) ?><br>
                Protein Nabati: <?= implode(', ', $summary['Protein Nabati'] ?? ['-']) ?><br>
                Sayur: <?= implode(', ', $summary['Sayur'] ?? ['-']) ?><br>
                Buah: <?= implode(', ', $summary['Buah'] ?? ['-']) ?>
            </td>
        </tr>
        <tr><td colspan="7"></td></tr>

        <thead>
            <tr>
                <th width="150">Kategori</th>
                <th width="200">Menu / Bahan</th>
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
                    <td rowspan="<?= count($details) ?>" class="text-center">
                        Porsi <?= $menu['kategori_sasaran'] ?><br>
                        (<?= ($menu['kategori_sasaran'] == 'Kelas Berat') ? 'SMA/Posyandu' : 'TK' ?>)
                    </td>
                <?php $first = false; endif; ?>
                
                <td><?= htmlspecialchars($row['nama_bahan']) ?></td>
                <td class="text-center"><?= $row['takaran_gram'] ?></td>
                <td class="num text-center"><?= number_format($row['energi'] * $f, 1) ?></td>
                <td class="num text-center"><?= number_format($row['protein'] * $f, 1) ?></td>
                <td class="num text-center"><?= number_format($row['lemak'] * $f, 1) ?></td>
                <td class="num text-center"><?= number_format($row['karbohidrat'] * $f, 1) ?></td>
            </tr>
            <?php endforeach; ?>
            
            <tr style="font-weight: bold; background-color: #f3f4f6;">
                <td colspan="2" class="text-center">TOTAL KANDUNGAN GIZI</td>
                <td class="text-center">-</td>
                <td class="num text-center"><?= number_format($menu['total_energi'], 1) ?></td>
                <td class="num text-center"><?= number_format($menu['total_protein'], 1) ?></td>
                <td class="num text-center"><?= number_format($menu['total_lemak'], 1) ?></td>
                <td class="num text-center"><?= number_format($menu['total_karbohidrat'], 1) ?></td>
            </tr>
        </tbody>
    </table>

    <br>
    <table>
        <tr>
            <td colspan="2" class="text-center">Menyusun,<br>Ahli Gizi</td>
            <td colspan="3"></td>
            <td colspan="2" class="text-center">Menyetujui,<br>Kepala SPPG</td>
        </tr>
        <tr><td colspan="7" height="50"></td></tr> <tr>
            <td colspan="2" class="text-center"><b><u>Salsabila Sakinah, S.Gz.</u></b></td>
            <td colspan="3"></td>
            <td colspan="2" class="text-center"><b><u>Irsyad Rahmadi, S.Tr.A.P.</u></b></td>
        </tr>
    </table>
</body>
</html>