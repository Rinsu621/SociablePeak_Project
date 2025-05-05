<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BusinessProfilePicture;
use Illuminate\Support\Facades\DB;
use App\Models\Business;
use App\Models\Ad;
use App\Models\AdLike;
use App\Models\AdComment;
use App\Models\AdImage;
use App\Models\User;
use App\Models\Follow;
use Carbon\Carbon;


class BusinessController extends Controller
{
    public function index()
    {
        $business = Auth::guard('business')->user(); // define $business
        $businessName = $business->name;
        $userAdsCount = Ad::where('business_id', $business->id)->count();
        $profilePicture = BusinessProfilePicture::where('business_id', $business->id)
        ->latest('id') // or ->latest('created_at') if you prefer
        ->first();
        // $ads = Ads::where('business_id', $business->id)->latest()->get();
        $ads = Ad::with('adimages','adLikes.user','adLikes.business')->where('business_id', $business->id)->latest()->get();

        $followersCount = $business->followers()->count();  // Get the number of followers
        $followers = $business->followers;

        return view('business.profile', compact('businessName', 'profilePicture', 'ads','userAdsCount','followersCount', 'followers'));
    }

    public function ProfilePicture(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $business = Auth::guard('business')->user();

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $path = $request->file('image')->store('public/images/profile');

            // Check if the file was uploaded correctly
            if ($path) {
                // Save the new profile picture without deleting the old one
                BusinessProfilePicture::create([
                    'business_id' => $business->id,
                    'file_path' => $path
                ]);

                return redirect()->back()->with('message', 'Profile picture updated successfully.');
            } else {
                return redirect()->back()->withErrors('Failed to upload image.');
            }
        }
    }

    public function postAdStore(Request $request)
{
    try {
        \Log::info('FILES:', $request->allFiles());
        // Retrieve the authenticated business user's ID
        $businessId = auth()->guard('business')->id();

        // Validate request data
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'image.*' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
        ]);

        // Create the new ad post
        $ad = Ad::create([
            'business_id' => $businessId,
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'category' => $request->input('category'),
            'status' => 0, // Default status for 'draft' or 'pending'
            'privacy' => $request->input('privacy', 'public'), // Optional privacy setting, default to 'public'
        ]);

        // Handle image upload if any
        if ($request->hasFile('image')) {
            \Log::info('Files received: ', $request->file('image'));

            foreach ($request->file('image') as $image) {
                // Make sure the file is valid
                if ($image->isValid()) {
                //    $path = $image->move(public_path('images/ads'), $image->getClientOriginalName());
                $path = 'images/ads/' . $image->getClientOriginalName();
                $image->move(public_path('images/ads'), $image->getClientOriginalName());

                    $ad->adimages()->create([
                        'file_path' => $path,
                    ]);
                    \Log::info('File stored at: ' . $path); // Log the stored path
                } else {
                    \Log::error('Invalid file upload');
                }
            }
        }


        // Optionally, set a 'scheduled time' for when the ad should go live
        $set_time = $request->input('set_time');
        if (!empty($set_time)) {
            $ad->set_time = $set_time;
            $ad->status = 0; // Assuming status 0 is for 'scheduled'
            $ad->save();
        }

        // Redirect back with success message and ad count
        $userAdsCount = Ad::where('business_id', $businessId)->count();
        return redirect()->back()->with('message', 'Ad successfully posted')->with('userAdsCount', $userAdsCount);

    } catch (ValidationException $e) {
        // Handle validation errors
        return redirect()->back()->withErrors($e->getMessage())->withInput();

    } catch (Exception $e) {
        // Handle any other exceptions
        return redirect()->back()->withErrors($e->getMessage())->withInput();
    }
}

public function likeAd($id)
{
    try {
        // Determine if a user or business is authenticated
        $userId = auth()->check() ? auth()->id() : null;
        $businessId = auth()->guard('business')->check() ? auth()->guard('business')->id() : null;

        if (!$userId && !$businessId) {
            return response()->json(['success' => false, 'message' => 'Unauthorized.'], 401);
        }

        // Confirm the ad exists
        $ad = Ad::findOrFail($id);

        // Check if already liked
        $like = AdLike::where('ad_id', $id)
            ->when($userId, fn($q) => $q->where('user_id', $userId))
            ->when($businessId, fn($q) => $q->orWhere('business_id', $businessId))
            ->first();

        $liked = false;

        if ($like) {
            $like->delete(); // Unlike
        } else {
            AdLike::create([
                'ad_id' => $id,
                'user_id' => $userId,
                'business_id' => $businessId,
            ]);
            $liked = true;
        } // before $likes_count is defined
        // Return updated like count
        $likes_count = $ad->adLikes()->count();
        \Log::info('Ad liked? ' . json_encode(['liked' => $liked, 'likes_count' => $likes_count]));

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likes_count
        ]);
    } catch (\Exception $e) {
        Log::error('Error in likeAd: ' . $e->getMessage());
        return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
    }
}

    public function storeComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $userId = auth()->check() ? auth()->id() : null;
        $businessId = auth()->guard('business')->check() ? auth()->guard('business')->id() : null;

        if (!$userId && !$businessId) {
            return redirect()->back()->with('error', 'You must be logged in to comment.');
        }

        AdComment::create([
            'ad_id' => $id,
            'user_id' => $userId,
            'business_id' => $businessId,
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('message', 'Comment added.');
    }

    public function dashboard()
    {

        $business = Auth::guard('business')->user(); // define $business
        $businessId = $business->id;
        $totalAds = Ad::where('business_id', $business->id)->count();
        $totalFollowers = $business->followers()->count();

        $ads = Ad::where('business_id', $business->id)
        ->with('adLikes')  // Eager load adLikes
        ->get();

        $totalLikes = $ads->sum(function ($ad) {
            return $ad->adLikes->count();  // Count the likes for each ad
        });
        $totalComments = Ad::where('business_id', $business->id)
                    ->withCount('comments')
                    ->get()
                    ->sum('comments_count');

                    $followerGrowth = DB::table('follows')
                    ->selectRaw('COUNT(*) as count, MONTH(created_at) as month')
                    ->where('following_id', $businessId) // Filter by the business
                    ->whereYear('created_at', Carbon::now()->year) // Filter for this year
                    ->groupBy('month')
                    ->orderBy('month')
                    ->pluck('count', 'month'); // Pluck the count and month values

                // Convert month numbers to human-readable month names (January, February, etc.)
                $followerGrowthLabels = collect($followerGrowth->keys())->map(function ($month) {
                    return Carbon::create()->month($month)->format('F'); // Convert month number to name
                });

                // Get the follower counts for the chart data
                $followerGrowthData = $followerGrowth->values();

        return view('business.dashboard', compact(
            'totalAds',
            'totalLikes',
            'totalComments',
            'totalFollowers',
            'followerGrowthLabels',
             'followerGrowthData'

        ));
    }
}
