@extends('layouts.app')

@section('content')
<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-users"></i></div>
                    <span>Approve Return</span>
                </h1>
            </div>
        </div>
    </div>
    <div class="container-fluid mt-4">
        <div class="card mb-2">
            <div class="card-body">
                <form class="form-horizontal" id="formFilter">
                    <div class="form-row mb-1">
                        <div class="col-md-2">
                                <label class="small font-weight-bold text-dark">Location*</label>
                                <select name="location" id="location" class="form-control form-control-sm">
                                    <option value="">Select Location</option>
                                    @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->branch_name}}</option>
                                            @endforeach
                                </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small font-weight-bold text-dark">Department</label>
                            <select name="department_f" id="department_f" class="form-control form-control-sm">
                                <option value="">Select Department</option>
                                @foreach($departments as $dept)
                                    <option value="{{$dept->id}}">{{$dept->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="small font-weight-bold text-dark">Emp Search Option</label>
                            <select name="search_option" id="search_option" class="form-control form-control-sm">
                                <option value="serviceno">Service No</option>
                                <option value="employee_name">Employee Name</option>
                                <option value="employee_nic">Employee NIC</option>
                            </select>
                        </div>
                        <div class="col-md-3" id="serviceno_div">
                            <label class="small font-weight-bold text-dark">Service No</label>
                            <select name="serviceno" id="serviceno" class="form-control form-control-sm">
                                <!-- Options for Service No -->
                                <option value="">Service No</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="employee_name_div">
                            <label class="small font-weight-bold text-dark">Employee Name</label>
                            <select name="employee_name" id="employee_name" class="form-control form-control-sm">
                                <!-- Options for Employee Name -->
                                <option value="">Employee Name</option>
                            </select>
                        </div>
                        
                        <div class="col-md-3" id="employee_nic_div">
                            <label class="small font-weight-bold text-dark">Employee NIC</label>
                            <select name="employee_nic" id="employee_nic" class="form-control form-control-sm">
                                <!-- Options for Employee NIC -->
                                <option value="">Employee NIC</option>
                            </select>
                        </div>
                        <div class="col-md-11">
                            <button type="submit" class="btn btn-primary btn-sm filter-btn float-right" id="btn-filter"> Filter</button>
                        </div>
                        <div class="col-md-1">
                            <button style="margin-top: 5px;width: 100px;" type="button" class="btn btn-secondary btn-sm reset-btn float-left" id="btn-reset"> Reset</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    {{-- <div class="col-12">
                        @can('employee-create')
                            <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record" id="create_record"><i class="fas fa-plus mr-2"></i>Add Return List</button>
                        @endcan
                    </div> --}}
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="emptable">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Issuing</th>
                                    <th>Department</th>
                                    <th>Employee</th>
                                    <th>Location</th>
                                    <th>Issue Month</th>
                                    <th>Issue Type</th>
                                    <th>Payment Type</th>
                                    <th>Remark</th>
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
    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title" id="staticBackdropLabel">Edit Return Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-4">
                        <span id="form_result"></span>
                        <form method="post" id="formTitle" class="form-horizontal">
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Issuing*</label>
                                    <select name="issuing" id="issuing" class="form-control form-control-sm"
                                        readonly>
                                        <option value="">Select Type</option>
                                        <option value="location">Location</option>
                                        <option value="department">Department</option>
                                        <option value="employee">Employee</option>
                                    </select>
                                </div>
                            </div>
                            <hr>
                            <div class="form-row mb-1">
                                <div id="locationDiv" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Location*</label>
                                        <select name="location1" id="location1"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Location</option>
                                            @foreach($locations as $location)
                                            <option value="{{$location->id}}">{{$location->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="departmentDiv" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Department*</label>
                                        <select name="department1" id="department1" class="form-control form-control-sm" readonly>
                                            <option value="">Select Department</option>
                                            @foreach($departments as $department)
                                            <option value="{{$department->id}}">{{$department->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="employeeDiv" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Employee*</label><br>
                                        <select name="employee" id="employee"
                                            class="form-control form-control-sm custom-select-width" readonly>
                                            <option value="">Select Employee</option>
                                            @foreach($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->service_no}} -
                                                {{$employee->emp_name_with_initial}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div id="selectTypeFirst" style="display: none;" class="col-12">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-danger">Please select an issue
                                            type first.</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Month*</label>
                                    <input type="month" id="month" name="month"
                                        class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                            <div id="PaymenttypeDiv" style="display: none;">
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Issue Type</label>
                                        <input type="text" id="issuetype" name="issuetype"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Payment Type</label>
                                        <input type="text" id="paymenttype" name="paymenttype"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Remark*</label>
                                    <textarea type="text" id="remark" name="remark"
                                        class="form-control form-control-sm" readonly></textarea>
                                </div>
                            </div>

                            <input type="hidden" name="hidden_id" id="hidden_id" />

                        </form>
                    </div>
                    <div class="col-8">
                        <table class="table table-striped table-bordered table-sm small" id="tableorder">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Rate</th>
                                    <th>QTy</th>
                                    <th>Total</th>
                                    <th>Asset value</th>
                                    <th>Store</th>
                                    <th class="d-none">ItemID</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody id="tableorderlist"></tbody>
                            <tfoot>
                                <tr style="font-weight: bold;font-size: 18px">
                                    <td colspan="3">Total:</td>
                                    <td id="totalField" class="text-left">0</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="button" name="edit_button" id="edit_button"
                    class="btn btn-warning px-3 btn-sm">Edit</button>
            </div>
        </div>
    </div>
</div>
   

<div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
        <div class="modal-header p-2">
            <h5 class="app_modal-title" id="staticBackdropLabel">Approve Return Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-4">
                    <span id="app_form_result"></span>
                    <form method="post" id="appformTitle" class="form-horizontal">
                        <div class="form-row mb-1">
                            <div class="col">
                                <label class="small font-weight-bold text-dark">Issuing*</label>
                                <select name="app_issuing" id="app_issuing" class="form-control form-control-sm"
                                    readonly>
                                    <option value="">Select Type</option>
                                    <option value="location">Location</option>
                                    <option value="department">Department</option>
                                    <option value="employee">Employee</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row mb-1">
                            <div id="app_locationDiv" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Location*</label>
                                    <select name="app_location" id="app_location"
                                        class="form-control form-control-sm" readonly>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->branch_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="app_departmentDiv" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Department*</label>
                                    <select name="app_department" id="app_department" class="form-control form-control-sm" readonly>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="app_employeeDiv" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Employee*</label><br>
                                    <select name="app_employee" id="app_employee"
                                        class="form-control form-control-sm custom-select-width" readonly>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->service_no}} -
                                            {{$employee->emp_name_with_initial}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="app_selectTypeFirst" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-danger">Please select an issue
                                        type first.</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Month*</label>
                                <input type="month" id="app_month" name="app_month"
                                    class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div id="app_PaymenttypeDiv" style="display: none;">
                            <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Issue Type</label>
                                    <input type="text" id="app_issuetype" name="app_issuetype"
                                        class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Payment Type</label>
                                    <input type="text" id="app_paymenttype" name="app_paymenttype"
                                        class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Remark*</label>
                                <textarea type="text" id="app_remark" name="app_remark"
                                    class="form-control form-control-sm" readonly></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="app_issue_id" id="app_issue_id" />
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />

                    </form>
                </div>
                <div class="col-8">
                    <table class="table table-striped table-bordered table-sm small" id="app_tableorder">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Rate</th>
                                <th>QTy</th>
                                <th>Total</th>
                                <th>Asset value</th>
                                <th>Store</th>
                                <th class="d-none">ItemID</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody id="app_tableorderlist"></tbody>
                        <tfoot>
                            <tr style="font-weight: bold;font-size: 18px">
                                <td colspan="3">Total:</td>
                                <td id="app_totalField" class="text-left">0</td>
                            </tr>
                        </tfoot>
                    </table>
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

<div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
        <div class="modal-header p-2">
            <h5 class="view_modal-title" id="staticBackdropLabel">View Return Details</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col-4">
                    <span id="view_form_result"></span>
                    <form method="post" id="viewformTitle" class="form-horizontal">
                        <div class="form-row mb-1">
                            <div class="col">
                                <label class="small font-weight-bold text-dark">Issuing*</label>
                                <select name="view_issuing" id="view_issuing" class="form-control form-control-sm"
                                    readonly>
                                    <option value="">Select Type</option>
                                    <option value="location">Location</option>
                                    <option value="department">Department</option>
                                    <option value="employee">Employee</option>
                                </select>
                            </div>
                        </div>
                        <hr>
                        <div class="form-row mb-1">
                            <div id="view_locationDiv" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Location*</label>
                                    <select name="view_location" id="view_location"
                                        class="form-control form-control-sm" readonly>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                        <option value="{{$location->id}}">{{$location->branch_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="view_departmentDiv" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Department*</label>
                                    <select name="view_department" id="view_department" class="form-control form-control-sm" readonly>
                                        <option value="">Select Department</option>
                                        @foreach($departments as $department)
                                        <option value="{{$department->id}}">{{$department->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="view_employeeDiv" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Employee*</label><br>
                                    <select name="view_employee" id="view_employee"
                                        class="form-control form-control-sm custom-select-width" readonly>
                                        <option value="">Select Employee</option>
                                        @foreach($employees as $employee)
                                        <option value="{{$employee->id}}">{{$employee->service_no}} -
                                            {{$employee->emp_name_with_initial}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="view_selectTypeFirst" style="display: none;" class="col-12">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-danger">Please select an issue
                                        type first.</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Month*</label>
                                <input type="month" id="view_month" name="view_month"
                                    class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                        <div id="view_PaymenttypeDiv" style="display: none;">
                            <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Issue Type</label>
                                    <input type="text" id="view_issuetype" name="view_issuetype"
                                        class="form-control form-control-sm" readonly>
                                </div>
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Payment Type</label>
                                    <input type="text" id="view_paymenttype" name="view_paymenttype"
                                        class="form-control form-control-sm" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Remark*</label>
                                <textarea type="text" id="view_remark" name="view_remark"
                                    class="form-control form-control-sm" readonly></textarea>
                            </div>
                        </div>

                        <input type="hidden" name="app_issue_id" id="app_issue_id" />
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />

                    </form>
                </div>
                <div class="col-8">
                    <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Rate</th>
                                <th>QTy</th>
                                <th>Total</th>
                                <th>Asset value</th>
                                <th>Store</th>
                                <th class="d-none">ItemID</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody id="view_tableorderlist"></tbody>
                        <tfoot>
                            <tr style="font-weight: bold;font-size: 18px">
                                <td colspan="3">Total:</td>
                                <td id="view_totalField" class="text-left">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
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
        var approvel01 = {{$approvel01permission}};
        var approvel02 = {{$approvel02permission}};
        var approvel03 = {{$approvel03permission}};

        var listcheck = {{$listpermission}};
        var editcheck = {{$editpermission}};
        var statuscheck = {{$statuspermission}};
        var deletecheck = {{$deletepermission}};

    $('#collapseCorporation').addClass('show');
        $('#collapsgrninfo').addClass('show');
        $('#returndrop').addClass('show');
        $('#approvereturn_link').addClass('active');

    


    $("#etfno").focusout(function(){
        let val = $(this).val();
        $('#emp_id').val(val);
    });



    function load_dt(department,location,employee){
        
        $('#emptable').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/appreturnlist.php',

                    type: "POST", // you can use GET
                    data: {'department':department, 
                           'location':location, 
                           'employee':employee, 
                        },
                    
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
                    title: 'Employee Details',
                    className: 'btn btn-primary btn-sm', 
                    text: '<i class="fas fa-print mr-2"></i> Print',
                    customize: function ( win ) {
                        $(win.document.body).find( 'table' )
                            .addClass( 'compact' )
                            .css( 'font-size', 'inherit' );
                    }, 
                },
            ],
                "order": [[ 5, "desc" ]],
                "columns": [
                    {
                        "data": "id",
                        "className": 'text-dark'
                    },
                    {
                        "data": "issuing",
                        "className": 'text-dark'
                    },
                    {
                        "data": "name",
                        "className": 'text-dark'
                    },  
                    {
                        "data": null,
                        "className": 'text-dark',
                        "render": function (data, type, full, meta) { 
                            if ((data.service_no == '') || (data.service_no== null)) {
                                return '';
                            } else {
                                return data.service_no + '-' + data.emp_name_with_initial;
                            }
                        }
                    },
                    {
                        "data": "branch_name",
                        "className": 'text-dark'
                    },
                    {
                        "data": "month",
                        "className": 'text-dark'
                    }, 
                    {
                        "data": "issue_type",
                        "className": 'text-dark'
                    },
                    {
                        "data": "payment_type",
                        "className": 'text-dark'
                    },
                    
                    {
                        "data": "remark",
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
                        if (listcheck) {
                            if (full['approve_03']==1) {
                                    button += ' <button name="view" id="' + full['id'] + '" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';
                            }
                        }
                        if (editcheck) {
                            if (full['approve_status']==0) {
                                    button += ' <button name="edit" id="' + full['id'] + '" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    }
                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="customerrequeststatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="customerrequeststatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
                        }
                        if (deletecheck) {
                            if (full['approve_status']==0) {
                            button += ' <button name="delete" id="' + full['id'] + '" issue_id="' + full['issue_id'] + '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }

                        return button;
                    }
                }
                ],
                drawCallback: function(settings) {
                    $('[data-toggle="tooltip"]').tooltip();
                }
            });
    }

    load_dt('', '', '');

$('#formFilter').on('submit',function(e) {
    e.preventDefault();
    let department = $('#department_f').val();
    let location = $('#location').val();
    var employee;

    var selecttype= $('#search_option').val();
    if(selecttype=='serviceno'){
        employee = $('#serviceno').val();
    }
    else if(selecttype=='employee_name'){
                employee = $('#employee_name').val();
    }
    else if(selecttype=='employee_nic'){
                employee = $('#employee_nic').val();
    }


    load_dt(department,location,employee);
    });

});
$(document).ready(function () {

    let company = $('#company');
    let department = $('#department');
    department.select2({
        placeholder: 'Select...',
        width: '100%',
        allowClear: true,
        ajax: {
            url: '{{url("department_list_sel2")}}',
            dataType: 'json',
            data: function(params) {
                return {
                    term: params.term || '',
                    page: params.page || 1,
                    company: company.val()
                }
            },
            cache: true
        }
    });


    $('#edit_button').click(function () {
        var issuing = $('#issuing').val();

                if(issuing=='location'){
                var location = $('#location1').val();
                var department = '';
                var employee = '';
                var month = $('#month').val();
                var issuetype = '';
                var paymenttype = '';
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }
                else if(issuing=='department'){
                var location = '';
                var department = $('#department1').val();
                var employee = '';
                var month = $('#month').val();
                var issuetype = '';
                var paymenttype = '';
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }
                else if(issuing=='employee'){
                var location = '';
                var department = '';
                var employee = $('#employee').val();
                var month = $('#month').val();
                var issuetype = $('#issuetype').val();
                var paymenttype = $('#paymenttype').val();
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }

        var tableDataArray = [];
        var isAnyFieldEmpty = false;

        $('#tableorder tbody tr').each(function() {
            var item = $(this).find('td#itemid').text();
            var itemname = $(this).find('td#itemname').text();
            var rate = $(this).find('td#rate').text();
            var qty = $(this).find('td#qty').text();
            var total = $(this).find('td#total').text();
            var assetvalue = $(this).find('input[type="text"]').val();
            var stockId = $(this).find('select').val();

            // Check if any input or select is empty
            if (!assetvalue || stockId === "") {
                // Display an alert if any input or select is empty
                alert("Please fill in all fields for row with item: " + itemname);
                isAnyFieldEmpty = true; // Set the flag to true
                return false; // Exit the loop
            }

            // Proceed with creating the rowData object and pushing it to the array
            var rowData = {
                'item': item,
                'rate': rate,
                'qty': qty,
                'total': total,
                'assetvalue': assetvalue,
                'stockId': stockId,
                // Add other properties as needed
            };

            // Push the current row data object to the array
            tableDataArray.push(rowData);
        });

        // Log the array if all rows have valid data
        if (!isAnyFieldEmpty) {
            // console.log(tableDataArray);
            // console.log(issuing,location,department,employee,month,issuetype,paymenttype,remark,hidden_id);
              $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableDataArray: tableDataArray,
                        issuing: issuing,
                        location: location,
                        department: department,
                        employee: employee,
                        month: month,
                        issuetype: issuetype,
                        paymenttype: paymenttype,
                        remark: remark,
                        hidden_id: hidden_id,

                    },
                    url: '{!! route("approvereturnupdate") !!}',
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

    $(document).on('click', '.edit', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("approvereturnedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#issuing').val(data.result.mainData.issuing);

                    edit_issuingChanges(data.result.mainData.issuing);

                    if (data.result.mainData.issuing == "employee") {
                        $('#employee').val(data.result.mainData.employee_id);
                        $('#issuetype').val(data.result.mainData.issue_type);
                        $('#paymenttype').val(data.result.mainData.payment_type);
                    } else if(data.result.mainData.issuing == "department"){
                        $('#department1').val(data.result.mainData.department_id);
                    }else {
                        $('#location1').val(data.result.mainData.location_id);
                    }

                    $('#month').val(data.result.mainData.month);
                    $('#remark').val(data.result.mainData.remark);

                    $('#tableorderlist').html(data.result.requestdata);
                    TotalSum();

                    $('#hidden_id').val(id_approve);
                    $('#editModal').modal('show');

                }
            })
        });

        $(document).on('click', '.appL1', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("appreturn") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_issuing').val(data.result.mainData.issuing);

                    app_issuingChanges(data.result.mainData.issuing);

                    if (data.result.mainData.issuing == "employee") {
                        $('#app_employee').val(data.result.mainData.employee_id);
                        $('#app_issuetype').val(data.result.mainData.issue_type);
                        $('#app_paymenttype').val(data.result.mainData.payment_type);
                    }else if(data.result.mainData.issuing == "department"){
                        $('#app_department').val(data.result.mainData.department_id);
                    } else {
                        $('#app_location').val(data.result.mainData.location_id);
                    }

                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    ApproveTotalSum();

                    $('#app_issue_id').val(data.result.mainData.issue_id);
                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('1');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        $(document).on('click', '.appL2', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("appreturn") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_issuing').val(data.result.mainData.issuing);

                    app_issuingChanges(data.result.mainData.issuing);

                    if (data.result.mainData.issuing == "employee") {
                        $('#app_employee').val(data.result.mainData.employee_id);
                        $('#app_issuetype').val(data.result.mainData.issue_type);
                        $('#app_paymenttype').val(data.result.mainData.payment_type);
                    }else if(data.result.mainData.issuing == "department"){
                        $('#app_department').val(data.result.mainData.department_id);
                    } else {
                        $('#app_location').val(data.result.mainData.location_id);
                    }

                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    ApproveTotalSum();

                    $('#app_issue_id').val(data.result.mainData.issue_id);
                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('2');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        $(document).on('click', '.appL3', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("appreturn") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_issuing').val(data.result.mainData.issuing);

                    app_issuingChanges(data.result.mainData.issuing);

                    if (data.result.mainData.issuing == "employee") {
                        $('#app_employee').val(data.result.mainData.employee_id);
                        $('#app_issuetype').val(data.result.mainData.issue_type);
                        $('#app_paymenttype').val(data.result.mainData.payment_type);
                    }else if(data.result.mainData.issuing == "department"){
                        $('#app_department').val(data.result.mainData.department_id);
                    } else {
                        $('#app_location').val(data.result.mainData.location_id);
                    }

                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    ApproveTotalSum();

                    $('#app_issue_id').val(data.result.mainData.issue_id);
                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });

        $('#approve_button').click(function () {
                var id_hidden = $('#app_hidden_id').val();
                var applevel = $('#app_level').val();
                var issue_id=$('#app_issue_id').val();

                var tableDataArray = [];

                $('#app_tableorder tbody tr').each(function() {
            var item = $(this).find('td#itemid').text();
            var itemname = $(this).find('td#itemname').text();
            var rate = $(this).find('td#rate').text();
            var qty = $(this).find('td#qty').text();
            var total = $(this).find('td#total').text();
            var assetvalue = $(this).find('input[type="text"]').val();
            var stockId = $(this).find('select').val();

            // Proceed with creating the rowData object and pushing it to the array
            var rowData = {
                'item': item,
                'rate': rate,
                'qty': qty,
                'total': total,
                'assetvalue': assetvalue,
                'stockId': stockId,
                // Add other properties as needed
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
                    url: '{!! route("approvereturnapprove") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: {id: id_hidden,
                            applevel: applevel,
                            issue_id:issue_id,
                            tableDataArray:tableDataArray },
                    success: function (data) {//alert(data);
                        setTimeout(function () {
                            $('#approveconfirmModal').modal('hide');
                            $('#dataTable').DataTable().ajax.reload();
                            // alert('Data Approved');
                        }, 2000);
                        location.reload()
                    }
                })
            });


            $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("appreturn") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#view_issuing').val(data.result.mainData.issuing);

                    view_issuingChanges(data.result.mainData.issuing);

                    if (data.result.mainData.issuing == "employee") {
                        $('#view_employee').val(data.result.mainData.employee_id);
                        $('#view_issuetype').val(data.result.mainData.issue_type);
                        $('#view_paymenttype').val(data.result.mainData.payment_type);
                    }else if(data.result.mainData.issuing == "department"){
                        $('#view_department').val(data.result.mainData.department_id);
                    } else {
                        $('#view_location').val(data.result.mainData.location_id);
                    }

                    $('#view_month').val(data.result.mainData.month);
                    $('#view_remark').val(data.result.mainData.remark);

                    $('#view_tableorderlist').html(data.result.requestdata);
                    viewTotalSum();

                    $('#view_issue_id').val(data.result.mainData.issue_id);
                    $('#viewModal').modal('show');

                }
            })
        });


        var user_id;
        var issue_id;
        $(document).on('click', '.delete', function () {
            user_id = $(this).attr('id');
            issue_id = $(this).attr('issue_id');
            $('#confirmModal').modal('show');
        });

        $('#ok_button').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("approvereturndelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: user_id,
                    issue_id:issue_id
                },
                beforeSend: function () {
                    $('#ok_button').text('Deleting...');
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Deleted');
                    }, 2000);
                    location.reload()
                }
            })
        });

});
function edit_issuingChanges(edit_issuing) {

if (edit_issuing === "location") {
    $("#locationDiv").show();
    $("#departmentDiv").hide();
    $("#employeeDiv").hide();
    $("#selectTypeFirst").hide();
    $("#PaymenttypeDiv").hide();
} else if (edit_issuing === "employee") {
    $("#locationDiv").hide();
    $("#departmentDiv").hide();
    $("#employeeDiv").show();
    $("#selectTypeFirst").hide();
    $("#PaymenttypeDiv").show();
} else if(edit_issuing === "department"){
        $("#edit_departmentDiv").show();
        $("#locationDiv").hide();
        $("#employeeDiv").hide();
        $("#selectTypeFirst").hide();
        $("#PaymenttypeDiv").hide();
    }else {
    $("#locationDiv").hide();
    $("#departmentDiv").hide();
    $("#employeeDiv").hide();
    $("#selectTypeFirst").show();
    $("#PaymenttypeDiv").hide();
}

}

function TotalSum() {
        var totalSum = 0;

        $('#tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#totalField').text(totalSum.toFixed(2));
    }


    function app_issuingChanges(app_issuing) {

if (app_issuing === "location") {
    $("#app_locationDiv").show();
    $("#app_departmentDiv").hide();
    $("#app_employeeDiv").hide();
    $("#app_selectTypeFirst").hide();
    $("#app_PaymenttypeDiv").hide();
} else if (app_issuing === "employee") {
    $("#app_locationDiv").hide();
    $("#app_departmentDiv").hide();
    $("#app_employeeDiv").show();
    $("#app_selectTypeFirst").hide();
    $("#app_PaymenttypeDiv").show();
} else if(app_issuing === "department"){
        $("#app_departmentDiv").show();
        $("#app_locationDiv").hide();
        $("#app_employeeDiv").hide();
        $("#app_selectTypeFirst").hide();
        $("#app_PaymenttypeDiv").hide();
    }else {
    $("#app_locationDiv").hide();
    $("#app_departmentDiv").hide();
    $("#app_employeeDiv").hide();
    $("#app_selectTypeFirst").show();
    $("#app_PaymenttypeDiv").hide();
}

}

function ApproveTotalSum() {
        var totalSum = 0;

        $('#app_tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#app_totalField').text(totalSum.toFixed(2));
    }


    function view_issuingChanges(view_issuing) {

if (view_issuing === "location") {
    $("#view_locationDiv").show();
    $("#view_departmentDiv").hide();
    $("#view_employeeDiv").hide();
    $("#app_selectTypeFirst").hide();
    $("#view_PaymenttypeDiv").hide();
} else if (view_issuing === "employee") {
    $("#view_locationDiv").hide();
    $("#view_departmentDiv").hide();
    $("#view_employeeDiv").show();
    $("#view_selectTypeFirst").hide();
    $("#view_PaymenttypeDiv").show();
} else if(view_issuing === "department"){
        $("#view_departmentDiv").show();
        $("#view_locationDiv").hide();
        $("#view_employeeDiv").hide();
        $("#view_selectTypeFirst").hide();
        $("#view_PaymenttypeDiv").hide();
    }else {
    $("#view_locationDiv").hide();
    $("#view_departmentDiv").hide();
    $("#view_employeeDiv").hide();
    $("#view_selectTypeFirst").show();
    $("#view_PaymenttypeDiv").hide();
}

}

function viewTotalSum() {
        var totalSum = 0;

        $('#view_tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#view_totalField').text(totalSum.toFixed(2));
    }
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
                url: '{!! route("returnserviceno") !!}',
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

        $("#employee_name_div, #employee_nic_div").hide();

        // Add change event listener to the search option select
        $("#search_option").change(function () {
            // Hide all divs
            $("#serviceno_div, #employee_name_div, #employee_nic_div").hide();
            var selectedOption = $(this).val();
            $("#" + selectedOption + "_div").show();

if(selectedOption=='serviceno'){
 $('#serviceno').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returnserviceno") !!}',
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
}
else if(selectedOption=='employee_name'){
    $('#employee_name').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returngetempname") !!}',
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
}
else if(selectedOption=='employee_nic'){
    $('#employee_nic').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("returngetempnic") !!}',
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
}
        });


        // Store the initial/default values of the select elements
        var initialEmployeeNameValue ='';
        var initialEmployeeNicValue = '';
        var initialServiceNoValue ='';

        // Add a click event listener to the Reset button
        $("#btn-reset").click(function () {

            // Reset the Select2 element
            $("#serviceno").val(initialServiceNoValue).trigger("change");
            $("#employee_name").val(initialEmployeeNameValue).trigger("change");
            $("#employee_nic").val(initialEmployeeNicValue).trigger("change");
            
            // Clear other form fields if needed
            $("#department_f").val("");
            $("#location").val("");
        });
    });
</script>

@endsection