<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('api/gemini/caption-suggest', function (\Illuminate\Http\Request $request) {
    try {
        // Log received prompt
        \Log::debug('Gemini API route triggered');
        $prompt = $request->input('prompt');
        \Log::debug('Received prompt:', ['prompt' => $prompt]);

        // Make a request to the Gemini API
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . env('GEMINI_API_KEY'),  // Your API key from .env
        ])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=' . env('GEMINI_API_KEY'), [
            'contents' => [
                [
                    'parts' => [
                        ['text' => "Suggest a creative and short Instagram caption for: $prompt"]
                    ]
                ]
            ]
        ]);

        // Log the API response
        \Log::debug('Gemini API Response:', $response->json());

        // Check if the response is valid
        if ($response->failed()) {
            \Log::error('Gemini API request failed', ['response' => $response->body()]);
            return response()->json([
                'error' => 'Failed to fetch caption from Gemini API.',
                'details' => $response->body()
            ], 500);
        }

        return response()->json([
            'reply' => $response['candidates'][0]['content']['parts'][0]['text'] ?? 'No caption generated.'
        ]);
    } catch (\Exception $e) {
        \Log::error('Error during Gemini API call', ['error' => $e->getMessage()]);
        return response()->json([
            'error' => 'An error occurred while processing your request.',
            'details' => $e->getMessage()
        ], 500);
    }
});
