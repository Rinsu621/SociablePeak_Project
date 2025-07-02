@extends('layoutAdmin')
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
        const ctx = document.getElementById('userGrowthChart').getContext('2d');

        const labels = @json($userGrowthLabels); // Dynamic labels
        const data = {
            labels: labels,
            datasets: [{
                label: 'User Growth',
                data: @json($userGrowthData), // Dynamic data
                fill: false,
                borderColor: 'rgb(75, 192, 192)',
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
                            text: 'Number of Users'
                        }
                    }
                }
            }
        };

        const userGrowthChart = new Chart(ctx, config);
    });




    document.addEventListener('DOMContentLoaded', function () {
    const btx = document.getElementById('businessGrowthChart').getContext('2d');

    const businessLabels = @json($businessGrowthLabels);
    const businessData = {
        labels: businessLabels,
        datasets: [{
            label: 'Business Growth',
            data: @json($businessGrowthData),
            fill: false,
            borderColor: '#4caf50',
            tension: 0.1
        }]
    };

    new Chart(btx, {
        type: 'line',
        data: businessData,
        options: {
            responsive: true,
            scales: {
                x: {
                    title: { display: true, text: 'Month' }
                },
                y: {
                    title: { display: true, text: 'Number of Businesses' }
                }
            }
        }
    });
});
</script>
@endsection
@section('content')
<h1>Admin Dashboard</h1>
<div class="container mt-5">
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-4">
          <div class="card text-white mb-3 custom-card" style="background: linear-gradient(135deg, rgba(54, 162, 235, 0.9), rgba(75, 192, 192, 0.85));">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <!-- User Icon -->
                            <i class="fas fa-user fa-3x"></i>
                        </div>
                        <div>
                            <h5 class="card-title mb-0" style="color: white">Total Users</h5>
                            <h2 class="card-text" style="color: white">{{ $totalUsers }}</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Reports Card -->
        <div class="col-md-4">
            <div class="card text-white mb-3 custom-card" style="background: linear-gradient(135deg, rgba(102, 3, 3, 0.85), rgba(139, 0, 0, 0.8));">
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
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">User Growth Over Time</h5>
                <canvas id="userGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div>


<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
    <div class="card text-white mb-3 custom-card" style="background: linear-gradient(135deg, rgba(76, 175, 80, 0.9), rgba(139, 195, 74, 0.85));">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-briefcase fa-3x"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0"style="color: white;">Total Businesses</h5>
                    <h2 class="card-text"style="color: white;">{{ $totalBusinesses }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="col-md-4">
    <div class="card text-white mb-3 custom-card" style="background: linear-gradient(135deg, rgba(255, 152, 0, 0.9), rgba(255, 193, 7, 0.85));">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div class="me-3">
                    <i class="fas fa-bullhorn fa-3x"></i>
                </div>
                <div>
                    <h5 class="card-title mb-0" style="color: white;">Total Business Ads</h5>
                    <h2 class="card-text"  style="color: white;">{{ $totalBusinessAds }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="row mt-5">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Business Growth Over Time</h5>
                <canvas id="businessGrowthChart"></canvas>
            </div>
        </div>
    </div>
</div>


    </div>
</div>


@endsection
