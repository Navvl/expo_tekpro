<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Note;
use App\Models\Room;
use App\Models\Access;
use Illuminate\Support\Facades\Session;

use Illuminate\Support\Facades\Auth;

class PageController extends Controller
{

    public function pages($id)
    {
        $note = Note::findOrFail($id);
        $loginId = Session::get('id');

        $room = Room::findOrFail($note->id_room);

        if ($room->id_user != $loginId) {

            if ($room->id_access) {

                $access = Access::find($room->id_access);

                if ($access) {
                    $allowedUsers = explode(',', $access->id_user);

                    if (!in_array($loginId, $allowedUsers)) {
                        return abort(403, 'You do not have access to these pages.');
                    }
                } else {
                    return abort(403, 'Access permission data not found.');
                }

            } else {
                return abort(403, 'You do not have access to these pages.');
            }
        }

        $pages = Page::where('pages_code', $note->pages_code)
                    ->orderBy('created_at', 'asc')
                    ->get();

        echo view('header');
        echo view('menu');
        echo view('pages', compact('note', 'pages'));
        echo view('footer');
    }


    public function store(Request $request)
    {
        $request->validate([
            'page_title' => 'required|string|max:255',
            'page_field' => 'nullable|string',
            'pages_code' => 'required|string',
            'id_page' => 'nullable|integer' // tambahkan id_page opsional
        ]);

        $content = trim($request->page_field ?? '');

        // Kalau kosong, jangan disimpan
        if ($content === '' || $content === '<p><br></p>') {
            return response()->json([
                'success' => false,
                'message' => 'Konten kosong, tidak disimpan'
            ], 400);
        }

        if ($request->filled('id_page')) {
            // === UPDATE ===
            $page = Page::find($request->id_page);
            if (!$page) {
                return response()->json([
                    'success' => false,
                    'message' => 'Page tidak ditemukan untuk diupdate'
                ], 404);
            }

            $page->page_title = $request->page_title;
            $page->page_field = $content;
            $page->updated_at = now();
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'Page berhasil diperbarui',
                'page_id' => $page->id_page
            ]);
        } else {
            // === CREATE ===
            $page = new Page();
            $page->page_title = $request->page_title;
            $page->page_field = $content;
            $page->pages_code = $request->pages_code;
            $page->id_user = Auth::id();
            $page->created_at = now();
            $page->updated_at = now();
            $page->save();

            return response()->json([
                'success' => true,
                'message' => 'Page baru berhasil dibuat',
                'page_id' => $page->id_page
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->page_title = $request->page_title;
        $page->page_field = $request->page_field;
        $page->updated_at = now();
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Page berhasil diupdate',
            'page_id' => $page->id_page
        ]);
    }


    public function getByCode(Request $request)
    {
        $request->validate([
            'pages_code' => 'required|string'
        ]);

        $pages = Page::where('pages_code', $request->pages_code)
                    ->orderBy('created_at', 'asc')
                    ->get();

        if ($pages->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $pages
        ]);
    }

    public function updateTitle(Request $request)
    {
        $request->validate([
            'id_page' => 'required|integer',
            'page_title' => 'required|string|max:255'
        ]);

        $page = Page::find($request->id_page);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found.'
            ], 404);
        }

        $page->page_title = $request->page_title;
        $page->updated_at = now();
        $page->save();

        return response()->json([
            'success' => true,
            'message' => 'Title updated successfully.'
        ]);
    }

    public function destroy($id)
    {
        $page = Page::find($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Note not found'
            ], 404);
        }

        $page->delete();

        return response()->json([
            'success' => true,
            'message' => 'Note deleted successfully'
        ]);
    }


}
