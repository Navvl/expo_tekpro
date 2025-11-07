<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Room;
use App\Models\Access;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class RoomController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function myroom()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke My Room.',
        ]);
        $room = Room::with('user')->get();
        $allUsers = User::all();
        echo view('header');
        echo view('menu');
        echo view('myroom', compact('room','allUsers'));
        echo view('footer');
    }

    public function t_room(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'),
            'description' => 'User menambah room.',
        ]);

        try {

            $request->validate([
                'room_title' => 'required|string',
            ]);

            $room = new Room();
            $room->room_title = $request->room_title;
            $room->id_user = Session::get('id');     // ✅ set creator automatically
            $room->save();

            return redirect()->back()->with('success', 'Room berhasil ditambahkan');

        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan room.']);
        }
    }



    public function room_destroy($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'room_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'Room Menghapus room.',
        ]);
        // Cari data room berdasarkan ID
        $room = Room::findOrFail($id);

        $room->delete();


        // Redirect dengan pesan sukses
        return redirect()->route('myroom')->with('success', 'Data room berhasil dihapus');
    }

    public function invite_user(Request $request, $id_room)
    {
        $request->validate(['user_id' => 'required|array']);
        $room = Room::findOrFail($id_room);

        if ($room->id_access) {
            $access = Access::findOrFail($room->id_access);
            $access->id_user = implode(',', $request->user_id); // replace with current selection
            $access->save();
        } else {
            $access = new Access();
            $access->id_user = implode(',', $request->user_id);
            $access->save();

            $room->id_access = $access->id_access;
            $room->save();
        }

        return back()->with('success', 'Users updated.');
    }


    public function detail($id)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Detail.',
        ]);

        // Mencari pengguna berdasarkan ID
        $user = User::findOrFail($id);

        // Mengembalikan view dengan data pengguna dan level
        echo view('header');
        echo view('menu');
        echo view('detail', compact('user'));
        echo view('footer');
    }

    public function updateDetail(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Mengupdate User.',
        ]);

        try {
            // Validasi input
            $request->validate([
                'username' => 'required',
                'email' => 'required',
                'level' => 'required',
                // Validasi lain sesuai kebutuhan
            ]);

            // Mencari user berdasarkan ID
            $user = User::findOrFail($request->input('id'));

            // Simpan versi lama ke tabel user_history
            UserHistory::create([
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'level' => $user->level,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ]);

            // Perbarui data user
            $user->username = $request->input('username');
            $user->email = $request->input('email');
            $user->level = $request->input('level');
            $user->save();

            // Redirect dengan pesan sukses
            return redirect()->route('user', $user->id)->with('success', 'Detail pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            // Log error
            Log::error('Gagal memperbarui detail pengguna: ' . $e->getMessage());

            // Redirect kembali dengan pesan kesalahan
            return redirect()->back()->withErrors(['msg' => 'Gagal memperbarui detail pengguna. Silakan coba lagi.']);
        }
    }
}
