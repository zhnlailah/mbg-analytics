<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi | MBG Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 flex items-center justify-center min-h-screen p-4">

    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-indigo-600 text-white rounded-2xl shadow-xl mb-4">
                <i class="fas fa-user-plus text-3xl"></i>
            </div>
            <h1 class="text-3xl font-extrabold text-slate-800 tracking-tight">Daftar Akun Baru</h1>
            <p class="text-slate-500 mt-2">Sistem Manajemen Gizi MBG Analytics</p>
        </div>

        <div class="bg-white p-8 rounded-3xl shadow-2xl border border-slate-100 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1.5 bg-indigo-600"></div>

            <?php if (!empty($message)): ?>
                <div class="mb-6 p-4 border-l-4 text-sm font-bold <?= $messageClass ?> animate-pulse">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>

            <form action="register.php" method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1 text-sm italic">Nama Lengkap</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-id-card text-xs"></i>
                        </span>
                        <input type="text" name="nama" required 
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700"
                            placeholder="Contoh: Salsabila Sakinah, S.Gz.">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1 text-sm italic">Email Kerja</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" required 
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700"
                            placeholder="nama@instansi.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1 text-sm italic">Hak Akses (Role)</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-user-tag text-xs"></i>
                        </span>
                        <select name="role" required 
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700 appearance-none">
                            <option value="Ahli Gizi">Ahli Gizi</option>
                            <option value="Admin">Administrator</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1 text-sm italic">Kata Sandi</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-slate-400">
                            <i class="fas fa-lock text-xs"></i>
                        </span>
                        <input type="password" name="password" required 
                            class="w-full pl-10 pr-4 py-3 bg-slate-50 border border-slate-200 rounded-xl focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700"
                            placeholder="••••••••">
                    </div>
                </div>

                <button type="submit" 
                    class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition-all active:scale-95 flex items-center justify-center gap-2">
                    <i class="fas fa-save text-sm"></i>
                    <span>Daftarkan Akun</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">Sudah punya akun? <a href="login.php" class="text-indigo-600 font-bold hover:underline">Masuk di sini</a></p>
            </div>
        </div>
    </div>

</body>
</html>