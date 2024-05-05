@extends('layout')

@section('style ')
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card position-relative inner-page-bg bg-primary" style="height: 150px;">
                <div class="inner-page-title">
                    <h3 class="text-white">User Engagement Data</h3>
                    <p class="text-white">see your engagement time over the time</p>
                </div>
            </div>
        </div>

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('seedUserEngagement') }}" class="btn btn-secondary">
                        Generate Data
                    </a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".bd-example-modal-xl">Engagement Analytics</button>
                    <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog"   aria-hidden="true">
                       <div class="modal-dialog modal-xl">
                          <div class="modal-content">
                             <div class="modal-header">
                                <h5 class="modal-title">Engagement Analytics (Hours/Day)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                
                                </button>
                             </div>
                             <div class="modal-body">
                                <div class="chart">
                                    <canvas id="myChart" width="400" height="200"></canvas>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="chart">
                        <canvas id="myChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div> --}}
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    {{-- <div class="header-title">
                        <h4 class="card-title">Bootstrap Datatables</h4>
                    </div> --}}
                </div>
                <div class="card-body">
                    {{-- <p>Images in Bootstrap are made responsive with <code>.img-fluid</code>. <code>max-width: 100%;</code>
                        and <code>height: auto;</code> are applied to the image so that it scales with the parent element.
                    </p> --}}
                    <div class="table-responsive">
                        <table id="datatable" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>SN</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>Total Time</th>
                                    <th>Tab Switch</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $count = 0; ?>
                                @foreach ($data as $key => $item)
                                    <?php $count++; ?>
                                    <tr>
                                        <td>
                                            {{ $count }}
                                        </td>
                                        <td>
                                            {{ $item->date }}
                                        </td>
                                        <td>
                                            {{ \Carbon\Carbon::parse($item->start_time)->format('h:i:s A') }}
                                            {{-- {{ \Carbon\Carbon::createFromTimestamp($item->start_time)->format('H:i:s') }} --}}
                                        </td>
                                        <td>
                                            <?php
                                            // Milliseconds value
                                            $seconds = $item->elapsed_time;
                                            
                                            // Create a DateTime object
                                            $date = new DateTime("@$seconds");
                                            
                                            // Format the date as time (HH:MM:SS)
                                            $time = $date->format('H:i:s');
                                            
                                            echo $time; // Output: 00:41:08
                                            ?>
                                        </td>
                                        <td>
                                            {{ $item->tab_switch }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>SN</th>
                                    <th>Date</th>
                                    <th>Start Time</th>
                                    <th>Total Time</th>
                                    <th>Tab Switch</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        var labelData = <?php echo $labels;?>;
        var barChartData = {
            labels: labelData,
            datasets: [
                {
                    label: 'Total Time',
                    backgroundColor: "#51A351",
                    // backgroundColor: function(context) { //change background color from green to red if engagement time is more than 10 hours
                    //     // Get the data value at the current index
                    //     var value = context.dataset.data[context.dataIndex];
                    //     // Return red if the value is more than 10, otherwise default color
                    //     return value > 10 ? '#ff0000' : '#51A351';
                    // },
                    data: <?php echo $elapsedTimeData;?>
                }, 
                {
                    label: 'Tab Switches',
                    backgroundColor: "#50b5ff",
                    data: <?php echo $tabSwitchData;?>
                }
            ]
        };

        var chartOptions = {
            responsive: true,
            legend: {
                position: "top",
            },
            title: {
                display: true,
                text: "Chart.js Bar Chart"
            },
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }

        window.onload = function() {
            var ctx = document.getElementById("myChart").getContext("2d");
            window.myBar = new Chart(ctx, {
                type: "bar",
                data: barChartData,
                options: chartOptions
            });
        };
    </script>
@endsection
