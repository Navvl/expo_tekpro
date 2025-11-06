<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Session;

class LogController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User  Masuk ke LogActivity.',
        ]);
        $activityLogs = ActivityLog::orderBy('created_at', 'desc')->get();

        echo view('header');
        echo view('menu');
        echo view('activity_logs.index', compact('activityLogs'));
        echo view('footer');
    }
}
