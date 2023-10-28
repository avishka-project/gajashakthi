@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">

                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus"></i></div>
                        <span>Security Attendance Approve</span>
                    </h1>
                </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <form method="post" id="formTitle" class="form-horizontal">
                        <div class="row">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Client*</label>
                                    <select name="customer" id="customer"
                                        class="form-control form-control-sm" required>
                                        <option value="">Select Client</option>
                                        @foreach($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Branch*</label>
                                    <select name="area" id="area" class="form-control form-control-sm"
                                        required>
                                        <option value="">Select Branch</option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">Search Employee*</label>
                                    <select name="serviceno" id="serviceno" class="form-control form-control-sm">
                                    <option value="">Select Service No</option>
                                    
                                    </select>
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">From Date*</label>
                                    <input type="date" class="form-control form-control-sm" name="fromdate" id="fromdate">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">To Date*</label>
                                    <input type="date" class="form-control form-control-sm" name="todate" id="todate">
                                </div>
                        </div>
                        <div class="row">
                            <div class="col-12" style="padding-top: 30px;">
                                <button type="button" id="serachbtn" class="btn btn-primary btn-sm px-4 fa-pull-right" ><i class="fas fa-search"></i>&nbsp;Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <br>
        <div class="container-fluid">
            <div class="card">
                <div class="card-body p-0 p-2">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-end">
                    @if ($permission_level1 == 1 || $permission_level2 == 1 || $permission_level3 == 1)
                            <button name="approve" id="approve" class=" btn btn-info btn-sm px-4" style="padding-right: 30px;"><i class="fas fa-check"></i>&nbsp;Approve</button>&nbsp;&nbsp;
                    @else

                    @endif
                            @can('empattendance-delete')
                            <button name="delete" id="delete" class=" btn btn-danger btn-sm px-4 "><i class="far fa-trash-alt"></i>&nbsp;Delete</button>
                        @endcan
                    
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
                            <div class="custom-control custom-checkbox ml-2 mb-2">
                                <input type="checkbox" class="custom-control-input checkallocate" id="selectAll">
                                <label class="custom-control-label" for="selectAll">Select All Records</label>
                            </div>
                            <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" id="dataTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>#</th>
                                        <th>Branch</th>
                                        <th>Employee Name</th>
                                        <th>Service No</th>
                                        <th>Date</th>
                                        <th>Shift</th>
                                        <th>Holiday Type</th>
                                        <th>On Time</th>
                                        <th>Off Time</th>
                                        <th class="text-right">Action</th>
                                        <th class="d-none">permission level</th>
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
        </div>


     <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-md">
         <div class="modal-content">
             <div class="modal-header p-2">
                 <h5 class="modal-title" id="staticBackdropLabel">Approve Security Attendance </h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                <div class="row">
                    <div class="col text-center">
                        <h4 class="font-weight-normal">Are you sure you want to Approve this data?</h4>
                    </div>
                </div>
            </div>
             <div class="modal-footer p-2">
                 <button type="button" name="approve_button" id="approve_button"
                     class="btn btn-warning px-3 btn-sm">Approve</button>
                 <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
             </div>
         </div>
     </div>
 </div>
{{-- delete model --}}
 <div class="modal fade" id="confirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
 aria-labelledby="staticBackdropLabel" aria-hidden="true">
 <div class="modal-dialog modal-dialog-centered modal-md">
     <div class="modal-content">
         <div class="modal-header p-2">
            <h5 class="modal-title" id="staticBackdropLabel">Delete Security Attendance </h5>
             <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                 <span aria-hidden="true">&times;</span>
             </button>
         </div>
         <div class="modal-body">
             <div class="row">
                 <div class="col text-center">
                     <h4 class="font-weight-normal">Are you sure you want to remove those data?</h4>
                 </div>
             </div>
         </div>
         <div class="modal-footer p-2">
             <button type="button" name="ok_button" id="ok_button" class="btn btn-danger px-3 btn-sm">OK</button>
             <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
         </div>
     </div>
 </div>
</div>

{{-- delete model --}}
<div class="modal fade" id="confirmModal2" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-md">
    <div class="modal-content">
        <div class="modal-header p-2">
           <h5 class="modal-title" id="staticBackdropLabel">Delete Security Attendance </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col text-center">
                    <h4 class="font-weight-normal">Are you sure you want to remove this data?</h4>
                </div>
            </div>
        </div>
        <div class="modal-footer p-2">
            <button type="button" name="ok_button2" id="ok_button2" class="btn btn-danger px-3 btn-sm">OK</button>
            <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>
</div>
        
    </main>

@endsection


@section('script')

    <script>
        $(document).ready(function(){

          var approvelevel1 = {!! json_encode($permission_level1) !!};
          var approvelevel2 = {!! json_encode($permission_level2) !!};
          var approvelevel3 = {!! json_encode($permission_level3) !!};


          $('#collapse_employee_info').addClass('show');
            $('#collapsattendance').addClass('show');
            $('#security_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#security_staff_collapse').addClass('show');
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empattendaceapprove_link').addClass('active');

            function load_dt(branch, fromdate, todate,employee) {
                
                  $('#dataTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: scripturl + '/attendanceapprovelist.php',
                        type: "POST",
                        data: {
                            'branch':branch, 
                           'employee':employee, 
                           'fromdate': fromdate, 
                           'todate': todate
                        },

                    },
                    dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
                    responsive: true,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, 'All']
                    ],
                    "buttons": [{
                            extend: 'csv',
                            className: 'btn btn-success btn-sm',
                            title: 'Employee Details',
                            text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                        },
                        {
                            extend: 'print',
                            title: 'Employee Attendance',
                            className: 'btn btn-primary btn-sm',
                            text: '<i class="fas fa-print mr-2"></i> Print',
                            customize: function (win) {
                                $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                            },
                        },
                    ],
                    "order": [
                        [1, "desc"]
                    ],
                    "columns": [
                        {
                        "data": null,
                        "orderable": false, 
                        "render": function (data, type,full) {
                            var checkbox='';
                            if (full['approve_status'] == 0) {
                                checkbox+= '<input type="checkbox" class="row-select-checkbox selectCheck removeIt">';
                            }else{

                            }
                            return checkbox;
                        }
                        },
                        {
                            "data": "id",
                            "className": 'text-dark'
                        },
                        {
                            "data": "branch_name",
                            "className": 'text-dark'
                        },
                        {
                            "data": "emp_name_with_initial",
                            "className": 'text-dark'
                        },
                        {
                            "data": "emp_serviceno",
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
                            "data": "ontime",
                            "className": 'text-dark'
                        },
                        {
                            "data": "outtime",
                            "className": 'text-dark'
                        },
                        {
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function (data, type, full) {
                                var button='';

                            if (full['approve_status'] == 1) {
                                button+='<button name="deletebyone" id="'+ full['id'] + '" class="deletebyone btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                            }
                            return button;
                            }
                        },
                        {
                            "targets": -1,
                            "className": 'd-none',
                            "data": null,
                            "name": "applevelcol",
                            "render": function (data, type, full) {
                                var applevel=0;

                            if (full['approve_status'] == 0) {
                                if (full['approve_01'] == 0) {
                                    
                                    applevel=1;
                                } else if (full['approve_01'] == 1 && full['approve_02'] == 0) {
                                    
                                    applevel=2;
                                   
                                } else if (full['approve_02'] == 1 && full['approve_03'] == 0) {
                                    applevel=3;
                                }
                            }
                            return applevel;
                            }
                        }


                    ],
                    drawCallback: function (settings) {
                        $('[data-toggle="tooltip"]').tooltip();
                    }
                });

            }

       

            load_dt('', '', '','');

                $('#serachbtn').click(function (e) {
                    e.preventDefault();
                    let branch = $('#area').val();
                    let fromdate = $('#fromdate').val();
                    let todate = $('#todate').val();
                    let employee = $('#todate').val();


                    load_dt(branch, fromdate, todate,employee);
                });


                //Get Sub Customer
         $('#customer').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                        .replace('id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#subcustomer').empty().append(
                            '<option value="">Select Sub Customer</option>');
                        $.each(data, function (index, subCustomer) {
                            $('#subcustomer').append('<option value="' + subCustomer
                                .id + '">' + subCustomer.sub_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#subcustomer').empty().append('<option value="">Select Sub Customer</option>');
            }

            //Get branch
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                        'id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area').append('<option value="' + branch.id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#area').empty().append('<option value="">Select Branch</option>');
            }
        });

        var selectedRowIds = [];

            $('#delete').click(function () {
     
                    selectedRowIds = [];
                $('#dataTable tbody .selectCheck:checked').each(function () {
                    var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
                    selectedRowIds.push(rowData.id);
                });

                if (selectedRowIds.length > 0) {
                    $('#confirmModal').modal('show');
                    // console.log(selectedRowIds);
                } else {
                    alert('Select Rows to Delete!!!!');
                }


            });
          
            $('#ok_button').click(function () {
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                $.ajax({
                    url: '{!! route("attendencedelete") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { recordID: selectedRowIds },
                    beforeSend: function () {
                        $('#ok_button').text('Deleting...');
                    },
                    success: function (data) {//alert(data);
                        setTimeout(function () {
                            $('#confirmModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                        }, 2000);
                        // location.reload()
                    }
                })
            });



            var selectedRowIdsapprove = [];

                $('#approve').click(function () {
                    selectedRowIdsapprove = [];

                    $('#dataTable tbody .selectCheck:checked').each(function () {
                        var rowData = $('#dataTable').DataTable().row($(this).closest('tr')).data();
                        var applevel = 0;

                        if (rowData['approve_status'] == 0) {
                            if (rowData['approve_01'] == 0) {
                                applevel = 1;
                            } else if (rowData['approve_01'] == 1 && rowData['approve_02'] == 0) {
                                applevel = 2;
                            } else if (rowData['approve_02'] == 1 && rowData['approve_03'] == 0) {
                                applevel = 3;
                            }
                        }

                        selectedRowIdsapprove.push({
                            id: rowData.id,
                            applevel: applevel
                        });
                    });

                    if (selectedRowIdsapprove.length > 0) {
                        //console.log(selectedRowIdsapprove);

                        $('#approveconfirmModal').modal('show');
                    } else {
                        alert('Select Rows to Delete!!!!');
                    }
                });
        

        $('#approve_button').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("attendanceapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    recordID: selectedRowIdsapprove
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                    }, 500);
                   
                }
            })
        });


        $(document).on('click', '.deletebyone', function () {
                user_id = $(this).attr('id');
                $('#confirmModal2').modal('show');
            });
          
            $('#ok_button2').click(function () {
                $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })
                $.ajax({
                    url: '{!! route("attendencesingledelete") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: user_id },
                    success: function (data) {//alert(data);
                        setTimeout(function () {
                            $('#confirmModal2').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                    }, 500);
                       
                    }
                })
            });

        // select all record 
        $('#selectAll').click(function (e) {
            $('#dataTable').closest('table').find('td input:checkbox').prop('checked', this.checked);
        });


        });
       
    </script>

<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("getsearchempinfo") !!}',
                type: 'POST',
                dataType: 'json',
                data: function (params) {
                    return {
                        search: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.service_no + ' - ' + item.emp_name_with_initial +
                                    ' - ' + item.emp_national_id,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
    });

    function idgetinserch() {
        var editempid = $('#serviceno').val();
        $('#editempid').val(editempid);
    };
</script>

@endsection