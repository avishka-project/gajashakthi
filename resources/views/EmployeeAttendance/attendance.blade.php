@extends('layouts.app')
@section('content')
    <main>
        <div class="page-header page-header-light bg-white shadow">
            <div class="container-fluid">
                <div class="page-header-content py-3">
                    <h1 class="page-header-title">
                        <div class="page-header-icon" style="padding-left: 12px"><i class="fas fa-user-plus"></i></div>
                        <span>Security Attendance</span>
                    </h1>
                </div>
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
                        <div class="col-12">
    
                            <a href="{{ route('empattendanceadd') }}"   class="btn btn-outline-primary btn-sm fa-pull-right"><i class="fas fa-plus mr-2"></i>Add Attendance</a>
                        </div>
                        <div class="col-12">
                            <hr class="border-dark">
                        </div>
                        <div class="col-12">
    
                            <table class="table table-striped table-bordered table-sm small" id="dataTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Branch</th>
                                        <th>Employee Name</th>
                                        <th>Service No</th>
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

        <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Edit Security Attendance</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-5">
                                <label class="small font-weight-bold text-dark">Branch*</label>
                                <input type="text" id="branch" name="branch" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Shift</label>
                                <input type="text" id="shift" name="shift" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Date*</label>
                                <input type="date" class="form-control form-control-sm" name="date" id="date" readonly>
                            </div>
                        </div>
                    </div>
                   
                    <br>
                    <div class="col-12">
                        
                        <table class="table table-striped table-bordered table-sm small" id="allocationtbl"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>Empolyee Name</th>
                                <th>Service No</th>
                                <th>Job Title</th>
                                <th>Age</th>
                                <th>On Time</th>
                                <th>Off Time</th>
                                <th class="d-none">empID</th>
                            </tr>
                        </thead>
                        <tbody id="emplistbody">
                            <tr>
                                <td>
                                    <select name="employeetbl" id="employeetbl"
                                        class="form-control form-control-sm">
                                        <option value="">Select Employees</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" id="servicenotbl" name="servicenotbl" class="form-control form-control-sm" readonly>
                                </td>
                                <td>

                                    <select name="title" id="title" class="form-control form-control-sm"
                                        required>
                                        <option value="">Select Job Title</option>
                                        @foreach($titles as $title)
                                        <option value="{{$title->id}}">{{$title->title}}</option>
                                        @endforeach
                                    </select>

                                </td>
                                <td>
                                    <input type="text" id="empage" name="empage" class="form-control form-control-sm" readonly>
                                </td>
                                <td>
                                    <input type="datetime-local" id="empontime" name="empontime"  class="form-control form-control-sm" required>
                                </td>
                                <td>
                                    <input type="datetime-local" id="empofftime" name="empofftime" class="form-control form-control-sm" required>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>

                    <div class="col-12">
                        <div class="form-group mt-2 fa-pull-right">
                            <button type="button" name="btncreateorder" id="btncreateorder" class="btn btn-primary btn-sm  fa-pull-right"><i class="fas fa-plus"></i>&nbsp;Update Attendance</button>&nbsp;
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="hidden" name="subregionid" id="subregionid" />
                        </div>

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

            $('#collapse_employee_info').addClass('show');
            $('#collapsattendance').addClass('show');
            $('#security_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#security_staff_collapse').addClass('show');
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empattendace_link').addClass('active');

            
            function load_dt(branch, fromdate, todate,employee) {
                  $('#dataTable').DataTable({
                    "destroy": true,
                    "processing": true,
                    "serverSide": true,
                    ajax: {
                        url: scripturl + '/attendancelist.php',
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
                        [10, 25, 50, 'All'],
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
                        [0, "desc"]
                    ],
                    "columns": [{
                            "data": "id",
                            "className": 'text-dark'
                        },
                        {
                            "data": "branch_name",
                            "className": 'text-dark'
                        },
                        {
                            "data": "emp_fullname",
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
                            "targets": -1,
                            "className": 'text-right',
                            "data": null,
                            "render": function (data, type, full) {

                                var button = '';
                                button += '<button class="btn btn-primary btn-sm edit mr-1" id="' + full['id'] + '"><i class="fas fa-pen"></i></button>';
                                return button;
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


        


                    $(document).on('click', '.edit', function () {

                        var id = $(this).attr('id');
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        })


                        $.ajax({
                            url: '{!! route("attendenceedit") !!}',
                            type: 'POST',
                            dataType: "json",
                            data: {
                                id: id
                            },
                            success: function (data) {
                              
                                $('#branch').val(data.result.branchname);
                                $('#shift').val(data.result.Shift);
                                $('#date').val(data.result.date);
                                $('#servicenotbl').val(data.result.emp_serviceno);
                                $('#title').val(data.result.jobtitle_id);
                                $('#empage').val(data.result.emp_age);
                                $('#empontime').val(data.result.ontime);
                                $('#empofftime').val(data.result.outtime);
                                $('#hidden_id').val(data.result.id);
                                $('#subregionid').val(data.result.subregion);
                                $('#employeetbl').val(data.result.emp_id);
                                $('#employeetbl').html('<option value="' + data.result.emp_id + '">' + data.result.empfullname + '</option>');
                                $('#formModal').modal('show');

                                var subregion_id = data.result.subregion;
                                var shift_id = data.result.shift_id;
                                var todaydate = data.result.date;

                            }
                        })
                    });

        $('#employeetbl').change(function () {
            var employeeID = $(this).val();
            if (employeeID !== '') {
            $.ajax({
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: '{{ csrf_token() }}',
                    employeeID: employeeID
                    },
                    url: "{{ route('attendenceemployeedetails') }}",
                success: function (data) {
                    $('#servicenotbl').val(data.result.serviceno);
                    $('#title').val(data.result.designation);
                    $('#empage').val(data.result.age);
                },
            });
        } 
        });


        $('#btncreateorder').click(function () {
                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i>Updating');

                var tbody = $("#allocationtbl tbody");

                if (tbody.children().length > 0) {
                    var jsonObj = [];
                    $("#allocationtbl tbody tr").each(function () {
                        var item = {};
                        $(this).find('td').each(function (col_idx) {
                            // Check if the element inside the cell is an input or select element
                            var inputElement = $(this).find('input, select');
                            
                            // If input or select element is found, get its value, otherwise, get the text
                            if (inputElement.length > 0) {
                                item["col_" + (col_idx + 1)] = inputElement.val();
                            } else {
                                item["col_" + (col_idx + 1)] = $(this).text();
                            }
                        });
                        jsonObj.push(item);
                    });
                

                   var hiddenid = $('#hidden_id').val();
                   var action_url = "{{ route('attendenceupdate') }}";
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tableData: jsonObj,
                            recordID: hiddenid
                        },
                        url: action_url,
                        success: function (result) {
                            location.reload();
                        }
                    });
                }
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