<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Friend;
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
            'user_id' => Session::get('id'),
            'description' => 'User Masuk Ke My Room.',
        ]);
        $currentUserId = Session::get('id');

        $room = Room::with('user')
            ->where('id_user', $currentUserId)
            ->get();
        $friends = Friend::where('status', 1)
                        ->where(function($query) use ($currentUserId) {
                            $query->where('friend.id_user', $currentUserId)
                                ->orWhere('id_user_friended', $currentUserId);
                        })
                        ->join('user', function($join) use ($currentUserId) {
                            $join->on('user.id_user', '=', 'friend.id_user')
                                ->orOn('user.id_user', '=', 'friend.id_user_friended');
                        })
                        ->where('user.id_user', '<>', $currentUserId)
                        ->select('user.id_user', 'user.username', 'user.email', 'user.foto')
                        ->distinct()
                        ->get();
        echo view('header');
        echo view('menu');
        echo view('myroom', compact('room','friends'));
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
            $access->id_user = implode(',', $request->user_id);
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

    public function all_room()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'),
            'description' => 'User Masuk Ke My Room.',
        ]);
        $currentUserId = Session::get('id');

        $room = Room::with(['user', 'access'])
        ->whereHas('access', function ($q) use ($currentUserId) {
            $q->whereRaw('FIND_IN_SET(?, id_user)', [$currentUserId]);
        })
        ->get();

        $allUsers = User::all();
        echo view('header');
        echo view('menu');
        echo view('all_room', compact('room','allUsers'));
        echo view('footer');
    }

    public function quit_room($id_room)
    {
        $userId = Session::get('id');

        $room = Room::with('access')->findOrFail($id_room);

        if (!$room->access) {
            return redirect()->back()->withErrors(['msg' => 'This room has no access record.']);
        }

        $access = $room->access;

        $userIds = array_filter(explode(',', $access->id_user));

        $userIds = array_filter($userIds, fn($id) => (string)$id !== (string)$userId);

        $access->id_user = implode(',', $userIds);
        $access->save();

        ActivityLog::create([
            'action' => 'quit',
            'user_id' => $userId,
            'description' => 'User quit from room ID ' . $id_room,
        ]);

        return redirect()->back()->with('success', 'You have left the room.');
    }
}
