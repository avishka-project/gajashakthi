@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            @include('layouts.employee_nav_bar')
    </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        @if(in_array('Travelrequest-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Gratuity Request</button>
                            @endif
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Location</th>
                                    <th>Month</th>
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
    </div>
    <!-- Modal Area Start -->
    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Gratuity Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-row mb-1">
                                <div class="col-4">
                                    <label class="small font-weight-bold text-dark">Select Request Type*</label>
                                    <select name="requesttype" id="requesttype" class="form-control form-control-sm">
                                        <option value="voregion">VO Region</option>
                                        <option value="singleemployee">Single Employee</option>
                                    </select>
                                </div>
                            </div>
                            <br>
                            <hr>
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div id="DivRegion" style="display: none;">
                                    <div class="form-row mb-1">
                                        <div class="col-4">
                                            <label class="small font-weight-bold text-dark">Location*</label>
                                            <select name="volocation" id="volocation" class="form-control form-control-sm" onchange="getAllVoEmp();" required>
                                                <option value="">Select Location</option>
                                                @foreach($branches as $branche)
                                                <option value="{{$branche->id}}">
                                                    {{$branche->branch_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-4">
                                            <label class="small font-weight-bold text-dark">VO Region*</label>
                                            <input type="text" class="form-control form-control-sm" placeholder=""
                                                name="voregion" id="voregion" readonly>
                                                <input type="hidden" class="form-control form-control-sm" placeholder=""
                                            name="voregion_id" id="voregion_id" readonly>
                                        </div>
                                        <div class="col-4">
                                            <label class="small font-weight-bold text-dark">Month*</label>
                                            <input type="month" id="vomonth" name="vomonth" class="form-control form-control-sm"
                                                required>
                                        </div>
                                    </div>
    
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Remark*</label>
                                            <textarea type="text" id="voremark" name="voremark"
                                                class="form-control form-control-sm"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <table class="table table-striped table-bordered table-sm small" id="votableorder">
                                            <thead>
                                                <tr>
                                                    <th>Employee</th>
                                                    <th>Amount</th>
                                                    <th class="d-none">EmployeeID</th>
                                                </tr>
                                            </thead>
                                            <tbody id="votableorderlist"></tbody>
                                        </table>
                                    </div>
                                </div>


                                <div id="DivSingle" style="display: none;">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="form-row mb-1">
                                                <div class="col-12">
                                                    <label class="small font-weight-bold text-dark">Location*</label>
                                                    <select name="location" id="location" class="form-control form-control-sm" required>
                                                        <option value="">Select Location</option>
                                                        @foreach($branches as $branche)
                                                        <option value="{{$branche->id}}">
                                                            {{$branche->branch_name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                                
                                                <div class="form-row mb-1">
                                                    <div class="col">
                                                        <label class="small font-weight-bold text-dark">Month*</label>
                                                        <input type="month" id="month" name="month" class="form-control form-control-sm"
                                                            required>
                                                    </div>
                                                </div>
                
                                                <div class="form-row mb-1">
                                                    <div class="col-12">
                                                        <label class="small font-weight-bold text-dark">Remark*</label>
                                                        <textarea type="text" id="remark" name="remark"
                                                            class="form-control form-control-sm"></textarea>
                                                    </div>
                                                </div>
                
                                            
                                        </div>
                                        <div class="col-8">
                                            <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                                <thead>
                                                    <tr>
                
                                                        <th>Employee</th>
                                                        <th>Amount</th>
                                                        <th class="d-none">EmployeeID</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="tableorderlist"></tbody>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="3">
                                                            <button id="addRowButton">Add New Row</button>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>

                                </div>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="boardingdeiailsid" id="boardingdeiailsid">
                            </form>
                        </div>  
                            <div class="col-12">
                            <button type="button" name="btncreateorder" id="btncreateorder"
                                class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                    class="fas fa-plus"></i>&nbsp;Create Request</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Area Start -->
    <div class="modal fade" id="confirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
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
                    <button type="button" name="ok_button" id="ok_button" class="btn btn-danger px-3 btn-sm">OK</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Area Start -->
    <div class="modal fade" id="confirmModal2" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header p-2">
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
                    <button type="button" name="ok_button2" id="ok_button2"
                        class="btn btn-danger px-3 btn-sm">OK</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="viewmodal-title" id="staticBackdropLabel">View Gratuity Request Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <span id="form_result"></span>
                        <form method="post" id="view_formTitle" class="form-horizontal">
                            {{ csrf_field() }}

                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Location*</label><br>
                                    <select name="view_location" id="view_location"
                                        class="form-control form-control-sm" readonly>
                                        <option value="">Select Location</option>
                                        @foreach($branches as $branche)
                                        <option value="{{$branche->id}}">
                                            {{$branche->branch_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">VO Region*</label>
                                <input type="text" class="form-control form-control-sm" placeholder=""
                                    name="view_voregion" id="view_voregion" readonly>
                                    <input type="hidden" class="form-control form-control-sm" placeholder=""
                                name="view_voregion_id" id="view_voregion_id" readonly>
                            </div>
                             </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Month*</label>
                                    <input type="month" id="view_month" name="view_month"
                                        class="form-control form-control-sm" required readonly>
                                </div>
                            </div>

                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Remark*</label>
                                    <textarea type="text" id="view_remark" name="view_remark"
                                        class="form-control form-control-sm" readonly></textarea>
                                </div>
                            </div>

                            <input type="hidden" name="view_hidden_id" id="view_hidden_id" />
                        </form>
                    </div>
                    <div class="col-8">
                        <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Cost</th>
                                    {{-- <th>Company Rate</th>
                                    <th>Guard Rate</th> --}}
                                </tr>
                            </thead>
                            <tbody id="view_tableorderlist"></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="app_modal-title" id="staticBackdropLabel">Approve Gratuity Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result"></span>
                            <form method="post" id="app_formTitle" class="form-horizontal">
                                {{ csrf_field() }}

                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Location*</label><br>
                                        <select name="app_location" id="app_location"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Location</option>
                                            @foreach($branches as $branche)
                                            <option value="{{$branche->id}}">
                                                {{$branche->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">VO Region*</label>
                                    <input type="text" class="form-control form-control-sm" placeholder=""
                                        name="app_voregion" id="app_voregion" readonly>
                                        <input type="hidden" class="form-control form-control-sm" placeholder=""
                                    name="app_voregion_id" id="app_voregion_id" readonly>
                                </div>
                            </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Month*</label>
                                        <input type="month" id="app_month" name="app_month"
                                            class="form-control form-control-sm" required readonly>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Remark*</label>
                                        <textarea type="text" id="app_remark" name="app_remark"
                                            class="form-control form-control-sm" readonly></textarea>
                                    </div>
                                </div>

                                <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                                <input type="hidden" name="app_level" id="app_level" value="1" />
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="app_tableorder">
                                <thead>
                                    <tr>
                                        <th>Employee</th>
                                        <th>Cost</th>
                                        {{-- <th>Company Rate</th>
                                        <th>Guard Rate</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="app_tableorderlist"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="approve_button" id="approve_button"
                        class="btn btn-warning px-3 btn-sm ">Approve</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

        
            <!-- Modal Area End -->
</main>

@endsection


@section('script')

<script>
    $(document).ready(function () {
        $('#employee_link').addClass('active');
    $("#employeerequest").addClass('navbtnactive');

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('gratuityrequestlist') !!}",

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'branch_name',
                    name: 'branch_name'
                },
                {
                    data: 'month',
                    name: 'month'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        return '<div style="text-align: right;">' + data + '</div>';
                    }
                },
            ],
            "bDestroy": true,
            "order": [
                [0, "desc"]
            ]
        });

        $('#create_record').click(function () {
            $('.modal-title').text('Add New Gratuity  Request');
            $('#action_button').html('Add');
            $('#btncreateorder').html('Create Request');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();

            $('#votableorder tbody').empty();
            $('#tableorder tbody').empty();

            $('#formModal').modal('show');
        });



        $('#btncreateorder').click(function () {
            var requesttype = $('#requesttype').val();
            var hidden_id = $('#hidden_id').val();  
            var location='';
            var month='';
            var remark='';
            var isAnyFieldEmpty = false;
            var tableDataArray = [];
            $('#btncreateorder').prop('disabled', true).html(
                '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

            if(requesttype=="voregion"){
                location = $('#volocation').val();
                month = $('#vomonth').val();
                remark = $('#voremark').val();

                if(location!='' && month!=''){
                   
                    $('#votableorder tbody tr').each(function() {
                    var empid = $(this).find('td#empid').text();
                    var amount = $(this).find('input[type="number"]').val();

                    var rowData = {
                        'empid': empid,
                        'amount': amount,
                    };
                    tableDataArray.push(rowData);
                    });
                }else{
                    $('#btncreateorder').prop('disabled', false).html(
                'Create Order');
                    alert("Please Select Location AND Month")
                    return false;
                }
               
            }else if(requesttype=="singleemployee"){
                var tbody = $("#tableorder tbody");

                location = $('#location').val();
                month = $('#month').val();
                remark = $('#remark').val();
                
                if(location!='' && month!=''){
                    $('#tableorder tbody tr').each(function() {
                    var empid = $(this).find('select').val();
                    var amount = $(this).find('input[type="number"]').val();

                    if (!empid || amount === "") {
                // Display an alert if any input or select is empty
                    alert("Please fill in all fields for row with Employee: " + empid);
                    $('#btncreateorder').prop('disabled', false).html('Create Order');
                    isAnyFieldEmpty = true;
                    return false; // Exit the loop
                     }

                    var rowData = {
                        'empid': empid,
                        'amount': amount,
                    };
                    tableDataArray.push(rowData);
                    });
                }else{
                    $('#btncreateorder').prop('disabled', false).html(
                'Create Order');
                    alert("Please Select Location AND Month")
                    return false;
                }
}


            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('gratuityrequestinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('gratuityrequestupdate') }}";
            }
            if (!isAnyFieldEmpty) {
//  console.log(requesttype,location,month,remark);
            // console.log(jsonObj);
            // console.log(tableDataArray);
            // console.log(action_url);
           $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableDataArray: tableDataArray,
                        requesttype: requesttype,
                        location: location,
                        month: month,
                        remark: remark,
                        hidden_id: hidden_id,

                    },
                    url: action_url,
                    success: function (data) { //alert(data);
                        var html = '';
                        if (data.errors) {
                            html = '<div class="alert alert-danger">';
                            for (var count = 0; count < data.errors.length; count++) {
                                html += '<p>' + data.errors[count] + '</p>';
                            }
                            html += '</div>';
                        }
                        if (data.success) {
                            html = '<div class="alert alert-success">' + data.success +
                                '</div>';
                            $('#formTitle')[0].reset();
                            //$('#titletable').DataTable().ajax.reload();
                            window.location.reload(); // Use window.location.reload()
                        }

                        $('#form_result').html(html);
                        // resetfield();

                    }
                });
            }
           
        });

        // edit function
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("gratuityrequestedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    var requesttype=(data.result.mainData.request_type);
                    $('#requesttype').val(data.result.mainData.request_type);
                    if(requesttype=="voregion"){
                        $("#DivRegion").show();
                        $("#DivSingle").hide();

                        $("#volocation").prop("required", true);
                        $("#vomonth").prop("required", true);

                        $("#location").prop("required", false);
                        $("#employee").prop("required", false);
                        $("#month").prop("required", false);
                        $("#amount").prop("required", false);

                        $('#volocation').val(data.result.mainData.location_id);
                        $('#voregion').val(data.result.mainData.subregion);
                        $('#vomonth').val(data.result.mainData.month);
                        $('#voremark').val(data.result.mainData.remark);

                        $('#votableorderlist').html(data.result.requestdata);
                    }
                    else if(requesttype=="singleemployee"){
                        $("#DivRegion").hide();
                        $("#DivSingle").show();

                        $("#location").prop("required", true);
                        $("#employee").prop("required", true);
                        $("#month").prop("required", true);
                        $("#amount").prop("required", true);

                        $("#volocation").prop("required", false);
                        $("#vomonth").prop("required", false);

                        $('#location').val(data.result.mainData.location_id);
                        $('#month').val(data.result.mainData.month);
                        $('#remark').val(data.result.mainData.remark);

                        $('#tableorderlist').html(data.result.requestdata);
                    }

                    // var valueToCheck = data.result.pay_by;

                    // if (valueToCheck == 1 ) {
                    //     $('#company').prop('checked', true);
                    // } else {
                    //      $('#branch').prop('checked', true);
                    // }

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Gratuity Request');
                    $('#action_button').html('Edit');
                    $('#btncreateorder').html('Update Request');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });


        var user_id;
        $(document).on('click', '.delete', function () {
            user_id = $(this).attr('id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("gratuityrequestdelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: user_id
                },
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Deleted');
                    }, 500);
                    location.reload()
                }
            })
        });





        // approve model
        var id_approve;
        // approve level 01 
        $(document).on('click', '.appL1', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("gratuityrequestdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_location').val(data.result.mainData.location_id);
                    $('#app_voregion').val(data.result.mainData.subregion);
                    $('#app_month').val(data.result.mainData.month);
                    $('#app_amount').val(data.result.mainData.amount);
                    $('#app_remark').val(data.result.mainData.remark);
                    $('#app_tableorderlist').html(data.result.requestdata);

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('1');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        // approve level 02 
        $(document).on('click', '.appL2', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("gratuityrequestdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_location').val(data.result.mainData.location_id);
                    $('#app_voregion').val(data.result.mainData.subregion);
                    $('#app_month').val(data.result.mainData.month);
                    $('#app_amount').val(data.result.mainData.amount);
                    $('#app_remark').val(data.result.mainData.remark);
                    $('#app_tableorderlist').html(data.result.requestdata);
                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('2');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        // approve level 03 
        $(document).on('click', '.appL3', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("gratuityrequestdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_location').val(data.result.mainData.location_id);
                    $('#app_voregion').val(data.result.mainData.subregion);
                    $('#app_month').val(data.result.mainData.month);
                    $('#app_amount').val(data.result.mainData.amount);
                    $('#app_remark').val(data.result.mainData.remark);
                    $('#app_tableorderlist').html(data.result.requestdata);
                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#app_hidden_id').val();
            var applevel = $('#app_level').val();

            var cost = $('#app_amount').val();
            var month = $('#app_month').val();

            var tableDataArray = [];
            $('#app_tableorder tbody tr').each(function() {
            var empid = $(this).find('td#empid').text();
            var cost = $(this).find('td#cost').text();

            // Check if any input or select is empty
            if (cost == "" || cost === null) {
                return; // Skip this row
            }
            // Proceed with creating the rowData object and pushing it to the array
            var rowData = {
                'empid': empid,
                'cost': cost,
            };

            // Push the current row data object to the array
            tableDataArray.push(rowData);
        });
// console.log(tableDataArray);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("gratuityrequestapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel,
                    tableDataArray: tableDataArray,
                    month: month,
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Approved');
                    }, 500);
                    location.reload()
                }
            })
        });

        // view model
        $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("gratuityrequestdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#view_location').val(data.result.mainData.location_id);
                    $('#view_voregion').val(data.result.mainData.subregion);
                    $('#view_month').val(data.result.mainData.month);
                    $('#view_remark').val(data.result.mainData.remark);
                    $('#view_tableorderlist').html(data.result.requestdata);
                    $('#view_hidden_id').val(id_approve);
                    $('#viewModal').modal('show');

                }
            })


        });
    });

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>
<script>
function getAllVoEmp() {
        var location = $('#volocation').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("travelrequestGetAllEmployee") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                location_id: location
            },
            success: function (data) {
                $('#voregion').val(data.result.mainData.subregion);
                $('#votableorderlist').html(data.result.requestdata);
            }
        })
    }


       
function getEmp(count){
    $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#employee' + count).select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("getemployeeinselect2") !!}',
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
                                text: item.service_no + ' - ' + item.emp_name_with_initial,
                                id: item.id
                            };
                        })
                    };
                }
            }
        });
}
        
</script>
<script>
    $(document).ready(function () {
        $("#DivRegion").show();
        $("#volocation").prop("required", true);
        $("#vomonth").prop("required", true);

        $("#location").prop("required", false);
        $("#employee").prop("required", false);
        $("#month").prop("required", false);
        $("#amount").prop("required", false);

        $("#requesttype").change(function () {
            $('#formTitle')[0].reset();
            $('#votableorder tbody').empty();
            $('#tableorder tbody').empty();

            var requesttype = $(this).val();
            if(requesttype=="voregion"){
                $("#DivRegion").show();
                $("#DivSingle").hide();

                $("#volocation").prop("required", true);
                $("#vomonth").prop("required", true);

                $("#location").prop("required", false);
                $("#employee").prop("required", false);
                $("#month").prop("required", false);
                $("#amount").prop("required", false);
            }else if(requesttype=="singleemployee"){
                $("#DivRegion").hide();
                $("#DivSingle").show();

                $("#location").prop("required", true);
                $("#employee").prop("required", true);
                $("#month").prop("required", true);
                $("#amount").prop("required", true);

                $("#volocation").prop("required", false);
                $("#vomonth").prop("required", false);
            }
           

        });
    });
    </script>
<script>
     function productDelete(row) {
        $(row).closest('tr').remove();
    }

    $(document).ready(function() {
        var count = 5000; 
        function addNewRow() {
            var newRow = '<tr>' +
                '<td>' +
                '<select name="employee' + count + '" id="employee' + count + '" class="form-control form-control-sm" onclick="getEmp('+count+');" required>' +
                '<option value="">Select Employees</option>' +
                '</select>' +
                '</td>' +
                '<td>' +
                '<input type="number" id="amount' + count + '" name="amount' + count + '" >' +
                '</td>' +
                '<td class="d-none" id="empid">New ID</td>' +
                '<td class="d-none">New ExistingData</td>' +
                '<td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td>' +
                '</tr>';

            $('#tableorder tbody').append(newRow);
            getEmp(count);
            count++;
        }

        // Handle click event for the "Add New Row" button
        $('#addRowButton').on('click', function() {
            addNewRow();
        });
    });
</script>
</body>

@endsection