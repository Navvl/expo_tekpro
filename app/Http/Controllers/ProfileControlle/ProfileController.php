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
            'current_password' => 'nullable',
            'password' => 'nullable|min:6|confirmed',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Update username & email
        $user->username = $request->username;
        $user->email = $request->email;

        // Update password jika ada input baru
        if (!empty($request->password)) {

            // Optional: cek current password (kalau mau strict)
            if (!empty($request->current_password) && md5($request->current_password) !== $user->password) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // Hash password baru
            $user->password = md5($request->password);
        }
        Log::info("asddassaasdasdasds...");
        // Update foto jika ada file baru
        if ($request->hasFile('foto')) {
            Log::info("UPLOADING FOTO...");

            // Check old file
            Log::info("Old file:", [
                'exists' => $user->foto ? file_exists(storage_path('app/public/' . $user->foto)) : null,
                'path' => $user->foto
            ]);

            $path = $request->file('foto')->store('profile', 'public');

            Log::info("NEW PHOTO PATH STORED:", [
                'path' => $path,
                'full_path' => storage_path('app/public/' . $path),
                'file_exists' => file_exists(storage_path('app/public/' . $path)),
            ]);

            $user->foto = $path;
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
