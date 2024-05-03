@extends('home.layout')

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
                                            {{$count}}
                                        </td>
                                        <td>
                                            {{$item->date}}
                                        </td>
                                        <td>
                                            {{$item->start_time}}
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
                                            {{$item->tab_switch}}
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
