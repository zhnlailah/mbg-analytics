<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPPG Pataruman 2 | Manajemen Gizi Digital</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .gradient-text { 
            background: linear-gradient(135deg, #4f46e5 0%, #06b6d4 100%); 
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent; 
        }
        .bg-gradient-primary { background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); }
        .glass-nav { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(12px); }
        
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
        .animate-fade-up { animation: fadeUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        .animate-float { animation: float 6s ease-in-out infinite; }

        .blob {
            position: absolute; filter: blur(80px); z-index: -1; opacity: 0.4;
            border-radius: 50%; animation: float 10s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 overflow-x-hidden relative">

    <div class="blob bg-indigo-300 w-96 h-96 top-0 left-[-100px]"></div>
    <div class="blob bg-cyan-200 w-96 h-96 top-40 right-[-100px] animation-delay-2000"></div>

    <nav class="fixed w-full z-50 glass-nav border-b border-slate-200 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <a href="#" class="flex items-center gap-3 hover:opacity-90 transition">
                    <img src="logo.png" alt="Logo SPPG" class="h-10 w-10 object-contain">
                    <div class="flex flex-col">
                        <span class="text-xl font-extrabold tracking-tight leading-none text-slate-800">SPPG <span class="text-indigo-600">Pataruman 2</span></span>
                    </div>
                </a>

                <div class="hidden md:flex items-center gap-8 font-semibold text-sm text-slate-600">
                    <a href="#fitur" class="hover:text-indigo-600 transition">Fitur Sistem</a>
                    <a href="#solusi" class="hover:text-indigo-600 transition">Solusi</a>
                    <a href="#tentang" class="hover:text-indigo-600 transition">Tentang Kami</a>
                    <div class="w-px h-6 bg-slate-300"></div>
                    <a href="login.php" class="bg-slate-100 text-indigo-700 border border-slate-200 px-6 py-2.5 rounded-full hover:bg-slate-200 transition-all font-bold">
                        Masuk
                    </a>
                </div>

                <div class="md:hidden flex items-center">
                    <a href="login.php" class="bg-indigo-600 text-white px-5 py-2 rounded-full text-sm font-bold">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-32 pb-20 lg:pt-48 lg:pb-32 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <div class="animate-fade-up text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-700 font-semibold text-xs uppercase tracking-wide mb-6">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-indigo-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-indigo-500"></span>
                        </span>
                        Aplikasi Standar Gizi v2.0
                    </div>
                    <h1 class="text-5xl lg:text-6xl font-extrabold tracking-tight mb-6 leading-[1.1]">
                        Transformasi Digital <br>
                        <span class="gradient-text">Manajemen Gizi</span>
                    </h1>
                    <p class="text-lg text-slate-500 mb-8 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Otomatisasi kalkulasi makronutrien, validasi standar porsi, dan cetak laporan harian hanya dalam hitungan detik. Dirancang khusus untuk efisiensi Ahli Gizi SPPG Pataruman 2.
                    </p>
                    <div class="flex flex-col sm:flex-row justify-center lg:justify-start gap-4">
                        <a href="login.php" class="bg-slate-900 text-white px-8 py-4 rounded-xl font-bold hover:bg-slate-800 transition shadow-xl shadow-slate-200 flex items-center justify-center gap-2">
                            Buka Dashboard <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                        <a href="#fitur" class="bg-white text-slate-700 border border-slate-200 px-8 py-4 rounded-xl font-bold hover:bg-slate-50 hover:border-slate-300 transition flex items-center justify-center gap-2">
                            Pelajari Fitur <i class="fas fa-play-circle text-indigo-500"></i>
                        </a>
                    </div>
                </div>

                <div class="animate-fade-up delay-200 hidden lg:block relative">
                    <div class="animate-float relative z-10 bg-white p-6 rounded-3xl shadow-2xl border border-slate-100 mx-auto w-full max-w-md">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-100 pb-4">
                            <div class="flex items-center gap-3">
                                <img src="logo.png" class="w-8 h-8 object-contain">
                                <div>
                                    <h4 class="font-bold text-sm text-slate-800">Status Menu Harian</h4>
                                    <p class="text-[10px] text-slate-400">Validasi Real-time</p>
                                </div>
                            </div>
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Terpenuhi</span>
                        </div>
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-sm font-semibold text-slate-600"><i class="fas fa-fire text-yellow-500 mr-2 w-4"></i> Energi</span>
                                <span class="font-bold text-slate-800">2400 / 2375 <span class="text-[10px] text-slate-400 font-normal">Kkal</span></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-sm font-semibold text-slate-600"><i class="fas fa-dna text-blue-500 mr-2 w-4"></i> Protein</span>
                                <span class="font-bold text-slate-800">75.5 / 70 <span class="text-[10px] text-slate-400 font-normal">g</span></span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl border border-slate-100">
                                <span class="text-sm font-semibold text-slate-600"><i class="fas fa-tint text-pink-500 mr-2 w-4"></i> Lemak</span>
                                <span class="font-bold text-slate-800">80.2 / 78 <span class="text-[10px] text-slate-400 font-normal">g</span></span>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="h-10 bg-indigo-50 border border-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 font-bold text-xs cursor-pointer hover:bg-indigo-100 transition">
                                <i class="fas fa-file-pdf mr-2"></i> Ekspor Laporan PDF
                            </div>
                        </div>
                    </div>
                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[120%] h-[120%] bg-gradient-to-tr from-indigo-100 to-cyan-50 rounded-full blur-3xl -z-10"></div>
                </div>

            </div>
        </div>
    </section>

    <div class="bg-slate-900 border-y border-slate-800 relative z-20 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center text-white divide-x divide-slate-700/50">
                <div class="animate-fade-up group">
                    <div class="text-4xl font-extrabold mb-1 text-indigo-400 group-hover:scale-110 transition-transform"><?= $statBahan ?></div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Master Bahan</div>
                </div>
                <div class="animate-fade-up delay-100 group">
                    <div class="text-4xl font-extrabold mb-1 text-emerald-400 group-hover:scale-110 transition-transform"><?= $statMenu ?></div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Menu Tersimpan</div>
                </div>
                <div class="animate-fade-up delay-200 group">
                    <div class="text-4xl font-extrabold mb-1 text-cyan-400 group-hover:scale-110 transition-transform"><?= $statUser ?></div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ahli Gizi Aktif</div>
                </div>
                <div class="animate-fade-up delay-300 group">
                    <div class="text-4xl font-extrabold mb-1 text-pink-400 group-hover:scale-110 transition-transform">100%</div>
                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Akurasi Sistem</div>
                </div>
            </div>
        </div>
    </div>

    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-fade-up">
                <h2 class="text-sm font-bold text-indigo-600 uppercase tracking-widest mb-2">Platform Capabilities</h2>
                <h3 class="text-3xl lg:text-4xl font-extrabold text-slate-900 mb-4">Sistem yang Bekerja Untuk Anda</h3>
                <p class="text-slate-500 max-w-2xl mx-auto">Kami mengotomatisasi pekerjaan administratif agar Anda dapat lebih fokus pada kualitas gizi asupan, bukan pada kerumitan perhitungan.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform group-hover:bg-indigo-600 group-hover:text-white">
                        <i class="fas fa-calculator text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Kalkulator Makronutrien</h3>
                    <p class="text-slate-500 leading-relaxed text-sm font-medium">Masukan bahan baku dan takaran, sistem akan menghitung secara real-time kandungan Energi, Protein, Lemak, dan Karbohidrat per 100 gram bahan.</p>
                </div>

                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-emerald-600 mb-6 group-hover:scale-110 transition-transform group-hover:bg-emerald-600 group-hover:text-white">
                        <i class="fas fa-file-pdf text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Ekspor 1-Klik PDF & Excel</h3>
                    <p class="text-slate-500 leading-relaxed text-sm font-medium">Tidak perlu merapikan format manual. Satu klik, laporan harian langsung jadi dalam bentuk PDF resmi (siap TTD) atau *worksheet* Excel untuk arsip.</p>
                </div>

                <div class="bg-slate-50 p-8 rounded-3xl border border-slate-100 hover:border-cyan-200 hover:shadow-xl hover:shadow-cyan-100/50 transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white shadow-sm rounded-2xl flex items-center justify-center text-cyan-600 mb-6 group-hover:scale-110 transition-transform group-hover:bg-cyan-600 group-hover:text-white">
                        <i class="fas fa-sliders-h text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-800">Standar Gizi Dinamis</h3>
                    <p class="text-slate-500 leading-relaxed text-sm font-medium">Atur batas minimal kalori Kelas Berat (SMA) dan Kelas Ringan (TK) secara mandiri lewat panel pengaturan khusus Admin dengan format JSON super ringan.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="solusi" class="py-24 bg-slate-50 border-t border-slate-100 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col lg:flex-row items-center gap-16">
                <div class="flex-1 w-full order-2 lg:order-1">
                    <div class="bg-white rounded-3xl p-8 border border-slate-200 shadow-xl relative animate-fade-up">
                        <div class="flex justify-between items-center mb-6">
                            <div class="flex gap-2">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <span class="text-xs font-bold text-slate-400">Role: Admin</span>
                        </div>
                        <div class="space-y-5">
                            <div class="h-6 bg-slate-100 rounded w-1/2"></div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="h-24 bg-indigo-50 border border-indigo-100 rounded-2xl flex items-center justify-center"><i class="fas fa-users text-3xl text-indigo-300"></i></div>
                                <div class="h-24 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center justify-center"><i class="fas fa-database text-3xl text-emerald-300"></i></div>
                            </div>
                            <div class="h-12 bg-slate-100 rounded-xl w-full"></div>
                        </div>
                    </div>
                </div>
                <div class="flex-1 order-1 lg:order-2 animate-fade-up delay-100">
                    <h2 class="text-3xl lg:text-4xl font-extrabold mb-6 text-slate-900 leading-tight">Keamanan Data <br>& Hak Akses Terstruktur</h2>
                    <p class="text-slate-500 mb-8 leading-relaxed text-lg">
                        Sistem dirancang dengan privasi tinggi, memisahkan hak dan wewenang antara Administrator sistem dan Ahli Gizi operasional di lapangan.
                    </p>
                    <div class="space-y-8">
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 flex-shrink-0"><i class="fas fa-user-shield text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-lg text-slate-800">Administrator Panel</h4>
                                <p class="text-slate-500 text-sm mt-1 leading-relaxed">Akses penuh untuk Manajemen User (Tambah/Edit/Hapus), pengaturan standar gizi JSON, dan modifikasi Master Bahan Makanan.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-5">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 flex items-center justify-center text-emerald-600 flex-shrink-0"><i class="fas fa-stethoscope text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-lg text-slate-800">Ahli Gizi Workspace</h4>
                                <p class="text-slate-500 text-sm mt-1 leading-relaxed">Antarmuka yang bersih dan bebas gangguan. Fokus murni pada penggunaan Kalkulator Gizi, pembuatan menu, dan pencetakan laporan PDF/Excel.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="tentang" class="py-24 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <img src="logo.png" alt="Logo SPPG" class="w-20 h-20 mx-auto mb-6 object-contain animate-float">
            <h2 class="text-3xl font-extrabold text-slate-900 mb-6">Tentang SPPG Pataruman 2</h2>
            <p class="text-lg text-slate-500 max-w-3xl mx-auto leading-relaxed mb-10">
                Kami adalah institusi pendidikan yang berkomitmen penuh pada kualitas asupan dan kesehatan generasi penerus. Melalui inisiatif <b>MBG Analytics</b>, kami membuktikan bahwa perhatian pada detail gizi harian dapat dilakukan dengan presisi teknologi modern, memastikan setiap porsi yang disajikan memenuhi standar emas kesehatan (AKG).
            </p>
        </div>
    </section>

    <section class="py-20 relative overflow-hidden">
        <div class="absolute inset-0 bg-indigo-600 -z-20"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] -z-10"></div>
        
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center animate-fade-up">
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-6">Siap Meracik Menu Sehat Hari Ini?</h2>
            <p class="text-indigo-100 text-lg mb-10 max-w-2xl mx-auto">Masuk ke dalam dashboard analitik Anda sekarang dan mulai susun laporan gizi harian dengan standar profesional SPPG.</p>
            <a href="login.php" class="inline-block bg-white text-indigo-700 font-bold text-lg px-10 py-4 rounded-full shadow-2xl hover:scale-105 hover:bg-slate-50 transition-all duration-300">
                Masuk ke Aplikasi <i class="fas fa-sign-in-alt ml-2"></i>
            </a>
        </div>
    </section>

    <footer class="bg-slate-900 border-t border-slate-800 py-12 text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-10 border-b border-slate-800 pb-10">
                <div class="col-span-2">
                    <div class="flex items-center gap-3 mb-4">
                        <img src="logo.png" alt="Logo SPPG" class="w-10 h-10 object-contain brightness-0 invert opacity-80">
                        <span class="text-xl font-extrabold text-white tracking-tight">SPPG Pataruman 2</span>
                    </div>
                    <p class="text-sm max-w-sm leading-relaxed">
                        Platform Analisis Gizi Terintegrasi. Mengedepankan akurasi data untuk mendukung pertumbuhan gizi anak bangsa.
                    </p>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 uppercase text-xs tracking-widest">Sistem Navigasi</h4>
                    <ul class="space-y-3 text-sm">
                        <li><a href="#fitur" class="hover:text-indigo-400 transition">Fitur Utama</a></li>
                        <li><a href="#solusi" class="hover:text-indigo-400 transition">Hak Akses</a></li>
                        <li><a href="login.php" class="hover:text-indigo-400 transition">Portal Login</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold text-white mb-4 uppercase text-xs tracking-widest">Bantuan</h4>
                    <ul class="space-y-3 text-sm">
                        <li class="flex items-center gap-3"><i class="fas fa-envelope text-indigo-400"></i> admin@sppg.sch.id</li>
                        <li class="flex items-center gap-3"><i class="fas fa-map-marker-alt text-indigo-400"></i> Pataruman, Jawa Barat</li>
                    </ul>
                </div>
            </div>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm font-medium">
                <div>
                    &copy; <?= date('Y') ?> SPPG Pataruman 2. All rights reserved.
                </div>
                <div class="flex gap-5 text-lg">
                    <a href="#" class="hover:text-white transition"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="hover:text-white transition"><i class="fab fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('animate-fade-up');
                        entry.target.style.opacity = 1;
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.animate-fade-up').forEach(el => observer.observe(el));
        });
    </script>
</body>
</html>