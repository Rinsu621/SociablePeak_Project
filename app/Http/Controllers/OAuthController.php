<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use App\Models\User;
use Laravel\Socialite\Facades\Socialite;

class OAuthController extends Controller
{


public function generateCaption(Request $request)
{
    $message = $request->input('text');

    if (!$message) {
        return response()->json(['error' => 'No message provided'], 400);
    }

    $apiKey = env('GEMINI_API_KEY');

    if (!$apiKey) {
        \Log::error('Gemini API key missing');
        return response()->json(['error' => 'API key not configured'], 500);
    }

    try {
       $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent?key={$apiKey}";


        $response = Http::post($url, [
            'contents' => [
                [
                    'parts' => [
                        [
                            'text' => "Generate a creative and catchy Instagram caption for this description: {$message}. Keep it under 200 characters."
                        ]
                    ]
                ]
            ]
        ]);

        if ($response->failed()) {
            \Log::error('Gemini API request failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            throw new \Exception("Gemini API request failed");
        }

        $data = $response->json();

        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return response()->json([
                'caption' => trim($data['candidates'][0]['content']['parts'][0]['text'])
            ]);
        }

        return response()->json([
            'caption' => $this->generateFallbackCaption($message)
        ]);

    } catch (\Exception $e) {
        \Log::error('Caption generation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json([
            'caption' => $this->generateFallbackCaption($message)
        ]);
    }
}

// Fallback caption generator based on input
private function generateFallbackCaption($message)
{
    // Example fallback logic: Generate simple captions based on keywords
    $message = strtolower($message);

    // Food related
    if (strpos($message, 'food') !== false || strpos($message, 'eat') !== false || strpos($message, 'dinner') !== false || strpos($message, 'lunch') !== false || strpos($message, 'breakfast') !== false) {
        return "Good food, good mood! 🍽️";
    }

    // Travel related
    if (strpos($message, 'travel') !== false || strpos($message, 'vacation') !== false || strpos($message, 'trip') !== false || strpos($message, 'adventure') !== false) {
        return "Adventure awaits! ✈️";
    }

    // Nature/outdoors
    if (strpos($message, 'nature') !== false || strpos($message, 'outdoor') !== false || strpos($message, 'hiking') !== false || strpos($message, 'mountain') !== false) {
        return "Nature is calling! 🌲";
    }

    // Beach related
    if (strpos($message, 'beach') !== false || strpos($message, 'ocean') !== false || strpos($message, 'sea') !== false || strpos($message, 'sunset') !== false) {
        return "Sandy toes, sun-kissed nose! 🌊";
    }

    // Selfie/portrait
    if (strpos($message, 'selfie') !== false || strpos($message, 'portrait') !== false || strpos($message, 'photo') !== false) {
        return "A perfect moment captured! 📸";
    }

    // Work/business
    if (strpos($message, 'work') !== false || strpos($message, 'office') !== false || strpos($message, 'business') !== false || strpos($message, 'meeting') !== false) {
        return "Making it happen! 💼";
    }

    // Fitness/health
    if (strpos($message, 'gym') !== false || strpos($message, 'workout') !== false || strpos($message, 'fitness') !== false || strpos($message, 'exercise') !== false) {
        return "Sweat is just fat crying! 💪";
    }

    // Friends/family
    if (strpos($message, 'friend') !== false || strpos($message, 'family') !== false || strpos($message, 'love') !== false || strpos($message, 'together') !== false) {
        return "Surrounded by love! ❤️";
    }

    // Coffee
    if (strpos($message, 'coffee') !== false || strpos($message, 'cafe') !== false) {
        return "Coffee first, then world domination! ☕";
    }

    // Music
    if (strpos($message, 'music') !== false || strpos($message, 'concert') !== false || strpos($message, 'song') !== false) {
        return "Music is life! 🎵";
    }

    // Default fallback
    return "Here's to making memories! ✨";
}


public function redirectToGoogle()
    {
        \Log::info('Redirecting to Google...');
        return Socialite::driver('google')->redirect();
    }

    // Handle the Google callback and log the user in
    public function handleGoogleCallback()
    {
        // Get user information from Google
           $googleUser = Socialite::driver('google')->user();

        // We don't need to store the user, just use their info for generating a caption
        $message = $request->input('text'); // Get message input for caption generation

        // Proceed with the caption generation using the user's Google data (you can use their profile, etc.)

        // Example API call to generate caption based on message
        $caption = $this->generateCaption($message);

        // Return the generated caption as a response
        return response()->json(['caption' => $caption]);
    }
}
