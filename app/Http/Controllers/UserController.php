<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function getUsers() {
        return response()->json(DB::table('users')->orderByDesc('id')->get());
    }

    public function prosesUser(Request $request) {
        if (session('role') !== 'Admin') die("Akses Ditolak");
        $action = $request->query('action');

        if ($action == 'save') {
            $data = ['nama' => $request->nama, 'email' => $request->email, 'role' => $request->role];
            if (!empty($request->password)) $data['password'] = password_hash($request->password, PASSWORD_DEFAULT);

            if (!empty($request->id)) {
                DB::table('users')->where('id', $request->id)->update($data);
            } else {
                if(empty($request->password)) $data['password'] = password_hash('123456', PASSWORD_DEFAULT);
                DB::table('users')->insert($data);
            }
        } elseif ($action == 'delete') {
            if ($request->query('id') != session('user_id')) {
                DB::table('users')->where('id', $request->query('id'))->delete();
            }
        }
        return redirect('/index.php');
    }

    public function updateSettings(Request $request) {
        if (session('role') !== 'Admin') die("Akses Ditolak");
        $data = [
            "Kelas Ringan" => ["energi" => (float)$request->r_energi, "protein" => (float)$request->r_protein, "lemak" => (float)$request->r_lemak, "karbo" => (float)$request->r_karbo],
            "Kelas Berat" => ["energi" => (float)$request->b_energi, "protein" => (float)$request->b_protein, "lemak" => (float)$request->b_lemak, "karbo" => (float)$request->b_karbo]
        ];
        file_put_contents(public_path('standar_gizi.json'), json_encode($data, JSON_PRETTY_PRINT));
        return redirect('/index.php?status=settings_updated');
    }
}