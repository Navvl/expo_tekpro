<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class RestoreEditController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function restore_e()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Restore Edit.',
        ]);

        $user_history = UserHistory::all();

        // Tampilkan view dengan data histori
        echo view('header');
        echo view('menu');
        echo view('restore_e', compact('user_history'));
        echo view('footer');
    }

    public function restoreEdit($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Merestore Data User.',
        ]);

        try {
            // Cari histori user berdasarkan id_user, bukan id
            $history = UserHistory::where('id_user', $id)->firstOrFail();

            // Cari user terkait berdasarkan user_id
            $user = User::findOrFail($history->id_user);

            // Kembalikan data user ke data yang ada di histori
            $user->update([
                'username' => $history->username,
                'email' => $history->email,
                'level' => $history->level,
            ]);

            // Redirect dengan pesan sukses
            return redirect()->route('restore_e')->with('success', 'Data pengguna berhasil dikembalikan ke versi sebelumnya.');
        } catch (\Exception $e) {
            // Log error jika terjadi kegagalan
            Log::error('Gagal mengembalikan data pengguna: ' . $e->getMessage());

            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal mengembalikan data pengguna. Silakan coba lagi.']);
        }
    }

    public function re_destroy($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Menghapus Data Restore.',
        ]);

        // Cari data user berdasarkan ID
        $user = UserHistory::findOrFail($id);

        // Hapus data user (soft delete)
        $user->delete();

        // Redirect dengan pesan sukses
        return redirect()->route('restore_e')->with('success', 'Data user berhasil dihapus');
    }
}
