<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserLevelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RestoreEditController;
use App\Http\Controllers\RestoreDeleteController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\FriendController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');
Route::get('/login', [LoginController::class, 'login'])->name('login');
Route::post('/aksi_login', [LoginController::class, 'aksi_login'])->name('aksi_login');
Route::get('/register', [LoginController::class, 'register'])->name('register');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/tambah_akun', [LoginController::class, 'tambah_akun'])->name('tambah_akun');
Route::get('/captcha', [LoginController::class, 'captcha'])->name('captcha');


// ROUTE SETTING
Route::get('settings', [SettingController::class, 'edit'])
    ->middleware('check.permission:setting')
    ->name('settings.edit');
Route::post('settings', [SettingController::class, 'update'])
    ->name('settings.update');

// ROUTE LOG ACTIVITY
Route::get('log', [LogController::class, 'index'])
    ->middleware('check.permission:setting')
    ->name('log');

// ROUTE PERMISSION
Route::get('/user-levels', [UserLevelController::class, 'index'])
    ->middleware('check.permission:setting')
    ->name('user.levels');
Route::get('/menu-permissions/{userLevel}', [UserLevelController::class, 'showMenuPermissions'])
    ->name('menu.permissions');
Route::post('/save-permissions', [UserLevelController::class, 'savePermissions'])
    ->name('save.permissions');

// ROUTE RESTORE EDIT
Route::get('/restore_e', [RestoreEditController::class, 'restore_e'])
    ->middleware('check.permission:setting')
    ->name('restore_e');
Route::post('/user/restore/{id_user}', [RestoreEditController::class, 'restoreEdit'])->name('user.restoreEdit');
Route::delete('/user_history/{id_user_history}', [RestoreEditController::class, 're_destroy'])->name('re.destroy');

// ROUTE RESTORE DELETE
Route::get('/restore_d', [RestoreDeleteController::class, 'restore_d'])
    ->middleware('check.permission:setting')
    ->name('restore_d');
Route::post('/user/restore-delete/{id}', [RestoreDeleteController::class, 'user_restore'])->name('user.restore');
Route::delete('/user/{id}', [RestoreDeleteController::class, 'rd_destroy'])->name('rd.destroy');

// ROUTE USER
Route::get('/user', [UserController::class, 'user'])
    ->middleware('check.permission:setting')
    ->name('user');
Route::post('/t_user', [UserController::class, 't_user'])->name('t_user');
Route::post('/user/reset-password/{id}', [UserController::class, 'resetPassword'])->name('user.resetPassword');
Route::post('/user/update', [UserController::class, 'updateDetail'])->name('update.user');
Route::delete('/user-destroy/{id_user}', [UserController::class, 'user_destroy'])->name('user.destroy');
Route::get('/user/detail/{id}', [UserController::class, 'detail'])->name('detail');

// ROUTE ROOM
Route::get('/myroom', [RoomController::class, 'myroom'])
    ->middleware('check.permission:setting')
    ->name('myroom');
Route::post('/t_room', [RoomController::class, 't_room'])->name('t_room');
Route::delete('/room-destroy/{id_room}', [RoomController::class, 'room_destroy'])->name('room.destroy');
Route::post('/invite_user/{id_room}', [RoomController::class, 'invite_user'])->name('invite_user');

Route::get('/all_room', [RoomController::class, 'all_room'])
    ->middleware('check.permission:setting')
    ->name('all_room');
Route::delete('/room-quit/{id_room}', [RoomController::class, 'quit_room'])->name('room.quit');

// ROUTE NOTE
Route::get('/note/{id_room}', [NoteController::class, 'note'])
    ->middleware('check.permission:setting')
    ->name('note');
Route::post('/t_note', [NoteController::class, 't_note'])->name('t_note');
Route::post('/e_note/{id_note}', [NoteController::class, 'e_note'])->name('e_note');
Route::post('/note/store', [NoteController::class, 'store'])->name('note.store');
Route::delete('/delete_note/{id_note}', [NoteController::class, 'delete_note']);

//ROUTE PAGE
Route::post('/page/store', [PageController::class, 'store'])->name('page.store');
Route::post('/page/getByCode', [PageController::class, 'getByCode'])->name('page.getByCode');
Route::get('/pages/{id}', [PageController::class, 'pages'])->name('page.pages');
Route::post('/page/update/{id}', [PageController::class, 'update']);
Route::post('/page/updateTitle', [PageController::class, 'updateTitle']);
Route::delete('/pages/{id}/delete', [PageController::class, 'destroy']);

// ROUTE PROFILE
Route::get('/profile', [UserController::class, 'profile'])
    ->middleware('check.permission:setting')
    ->name('profile');
Route::post('/profile/update', [UserController::class, 'updateProfile'])
    ->middleware('check.permission:setting')
    ->name('profile.update');



// ROUTE FRIEND
Route::get('/friend', [FriendController::class, 'friend'])
    ->middleware('check.permission:setting')
    ->name('friend');
Route::get('/add_friend', [FriendController::class, 'add_friend'])
    ->middleware('check.permission:setting')
    ->name('add_friend');
 Route::get('/friend/search', [FriendController::class, 'search'])
    ->name('friend.search');