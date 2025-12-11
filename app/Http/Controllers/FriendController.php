<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Friend;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FriendController extends Controller
{
    public function friend()
    {
        $loggedUser = Session::get('id');

         $friends = Friend::where('status', 1)
            ->where(function($query) use ($loggedUser) {
                $query->where('friend.id_user', $loggedUser) // user mengirim teman
                    ->orWhere('id_user_friended', $loggedUser); // user menerima teman
            })
            ->join('user', function($join) use ($loggedUser) {
                $join->on('user.id_user', '=', 'friend.id_user')
                    ->orOn('user.id_user', '=', 'friend.id_user_friended');
            })
        ->where('user.id_user', '<>', $loggedUser) // jangan tampilkan user sendiri
        ->select('user.id_user', 'user.username', 'user.email', 'user.foto')
        ->distinct()
        ->get();

        ActivityLog::create([
            'action' => 'view',
            'user_id' => $loggedUser,
            'description' => 'User membuka Friend List',
        ]);

        echo view('header');
        echo view('menu');
        echo view('friend', compact('friends'));
        echo view('footer');
    }


    /** Show add friend page */
    public function add_friend()
    {
        ActivityLog::create([
            'action' => 'view',
            'user_id' => Session::get('id'),
            'description' => 'User membuka add friend',
        ]);

        echo view('header');
        echo view('menu');
        echo view('add_friend');
        echo view('footer');
    }



    public function store_friend(Request $request)
    {
        $loggedUser = Session::get('id');
        $target = $request->id_user;

        // Prevent duplicate
        if (Friend::where('id_user', $loggedUser)
            ->where('id_user_friended', $target)
            ->exists())
        {
            return response()->json(['status' => 'exist']);
        }

        Friend::create([
            'id_user' => $loggedUser,
            'id_user_friended' => $target,
            'status' => 0, // pending request
        ]);

        return response()->json(['status' => 'pending']);
    }



    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        $loggedUser = Session::get('id');

        $users = User::where('id_user', '!=', $loggedUser)
            ->where(function($q) use ($keyword) {
                $q->where('username', 'like', "%$keyword%")
                ->orWhere('email', 'like', "%$keyword%");
            })
            ->get();

        $result = $users->map(function($user) use ($loggedUser) {
            // Cek apakah ada record friend
            $friend = Friend::where(function($q) use ($loggedUser, $user) {
                $q->where('id_user', $loggedUser)->where('id_user_friended', $user->id_user);
            })->orWhere(function($q) use ($loggedUser, $user) {
                $q->where('id_user', $user->id_user)->where('id_user_friended', $loggedUser);
            })->first();

            if ($friend) {
                if ($friend->status == 1) {
                    $relation = 'added';
                } else {
                    // cek arah request
                    $relation = $friend->id_user_friended == $loggedUser ? 'incoming' : 'pending';
                }
                $friend_id = $friend->id;
            } else {
                $relation = 'none';
                $friend_id = null;
            }

            return [
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'foto' => $user->foto,
                'relation' => $relation,
                'friend_id' => $friend_id
            ];
        });

        return response()->json($result);
    }

    public function incoming_requests()
    {
        $loggedUser = Session::get('id');

        $requests = Friend::where('id_user_friended', $loggedUser)
            ->where('status', 0)
            ->join('user', 'user.id_user', '=', 'friend.id_user')
            ->select('friend.id_friend', 'user.username', 'user.email', 'user.foto')
            ->get();

        return view('friend_request', compact('requests'));
    }

    public function accept($id)
    {
        $loggedUser = Session::get('id');

        $friend = Friend::findOrFail($id);

        // Pastikan hanya penerima yang bisa accept request
        if ($friend->id_user_friended != $loggedUser) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Update status menjadi friend
        $friend->status = 1;
        $friend->save();

        // Ambil info user yang mengirim request (id_user)
        $user = User::find($friend->id_user);

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'foto' => $user->foto
            ]
        ]);
    }

    public function search_friend_accept(Request $request)
    {
        $loggedUser = Session::get('id');
        $userSender = $request->id_user; 

        $friend = Friend::where('id_user', $userSender)
            ->where('id_user_friended', $loggedUser)
            ->where('status', 0)
            ->first();

        if (!$friend) {
            return response()->json(['error' => 'Friend request not found'], 404);
        }

        // Update status menjadi accepted
        $friend->status = 1;
        $friend->save();

        // Ambil info pengguna pengirim request
        $user = User::find($userSender);

        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'foto' => $user->foto
            ]
        ]);
    }

    public function reject($id)
    {
        $loggedUser = Session::get('id');

        $friend = Friend::findOrFail($id);

        // Pastikan hanya penerima yang boleh reject
        if ($friend->id_user_friended != $loggedUser) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Delete request
        $friend->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Friend request rejected'
        ]);
    }

    public function pendingCount()
    {
        $userId = session('id');

        $count = Friend::where('id_user_friended', $userId)
                    ->where('status', 0)
                    ->count();

        return response()->json(['count' => $count]);
    }

    public function unfriend($id_user)
    {
        $idLogin = Session::get('id');

        // cari relasi friend, dua arah
        $friend = Friend::where(function ($q) use ($id_user, $idLogin) {
                        $q->where('id_user', $id_user)
                        ->where('id_user_friended', $idLogin);
                    })
                    ->orWhere(function ($q) use ($id_user, $idLogin) {
                        $q->where('id_user', $idLogin)
                        ->where('id_user_friended', $id_user);
                    })
                    ->first();

        if (!$friend) {
            return response()->json([
                'status' => 'error',
                'message' => 'Friend not found'
            ], 404);
        }

        $friend->delete();

        return response()->json(['status' => 'success']);
    }


}
