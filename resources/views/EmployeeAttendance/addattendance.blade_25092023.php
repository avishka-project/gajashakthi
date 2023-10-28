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
                        <div class="col-5">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                <input type="hidden" id="req_id" name="req_id" value="">
                                <input type="hidden" id="subregionid" name="subregionid">
                                <input type="hidden" id="pageaction" name="pageaction" value="">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Client*</label>
                                        <select name="customer" id="customer" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="subcustomer" id="subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="area" id="area" class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Shift</label>
                                        <select name="shift" id="shift" class="form-control form-control-sm" required>
                                            <option value="">Select Shift</option>
                                            @foreach($shifttypes as $shifttype)
                                            <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="date" id="date" value="<?php echo date('Y-m-d') ?>">
                                    </div>
                                    <div class="col">
                                        <div class="container-fluid" style="padding-top: 30px;">
                                            <button type="button" id="serachbtn" class="btn btn-primary btn-sm px-4 fa-pull-right"><i
                                                class="fas fa-search"></i>&nbsp;Search</button>
                                        </div>

                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                    <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Count</th>
                                        </tr>
                                    </thead>
                                    <tbody id="requestdetaillist"></tbody>
                                </table>
                                <input type="hidden" id="requestid" name="requestid" >
                                <input type="hidden" id="subregion" name="subregion" >
                                    <input type="hidden" id="totalcount" name="totalcount"
                                    class="form-control form-control-sm">
                                    <input type="hidden" id="holidaytype" name="holidaytype"
                                    class="form-control form-control-sm">
                            </form>
                        </div>
                        <div class="col-7">
                            <form method="post" id="emplist" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employees*</label>
                                        <select name="employee" id="employee" class="form-control form-control-sm"
                                            >
                                            <option value="">Select Employees</option>
                                           
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark"> Transfer Employees*</label>
                                        <select name="transferemployee" id="transferemployee" class="form-control form-control-sm"
                                            >
                                            <option value="">Select Transfer Employees</option>
                                           
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                  
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="title" id="title" class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                            @foreach($titles as $title)
                                        <option value="{{$title->id}}">{{$title->title}}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employee Designation*</label>
                                        <input type="text" id="empdesignation" name="empdesignation" class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark"> Service No*</label>
                                        <input type="text" id="serviceno" name="serviceno" class="form-control form-control-sm" readonly>
                                    </div>
                                   
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Employee Age*</label>
                                        <input type="text" id="empage" name="empage" class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">On Time *</label>
                                        <input type="datetime-local" id="empontime" name="empontime" class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Off Time*</label>
                                        <input type="datetime-local" id="empofftime" name="empofftime" class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-row mt-3">
                                    &nbsp;&nbsp;<button type="button" id="formsubmit"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                </div>
                            </form>
                            <hr>

                            <table class="table table-striped table-bordered table-sm small" id="allocationtbl">
                                <thead>
                                    <tr>
                                        <th>Empolyee Name</th>
                                        <th>Job Title</th>
                                        <th>Service No</th>
                                        <th>Age</th>
                                        <th>On Time</th>
                                        <th>Off Time</th>
                                        <th>Action</th>
                                        <th class="d-none">empID</th>
                                    </tr>
                                </thead>
                                <tbody id="Detilslist"></tbody>
                            </table>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Attendance</button>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
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
        $(document).ready(function(){
            $('#empmanagementlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#collapseemployee').addClass('show');
            $('#security_staff_main_nav_link').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#security_staff_collapse').addClass('show');
            $('#empallocationlist').prop('aria-expanded', 'true').removeClass('collapsed');
            $('#empallocationlistdrop').addClass('show');
            $('#empattendace_link').addClass('active');

            $("#employee").select2();

            $('#serachbtn').click(function () {

                var branch = $('#area').val();
                var shift = $('#shift').val();
                var todate = $('#date').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        branch: branch,
                        shift: shift,
                        todate: todate
                    },
                    url: "{{ route('attendencgetrequest') }}",
                    success: function (data) {

                        $('#requestdetaillist').html(data.result.detaildata.html);
                        $('#totalcount').val(data.result.detaildata.totalCount);
                        $('#empontime').val(data.result.detaildata.ontime);
                        $('#empofftime').val(data.result.detaildata.offtime);
                        $('#requestid').val(data.result.maindata);
                        $('#subregion').val(data.result.subregion);
                        $('#holidaytype').val(data.result.holidaytype);

                        var subregion_id = $('#subregion').val();
                        var shift_id = $('#shift').val();
                        var todaydate = $('#date').val();
                            if (subregion_id !== '') {
                         $.ajax({
                            url: '{{ route("empattendancegetstafflistall", ["subregion_id" => "id_subregion", "shiftId" => "shift_id", "today" => "todaydate"]) }}'
                                .replace('id_subregion', subregion_id)
                                .replace('shift_id', shift_id)
                                .replace('todaydate', todaydate),
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                $.each(data, function (index, staff) {
                                    $('#employee').append('<option value="' + staff.id + '">' +
                                        staff.emp_name_with_initial + '</option>');
                                });
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                            }
                        });


                        $.ajax({
                            url: '{{ route("empattendancegetstafflist", ["subregion_id" => "id_subregion", "shiftId" => "shift_id", "today" => "todaydate"]) }}'
                                .replace('id_subregion', subregion_id)
                                .replace('shift_id', shift_id)
                                .replace('todaydate', todaydate),
                            type: 'GET',
                            dataType: 'json',
                            success: function (data) {
                                $.each(data, function (index, staff) {
                                    $('#transferemployee').append('<option value="' + staff.id + '">' +
                                        staff.emp_name_with_initial + '</option>');
                                });
                            },
                            error: function (xhr, status, error) {
                                console.error(error);
                            }
                        });

                        }
                    }
                });
            });
  
             // staffFilter in subregionId

        $('#employee').change(function () {
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
                    $('#serviceno').val(data.result.serviceno);
                    $('#empdesignation').val(data.result.designation);
                    $('#empage').val(data.result.age);
                },
            });
        } 
        });
        
// create add attendance layout

        $('#btncreateorder').click(function () {
                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

                var tbody = $("#allocationtbl tbody");

                if (tbody.children().length > 0) {
                    var jsonObj = [];
                    $("#allocationtbl tbody tr").each(function () {
                        var item = {};
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });

                    var customer = $('#customer').val();
                    var subcustomer = $('#subcustomer').val();
                    var branch = $('#area').val();
                    var shiftid = $('#shift').val();
                    var date = $('#date').val();
                    var requestid = $('#requestid').val();
                    var holidaytype = $('#holidaytype').val();

                   var action_url = "{{ route('attendenceinsert') }}";
              
                    
                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tableData: jsonObj,
                            customer: customer,
                            subcustomer: subcustomer,
                            branch: branch,
                            shiftid: shiftid,
                            date: date,
                            requestid: requestid,
                            holidaytype: holidaytype
                        },
                        url: action_url,
                        success: function (result) {
                            if (result.redirectUrl) {
           
                   window.location.href = result.redirectUrl;
                        } else {
                            
                        }
                        }
                    });
                }
        }); 


        });

        
       
    function productDelete(ctl) {
    	$(ctl).parents("tr").remove();
    }
    function action(data) { //alert(data);
        var obj = JSON.parse(data);
        $.notify({
            // options
            icon: obj.icon,
            title: obj.title,
            message: obj.message,
            url: obj.url,
            target: obj.target
        }, {
            // settings
            element: 'body',
            position: null,
            type: obj.type,
            allow_dismiss: true,
            newest_on_top: false,
            showProgressbar: false,
            placement: {
                from: "top",
                align: "center"
            },
            offset: 100,
            spacing: 10,
            z_index: 1031,
            delay: 5000,
            timer: 1000,
            url_target: '_blank',
            mouse_over: null,
            animate: {
                enter: 'animated fadeInDown',
                exit: 'animated fadeOutUp'
            },
            onShow: null,
            onShown: null,
            onClose: null,
            onClosed: null,
            icon_type: 'class',
            template: '<div data-notify="container" class="col-xs-11 col-sm-3 alert alert-{0}" role="alert">' +
                '<button type="button" aria-hidden="true" class="close" data-notify="dismiss">×</button>' +
                '<span data-notify="icon"></span> ' +
                '<span data-notify="title">{1}</span> ' +
                '<span data-notify="message">{2}</span>' +
                '<div class="progress" data-notify="progressbar">' +
                '<div class="progress-bar progress-bar-{0}" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>' +
                '</div>' +
                '<a href="{3}" target="{4}" data-notify="url"></a>' +
                '</div>'

			});
 }
       
    </script>


<script>
   $(document).ready(function () {
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



        //Get branch filtering about subcustomer
        $('#subcustomer').change(function () {
            var customerId = $('#customer').val();
            var subcustomerId = $('#subcustomer').val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                        .replace('id_subcustomer', subcustomerId).replace('id_customer',
                            customerId),
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
            }
        });

        $("#formsubmit").click(function () {
            

            // need to fix

            
                var  employeename = $("#employee option:selected").text();
                var employeeID = $('#employee').val();
            

            var titlename = $("#title option:selected").text();
            var titleID = $('#title').val();
            var serviceno = $('#serviceno').val();
            var empdesignation = $('#empdesignation').val();
            var empage = $('#empage').val();
            var empontime = $('#empontime').val();
            var empofftime = $('#empofftime').val();
            var totalcount = $('#totalcount').val();

            var rowCount = $('#allocationtbl tbody tr').length;

            if (rowCount >= totalcount) {
                alert("You have added the maximum limit of " + totalcount + " employees.");
                return;
            }

            var rowClass = empage < 60 ? 'text-success' : 'text-danger';

            $('#allocationtbl > tbody:last').append('<tr class="pointer ' + rowClass + '"><td>' + employeename +
                '</td><td>' + titlename + '</td><td>' + serviceno + '</td><td>' + empage + '</td><td>' + empontime + '</td><td>' +
                empofftime + '</td><td class="d-none">' + employeeID + '</td><td class="d-none">' + titleID +
                '</td><td><button type="button" onclick="productDelete(this);" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button></td></tr>');

            // Clear input fields
            $('#employee').val('');
            $('#title').val('');
            $('#serviceno').val('');
            $('#empdesignation').val('');
            $('#empage').val('');
        });

    });

      // Calculate and update the total sum in the table footer
      function updateTotalcount() {
        var totalSum = 0;

        $('#tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#totalField').text(totalSum);
    }
</script>
@endsection