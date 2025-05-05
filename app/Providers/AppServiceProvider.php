<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ProfilePicture;
use App\Models\Notification;
use App\Models\Business;
use App\Models\BusinessProfilePicture;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('*', function ($view) {
            $user=auth()->user();
            $userId = auth()->id();
            $unreadCount = 0;
            $notifications = collect();
            $profilePicture = null;
            $viewedUser = null;
            $businessName = null;

            if (auth()->guard('business')->check()) {
                $business = auth()->guard('business')->user();
                $profilePicture = BusinessProfilePicture::where('business_id', $business->id)
                    ->latest('id')
                    ->first();
                $businessName = $business->name ?? null;
            }

            if ($userId) {
                $unreadCount = Notification::where('user_id', $userId)
                                           ->where('is_read', false)
                                           ->count();

                $notifications = Notification::where('user_id', $userId)
                                             ->orderBy('created_at', 'desc')
                                             ->limit(10)
                                             ->get();

                $viewedUser = User::find($userId);
            }

            $view->with('unreadCount', $unreadCount)
                 ->with('notifications', $notifications)
                 ->with('profilePicture', $profilePicture)
                 ->with('viewedUser', $viewedUser)
                 ->with('businessName', $businessName);
        });

        // View::composer('*', function ($view) {
        //     $view->with('user', Auth::user());
        // });
    }

    public function register(): void
    {
        //
    }
}
