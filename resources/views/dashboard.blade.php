<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>MBG Analytics | Dashboard Modern</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    @php $role = session('role'); @endphp
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background: linear-gradient(135deg, #f5f7fe 0%, #eef2f9 100%); min-height: 100vh; overflow-x: hidden; color: #1e293b; }
        @keyframes scaleIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        
        .app-container { display: flex; min-height: 100vh; }
        
        /* SIDEBAR */
        .sidebar { width: 280px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border-right: 1px solid rgba(0,0,0,0.05); padding: 2rem 1.5rem; position: fixed; height: 100vh; z-index: 100; box-shadow: 4px 0 20px rgba(0,0,0,0.02); display: flex; flex-direction: column; transition: 0.3s; }
        .logo-area { display: flex; align-items: center; gap: 12px; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid rgba(99, 102, 241, 0.2); }
        .logo-img { width: 42px; height: 42px; object-fit: contain; }
        .logo-text { font-size: 1.1rem; font-weight: 700; color: #4f46e5; line-height: 1.2; }
        .nav-item { display: flex; align-items: center; gap: 14px; padding: 12px 18px; margin: 6px 0; border-radius: 12px; color: #475569; font-weight: 500; transition: 0.3s; cursor: pointer; text-decoration: none; }
        .nav-item i { width: 20px; font-size: 1.2rem; }
        .nav-item:hover { background: rgba(99,102,241,0.08); color: #4f46e5; transform: translateX(5px); }
        .nav-item.active { background: linear-gradient(95deg, rgba(99,102,241,0.12), rgba(139,92,246,0.05)); color: #4f46e5; font-weight: 600; border-left: 3px solid #6366f1; }
        
        /* MAIN CONTENT */
        .main-content { flex: 1; margin-left: 280px; padding: 2rem; transition: 0.3s; }
        .top-bar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; }
        .page-title h1 { font-size: 1.6rem; font-weight: 700; }
        .admin-profile { display: flex; align-items: center; gap: 15px; background: white; padding: 8px 20px; border-radius: 40px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); cursor: pointer; transition: 0.2s; }
        .admin-profile:hover { transform: scale(1.02); }
        .avatar { width: 40px; height: 40px; background: linear-gradient(145deg, #6366f1, #a855f7); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; }
        
        /* CARDS & GRIDS */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card { background: white; border-radius: 20px; padding: 1.5rem; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 15px rgba(0,0,0,0.02); }
        .stat-number { font-size: 2rem; font-weight: 800; color: #0f172a; }
        .charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem; }
        .card { background: white; border-radius: 20px; padding: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.02); margin-bottom: 2rem; }
        .card-header { display: flex; justify-content: space-between; margin-bottom: 1.2rem; font-weight: 600; font-size: 1.1rem; }
        
        /* TABLES & FORMS */
        .table-wrapper { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th { text-align: left; padding: 12px; background: #f8fafc; color: #64748b; font-weight: 600; font-size: 0.85rem; text-transform: uppercase; }
        td { padding: 12px; border-bottom: 1px solid #eef2ff; color: #1e293b; font-size: 0.95rem; }
        .badge { background: #e0e7ff; color: #4f46e5; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        
        /* BUTTONS */
        .btn-modern { background: linear-gradient(135deg, #4f46e5, #7c3aed); color: white; border: none; padding: 10px 20px; border-radius: 10px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; font-size: 0.9rem; transition: 0.2s; }
        .btn-modern:hover { opacity: 0.9; }
        .btn-detail { background: #f1f5f9; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; color: #4f46e5; font-weight: 600; transition: 0.2s; }
        .btn-detail:hover { background: #e2e8f0; }
        .btn-hapus { background: #fee2e2; border: none; padding: 8px 12px; border-radius: 8px; cursor: pointer; color: #ef4444; font-weight: 600; transition: 0.2s; }
        .btn-hapus:hover { background: #fecaca; }
        
        /* MODAL */
        .modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background: rgba(15,23,42,0.6); backdrop-filter: blur(4px); }
        .modal-content { background: white; margin: 5% auto; padding: 2rem; border-radius: 20px; width: 90%; max-width: 600px; position: relative; animation: scaleIn 0.3s; }
        .close-modal { position: absolute; right: 20px; top: 20px; font-size: 1.5rem; cursor: pointer; color: #94a3b8; }

        @media (max-width: 768px) { 
            .sidebar { transform: translateX(-100%); } 
            .sidebar.open { transform: translateX(0); } 
            .main-content { margin-left: 0; padding: 1rem; } 
            .charts-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

<div id="menuToggle" style="display:none; position:fixed; top:1rem; left:1rem; z-index:200; background:white; padding:10px; border-radius:10px; cursor:pointer; box-shadow:0 2px 10px rgba(0,0,0,0.1);">
    <i class="fas fa-bars" style="color:#4f46e5; font-size: 1.2rem;"></i>
</div>

<div class="app-container">
    <aside class="sidebar" id="sidebar">
        <div class="logo-area">
            <img src="logo.png" alt="Logo" class="logo-img">
            <div class="logo-text">SPPG PATARUMAN 2</div>
        </div>
        <nav style="flex:1; display:flex; flex-direction:column;">
            <div class="nav-item active" data-tab="dashboard"><i class="fas fa-chart-line"></i><span>Dashboard</span></div>
            
            <?php if ($role === 'Admin'): ?>
            <div class="nav-item" data-tab="master_bahan"><i class="fas fa-database"></i><span>Master Bahan</span></div>
            <?php endif; ?>
            
            <div class="nav-item" data-tab="kalkulator"><i class="fas fa-calculator"></i><span>Kalkulator Menu</span></div>
            
            <?php if ($role === 'Admin'): ?>
            <div class="nav-item" data-tab="manage_users"><i class="fas fa-users-cog"></i><span>Manajemen User</span></div>
            <div class="nav-item" data-tab="settings"><i class="fas fa-sliders-h"></i><span>Pengaturan JSON</span></div>
            <?php endif; ?>
            
            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(0,0,0,0.05);">
                <a href="logout.php" class="nav-item" style="color: #fb7185;"><i class="fas fa-sign-out-alt"></i><span>Keluar Sistem</span></a>
            </div>
        </nav>
    </aside>

    <main class="main-content">
        <div class="top-bar">
            <div class="page-title"><h1 id="mainHeading">Dashboard Overview</h1></div>
            <div class="admin-profile" onclick="if(confirm('Yakin ingin keluar?')) window.location.href='logout.php';">
                <div class="avatar"><?= substr(session('nama', 'U'), 0, 1) ?></div>
                <div style="display: flex; flex-direction: column;">
                    <span style="font-weight: 600; font-size: 0.9rem;"><?= session('nama', 'Pengguna') ?></span>
                    <span style="font-size: 0.7rem; color: #64748b; font-weight: 700; text-transform: uppercase;"><?= $role ?></span>
                </div>
                <i class="fas fa-power-off" style="font-size: 0.8rem; color: #ef4444; margin-left: 10px;"></i>
            </div>
        </div>

        <div id="dashboardTab">
            <div class="stats-grid">
                <div class="stat-card">
                    <div>
                        <h3 style="font-size:0.8rem; color:#64748b; text-transform:uppercase;">Total Bahan Baku</h3>
                        <div class="stat-number"><?= $countBahan ?></div>
                    </div>
                    <i class="fas fa-boxes" style="font-size: 2.5rem; color: #e0e7ff;"></i>
                </div>
                <div class="stat-card">
                    <div>
                        <h3 style="font-size:0.8rem; color:#64748b; text-transform:uppercase;">Menu Tersimpan</h3>
                        <div class="stat-number"><?= $countMenu ?></div>
                    </div>
                    <i class="fas fa-utensils" style="font-size: 2.5rem; color: #e0e7ff;"></i>
                </div>
            </div>
            
            <div class="charts-row">
                <div class="card">
                    <div class="card-header">📈 Tren Kalori (7 Menu)</div>
                    <canvas id="lineChart" height="200"></canvas>
                </div>
                <div class="card">
                    <div class="card-header">🥧 Rata-rata Makronutrien</div>
                    <canvas id="pieChart" height="200"></canvas>
                </div>
            </div>

            <div class="card">
                <div class="card-header">📂 Daftar Paket Menu Tersimpan</div>
                
                <div style="background: #f8fafc; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #e2e8f0; display: flex; gap: 15px; align-items: flex-end; flex-wrap: wrap;">
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size:0.8rem; font-weight:600; color:#64748b;">Dari Tanggal</label>
                        <input type="date" id="filter_tgl_mulai" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:8px;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size:0.8rem; font-weight:600; color:#64748b;">Sampai Tanggal</label>
                        <input type="date" id="filter_tgl_selesai" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:8px;">
                    </div>
                    <div style="flex: 1; min-width: 150px;">
                        <label style="font-size:0.8rem; font-weight:600; color:#64748b;">Kategori Sasaran</label>
                        <select id="filter_sasaran" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:8px; background:white;">
                            <option value="">Semua Kategori</option>
                            <option value="Kelas Berat">Kelas Berat</option>
                            <option value="Kelas Ringan">Kelas Ringan</option>
                        </select>
                    </div>
                    <div style="display:flex; gap:10px;">
                        <button onclick="applyFilter()" class="btn-modern" style="padding:10px;"><i class="fas fa-filter"></i></button>
                        <button onclick="resetFilter()" class="btn-hapus" style="padding:10px; background:#64748b; color:white;"><i class="fas fa-undo"></i></button>
                    </div>
                </div>

                <div class="table-wrapper">
                    <table id="tabelDashboard">
                        <thead><tr><th>Tanggal</th><th>Nama Paket</th><th>Sasaran</th><th>Energi (kkal)</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php if(!empty($allMenus)): foreach($allMenus as $m): ?>
                            <tr class="menu-row" data-tanggal="<?= $m['tanggal_menu'] ?>" data-sasaran="<?= $m['kategori_sasaran'] ?>">
                                <td><?= !empty($m['tanggal_menu']) ? date('d/m/Y', strtotime($m['tanggal_menu'])) : '-' ?></td>
                                <td style="font-weight:600"><?= htmlspecialchars($m['nama_paket']) ?></td>
                                <td><span class="badge"><?= $m['kategori_sasaran'] ?></span></td>
                                <td style="font-weight:700; color:#4f46e5;"><?= number_format($m['total_energi'], 1) ?></td>
                                <td style="display:flex; gap:5px;">
                                    <button class="btn-detail" onclick="viewDetail(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['nama_paket'])) ?>')"><i class="fas fa-eye"></i></button>
                                    <a href="cetak_pdf.php?id=<?= $m['id'] ?>" class="btn-modern" style="padding:8px; background:#ef4444;"><i class="fas fa-file-pdf"></i></a>
                                    <a href="export_excel.php?id=<?= $m['id'] ?>" class="btn-modern" style="padding:8px; background:#10b981;"><i class="fas fa-file-excel"></i></a>
                                    <button class="btn-hapus" onclick="hapusMenu(<?= $m['id'] ?>)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr class="no-data-row"><td colspan="5" style="text-align:center; padding:2rem;">Belum ada menu tersimpan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if ($role === 'Admin'): ?>
        <div id="master_bahanTab" style="display: none;">
            <div class="card">
                <div class="card-header">➕ Input Manual Bahan Baru</div>
                <form action="simpan_bahan_manual.php" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                    <div style="grid-column: span 2;"><label>Nama Bahan</label><input type="text" name="nama_bahan" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Kategori</label>
                        <select name="kategori" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; background:white;">
                            <option value="Karbohidrat">Karbohidrat</option><option value="Protein Hewani">Protein Hewani</option><option value="Protein Nabati">Protein Nabati</option><option value="Sayur">Sayur</option><option value="Buah">Buah</option><option value="Lemak">Lemak</option>
                        </select>
                    </div>
                    <div><label>Energi</label><input type="number" step="0.1" name="energi" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Protein</label><input type="number" step="0.1" name="protein" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Lemak</label><input type="number" step="0.1" name="lemak" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Karbo</label><input type="number" step="0.1" name="karbohidrat" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Serat</label><input type="number" step="0.1" name="serat" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div style="display:flex; align-items:flex-end;"><button type="submit" class="btn-modern" style="width:100%; justify-content:center;">Simpan</button></div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">📋 Database Bahan Makanan</div>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>No</th><th>Nama Bahan</th><th>Kategori</th><th>Energi</th><th>Protein</th><th>Lemak</th><th>Aksi</th></tr></thead>
                        <tbody>
                            <?php $no=1; foreach($master_bahan as $row): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td style="font-weight: 500;"><?= htmlspecialchars($row['nama_bahan']) ?></td>
                                <td><span class="badge"><?= htmlspecialchars($row['kategori']) ?></span></td>
                                <td><?= $row['energi'] ?></td><td><?= $row['protein'] ?></td><td><?= $row['lemak'] ?></td>
                                <td style="display:flex; gap:5px;">
                                    <button class="btn-detail" onclick="editBahan(<?= htmlspecialchars(json_encode($row)) ?>)"><i class="fas fa-edit"></i></button>
                                    <button class="btn-hapus" onclick="hapusBahan(<?= $row['id'] ?>)"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($role === 'Admin'): ?>
        <div id="manage_usersTab" style="display: none;">
            <div class="card">
                <div class="card-header"><span id="formUserTitle">➕ Manajemen Pengguna Sistem</span></div>
                <form action="proses_user.php?action=save" method="POST" id="formUser" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px;">
                    <input type="hidden" name="id" id="user_id">
                    <div><label>Nama Lengkap</label><input type="text" name="nama" id="user_nama" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Email</label><input type="email" name="email" id="user_email" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div><label>Role</label><select name="role" id="user_role" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; background:white;"><option value="Ahli Gizi">Ahli Gizi</option><option value="Admin">Admin</option></select></div>
                    <div><label>Password</label><input type="password" name="password" id="user_pass" placeholder="Isi untuk ganti/buat" style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                    <div style="grid-column: 1 / -1; display:flex; gap:10px;">
                        <button type="submit" class="btn-modern"><i class="fas fa-save"></i> Simpan Pengguna</button>
                        <button type="button" onclick="resetUserForm()" class="btn-hapus" style="background:#64748b; color:white;"><i class="fas fa-undo"></i> Reset</button>
                    </div>
                </form>
            </div>
            <div class="card">
                <div class="card-header">📋 Daftar Akun Terdaftar</div>
                <div class="table-wrapper">
                    <table>
                        <thead><tr><th>Nama</th><th>Email</th><th>Role</th><th>Aksi</th></tr></thead>
                        <tbody id="userTableBody"></tbody>
                    </table>
                </div>
            </div>
        </div>

        <div id="settingsTab" style="display: none;">
            <div class="card">
                <div class="card-header">⚙️ Pengaturan Standar Gizi (JSON)</div>
                <form action="update_settings.php" method="POST">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 30px;">
                        <?php foreach(['Kelas Ringan' => 'r', 'Kelas Berat' => 'b'] as $lbl => $pref): ?>
                        <div style="background:#f8fafc; padding:20px; border-radius:12px; border:1px solid #e2e8f0;">
                            <h4 style="margin-bottom:15px; color:#4f46e5;"><?= $lbl ?></h4>
                            <div style="display:grid; gap:10px;">
                                <?php foreach(['energi' => 'Energi (Kkal)', 'protein' => 'Protein (g)', 'lemak' => 'Lemak (g)', 'karbo' => 'Karbo (g)', 'serat' => 'Serat (g)'] as $k => $v): ?>
                                <div>
                                    <label style="font-size:0.8rem; font-weight:600;"><?= $v ?></label>
                                    <input type="number" step="0.1" name="<?= $pref ?>_<?= $k ?>" value="<?= $standarGizi[$lbl][$k] ?>" style="width:100%; padding:8px; border:1px solid #cbd5e1; border-radius:6px;">
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <button type="submit" class="btn-modern" style="margin-top:20px; width:100%; justify-content:center;"><i class="fas fa-save"></i> Simpan Konfigurasi JSON</button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <div id="kalkulatorTab" style="display: none;">
            <div class="card">
                <div class="card-header">⚙️ Kalkulator Makronutrien</div>
                <form action="simpan_menu.php" method="POST" id="formMenu">
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px; background: #f8fafc; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; margin-bottom:20px;">
                        <div><label style="font-weight:600;">Nama Paket Menu</label><input type="text" name="nama_paket" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                        <div><label style="font-weight:600;">Tanggal Menu</label><input type="date" name="tanggal_menu" value="<?= date('Y-m-d') ?>" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px;"></div>
                        <div><label style="font-weight:600;">Sasaran</label>
                            <select name="kategori_sasaran" id="kategori_sasaran" required style="width:100%; padding:10px; border:1px solid #cbd5e1; border-radius:8px; background:white;" onchange="hitungTotal()">
                                <option value="Kelas Berat">🍱 Kelas Berat</option><option value="Kelas Ringan">🥪 Kelas Ringan</option>
                            </select>
                        </div>
                    </div>

                    <div id="kategoriWrapper">
                        <?php foreach(['Karbohidrat', 'Protein Hewani', 'Protein Nabati', 'Sayur', 'Buah', 'Lemak'] as $kat): ?>
                        <div style="margin-bottom:15px; border:1px solid #e2e8f0; border-radius:10px; overflow:hidden;">
                            <div style="background:#f8fafc; padding:10px 15px; border-bottom:1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center;">
                                <strong><?= $kat ?></strong>
                                <button type="button" onclick="tambahBarisKategori('<?= $kat ?>')" class="btn-detail" style="margin:0; font-size:0.75rem;">+ Tambah</button>
                            </div>
                            <div id="box-<?= str_replace(' ', '', $kat) ?>" style="padding:10px;"></div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <div style="margin-top: 30px; padding: 20px; background: #1e293b; border-radius: 16px; color: white;">
                        <h3 style="margin-bottom: 15px; color: #cbd5e1;">Analisis Kandungan Gizi vs Standar</h3>
                        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 10px; text-align:center;">
                            <?php foreach(['Energi'=>'E','Protein'=>'P','Lemak'=>'L','Karbo'=>'Karbo','Serat'=>'Serat'] as $lbl => $id): ?>
                            <div style="background:rgba(255,255,255,0.05); padding:10px; border-radius:10px;">
                                <div style="font-size:0.8rem; color:#94a3b8;"><?= $lbl ?></div>
                                <div style="font-size:1.3rem; font-weight:700; color:#facc15;"><span id="tot<?= $id ?>">0</span>/<span id="std<?= $id ?>">0</span></div>
                                <small id="status<?= $id ?>" style="font-weight:600;"></small>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button type="submit" class="btn-modern" style="width:100%; margin-top:20px; justify-content:center;"><i class="fas fa-save"></i> SIMPAN RESEP & MENU</button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<div id="detailModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal('detailModal')">&times;</span>
        <h2 id="modalTitle" style="margin-bottom:1.5rem; font-size:1.5rem;"></h2>
        <div id="modalBody"></div>
    </div>
</div>

<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close-modal" onclick="closeModal('editModal')">&times;</span>
        <h2 style="margin-bottom:1.5rem; font-size:1.5rem;">Edit Master Bahan</h2>
        <form action="edit_bahan.php" method="POST">
            <input type="hidden" name="id" id="edit_id">
            <div style="margin-bottom: 15px;"><label>Nama Bahan</label><input type="text" name="nama_bahan" id="edit_nama" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #cbd5e1;"></div>
            <div style="margin-bottom: 15px;"><label>Kategori</label>
                <select name="kategori" id="edit_kategori" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #cbd5e1; background:white;">
                    <option value="Karbohidrat">Karbohidrat</option><option value="Protein Hewani">Protein Hewani</option><option value="Protein Nabati">Protein Nabati</option><option value="Sayur">Sayur</option><option value="Buah">Buah</option><option value="Lemak">Lemak</option>
                </select>
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:15px;">
                <div><label>Energi</label><input type="number" step="0.01" name="energi" id="edit_energi" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #cbd5e1;"></div>
                <div><label>Protein</label><input type="number" step="0.01" name="protein" id="edit_protein" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #cbd5e1;"></div>
                <div><label>Lemak</label><input type="number" step="0.01" name="lemak" id="edit_lemak" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #cbd5e1;"></div>
                <div><label>Karbo</label><input type="number" step="0.01" name="karbohidrat" id="edit_karbo" required style="width:100%; padding:10px; border-radius:6px; border:1px solid #cbd5e1;"></div>
                <div style="display:none;"><label>Serat</label><input type="number" step="0.01" name="serat" id="edit_serat"></div>
            </div>
            <button type="submit" class="btn-modern" style="width:100%; justify-content:center;">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    // Inisialisasi Data dari PHP
    const masterData = <?php echo json_encode($master_bahan); ?>;
    const standarGizi = <?php echo json_encode($standarGizi); ?>;
    const bahanMap = {};
    const catOptions = {}; 
    
    if(masterData && masterData.length > 0) {
        masterData.forEach(b => {
            bahanMap[b.id] = b;
            if(!catOptions[b.kategori]) catOptions[b.kategori] = '<option value="">-- Pilih Bahan --</option>';
            catOptions[b.kategori] += `<option value="${b.id}">${b.nama_bahan}</option>`;
        });
    }

    // 1. FUNGSI TAB & NAVIGASI
    const sections = ['dashboard', 'master_bahan', 'kalkulator', 'manage_users', 'settings'];
    const titles = {
        'dashboard': 'Dashboard Overview',
        'master_bahan': 'Master Data Gizi',
        'kalkulator': 'Kalkulator Menu',
        'manage_users': 'Manajemen Pengguna',
        'settings': 'Pengaturan Standar Gizi'
    };

    function setActiveTab(tabId) {
        sections.forEach(sec => {
            const el = document.getElementById(sec + 'Tab');
            if(el) el.style.display = 'none';
        });
        
        const activeTab = document.getElementById(tabId + 'Tab');
        if(activeTab) {
            activeTab.style.display = 'block';
            if(tabId === 'manage_users') loadUsers();
            if(tabId === 'kalkulator') hitungTotal();
        }
        
        document.getElementById('mainHeading').innerText = titles[tabId] || 'Dashboard';
        document.querySelectorAll('.nav-item').forEach(t => t.classList.remove('active'));
        const activeNav = document.querySelector(`.nav-item[data-tab="${tabId}"]`);
        if(activeNav) activeNav.classList.add('active');
    }

    document.querySelectorAll('.nav-item[data-tab]').forEach(tab => {
        tab.addEventListener('click', () => {
            setActiveTab(tab.getAttribute('data-tab'));
            if(window.innerWidth <= 768) document.getElementById('sidebar').style.transform = 'translateX(-100%)';
        });
    });

    document.getElementById('menuToggle').addEventListener('click', () => {
        const sb = document.getElementById('sidebar');
        sb.style.transform = sb.style.transform === 'translateX(0px)' ? 'translateX(-100%)' : 'translateX(0px)';
    });

    // 2. FUNGSI KALKULATOR MENU
    function tambahBarisKategori(cat) {
        const container = document.getElementById('box-' + cat.replace(/\s+/g, ''));
        if (!container) return;
        const row = document.createElement('div');
        row.className = 'bahan-row';
        row.style.cssText = 'display: flex; gap: 10px; align-items: center; margin-bottom: 8px;';
        row.innerHTML = `
            <select name="bahan_id[]" class="input-bahan" required style="flex:3; padding:8px; border:1px solid #cbd5e1; border-radius:6px; background:white;" onchange="hitungTotal()">
                ${catOptions[cat] || '<option value="">Data Kosong</option>'}
            </select>
            <input type="number" name="takaran_gram[]" class="input-takaran" placeholder="Gram" required min="0.1" step="0.1" style="flex:1; padding:8px; border:1px solid #cbd5e1; border-radius:6px;" oninput="hitungTotal()">
            <button type="button" onclick="this.parentElement.remove(); hitungTotal();" class="btn-hapus" style="padding:8px 12px;"><i class="fas fa-times"></i></button>
        `;
        container.appendChild(row);
    }

    function hitungTotal() {
        let tE = 0, tP = 0, tL = 0, tK = 0, tS = 0;
        const sasaran = document.getElementById('kategori_sasaran').value;
        const std = standarGizi[sasaran];

        document.getElementById('stdE').innerText = std.energi;
        document.getElementById('stdP').innerText = std.protein;
        document.getElementById('stdL').innerText = std.lemak;
        document.getElementById('stdKarbo').innerText = std.karbo;
        document.getElementById('stdSerat').innerText = std.serat;

        document.querySelectorAll('.bahan-row').forEach(row => {
            const idBahan = row.querySelector('.input-bahan').value;
            const gram = parseFloat(row.querySelector('.input-takaran').value) || 0;
            if (idBahan && bahanMap[idBahan]) {
                const b = bahanMap[idBahan];
                const factor = gram / 100;
                tE += factor * parseFloat(b.energi);
                tP += factor * parseFloat(b.protein);
                tL += factor * parseFloat(b.lemak);
                tK += factor * parseFloat(b.karbohidrat);
                tS += factor * parseFloat(b.serat || 0);
            }
        });

        document.getElementById('totE').innerText = tE.toFixed(1);
        document.getElementById('totP').innerText = tP.toFixed(1);
        document.getElementById('totL').innerText = tL.toFixed(1);
        document.getElementById('totKarbo').innerText = tK.toFixed(1);
        document.getElementById('totSerat').innerText = tS.toFixed(1);

        updateUIStatus('statusE', tE, std.energi);
        updateUIStatus('statusP', tP, std.protein);
        updateUIStatus('statusL', tL, std.lemak);
        updateUIStatus('statusKarbo', tK, std.karbo);
        updateUIStatus('statusSerat', tS, std.serat);
    }

    function updateUIStatus(id, val, target) {
        const el = document.getElementById(id);
        if (!el) return;
        if (val >= target) { el.innerText = '✅ Terpenuhi'; el.style.color = '#10b981'; } 
        else { el.innerText = '❌ Kurang ' + (target - val).toFixed(1); el.style.color = '#fb7185'; }
    }

    // 3. FUNGSI FILTER DASHBOARD
    function applyFilter() {
        const tglMulai = document.getElementById('filter_tgl_mulai').value;
        const tglSelesai = document.getElementById('filter_tgl_selesai').value;
        const sasaran = document.getElementById('filter_sasaran').value;
        
        let found = 0;
        document.querySelectorAll('.menu-row').forEach(row => {
            const rowTgl = row.getAttribute('data-tanggal');
            const rowSasaran = row.getAttribute('data-sasaran');
            
            let show = true;
            if (tglMulai && rowTgl < tglMulai) show = false;
            if (tglSelesai && rowTgl > tglSelesai) show = false;
            if (sasaran && rowSasaran !== sasaran) show = false;

            row.style.display = show ? '' : 'none';
            if(show) found++;
        });
        
        const existingNoData = document.querySelector('.no-filter-data');
        if (found === 0) {
            if (!existingNoData) {
                const tr = document.createElement('tr');
                tr.className = 'no-filter-data';
                tr.innerHTML = `<td colspan="5" style="text-align:center; padding:2rem; color:#64748b;">Data tidak ditemukan.</td>`;
                document.querySelector('#tabelDashboard tbody').appendChild(tr);
            }
        } else if (existingNoData) {
            existingNoData.remove();
        }
    }
    
    function resetFilter() {
        document.getElementById('filter_tgl_mulai').value = '';
        document.getElementById('filter_tgl_selesai').value = '';
        document.getElementById('filter_sasaran').value = '';
        document.querySelectorAll('.menu-row').forEach(row => row.style.display = '');
        const existingNoData = document.querySelector('.no-filter-data');
        if (existingNoData) existingNoData.remove();
    }

    // 4. FUNGSI MANAJEMEN USER (ADMIN)
    function loadUsers() {
        fetch('get_users.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('userTableBody');
                if (!tbody) return;
                tbody.innerHTML = '';
                data.forEach(u => {
                    const userData = btoa(JSON.stringify(u)); 
                    tbody.innerHTML += `
                        <tr>
                            <td style="font-weight:600">${u.nama}</td>
                            <td>${u.email}</td>
                            <td><span class="badge">${u.role}</span></td>
                            <td style="display:flex; gap:5px;">
                                <button onclick="prepareEditUser('${userData}')" class="btn-detail"><i class="fas fa-edit"></i></button>
                                <button onclick="hapusUser(${u.id})" class="btn-hapus"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                });
            })
            .catch(err => console.error("Error loading users:", err));
    }
    
    function prepareEditUser(encodedData) {
        const user = JSON.parse(atob(encodedData));
        document.getElementById('user_id').value = user.id;
        document.getElementById('user_nama').value = user.nama;
        document.getElementById('user_email').value = user.email;
        document.getElementById('user_role').value = user.role;
        document.getElementById('formUserTitle').innerText = "📝 Edit Pengguna: " + user.nama;
        document.getElementById('user_pass').value = '';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function resetUserForm() {
        document.getElementById('formUser').reset();
        document.getElementById('user_id').value = '';
        document.getElementById('formUserTitle').innerText = "➕ Manajemen Pengguna Sistem";
    }

    // 5. FUNGSI MODAL & DELETE
    function viewDetail(id, nama) {
        document.getElementById('modalTitle').innerText = 'Detail Resep: ' + nama;
        document.getElementById('detailModal').style.display = 'block';
        fetch('get_detail_menu.php?id=' + id).then(r => r.text()).then(d => { document.getElementById('modalBody').innerHTML = d; });
    }
    function editBahan(b) {
        document.getElementById('edit_id').value = b.id;
        document.getElementById('edit_nama').value = b.nama_bahan;
        document.getElementById('edit_kategori').value = b.kategori;
        document.getElementById('edit_energi').value = b.energi;
        document.getElementById('edit_protein').value = b.protein;
        document.getElementById('edit_lemak').value = b.lemak;
        document.getElementById('edit_karbo').value = b.karbohidrat;
        document.getElementById('edit_serat').value = b.serat;
        document.getElementById('editModal').style.display = 'block';
    }
    function closeModal(modalId) { document.getElementById(modalId).style.display = 'none'; }
    function hapusMenu(id) { if(confirm('Hapus menu ini?')) window.location.href = 'hapus_menu.php?id=' + id; }
    function hapusBahan(id) { if(confirm('Hapus bahan ini?')) window.location.href = 'hapus_bahan.php?id=' + id; }
    function hapusUser(id) { if(confirm('Hapus user ini?')) window.location.href = 'proses_user.php?action=delete&id=' + id; }

    // 6. INITIALIZATION
    window.addEventListener('DOMContentLoaded', () => {
        // Init Grafik
        const labelLine = <?= json_encode(array_column($dataLine, 'nama_paket') ?: []) ?>;
        const valLine = <?= json_encode(array_column($dataLine, 'total_energi') ?: []) ?>;
        const avgP = <?= (float)($dataPie['avg_p'] ?? 0) ?>;
        const avgL = <?= (float)($dataPie['avg_l'] ?? 0) ?>;
        const avgK = <?= (float)($dataPie['avg_k'] ?? 0) ?>;

        new Chart(document.getElementById('lineChart').getContext('2d'), {
            type: 'line',
            data: { labels: labelLine.length > 0 ? labelLine : ['-'], datasets: [{ label: 'Kalori', data: valLine.length > 0 ? valLine : [0], borderColor: '#6366f1', tension: 0.4, fill: true, backgroundColor: 'rgba(99, 102, 241, 0.1)' }] }
        });
        new Chart(document.getElementById('pieChart').getContext('2d'), {
            type: 'doughnut',
            data: { labels: ['Protein', 'Lemak', 'Karbo'], datasets: [{ data: [avgP, avgL, avgK], backgroundColor: ['#10b981', '#fb7185', '#6366f1'] }] }
        });

        // Tambah default baris kalkulator
        ['Karbohidrat', 'Protein Hewani', 'Sayur'].forEach(cat => tambahBarisKategori(cat));
        
        // Buka dashboard pertama kali
        setActiveTab('dashboard');
    });
</script>
</body>
</html>