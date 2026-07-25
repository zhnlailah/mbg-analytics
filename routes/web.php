<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\UserController;

// 1. Rute Halaman Utama & Dashboard
Route::get('/', [MenuController::class, 'landing']);
Route::get('/index.php', [MenuController::class, 'index']);
Route::get('/dashboard', [MenuController::class, 'index']);

// 2. Rute Otentikasi (Mendukung URL pakai .php maupun URL bersih)
Route::get('/login.php', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login.php', [AuthController::class, 'prosesLogin']);
Route::post('/login', [AuthController::class, 'prosesLogin']);

Route::get('/register.php', [AuthController::class, 'showRegister']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register.php', [AuthController::class, 'prosesRegister']);
Route::post('/register', [AuthController::class, 'prosesRegister']);

Route::get('/logout.php', [AuthController::class, 'logout']);
Route::get('/logout', [AuthController::class, 'logout']);

// 3. Rute Fitur Gizi & Menu (Action dan Endpoint AJAX)
Route::get('/get_detail_menu.php', [MenuController::class, 'getDetailMenu']);
Route::post('/simpan_menu.php', [MenuController::class, 'simpanMenu']);
Route::post('/simpan_bahan_manual.php', [MenuController::class, 'simpanBahanManual']);
Route::post('/edit_bahan.php', [MenuController::class, 'editBahan']);
Route::get('/hapus_bahan.php', [MenuController::class, 'hapusBahan']);
Route::get('/hapus_menu.php', [MenuController::class, 'hapusMenu']);
Route::get('/cetak_pdf.php', [MenuController::class, 'cetakPdf']);
Route::get('/export_excel.php', [MenuController::class, 'exportExcel']);
Route::post('/upload_json.php', [MenuController::class, 'uploadJson']);
Route::get('/import_db.php', [MenuController::class, 'importDb']);

// 4. Rute Fitur Admin & Pengaturan User
Route::get('/get_users.php', [UserController::class, 'getUsers']);
Route::any('/proses_user.php', [UserController::class, 'prosesUser']);
Route::post('/update_settings.php', [UserController::class, 'updateSettings']);