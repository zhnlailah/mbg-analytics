<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    // 1. Menampilkan Halaman Login
    public function showLogin()
    {
        if (session()->has('user_id')) {
            return redirect('/dashboard');
        }
        return view('login');
    }

    // 2. Memproses Data Login
    public function prosesLogin(Request $request)
    {
        $user = DB::table('users')->where('email', trim($request->email))->first();

        if ($user && password_verify($request->password, $user->password)) {
            // Simpan data login ke session Laravel
            session([
                'user_id' => $user->id,
                'nama'    => $user->nama,
                'role'    => $user->role
            ]);

            return redirect('/dashboard');
        }

        return view('login', ['error' => 'Email atau Password salah!']);
    }

    // 3. Menampilkan Halaman Register
    public function showRegister()
    {
        return view('register');
    }

    // 4. Memproses Pendaftaran Akun Baru
    public function prosesRegister(Request $request)
    {
        $cek = DB::table('users')->where('email', trim($request->email))->count();

        if ($cek > 0) {
            return view('register', [
                'message'      => 'Email sudah terdaftar!',
                'messageClass' => 'bg-orange-50 text-orange-600 border-orange-500'
            ]);
        }

        DB::table('users')->insert([
            'nama'     => trim($request->nama),
            'email'    => trim($request->email),
            'password' => password_hash($request->password, PASSWORD_DEFAULT),
            'role'     => $request->role
        ]);

        return view('register', [
            'message'      => 'Registrasi berhasil! Silakan login.',
            'messageClass' => 'bg-emerald-50 text-emerald-600 border-emerald-500'
        ]);
    }

    // 5. Keluar dari Sistem (LOGOUT) - INI YANG TADI HILANG
    public function logout()
    {
        // Bersihkan seluruh data session Laravel
        session()->flush();

        // Arahkan kembali ke halaman login
        return redirect('/');
    }
}