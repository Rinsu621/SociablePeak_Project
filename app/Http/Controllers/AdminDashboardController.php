<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Report;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalReports = Report::count();
        $userGrowth = User::selectRaw('count(*) as count, MONTH(created_at) as month')
        ->whereYear('created_at', Carbon::now()->year)
        ->groupBy('month')
        ->pluck('count', 'month');

// Convert to the format expected by Chart.js
$userGrowthLabels = $userGrowth->keys()->map(function ($month) {
return Carbon::create()->month($month)->format('F');
});

$userGrowthData = $userGrowth->values();

return view('admin.dashboard', compact('totalUsers', 'totalReports', 'userGrowthLabels', 'userGrowthData'));
    }
}
