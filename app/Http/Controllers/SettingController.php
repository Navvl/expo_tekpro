<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function edit()
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'), // ID pengguna yang sedang login
            'description' => 'User Masuk Ke Setting.',
        ]);

        $setting = Setting::first();

        echo view('header');
        echo view('menu');
        echo view('settings.edit', compact('setting'));
        echo view('footer');
    }

    public function update(Request $request)
    {

        $request->validate([
            'site_name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validasi file logo
        ]);

        $setting = Setting::first();
        if (!$setting) {
            $setting = new Setting();
        }

        $setting->site_name = $request->site_name;

        // Upload logo jika ada
        if ($request->hasFile('logo')) {
            // Hapus logo lama jika ada
            if ($setting->logo) {
                Storage::delete('public/logos/' . $setting->logo);
            }
            $logoName = time() . '.' . $request->logo->extension();
            $request->logo->storeAs('public/logos', $logoName);
            $setting->logo = $logoName;
        }

        $setting->save();

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }
}
