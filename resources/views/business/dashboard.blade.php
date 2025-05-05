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
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('followerGrowthChart').getContext('2d');

        const labels = @json($followerGrowthLabels); // Dynamic labels
        const data = {
            labels: labels,
            datasets: [{
                label: 'Follower Growth',
                data: @json($followerGrowthData), // Dynamic data
                fill: false,
                borderColor: 'rgb(153, 102, 255)', // Color change for differentiation
                tension: 0.1
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                responsive: true,
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Month'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Number of Followers'
                        }
                    }
                }
            }
        };

        new Chart(ctx, config);
    });
</script>

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
                                <!-- Icon for Total Comments -->
                                <i class="ri-chat-3-line"></i>
                            </div>
                            <div>
                                <h5 class="card-title mb-0" style="color: white">Total Followers</h5>
                                <h2 class="card-text" style="color: white">{{ $totalFollowers }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

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




    </div>

    <div class="row mt-5">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Follower Growth Over Time</h5>
                    <canvas id="followerGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Follower Growth Over Time</h5>
                <canvas id="followerGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div> --}}





@endsection

