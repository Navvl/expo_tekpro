<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiController extends Controller
{
    public function ask(Request $request)
    {
        try {
            $prompt = $request->input('prompt');

            if (!$prompt) {
                return response()->json([
                    'error' => 'Prompt is required'
                ], 400);
            }

            $response = Http::post('http://localhost:11434/api/generate', [
                'model' => 'tinyllama',
                'prompt' => $prompt,
                'stream' => false
            ]);

            if ($response->failed()) {
                return response()->json([
                    'error' => 'AI server failed',
                    'details' => $response->body()
                ], 500);
            }

            return response()->json([
                'answer' => $response->json()['response'] ?? ''
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Laravel internal error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
