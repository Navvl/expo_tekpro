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


class FriendController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    
    public function friend()
    {
        $user = User::all();

        ActivityLog::create([
            'action' => 'view',
            'user_id' => Session::get('id'),
            'description' => 'User membuka Friend',
        ]);

        echo view('header');
        echo view('menu');
        echo view('friend', compact('user'));
        echo view('footer');
    }

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

    public function search(Request $request)
    {
        $keyword = trim($request->input('keyword'));

        if (strlen($keyword) < 1) {
            return response()->json([]);
        }

        $results = User::where(function ($q) use ($keyword) {
                $q->where('username', 'LIKE', "%$keyword%")
                  ->orWhere('email', 'LIKE', "%$keyword%");
            })
            ->limit(10)
            ->get(['id_user', 'username', 'email']);

        return response()->json($results);
    }

}
