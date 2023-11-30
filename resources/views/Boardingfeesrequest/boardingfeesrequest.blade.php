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
                        @if(in_array('Boardingfees-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Boarding Fees Request</button>
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
                                    <th>Supplier</th>
                                    <th>VO Region</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Add Boarding Fees Request</h5>
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
                                            <div class="col-3">
                                                <label class="small font-weight-bold text-dark">Supplier*</label><br>
                                                <select name="vosupplier" id="vosupplier"
                                                    class="form-control form-control-sm">
                                                    <option value="">Select Supplier</option>
                                                    @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier->id}}">
                                                        {{$supplier->supplier_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <label class="small font-weight-bold text-dark">VO Region*</label>
                                                <select name="voregion" id="voregion" class="form-control form-control-sm" onchange="getAllVoEmp();" required>
                                                    <option value="">Select VO Region</option>
                                                    @foreach($voregions as $voregion)
                                                    <option value="{{$voregion->id}}">
                                                        {{$voregion->subregion}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Month*</label>
                                            <input type="month" id="vomonth" name="vomonth" class="form-control form-control-sm"
                                                required>
                                        </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Company Discount Precentage*</label>
                                            <input type="number" id="vodiscountprecentage" name="vodiscountprecentage" class="form-control form-control-sm"
                                                required onkeyup="calculatediscountAll(this.value)">
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
                                                    <th>Boarding Fee</th>
                                                    <th>Company Discount</th>
                                                    <th>Total Cost</th>
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
                                                <label class="small font-weight-bold text-dark">Supplier*</label><br>
                                                <select name="supplier" id="supplier"
                                                    class="form-control form-control-sm">
                                                    <option value="">Select Supplier</option>
                                                    @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier->id}}">
                                                        {{$supplier->supplier_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row mb-1">
                                            <div class="col-12">
                                                <label class="small font-weight-bold text-dark">Location*</label>
                                                <select name="region" id="region" class="form-control form-control-sm" required>
                                                    <option value="">Select VO Region</option>
                                                    @foreach($voregions as $voregion)
                                                    <option value="{{$voregion->id}}">
                                                        {{$voregion->subregion}}</option>
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
                                                    <label class="small font-weight-bold text-dark">Company Discount Precentage*</label>
                                                    <input type="number" id="discountprecentage" name="discountprecentage" class="form-control form-control-sm"
                                                        required onkeyup="calculatediscountAll1(this.value)">
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
                                                        <th>Boarding Fee</th>
                                                        <th>Company Discount</th>
                                                        <th>Total Cost</th>
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
                <h5 class="viewmodal-title" id="staticBackdropLabel">View Boarding Fees Request Details</h5>
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
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Supplier*</label><br>
                                    <select name="view_supplier" id="view_supplier"
                                        class="form-control form-control-sm" readonly>
                                        <option value="">Select Supplier</option>
                                        @foreach($suppliers as $supplier)
                                        <option value="{{$supplier->id}}">
                                            {{$supplier->supplier_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">VO Region*</label><br>
                                    <select name="view_voregion" id="view_voregion"
                                        class="form-control form-control-sm" readonly>
                                        <option value="">Select VO Region</option>
                                                @foreach($voregions as $voregion)
                                                <option value="{{$voregion->id}}">
                                                    {{$voregion->subregion}}</option>
                                                @endforeach
                                    </select>
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
                                    <label class="small font-weight-bold text-dark">Company Discount Precentage*</label>
                                    <input type="number" id="view_discountprecentage" name="view_discountprecentage" class="form-control form-control-sm"
                                        required readonly>
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
                                    <th>Boarding Fee</th>
                                    <th>Company Discount</th>
                                    <th>Total Cost</th>
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
                    <h5 class="app_modal-title" id="staticBackdropLabel">Approve Boarding Fees Request Details</h5>
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
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Supplier*</label><br>
                                        <select name="app_supplier" id="app_supplier"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">
                                                {{$supplier->supplier_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">VO Region*</label><br>
                                        <select name="app_voregion" id="app_voregion"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select VO Region</option>
                                                @foreach($voregions as $voregion)
                                                <option value="{{$voregion->id}}">
                                                    {{$voregion->subregion}}</option>
                                                @endforeach
                                        </select>
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
                                        <label class="small font-weight-bold text-dark">Company Discount Precentage*</label>
                                        <input type="number" id="app_discountprecentage" name="app_discountprecentage" class="form-control form-control-sm"
                                            required readonly>
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
                                        <th>Boarding Fee</th>
                                        <th>Company Discount</th>
                                        <th>Total Cost</th>
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

        var approvel01 = {{$approvel01permission}};
        var approvel02 = {{$approvel02permission}};
        var approvel03 = {{$approvel03permission}};

        var listcheck = {{$listpermission}};
        var editcheck = {{$editpermission}};
        var statuscheck = {{$statuspermission}};
        var deletecheck = {{$deletepermission}};
        
        $('#dataTable').DataTable({
            "destroy": true,
            "processing": true,
            "serverSide": true,
            ajax: {
                url: scripturl + '/boardingfeeslist.php',

                type: "POST", // you can use GET
                // data: {
                //     },

            },
            dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [{
                    extend: 'csv',
                    className: 'btn btn-success btn-sm',
                    title: 'Boarding Fees Details',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'print',
                    title: 'Boarding Fees Details',
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
                    "data": "supplier_name",
                    "className": 'text-dark'
                },

                {
                    "data": "subregion",
                    "className": 'text-dark'
                },
                {
                    "data": "month",
                    "className": 'text-dark'
                },
                {
                    "targets": -1,
                    "className": 'text-right',
                    "data": null,
                    "render": function (data, type, full) {

                        var approvelevel = '';
                        var requesttype = '';
                        var button = '';

                        if (approvel01) {
                            if (full['approve_01'] == 0) {
                                    button += ' <button name="appL1" id="' + full['id'] + '" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if (approvel02) {
                            if (full['approve_01'] == 1 && full['approve_02']==0) {
                                    button += ' <button name="appL2" id="' + full['id'] + '" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>'; 
                            }
                        }
                        if (approvel03) {
                            if (full['approve_02'] == 1 && full['approve_03']==0) {
                                    button += ' <button name="appL3" id="' + full['id'] + '" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                            if (full['approve_03']==1) {
                                    button += ' <button name="view" id="' + full['id'] + '" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';      
                            }
                        if (editcheck) {
                            if (full['approve_status']==0) {
                                    button += ' <button name="edit" id="' + full['id'] + '" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';                      
                        }
                    }
                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="boardingfeesstatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="boardingfeesstatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
                        }
                        if (deletecheck) {
                            button += ' <button name="delete" id="' + full['id'] + '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }

                        return button;
                    }
                }
            ],
            drawCallback: function (settings) {
                $('[data-toggle="tooltip"]').tooltip();
            }
        });

        $('#create_record').click(function () {
            $('.modal-title').text('Add Boarding Fees  Request');
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
            var supplier='';
            var location='';
            var month='';
            var discount_presentage='';
            var remark='';
            var isAnyFieldEmpty = false;
            var tableDataArray = [];
            $('#btncreateorder').prop('disabled', true).html(
                '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

            if(requesttype=="voregion"){
                supplier = $('#vosupplier').val();
                location = $('#voregion').val();
                month = $('#vomonth').val();
                discount_presentage = $('#vodiscountprecentage').val();
                remark = $('#voremark').val();

                if(location!='' && month!='' && supplier!='' && discount_presentage!=''){
                   
                    var count=1;
                    $('#votableorder tbody tr').each(function() {
                    var empid = $(this).find('td#empid').text();
                    var boardingfee = $(this).find('input[type="number"]').val();
                    var companydiscount = $(this).find('td#companydiscount').text();
                    var totalcost = $(this).find('td#totalcost').text();

                    var rowData = {
                        'empid': empid,
                        'boardingfee': boardingfee,
                        'companydiscount': companydiscount,
                        'totalcost': totalcost,
                    };
                    tableDataArray.push(rowData);
                    count++;
                    });
                }else{
                    $('#btncreateorder').prop('disabled', false).html(
                'Create Order');
                    alert("Please Select All feilds and Table data")
                    return false;
                }
               
            }else if(requesttype=="singleemployee"){
                var tbody = $("#tableorder tbody");
                var rowCount = $('#tableorder tbody tr').length;

                supplier = $('#supplier').val();
                location = $('#region').val();
                month = $('#month').val();
                discount_presentage = $('#discountprecentage').val();
                remark = $('#remark').val();
                
                if(location!='' && month!='' && supplier!='' && discount_presentage!='' && rowCount!=0){
                    var count=5000;
                    $('#tableorder tbody tr').each(function() {
                    var empid = $(this).find('select').val();
                    var boardingfee = $(this).find('input[type="number"]').val();
                    var companydiscount = $(this).find('td#companydiscount').text();
                    var totalcost = $(this).find('td#totalcost').text();

                    if (!empid || boardingfee === "") {
                // Display an alert if any input or select is empty
                    alert("Please fill in all fields for row with Employee: " + empid);
                    $('#btncreateorder').prop('disabled', false).html('Create Order');
                    isAnyFieldEmpty = true;
                    return false; // Exit the loop
                     }

                    var rowData = {
                        'empid': empid,
                        'boardingfee': boardingfee,
                        'companydiscount': companydiscount,
                        'totalcost': totalcost,
                    };
                    tableDataArray.push(rowData);
                    count++;
                    });
                }else{
                    $('#btncreateorder').prop('disabled', false).html(
                'Create Order');
                    alert("Please Select All feilds and Table data")
                    return false;
                }
}


            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('boardingfeesinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('boardingfeesupdate') }}";
            }
            if (!isAnyFieldEmpty) {
//  console.log(requesttype,supplier,location,discount_presentage,month,remark,hidden_id);
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
                        supplier:supplier,
                        location: location,
                        discount_presentage:discount_presentage,
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
                url: '{!! route("boardingfeesedit") !!}',
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

                        $("#voregion").prop("required", true);
                        $("#vomonth").prop("required", true);
                        $("#vodiscountprecentage").prop("required", true);

                        $("#region").prop("required", false);
                        $("#employee").prop("required", false);
                        $("#month").prop("required", false);
                        $("#discountprecentage").prop("required", false);

                        $('#vosupplier').val(data.result.mainData.sup_id);
                        $('#voregion').val(data.result.mainData.location_id);
                        $('#vomonth').val(data.result.mainData.month);
                        $('#vodiscountprecentage').val(data.result.mainData.discount_precentage);
                        $('#voremark').val(data.result.mainData.remark);

                        $('#votableorderlist').html(data.result.requestdata);
                    }
                    else if(requesttype=="singleemployee"){
                        $("#DivRegion").hide();
                        $("#DivSingle").show();

                        $("#region").prop("required", true);
                        $("#employee").prop("required", true);
                        $("#month").prop("required", true);
                        $("#discountprecentage").prop("required", true);

                        $("#voregion").prop("required", false);
                        $("#vomonth").prop("required", false);
                        $("#vodiscountprecentage").prop("required", false);

                        $('#supplier').val(data.result.mainData.sup_id);
                        $('#region').val(data.result.mainData.location_id);
                        $('#month').val(data.result.mainData.month);
                        $('#discountprecentage').val(data.result.mainData.discount_precentage);
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
                    $('.modal-title').text('Edit Boarding fees Request');
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
                url: '{!! route("boardingfeesdelete") !!}',
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
                url: '{!! route("boardingfeesdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_supplier').val(data.result.mainData.sup_id);
                    $('#app_discountprecentage').val(data.result.mainData.discount_precentage);
                    $('#app_voregion').val(data.result.mainData.location_id);
                    $('#app_month').val(data.result.mainData.month);
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
                url: '{!! route("boardingfeesdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_supplier').val(data.result.mainData.sup_id);
                    $('#app_discountprecentage').val(data.result.mainData.discount_precentage);
                    $('#app_voregion').val(data.result.mainData.location_id);
                    $('#app_month').val(data.result.mainData.month);
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
                url: '{!! route("boardingfeesdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_supplier').val(data.result.mainData.sup_id);
                    $('#app_discountprecentage').val(data.result.mainData.discount_precentage);
                    $('#app_voregion').val(data.result.mainData.location_id);
                    $('#app_month').val(data.result.mainData.month);
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

            var month = $('#app_month').val();

            var tableDataArray = [];
            $('#app_tableorder tbody tr').each(function() {
            var empid = $(this).find('td#empid').text();
            var boardingfee = $(this).find('td#boardingfee').text();
            var company_discount = $(this).find('td#company_discount').text();
            var total_cost = $(this).find('td#total_cost').text();

            // Check if any input or select is empty
            if (total_cost == "" || total_cost === null) {
                return; // Skip this row
            }
            // Proceed with creating the rowData object and pushing it to the array
            var rowData = {
                'empid': empid,
                'boardingfee': boardingfee,
                'company_discount': company_discount,
                'total_cost': total_cost,
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
                url: '{!! route("boardingfeesapprove") !!}',
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
                url: '{!! route("boardingfeesdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#view_supplier').val(data.result.mainData.sup_id);
                    $('#view_discountprecentage').val(data.result.mainData.discount_precentage);
                    $('#view_voregion').val(data.result.mainData.location_id);
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
        var subregion = $('#voregion').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("accommodationfeeGetAllEmployee") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                subregion_id: subregion
            },
            success: function (data) {
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
        $("#vodiscountprecentage").prop("required", true);

        $("#location").prop("required", false);
        $("#employee").prop("required", false);
        $("#month").prop("required", false);
        $("#discountprecentage").prop("required", false);

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
                $("#vodiscountprecentage").prop("required", true);

                $("#location").prop("required", false);
                $("#employee").prop("required", false);
                $("#month").prop("required", false);
                $("#discountprecentage").prop("required", false);
            }else if(requesttype=="singleemployee"){
                $("#DivRegion").hide();
                $("#DivSingle").show();

                $("#location").prop("required", true);
                $("#employee").prop("required", true);
                $("#month").prop("required", true);
                $("#discountprecentage").prop("required", true);

                $("#volocation").prop("required", false);
                $("#vomonth").prop("required", false);
                $("#vodiscountprecentage").prop("required", false);
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
                '<input type="number" id="amount' + count + '" name="amount' + count + '" onkeyup="calculatediscount1(this.value, ' + count +  ');">' +
                '</td>' +
                '<td id="companydiscount" name="companydiscount' + count + '"></td>'+
                '<td id="totalcost" name="totalcost' + count + '"></td>'+
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
<script>
//  calculate discount vo region group
    function calculatediscount(inputValue, count) {
        var discountprecentage = $('#vodiscountprecentage').val();
        if(discountprecentage=="" || null){
            alert("please Insert Discount precentage first");
            return false;
        }
        else{
            var fee=inputValue;
            var discount=fee*(discountprecentage/100);
            var total=fee-discount;

            // $('#companydiscount'+count).text(discount);
            // $('#totalcost'+count).text(total);

            $('[name="companydiscount' + count + '"]').text(discount);
            $('[name="totalcost' + count + '"]').text(total);
        }
}

function calculatediscountAll(value) {
    var rowCount = $('#votableorder tbody tr').length;
var count='';
if (rowCount > 0) {
    $('#votableorder tbody tr').each(function(count) {
        var fee = $('#amount' + (count + 1)).val();
        if(fee==''||null){
            return false;
        }
        else{
            var discount = fee * (value / 100);
            var total = fee - discount;

            // $('#companydiscount' + (count + 1)).text(discount); 
            // $('#totalcost' + (count + 1)).text(total); 

            $('[name="companydiscount' + (count + 1) + '"]').text(discount);
            $('[name="totalcost' + (count + 1) + '"]').text(total);
        }
    });

    count++;
} else {
    return false;
}

}


//  calculate discount single employee
function calculatediscount1(inputValue, count) {
        var discountprecentage = $('#discountprecentage').val();
        if(discountprecentage=="" || null){
            alert("please Insert Discount precentage first");
            return false;
        }
        else{
            var fee=inputValue;
            var discount=fee*(discountprecentage/100);
            var total=fee-discount;

            // $('#companydiscount'+count).text(discount);
            // $('#totalcost'+count).text(total);

            $('[name="companydiscount' + count + '"]').text(discount);
            $('[name="totalcost' + count + '"]').text(total);
        }
}

function calculatediscountAll1(value) {
    var rowCount = $('#tableorder tbody tr').length;
var count='';
if (rowCount > 0) {
    $('#tableorder tbody tr').each(function(count) {
        var fee = $('#amount' + (count + 5000)).val();
        if(fee==''||null){
            return false;
        }
        else{
            var discount = fee * (value / 100);
            var total = fee - discount;

            // $('#companydiscount' + (count + 5000)).text(discount); 
            // $('#totalcost' + (count + 5000)).text(total); 

            $('[name="companydiscount' + (count + 5000) + '"]').text(discount);
            $('[name="totalcost' + (count + 5000) + '"]').text(total);
        }
    });

    count++;
} else {
    return false;
}

}

// edit discount calculator
function editcalculatediscount(inputValue, count, request_type) {
    // console.log("Ok"+request_type);
    if(request_type=="voregion"){
        var discountprecentage = $('#vodiscountprecentage').val();
        if(discountprecentage=="" || null){
            alert("please Insert Discount precentage first");
            return false;
        }
        else{
            var fee=inputValue;
            var discount=fee*(discountprecentage/100);
            var total=fee-discount;

            // $('#companydiscount'+count).text(discount);
            // $('#totalcost'+count).text(total);

            $('[name="companydiscount' + count + '"]').text(discount);
            $('[name="totalcost' + count + '"]').text(total);
        }
    }
    else if(request_type=="singleemployee"){
        var discountprecentage = $('#discountprecentage').val();
        if(discountprecentage=="" || null){
            alert("please Insert Discount precentage first");
            return false;
        }
        else{
            var fee=inputValue;
            var discount=fee*(discountprecentage/100);
            var total=fee-discount;

            // $('#companydiscount'+count).text(discount);
            // $('#totalcost'+count).text(total);

            $('[name="companydiscount' + count + '"]').text(discount);
            $('[name="totalcost' + count + '"]').text(total);
        }
    }
        
}
</script>
</body>

@endsection