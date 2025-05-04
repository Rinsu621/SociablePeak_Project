@extends('layoutBusiness')
@section('style')
<style>
    .custom-card {
            background-color: #50b5ff; /* Skyblue with transparency */
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            transform-style: preserve-3d;
            perspective: 1000px;
        }

        /* Hover Effect */
        .custom-card:hover {
            transform: translateY(-10px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.3);
        }

        .custom-card .card-body {
            transform: translateZ(20px);
        }

        .fas.fa-user {
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
</style>
@endsection


@section('content')
<h1>Business Dashboard</h1>
<div class="container mt-5">
    {{-- <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-4">
            <div class="card text-white mb-3 custom-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <!-- User Icon -->
                            <i class="ri-file-text-line"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0" style="color: white">Total Ads</h5>
                            <h2 class="card-text" style="color: white">{{ $totalAds }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="row">
            <!-- Total Ads Card -->
            <div class="col-md-4">
                <div class="card text-white mb-3 custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <!-- Icon for Total Ads -->
                                <i class="ri-file-text-line"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0" style="color: white">Total Ads</h5>
                                <h2 class="card-text" style="color: white">{{ $totalAds }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Likes Card -->
            <div class="col-md-4">
                <div class="card text-white mb-3 custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <!-- Icon for Total Likes -->
                                <i class="ri-thumb-up-line"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0" style="color: white">Total Likes</h5>
                                <h2 class="card-text" style="color: white">{{ $totalLikes }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Comments Card -->
            <div class="col-md-4">
                <div class="card text-white mb-3 custom-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <!-- Icon for Total Comments -->
                                <i class="ri-chat-3-line"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0" style="color: white">Total Comments</h5>
                                <h2 class="card-text" style="color: white">{{ $totalComments }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <!-- Total Reports Card -->
        {{-- <div class="col-md-4">
            <div class="card text-white mb-3 custom-card" style="background-color: rgba(102, 3, 3, 0.85);">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <i class="fas fa-exclamation-triangle fa-3x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0" style="color: white">Total Reports</h5>
                            <h2 class="card-text" style="color: white">{{ $totalReports }}</h2>
                        </div>
                    </div>
                    <a href="{{ route('admin.reports.index') }}" class="stretched-link"></a>
                </div>
            </div>
        </div> --}}
    </div>
</div>

{{-- <div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">User Growth Over Time</h5>
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div> --}}


@endsection
