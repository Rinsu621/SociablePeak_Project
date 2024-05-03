<?php

namespace App\Http\Controllers;

use App\Models\UserEngagement;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index(){
        return view('analytics.index');
    }

}
