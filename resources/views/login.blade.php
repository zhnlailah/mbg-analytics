<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login | MBG Analytics</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-slate-100 flex items-center justify-center min-h-screen p-4">
    <div class="max-w-md w-full bg-white p-8 rounded-3xl shadow-2xl border-t-4 border-indigo-600">
        <div class="text-center mb-8">
            <i class="fas fa-leaf text-4xl text-indigo-600 mb-2"></i>
            <h1 class="text-2xl font-bold text-slate-800">MBG Analytics</h1>
            <p class="text-slate-500">Masuk untuk mengelola data gizi</p>
        </div>
        
        <?php if(!empty($error)): ?>
            <div class="bg-red-50 text-red-600 p-3 rounded-lg mb-4 text-sm text-center font-bold"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST" class="space-y-5">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Email</label>
                <input type="email" name="email" required class="w-full p-3 border rounded-xl outline-indigo-500" placeholder="admin@mbg.com">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Password</label>
                <input type="password" name="password" required class="w-full p-3 border rounded-xl outline-indigo-500" placeholder="••••••••">
            </div>
            <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg transition">Masuk ke Sistem</button>
        </form>
    </div>
</body>
</html>