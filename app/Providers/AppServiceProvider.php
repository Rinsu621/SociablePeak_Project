<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilePicture;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // View::composer('*', function ($view) {
        //     $user = Auth::user();
        //     $profilePicture = null;

        //     if ($user) {
        //         // Fetch the latest profile picture for the authenticated user
        //         $profilePicture = ProfilePicture::where('user_id', $user->id)->latest()->first();
        //     }

        //     $view->with('profilePicture', $profilePicture);
        // });
       
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
