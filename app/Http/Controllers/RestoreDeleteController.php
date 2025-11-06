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


class RestoreDeleteController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function restore_d()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Restore Delete.',
        ]);
        $user_terhapus = User::onlyTrashed()->get(); // Ambil semua user yang dihapus

        // Mengembalikan view dengan data pengguna dan level
        echo view('header');
        echo view('menu');
        echo view('restore_d', compact('user_terhapus'));
        echo view('footer');
    }

    public function user_restore($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Merestore Data User.',
        ]);

        // Cari user yang sudah terhapus (soft deleted)
        $user = User::withTrashed()->findOrFail($id)->restore();


        // Redirect dengan pesan sukses
        return redirect()->back()->with('success', 'Data paket berhasil dikembalikan');
    }
    public function rd_destroy($id)
    {
        // Find the user and delete permanently
        $user = User::withTrashed()->find($id); // You can use withTrashed() if you want to handle soft deletes
        $user->forceDelete(); // Permanently delete the user

        // Redirect or return a response
        return redirect()->back()->with('success', 'User deleted permanently');
    }
}
