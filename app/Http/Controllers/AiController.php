<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function ask(Request $request)
    {
        $prompt = $request->input('prompt');

        if (!$prompt) {
            return response()->json(['error' => 'Prompt required'], 400);
        }

        $finalPrompt = "Tolong kerjakan sesuai instruksi berikut, dan JANGAN menambahkan penjelasan apapun.\n\n".
            "=== INSTRUKSI ===\n".
            $prompt . "\n\n".
            "=== OUTPUT ===\n".
            "Berikan hasilnya langsung tanpa tambahan kalimat lain.";

        $response = Http::post("http://localhost:11434/api/generate", [
            // "model" => "gpt-oss:120b-cloud",
            "model" => "gemma3:1b",
            "prompt" => $finalPrompt,
            "stream" => false
        ]);

        return response()->json([
            "answer" => $response->json("response")
        ]);
    }


}
