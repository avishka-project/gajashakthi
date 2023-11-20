@extends('layouts.app')

@section('content')

<main>
    <div class="d-flex justify-content-between align-items-sm-center flex-column flex-sm-row mb-1 mt-0 p-2">
        <div class="me-4 mb-3 mb-sm-0">
            <h1 class="mb-0">Dashboard</h1>
            <div class="small">
                <span class="fw-500 text-primary"><?= date("l") ?></span>
                <?= date("jS \of F Y") ?>
                <span id="clock" onload="showTime()"></span>
            </div>
        </div>
    </div>
   <div class="container-fluid mt-0 p-0 p-2">
        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">

                <!-- Dashboard info widget 1-->
                <div class="card border-top-0 border-right-0 border-bottom-0 border-lg rounded border-primary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="small fw-bold text-primary mb-1">Total Employees</div>
                                <div class="h5">{{$empcount}}</div>
                                <div class="text-xs fw-bold text-success d-inline-flex align-items-center">
                                    <a href="{{route('addEmployee')}}"> View More <i class="fas fa-external-link-alt"></i> </a>
                                </div>
                            </div>
                            <div class="ms-2">
                                <i class="fas fa-hashtag fa-2x text-primary-soft"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Dashboard info widget 2-->
                <div class="card border-top-0 border-right-0 border-bottom-0 border-lg rounded border-secondary h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fw-bold text-secondary mb-1">Attendance </div>
                                <div class="h5">{{$todaycount}}</div>
                                <div class="text-xs fw-bold text-danger d-inline-flex align-items-center">
                                    <a href="#"> View More <i class="fas fa-external-link-alt"></i> </a>
                                </div>
                            </div>
                            <div class="ms-2">
                                <i class="fas fa-check fa-2x text-gray-200"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Dashboard info widget 3-->
                <div class="card border-top-0 border-right-0 border-bottom-0 border-lg rounded border-success h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="fw-bold text-success mb-1">Late Attendance </div>
                                <div class="h5">1</div>
                                <div class="text-xs fw-bold text-success d-inline-flex align-items-center">
                                    <a href="#"> View More <i class="fas fa-external-link-alt "></i> </a>
                                </div>
                            </div>
                            <div class="ms-2">
                                <i class="fas fa-clock fa-2x text-success-soft"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
                <!-- Dashboard info widget 4-->
                <div class="card border-top-0 border-right-0 border-bottom-0 border-lg rounded border-info h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <div class="small fw-bold text-info mb-1">Absent</div>
                                <div class="h5">{{$empcount-$todaycount}}</div>
                                <div class="text-xs fw-bold text-danger d-inline-flex align-items-center">
                                    <a href="#"> View More <i class="fas fa-external-link-alt "></i> </a>
                                </div>
                            </div>
                            <div class="ms-2">
                                <i class="fas fa-exclamation-circle fa-2x text-info-soft"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12">
                <div class="card shadow-none">
                    <div class="card-header p-2 text-decoration-none text-dark">Attendant of the Employees</div>
                    <div class="card-body">
                        <canvas id="myAreaChart" width="100%" height="30"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>



               
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>

<script>
$(document).ready( function () {

    $('#dashboard_link').addClass('active');

    showTime();

} );

   
</script>



@endsection