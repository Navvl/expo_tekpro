<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use App\Models\Room;
use App\Models\User;
use App\Models\Note;
use App\Models\Page;
use Illuminate\Support\Facades\Session;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function dashboard()
    {
        $userId = Session::get('id');

        ActivityLog::create([
            'action' => 'view',
            'user_id' => $userId,
            'description' => 'User masuk ke Dashboard.',
        ]);

        $user = User::find($userId);

        $totalRooms = Room::where('id_user', $userId)->count();
        $totalNotes = Note::whereHas('room', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->count();

        echo view('header');
        echo view('menu');
        echo view('dashboard', compact('user', 'totalRooms', 'totalNotes'));
        echo view('footer');
    }


}
