<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; // Tambahkan ini
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function login()
    {

        if (!session()->isStarted()) {
            session()->start();
        }
        
        $this->generateCaptcha();
        
        Log::info("Login page accessed - Session ID: " . session()->getId());
        Log::info("Captcha generated: " . Session::get('captcha'));
        
        return view('login');
    }

    public function aksi_login(Request $request)
    {

        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
    
        if ($request->filled('backup_captcha')) {
            $sessionCaptcha = Session::get('captcha');

            if (!$sessionCaptcha) {
                return back()
                    ->withErrors(['backup_captcha' => 'CAPTCHA expired. Please try again.'])
                    ->withInput($request->only('username'));
            }

            if (strtolower($request->backup_captcha) !== strtolower($sessionCaptcha)) {
                
                $this->generateCaptcha();
                
                return back()
                    ->withErrors(['backup_captcha' => 'Invalid CAPTCHA'])
                    ->withInput($request->only('username'));
            }
            
        }

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            
            if ($request->filled('backup_captcha')) {
                $this->generateCaptcha();
            }
            
            return back()
                ->withErrors(['loginError' => 'Invalid username or password'])
                ->withInput($request->only('username'));
        }

        // Password check
        if ($user && $request->password === $user->password) {

            Log::info("============ LOGIN SUCCESS: {$user->username} ============");

            $request->session()->regenerate();
            
            Session::forget('captcha');
            
            $request->session()->put([
                'id' => $user->id_user,
                'level' => $user->level,
                'username' => $user->username,
                'logged_in' => true,
                'login_time' => now()->timestamp
            ]);
            
            $request->session()->save();
            
            return redirect()->intended(route('dashboard'));
        }
        
        if ($request->filled('backup_captcha')) {
            $this->generateCaptcha();
        }
        
        return back()
            ->withErrors(['loginError' => 'Invalid username or password'])
            ->withInput($request->only('username'));
    }

    private function generateCaptcha()
    {
        $captcha_code = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 6);
        Session::put('captcha', $captcha_code);
        Session::save();
        
        return $captcha_code;
    }

    public function captcha()
    {

        if (!session()->isStarted()) {
            session()->start();
        }
        
        $captcha_code = Session::get('captcha');
        
        if (!$captcha_code) {
            $captcha_code = $this->generateCaptcha();
        }
        
        header('Content-Type: image/png');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        
        $image = imagecreatetruecolor(150, 50);
        $background_color = imagecolorallocate($image, 255, 255, 255);
        $text_color = imagecolorallocate($image, 0, 0, 0);
        $line_color = imagecolorallocate($image, 200, 200, 200);
        
        imagefilledrectangle($image, 0, 0, 150, 50, $background_color);
        
        for ($i = 0; $i < 5; $i++) {
            imageline($image, rand(0, 150), rand(0, 50), rand(0, 150), rand(0, 50), $line_color);
        }
        
        imagestring($image, 5, 35, 15, $captcha_code, $text_color);
        
        imagepng($image);
        imagedestroy($image);
        exit; 
    }



    public function logout()
    {
        Session::flush();
        return redirect()->route('login')->with('success', 'Logout successful');
    }

    public function register()
    {

        echo view('register');
    }

    public function tambah_akun(Request $request)
    {
        try {
            $request->validate([
                'username' => 'required',
                'email' => 'required|email|unique:user,email',
                'password' => 'required|min:4',
                'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $user = new User();
            $user->username = $request->username;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->level = 'User';

            // Simpan foto ke storage/app/public/profile
            if ($request->hasFile('foto')) {
                $path = $request->file('foto')->store('profile', 'public');
                $user->foto = basename($path); // contoh: "profile/xxxxx.jpg"
            }

            $user->save();

            return redirect()->route('login')->with('success', 'Akun berhasil dibuat.');

        } catch (\Exception $e) {
            Log::error('Gagal membuat akun: ' . $e->getMessage());
            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan akun.']);
        }
    }
}