<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BusinessProfilePicture;
use App\Models\Business;
use App\Models\Ad;
use App\Models\AdImage;

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
        $ads = Ad::with('adimages')->where('business_id', $business->id)->latest()->get();




        return view('business.profile', compact('businessName', 'profilePicture', 'ads','userAdsCount'));
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






}
