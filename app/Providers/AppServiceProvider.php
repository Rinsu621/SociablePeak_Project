<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilePicture;
use App\Models\Notification;
use App\Models\Business;


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
        View::composer('*', function ($view) {
            $userId = auth()->id();
            $unreadCount = 0;
            $notifications = collect(); // Initialize as an empty collection to avoid undefined variable errors

            if ($userId) {
                $unreadCount = Notification::where('user_id', $userId)
                                           ->where('is_read', false)
                                           ->count();

                $notifications = Notification::where('user_id', $userId)
                                             ->orderBy('created_at', 'desc')
                                             ->limit(10)
                                             ->get();
            }

            // Pass both variables to the view
            $view->with('unreadCount', $unreadCount)
                 ->with('notifications', $notifications);
        });

        View::composer('layouts.layoutBusiness', function ($view) {
            $business = Auth::user(); // Or use Auth::guard('business')->user(); if using custom guard
            $view->with('businessName', $business?->name);
        });


    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
}
