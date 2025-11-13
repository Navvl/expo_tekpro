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
    
    public function note($id_room)
    {
        $room = Room::findOrFail($id_room);
        $note = Note::where('id_room', $id_room)->get();

        ActivityLog::create([
            'action' => 'view',
            'user_id' => Session::get('id'),
            'description' => 'User membuka room ' . $room->room_title,
        ]);

        echo view('header');
        echo view('menu');
        echo view('note', compact('note', 'room'));
        echo view('footer');
    }

    public function t_note(Request $request)
    {
        ActivityLog::create([
            'action' => 'create',
            'user_id' => Session::get('id'),
            'description' => 'User menambah Note.',
        ]);

        try {

            $request->validate([
                'note_title' => 'required|string',
            ]);

            $note = new Note();
            $note->note_title = $request->note_title;
            $note->id_room = $request->id_room;
            $note->save();
            $pagesCode = 'PGS' . $note->id_note;
            $exists = Note::where('pages_code', $pagesCode)->exists();
            if ($exists) {
                $pagesCode .= rand(10, 99);
            }
            $note->pages_code = $pagesCode;
            $note->save();

            return redirect()->back()->with('success', 'note berhasil ditambahkan');

        } catch (\Exception $e) {

            return redirect()->back()->withErrors(['msg' => 'Gagal menambahkan note.']);
        }
    }

}
