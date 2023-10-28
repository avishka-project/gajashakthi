@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus"></i></div>
                        <span>Employee Attendance</span>
                    </h1>
                </div>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-12">
    
                            <a href="{{ route('empattendanceadd') }}"   class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-plus mr-2"></i>Add Attendance</a>
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
    
                            <table class="table table-striped table-bordered table-sm small" id="dataTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Branch</th>
                                        <th>Date</th>
                                        <th>Shift</th>
                                        <th>Holiday Type</th>
                                        <th class="text-right">Action</th>
                                    </tr>
                                </thead>
    
                                <tbody>
                                </tbody>
                            </table>
    
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </main>

@endsection


@section('script')

    <script>
     $(document).ready(function () {

        $('#empmanagementlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#collapseemployee').addClass('show');
            $('#security_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#security_staff_collapse').addClass('show');
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empattendace_link').addClass('active');
            
        $('#dataTable').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/attendancelist.php',
                    type: "POST",

                },
                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Employee Details', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { 
                    extend: 'print', 
                    title: 'Employee Attendance',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
            ],
                "order": [[ 0, "desc" ]],
                "columns": [
                    {
                        "data": "id",
                        "className": 'text-dark'
                    },      
                    {
                        "data": "branch_name",
                        "className": 'text-dark'
                    },
                    {
                        "data": "date",
                        "className": 'text-dark'
                    },
                    {
                        "data": "shift_name",
                        "className": 'text-dark'
                    },
                    {
                        "data": "holidayname",
                        "className": 'text-dark'
                    },
                    {
                        "targets": -1,
                        "className": 'text-right',
                        "data": null,
                        "render": function(data, type, full) {

                            var button='';
                            button+='<button class="btn btn-primary btn-sm edit mr-1" id="'+full['id']+'"><i class="fas fa-pen"></i></button>';
                           return button;
                        }
                    }
                   
                ],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });

     });
     

        function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
    </script>

@endsection