<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\BusinessProfilePicture;
use App\Models\Business;
use App\Models\Ads;

class BusinessController extends Controller
{
    public function index()
    {
        $business = Auth::guard('business')->user(); // define $business
        $businessName = $business->name;
        $profilePicture = BusinessProfilePicture::where('business_id', $business->id)->first();
        $ads = Ads::where('business_id', $business->id)->latest()->get();

        return view('business.profile', compact('businessName', 'profilePicture', 'ads'));
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

    public function postAd(Request $request)
    {
        $request->validate([
            'title'=>'nullable|string',
            'description'=>'nullable|string',
            'image'=>'nullable|image|mimes:png,jpg,jpeg,gif|max:2048'
        ]);
        $business= Auth::guard('business')->user();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/images/ads');
            Ad::create([
                'business_id' => $business->id,
                'title' => $request->title,
                'description' => $request->description,
                'image_path' => $path,
            ]);
            return redirect()->back()->with('success', 'Ad posted successfully.');
        }

        return redirect()->back()->withErrors('Failed to upload ad image.');
    }
}
