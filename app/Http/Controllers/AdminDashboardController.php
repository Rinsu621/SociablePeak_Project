<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ad;
use App\Models\Business;
use App\Models\Report;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalReports = Report::count();

        $totalBusinesses=Business::count();
        $userGrowth = User::selectRaw('count(*) as count, MONTH(created_at) as month')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->pluck('count', 'month');


        $businessGrowth = Business::selectRaw('count(*) as count, MONTH(created_at) as month')
    ->whereYear('created_at', now()->year)
    ->groupBy('month')
    ->pluck('count', 'month');

// Convert to the format expected by Chart.js
$userGrowthLabels = $userGrowth->keys()->map(function ($month) {
return Carbon::create()->month($month)->format('F');
});

$userGrowthData = $userGrowth->values();
$businessGrowthLabels = $businessGrowth->keys()->map(fn($month) => Carbon::create()->month($month)->format('F'));
$businessGrowthData = $businessGrowth->values();
 $totalBusinessAds = Ad::count();

return view('admin.dashboard', compact('totalUsers', 'totalReports', 'userGrowthLabels', 'userGrowthData', 'totalBusinesses',
    'businessGrowthLabels',
    'businessGrowthData','totalBusinessAds'));
    }
}
