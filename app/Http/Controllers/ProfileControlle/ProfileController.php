<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\Room;
use App\Models\Note;
use App\Models\UserHistory;
use App\Models\Keterlambatan;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;


class NoteController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function profile()
    {
        $userId = Session::get('id');
        $user = User::findOrFail($userId);

        ActivityLog::create([
            'action' => 'view',
            'user_id' => $userId,
            'description' => 'User membuka halaman profile',
        ]);

        echo view('header');
        echo view('menu');
        echo view('profile', compact('user'));
        echo view('footer');
    }

    public function updateProfile(Request $request)
    {
        $userId = Session::get('id');
        $user = User::findOrFail($userId);

        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'nullable|email',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->username = $request->username;
        $user->email = $request->email;

        if (!empty($request->password)) {
            $user->password = $request->password;
        }

        $user->save();

        ActivityLog::create([
            'action' => 'update',
            'user_id' => $userId,
            'description' => 'User memperbarui profile',
        ]);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }

}
