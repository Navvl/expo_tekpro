<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class UserLevelController extends Controller
{
    public function index()
    {

        echo view('header');
        echo view('menu');
        echo view('user_levels');
        echo view('footer');
    }

    public function showMenuPermissions($userLevel)
    {

        $validLevels = ['Admin', 'Kepsek', 'Kesiswaan', 'Guru'];
        if (!in_array($userLevel, $validLevels)) {
            abort(404);
        }

        $permissions = Permission::where('user_level', $userLevel)
            ->pluck('has_access', 'menu')
            ->toArray();

        echo view('header');
        echo view('menu');
        echo view('menu_permissions', [
            'userLevel' => $userLevel,
            'permissions' => $permissions
        ]);
        echo view('footer');
    }

    public function savePermissions(Request $request)
    {

        $userLevel = $request->input('user_level');
        $selectedMenus = $request->input('menus', []);

        $allMenus = ['Dashboard', 'Setting', 'Surat']; // Sesuaikan dengan daftar menu Anda

        foreach ($allMenus as $menu) {
            Permission::updateOrCreate(
                ['user_level' => $userLevel, 'menu' => $menu],
                ['has_access' => in_array($menu, $selectedMenus)]
            );
        }

        return redirect()->route('user.levels')->with('success', 'Permissions saved successfully');
    }
}
