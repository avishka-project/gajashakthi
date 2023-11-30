@extends('layouts.app')

@section('content')
<style>
    .custom-select-width {
        /* Adjust the width as needed */
        width: 200px;
        /* For example */
    }
</style>
<main>
    <div class="page-header page-header-light bg-white shadow">
    <div class="container-fluid">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-users"></i></div>
                <span>Item Request</span>
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
                        @if(in_array('Issue-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Issue</button>
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
                                    <th>Issuing</th>
                                    <th>Department</th>
                                    <th>Employee</th>
                                    <th>Location</th>
                                    <th>Month</th>
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
    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Add Issue</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Issuing*</label>
                                        <select name="issuing" id="issuing" class="form-control form-control-sm"
                                            required>
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
                                            <select name="location" id="location" class="form-control form-control-sm">
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
                                            <select name="department" id="department" class="form-control form-control-sm">
                                                <option value="">Select Department</option>
                                                @foreach($departments as $department)
                                                <option value="{{$department->id}}">{{$department->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div id="employeeDiv" style="display: none;" class="col-12">
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Search Employee*</label><br>
                                            <select name="employee" id="employee"
                                                class="form-control form-control-sm custom-select-width"
                                                onchange="getEmpName();idgetinserch()">
                                                <option value="">Select Employee</option>

                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Emp Name*</label>
                                            <input type="text" class="form-control form-control-sm" placeholder=""
                                                name="empname" id="empname" readonly>
                                        </div>
                                        <input type="hidden" class="form-control form-control-sm" placeholder=""
                                            name="editempid" id="editempid" readonly>
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
                                        <input type="month" id="month" name="month" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>

                                <div id="PaymenttypeDiv" style="display: none;">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Issue Type</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="issuetype" id="freeIssueRadio" value="free">
                                                <label class="form-check-label" for="freeIssueRadio">
                                                    Free Issue
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="issuetype" id="paidRadio" value="paid">
                                                <label class="form-check-label" for="paidRadio">
                                                    Paid
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Payment Type</label>
                                            <div class="form-check" id="cashRadioSection">
                                                <input class="form-check-input" type="radio" name="paymenttype" id="cashRadio" value="cash">
                                                <label class="form-check-label" for="cashRadio">
                                                    Cash
                                                </label>
                                            </div>
                                            <div class="form-check" id="loanRadioSection">
                                                <input class="form-check-input" type="radio" name="paymenttype" id="loanRadio" value="loan">
                                                <label class="form-check-label" for="loanRadio">
                                                    Loan
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Remark*</label>
                                        <textarea type="text" id="remark" name="remark"
                                            class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Asset value*</label>
                                        <select name="assetvalue" id="assetvalue" class="form-control form-control-sm" required>
                                            <option value="">Select Asset value</option>
                                            <option value="brandnew">Brand New</option>
                                            <option value="used">Used</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Store*</label>
                                        <select name="store" id="store" class="form-control form-control-sm" required>
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Item*</label>
                                        <select name="item" id="item" class="form-control form-control-sm"required>
                                            <option value="">Select Item</option>
                                            
                                        </select>
                                    </div>
                                    <diV id="DivBatchNo" class="col-6">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Batch No*</label>
                                        <select name="batchno" id="batchno" class="form-control form-control-sm" required>
                                            <option value="">Select Batch No</option>
                                            
                                        </select>
                                    </div>
                                    </diV>
                                    <diV id="DivUsedQuality" style="display: none;" class="col-6">
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Quality*</label>
                                            <select name="usedquality" id="usedquality" class="form-control form-control-sm" required>
                                                <option value="">Select Quality</option>
                                                
                                            </select>
                                        </div>
                                        </diV>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Rate*</label>
                                        <div class="input-group">
                                        <input type="number" id="rate" name="rate" class="form-control form-control-sm"
                                            required>
                                            &nbsp;
										<div class="input-group-append">
											<button title="Update Price" class="form-control-sm btn-warning updateprice" type="button"><i
													class="fas fa-pen"></i></button>
										</div>
                                    </div>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">QTY*<span style="color: red" id="stockqty"></span></label>
                                        <input type="number" id="qty" name="qty" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="button" id="formsubmit"
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                    <button type="button" name="Btnupdatelist" id="Btnupdatelist"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;"><i
                                            class="fas fa-plus"></i>&nbsp;Update List</button>
                                </div>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="issuedeiailsid" id="issuedeiailsid">
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Asset Value</th>
                                        <th>Rate</th>
                                        <th>QTy</th>
                                        <th>Total</th>
                                        <th class="d-none">ItemID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableorderlist"></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 18px">
                                        <td colspan="4">Total:</td>
                                        <td id="totalField" class="text-left">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Request</button>

                            </div>
                        </div>
                    </div>
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

    <div class="modal fade" id="confirmModal3" data-backdrop="static" data-keyboard="false" tabindex="-1"
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
                        <h4 class="font-weight-normal">Are you sure you want to Update Price?</h4>
                    </div>
                </div>
            </div>
            <div class="modal-footer p-2">
                <button type="button" name="ok_button3" id="ok_button3"
                    class="btn btn-danger px-3 btn-sm">OK</button>
                <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Issue Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result"></span>
                            <form class="form-horizontal">
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

                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="app_level" id="app_level" value="1" />

                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="app_tableorder">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Asset Value</th>
                                        <th>Rate</th>
                                        <th>Qty</th>
                                        <th>Total</th>
                                        <th class="d-none">ItemID</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="app_tableorderlist"></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 18px">
                                        <td colspan="4">Total:</td>
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

    <div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">View Issue Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result"></span>
                            <form class="form-horizontal">
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Issuing*</label>
                                        <select name="view_issuing" id="view_issuing"
                                            class="form-control form-control-sm" readonly>
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
                                            <select name="view_location" id="view_location" class="form-control form-control-sm" readonly>
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

                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Asset Value</th>
                                        <th>Rate</th>
                                        <th>QTy</th>
                                        <th>Total</th>
                                        <th class="d-none">ItemID</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="view_tableorderlist"></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 18px">
                                        <td colspan="4">Total:</td>
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
        $('#collapseCorporation').addClass('show');
        $('#collapsgrninfo').addClass('show');
        $('#issue_link').addClass('active');

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
                url: scripturl + '/issuelist.php',

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
                    title: 'Item Request Details',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'print',
                    title: 'Item Request Details',
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
                    "data": "issuing",
                    "className": 'text-dark'
                },

                {
                    "data": "name",
                    "className": 'text-dark'
                },
                {
                    "data": "emp_name_with_initial",
                    "className": 'text-dark'
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
                                    button += ' <a href="issuestatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="issuestatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
                        }
                        if (deletecheck) {
                            if (full['approve_status']==0) {
                            button += ' <button name="delete" id="' + full['id'] + '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
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
            $('.modal-title').text('Add New Issue');
            $('#action_button').html('Add');
            $('#btncreateorder').html('Create Request');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();

            $('#formModal').modal('show');
        });

        $("#formsubmit").click(function () {
            if (!$("#formTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var ItemID = $('#item').val();
                var Rate = $('#rate').val();
                var QTy = $('#qty').val();
                var AssetvalueID = $("#assetvalue").val();
                var StoreID = $("#store").val();
                var StockID = $("#batchno").val();
                var ReturnStockID = $("#usedquality").val();

                var total = (Rate * QTy)

                var Item = $("#item option:selected").text();
                var Assetvalue = $("#assetvalue option:selected").text();

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + Item +
                    '</td><td>' + Assetvalue +
                    '</td><td>' + Rate + '</td><td>' + QTy + '</td><td>' + total +
                    '</td><td name="itemid" class="d-none">' + ItemID +
                    '</td><td name="assetvalue" class="d-none">' + AssetvalueID +
                    '</td><td name="storeid" class="d-none">' + StoreID +
                    '</td><td name="newstock_id" class="d-none">' + StockID +
                    '</td><td name="returnstock_id" class="d-none">' + ReturnStockID +
                    '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
                );

                updateTotalSum();
                $('#assetvalue').val('');
                $('#item').val('');
                $('#rate').val('');
                $('#qty').val('');
                $('#batchno').val('');
                $('#stockqty').text('');
                $('#usedquality').val('');
            }
        });


        $('#btncreateorder').click(function () {

            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('issueinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('issueupdate') }}";
            }

            // $('#btncreateorder').prop('disabled', true).html(
            //     '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

            var tbody = $("#tableorder tbody");

            if (tbody.children().length > 0) {
                var jsonObj = [];
                $("#tableorder tbody tr").each(function () {
                    var item = {};
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });
                var issuing = $('#issuing').val();

                if(issuing=='location'){
                var location = $('#location').val();
                var department = '';
                var employee = '';
                var editempid = '';
                var month = $('#month').val();
                var issuetype = '';
                var paymenttype = '';
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }
                else if(issuing=='department'){
                var location = '';
                var department = $('#department').val();
                var employee = '';
                var editempid = '';
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
                var editempid = $('#editempid').val();
                var month = $('#month').val();
                var issuetype = $('input[name="issuetype"]:checked').val();
                var paymenttype = $('input[name="paymenttype"]:checked').val();
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();
                }

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        issuing: issuing,
                        location: location,
                        department: department,
                        employee: employee,
                        editempid: editempid,
                        month: month,
                        issuetype: issuetype,
                        paymenttype: paymenttype,
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
        var issueTypeValue;
        var paymentTypeValue;
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            resetfield();

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issueedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#issuing').val(data.result.mainData.issuing);

                    edit_issuingChanges(data.result.mainData.issuing);


                    if (data.result.mainData.issuing == "employee") {
                        $('#editempid').val(data.result.mainData.employee_id);
                        getEmpNameforEdit(data.result.mainData.employee_id)
                    }else if(data.result.mainData.issuing == "department"){
                        $('#department').val(data.result.mainData.department_id);
                    } else {
                        $('#location').val(data.result.mainData.location_id);
                    }

                    $('#month').val(data.result.mainData.month);
                    $('#remark').val(data.result.mainData.remark);

                    issueTypeValue = (data.result.mainData.issue_type);
                    paymentTypeValue = (data.result.mainData.payment_type);
                    selectTypeInEdit();

                    $('#tableorderlist').html(data.result.requestdata);
                    updateTotalSum();

                    // var valueToCheck = data.result.pay_by;

                    // if (valueToCheck == 1 ) {
                    //     $('#company').prop('checked', true);
                    // } else {
                    //      $('#branch').prop('checked', true);
                    // }

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Issues');
                    $('#action_button').html('Edit');
                    $('#btncreateorder').html('Update Request');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        // request detail edit
        $(document).on('click', '.btnEditlist', function () {
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuedetailedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#item').val(data.result.item_id);
                    $('#rate').val(data.result.rate);
                    $('#qty').val(data.result.qty);

                    $('#assetvalue').val(data.result.asset_value);
                    $('#store').val(data.result.storelist_id);
                    editGetItem(data.result.asset_value,data.result.storelist_id,data.result.item_id);
                    editGetBatchNo(data.result.asset_value,data.result.storelist_id,data.result.item_id,data.result.stock_id);
                    editChooseBatch_or_Used(data.result.asset_value);
                    editgetBatchnoPriceQty(data.result.stock_id);
                    editgetUsedQualityPriceQty(data.result.stock_id);

                    $('#issuedeiailsid').val(data.result.id);
                    $('#Btnupdatelist').show();
                    $('#formsubmit').hide();
                }
            })
        });

        // request detail update list


        $(document).on("click", "#Btnupdatelist", function () {
            if (!$("#formTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
            var ItemID = $('#item').val();
            var AssetvalueID = $("#assetvalue").val();
            var Rate = $('#rate').val();
            var Qty = $('#qty').val();
            var Item = $("#item option:selected").text();
            var detailid = $('#issuedeiailsid').val();
            var StoreID = $("#store").val();
            var StockID = $("#batchno").val();
            var ReturnStockID = $("#usedquality").val();

            var total = (Rate * Qty)
            

            $("#tableorder> tbody").find('input[name="hiddenid"]').each(function () {
                var hiddenid = $(this).val();
                if (hiddenid == detailid) {
                    $(this).parents("tr").remove();
                }
            });

            $('#tableorder> tbody:last').append('<tr class="pointer"><td>' + Item +
                '</td>><td>' + AssetvalueID +
                '</td><td>' + Rate + '</td><td>' + Qty + '</td><td>' + total +
                '</td><td class="d-none">' + ItemID +
                '</td><td name="assetvalue" class="d-none">' + AssetvalueID +
                '</td><td name="storeid" class="d-none">' + StoreID +
                '</td><td name="newstock_id" class="d-none">' + StockID +
                '</td><td name="returnstock_id" class="d-none">' + ReturnStockID +
                '</td><td class="d-none">Updated</td><td class="d-none">' +
                detailid +
                '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
            );

            updateTotalSum();

                $('#Btnupdatelist').hide();
                $('#formsubmit').show();
                $('#assetvalue').val('');
                $('#item').val('');
                $('#rate').val('');
                $('#qty').val('');
                $('#batchno').val('');
                $('#stockqty').text('');
                $('#usedquality').val('');
                $('#store').val('');
            }
        });

        //   details delete
        var rowid
        $(document).on('click', '.btnDeletelist', function () {
            rowid = $(this).attr('rowid');
            $('#confirmModal2').modal('show');

        });

        $('#ok_button2').click(function () {

            $('#form_result').html('');
            productDelete(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuedetaildelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: rowid
                },
                beforeSend: function () {
                    $('#ok_button2').text('Deleting...');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#confirmModal2').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Deleted');
                    }, 2000);
                    location.reload()
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
                url: '{!! route("issuedelete") !!}',
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
                        alert('Data Deleted');
                    }, 2000);
                    location.reload()
                }
            })
        });
        var assetvalue;
        var unite_price;
        var batchno;
        var returnItemQuality;
        var itemid;
        var itemname;
        var storeid;
        $(document).on('click', '.updateprice', function () {
            assetvalue = $('#assetvalue').val();
            itemid = $('#item').val();
            itemname = $("#item option:selected").text();
            batchno = $('#batchno').val();
            returnItemQuality = $('#usedquality').val();
            unite_price = $('#rate').val();
            if(unite_price==null || unite_price==""){
                alert("Please Insert Unite Price")
            }
            else{
                $('#confirmModal3').modal('show');
            } 
        });

        $('#ok_button3').click(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("issueupdateprice") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    assetvalue: assetvalue,
                    itemid: itemid,
                    itemname: itemname,
                    batchno_id: batchno,
                    returnItemQuality_id: returnItemQuality,
                    unite_price: unite_price
                },
                beforeSend: function () {
                    $('#ok_button3').text('Updating...');
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#confirmModal3').modal('hide');
                        $('#ok_button3').text('Ok');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Deleted');
                    }, 2000);
                    // location.reload()
                }
            })

            // console.log(assetvalue,itemid,itemname,batchno,returnItemQuality,unite_price);
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
                url: '{!! route("issuedetailapprovel_details") !!}',
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
                    } else if(data.result.mainData.issuing == "department"){
                        $('#app_department').val(data.result.mainData.department_id);
                    }else {
                        $('#app_location').val(data.result.mainData.location_id);
                    }

                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    ApproveTotalSum();

                    $('#hidden_id').val(id_approve);
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
                url: '{!! route("issuedetailapprovel_details") !!}',
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

                    $('#hidden_id').val(id_approve);
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
                url: '{!! route("issuedetailapprovel_details") !!}',
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

                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#hidden_id').val();
            var applevel = $('#app_level').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("issueapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // alert('Data Approved');
                    }, 2000);
                    location.reload()
                }
            })

            if(applevel==3){
                updatestock();
            }
        });

function updatestock(){
    var tbody = $("#app_tableorder tbody");

if (tbody.children().length > 0) {
    var jsonObj = [];
    $("#app_tableorder tbody tr").each(function () {
        var item = {};
        $(this).find('td').each(function (col_idx) {
            item["col_" + (col_idx + 1)] = $(this).text();
        });
        jsonObj.push(item);
    });
// console.log(jsonObj);
$.ajax({
                method: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    tableData: jsonObj,

                },
                url: '{!! route("issuestockupdate") !!}',
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
                        //$('#titletable').DataTable().ajax.reload();
                        window.location.reload(); // Use window.location.reload()
                    }
                    // resetfield();

                }
            });

}
}
        // View model
        $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuedetailapprovel_details") !!}',
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
                    }
                    else if(data.result.mainData.issuing == "department"){
                        $('#view_department').val(data.result.mainData.department_id);
                    } else {
                        $('#view_location').val(data.result.mainData.location_id);
                    }

                    $('#view_month').val(data.result.mainData.month);
                    $('#view_remark').val(data.result.mainData.remark);

                    $('#view_tableorderlist').html(data.result.requestdata);
                    ViewTotalSum();

                    $('#viewModal').modal('show');

                }
            })


        });

        function selectTypeInEdit() {

            var freeRadio = document.querySelector('input[value="free"]');
            var paidRadio = document.querySelector('input[value="paid"]');

            var cashRadio = document.querySelector('input[value="cash"]');
            var loanRadio = document.querySelector('input[value="loan"]');

            if (issueTypeValue === 'free') {
                $("#cashRadioSection").hide();
                $("#loanRadioSection").hide();
                freeRadio.checked = true;
            } else if (issueTypeValue === 'paid') {
                $("#cashRadioSection").show();
                $("#loanRadioSection").show();
                paidRadio.checked = true;
            }

            if (paymentTypeValue === 'cash') {
                cashRadio.checked = true;
            } else if (paymentTypeValue === 'loan') {
                loanRadio.checked = true;
            }
        }


        function resetfield() {
            $('#issuing').val('');
            $('#location').val('');
            $('#employee').val('');
            $('#month').val('');
            $('input[name="issuetype"]').prop('checked', false);
            $('input[name="paymenttype"]').prop('checked', false);
            $('#remark').val('');
        }


        
    });

    function getEmpName() {
        var empid = $('#employee').val();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("deaddonationgetempname") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: empid
            },
            success: function (data) {
                $('#empname').val(data.result.emp_name_with_initial);
                // $('#app_empname').val(data.result.emp_name_with_initial);
            }
        })
    }

    function getEmpNameforEdit(empid) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("deaddonationgetempname") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: empid
            },
            success: function (data) {
                $('#empname').val(data.result.emp_name_with_initial);
                // $('#app_empname').val(data.result.emp_name_with_initial);
            }
        })
    }

    // get sale price in select item
    // function getsaleprice() {
    //     var itemid = $('#item').val();

    //     $.ajaxSetup({
    //         headers: {
    //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //         }
    //     })

    //     $.ajax({
    //         url: '{!! route("getsaleprice") !!}',
    //         type: 'POST',
    //         dataType: "json",
    //         data: {
    //             id: itemid
    //         },
    //         success: function (data) {
    //             $('#rate').val(data.result.sale_price);
    //             // $('#app_empname').val(data.result.emp_name_with_initial);
    //         }
    //     })

    // }

    function updateTotalSum() {
        var totalSum = 0;

        $('#tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(4)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#totalField').text(totalSum.toFixed(2));
        $('#totalcost').val(totalSum.toFixed(2));
    }

    function ApproveTotalSum() {
        var totalSum = 0;

        $('#app_tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(4)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#app_totalField').text(totalSum.toFixed(2));
    }

    function ViewTotalSum() {
        var totalSum = 0;

        $('#view_tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(4)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#view_totalField').text(totalSum.toFixed(2));
    }

    function productDelete(row) {
        $(row).closest('tr').remove();
        updateTotalSum();
    }


    function productDelete(ctl) {
        $(ctl).parents("tr").remove();
        updateTotalSum();
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>

<script>
    $(document).ready(function () {
        $("#selectTypeFirst").show();
        $("#issuing").change(function () {
            var selectedOption = $(this).val();
            if (selectedOption === "location") {
                $("#locationDiv").show();
                $("#departmentDiv").hide();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").hide();
                $("#PaymenttypeDiv").hide();

                $("#location").prop("required", true);
                $("#department").prop("required", false);
                $("#employee").prop("required", false);
                $("#employeeDiv input[name='issuetype']").prop("required", false);
            } else if (selectedOption === "employee") {
                $("#locationDiv").hide();
                $("#departmentDiv").hide();
                $("#employeeDiv").show();
                $("#selectTypeFirst").hide();
                $("#PaymenttypeDiv").show();

                
                $("#location").prop("required", false);
                $("#department").prop("required", false);
                $("#employee").prop("required", true);
                $("#employeeDiv input[name='issuetype']").prop("required", true);
            } else if(selectedOption === "department"){
                $("#departmentDiv").show();
                $("#locationDiv").hide();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").hide();
                $("#PaymenttypeDiv").hide();

                
                $("#location").prop("required", false);
                $("#department").prop("required", true);
                $("#employee").prop("required", false);
                $("#employeeDiv input[name='issuetype']").prop("required", false);
            }
            else{
                $("#locationDiv").hide();
                $("#departmentDiv").hide();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").show();
                $("#PaymenttypeDiv").hide();

                
                $("#location").prop("required", false);
                $("#department").prop("required", false);
                $("#employee").prop("required", false);
                $("#employeeDiv input[name='issuetype']").prop("required", false);
            }
        });



        // Initially hide the payment type section
        $("#cashRadioSection").hide();
        $("#loanRadioSection").hide();

        // When the "issuetype" radio buttons change
        $("input[name='issuetype']").change(function () {
            if ($("#paidRadio").is(":checked")) {
                // If "paidRadio" is selected, show the payment type section
                $("#cashRadio").prop("checked", false);
                $("#loanRadio").prop("checked", true);
                $("#cashRadioSection").show();
                $("#loanRadioSection").show();
            } else {
                // If "freeIssueRadio" is selected, hide the payment type section 
                $("#cashRadio").prop("checked", false);
                $("#loanRadio").prop("checked", false);
                $("#cashRadioSection").hide();
                $("#loanRadioSection").hide();
            }
        });
    });

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

    function view_issuingChanges(app_issuing) {

        if (app_issuing === "location") {
            $("#view_locationDiv").show();
            $("#view_departmentDiv").hide();
            $("#view_employeeDiv").hide();
            $("#view_selectTypeFirst").hide();
            $("#view_PaymenttypeDiv").hide();
        } else if (app_issuing === "employee") {
            $("#view_locationDiv").hide();
            $("#view_departmentDiv").hide();
            $("#view_employeeDiv").show();
            $("#view_selectTypeFirst").hide();
            $("#view_PaymenttypeDiv").show();
        } else if(app_issuing === "department"){
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


    function edit_issuingChanges(issuing) {

        if (issuing === "location") {
            $("#locationDiv").show();
            $("#departmentDiv").hide();
            $("#employeeDiv").hide();
            $("#selectTypeFirst").hide();
            $("#PaymenttypeDiv").hide();
        } else if (issuing === "employee") {
            $("#locationDiv").hide();
            $("#departmentDiv").hide();
            $("#employeeDiv").show();
            $("#selectTypeFirst").hide();
            $("#PaymenttypeDiv").show();
        } else if(issuing === "department"){
                $("#departmentDiv").show();
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

        if ($("#paidRadio").is(":checked")) {
                // If "paidRadio" is selected, show the payment type section
                $("#cashRadio").prop("checked", false);
                $("#loanRadio").prop("checked", true);
                $("#cashRadioSection").show();
                $("#loanRadioSection").show();
            } else {
                // If "freeIssueRadio" is selected, hide the payment type section 
                $("#cashRadio").prop("checked", false);
                $("#loanRadio").prop("checked", false);
                $("#cashRadioSection").hide();
                $("#loanRadioSection").hide();
            }

    }
</script>
<script>
    // Initialize Select2 on the select element
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#employee').select2({
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

    });

    function idgetinserch() {
        var editempid = $('#employee').val();
        $('#editempid').val(editempid);
    };
</script>
<script>
    // normal request form reset
    $('#formModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#formTitle')[0].reset();

        $('#tableorderlist').empty();
        //reset table footercount
        $('#totalField').text('0');
   
    });

    </script>
<script>
    var assetvalue='';
   $(document).ready(function() {
//    check which caregory asset value
    $('#assetvalue').change(function () {
            assetvalue = $(this).val();
                $('#store').val('');
                $('#item').val('');
                $('#rate').val('');
                $('#qty').val('');
                $('#batchno').val('');
                $('#stockqty').text('');
                $('#usedquality').val('');
        });
        
 // get item in store
    $('#store').change(function () {
        var store_id = $(this).val();
        if (assetvalue == 'brandnew') {
            if (store_id !== '') {
                $.ajax({
                    url: '{!! route("getitemToIssue", ["store_id" => "id_store"]) !!}'
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, items) {
                            $('#item').append('<option value="' + items
                                .item_id + '">' + items.inventorylist_id + '-' + items.name + ' ' + (items.uniform_size==null?'':items.uniform_size+'"') + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#item').empty().append('<option value="">Select Item</option>');
            }
        } else {
            if (store_id !== '') {
                $.ajax({
                    url: '{!! route("getReturnitemToIssue", ["store_id" => "id_store"]) !!}'
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, returnitems) {
                            $('#item').append('<option value="' + returnitems
                                .item_id + '">' + returnitems.inventorylist_id + '-' + returnitems.name + ' ' + (returnitems.uniform_size==null?'':returnitems.uniform_size+'"') + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#item').empty().append('<option value="">Select Item</option>');
            }
        }
        
        });



    // filter batch no 
    $('#item').change(function () {
        $('#stockqty').text('');
        // $('#usedquality').val('');
        // $('#rate').val('');
        // $('#qty').val('');
        // $('#batchno').val('');
            var itemid = $(this).val();
            var store_id = $('#store').val();
            console.log(itemid,store_id,assetvalue);
            if (assetvalue == 'brandnew') {
                if (itemid !== '') {
                $.ajax({
                    url: '{!! route("getbatchnoToIssue", ["itemId" => "id_item", "store_id" => "id_store"]) !!}'
                            .replace('id_item', itemid)
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#batchno').empty().append(
                            '<option value="">Select Batch No</option>');
                        $.each(data, function (index, stocks) {
                            $('#batchno').append('<option value="' + stocks
                                .id + '">' + stocks.batch_no + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#batchno').empty().append('<option value="">Select Batch No</option>');
            }
            }else{
                if (itemid !== '') {
                $.ajax({
                    url: '{!! route("getReturnItemQualityToIssue", ["itemId" => "id_item", "store_id" => "id_store"]) !!}'
                            .replace('id_item', itemid)
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#usedquality').empty().append(
                            '<option value="">Select Quality</option>');
                        $.each(data, function (index, returnstocks) {
                            $('#usedquality').append('<option value="' + returnstocks
                                .id + '">' + returnstocks.quality_percentage + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#usedquality').empty().append('<option value="">Select Quality</option>');
            }
            }
            
        });


// get qty and price in batch no
$('#batchno').change(function () {
            var batchno = $(this).val();
            if (batchno !== '') {
              
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuegetQtyPriceList") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: batchno
                },
                success: function (data) {
                    var issueQty=data.result.issueQty;
                    $('#rate').val(data.result.unit_price);
                    $('#stockqty').text((data.result.qty)-issueQty);
                    // $('#app_empname').val(data.result.emp_name_with_initial);
                }
            })
            } else {
                $('#rate').val('');
                $('#stockqty').text('');
            }
        });

// get qty and price in Return Item
$('#usedquality').change(function () {
            var usedquality = $(this).val();
            if (usedquality !== '') {
              
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuegetRetrunItemQtyPriceList") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: usedquality
                },
                success: function (data) {
                    var issueQty=data.result.issueQty;
                    $('#rate').val(data.result.unit_price);
                    $('#stockqty').text((data.result.qty)-issueQty);
                    // $('#app_empname').val(data.result.emp_name_with_initial);
                }
            })
            } else {
                $('#rate').val('');
                $('#stockqty').text('');
            }
        });


});


       // get item in store for edit
       function editGetItem (assetvalue1,store_id,item_id) {
        assetvalue=assetvalue1;
        console.log();
        if (assetvalue1 == 'brandnew') {
            if (store_id !== '') {
                $.ajax({
                    url: '{!! route("getitemToIssue", ["store_id" => "id_store"]) !!}'
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, items) {
                            $('#item').append('<option value="' + items
                                .item_id + '">' + items.inventorylist_id + '-' + items.name + ' ' + (items.uniform_size==null?'':items.uniform_size+'"') + '</option>');
                        });
                        $('#item').val(item_id);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#item').empty().append('<option value="">Select Item</option>');
            }
        } else {
            if (store_id !== '') {
                $.ajax({
                    url: '{!! route("getReturnitemToIssue", ["store_id" => "id_store"]) !!}'
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, returnitems) {
                            $('#item').append('<option value="' + returnitems
                                .item_id + '">' + returnitems.inventorylist_id + '-' + returnitems.name + ' ' + (returnitems.uniform_size==null?'':returnitems.uniform_size+'"') + '</option>');
                        });
                        $('#item').val(item_id);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#item').empty().append('<option value="">Select Item</option>');
            }
        }
        
        };

        // filter batch no for edit
        function editGetBatchNo (assetvalue1,store_id,itemid,batchid) {
            assetvalue=assetvalue1;
            if (assetvalue1 == 'brandnew') {
                if (itemid !== '') {
                $.ajax({
                    url: '{!! route("getbatchnoToIssue", ["itemId" => "id_item", "store_id" => "id_store"]) !!}'
                            .replace('id_item', itemid)
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#batchno').empty().append(
                            '<option value="">Select Batch No</option>');
                        $.each(data, function (index, stocks) {
                            $('#batchno').append('<option value="' + stocks
                                .id + '">' + stocks.batch_no + '</option>');
                        });
                        $('#batchno').val(batchid);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#batchno').empty().append('<option value="">Select Batch No</option>');
            }
            }else{
                if (itemid !== '') {
                $.ajax({
                    url: '{!! route("getReturnItemQualityToIssue", ["itemId" => "id_item", "store_id" => "id_store"]) !!}'
                            .replace('id_item', itemid)
                            .replace('id_store', store_id),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#usedquality').empty().append(
                            '<option value="">Select Quality</option>');
                        $.each(data, function (index, returnstocks) {
                            $('#usedquality').append('<option value="' + returnstocks
                                .id + '">' + returnstocks.quality_percentage + '</option>');
                        });
                        $('#usedquality').val(batchid);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#usedquality').empty().append('<option value="">Select Quality</option>');
            }
            }
            
        };

        function editgetBatchnoPriceQty(batchno){
            if (batchno !== '') {
              
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuegetQtyPriceList") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: batchno
                },
                success: function (data) {
                    var issueQty=data.result.issueQty;
                    // $('#rate').val(data.result.unit_price);
                    $('#stockqty').text((data.result.qty)-issueQty);
                    // $('#app_empname').val(data.result.emp_name_with_initial);
                }
            })
            } else {
                // $('#rate').val('');
                $('#stockqty').text('');
            }
        }

        function editgetUsedQualityPriceQty(usedquality){
            if (usedquality !== '') {
              
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("issuegetRetrunItemQtyPriceList") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: usedquality
                },
                success: function (data) {
                    var issueQty=data.result.issueQty;
                    // $('#rate').val(data.result.unit_price);
                    $('#stockqty').text((data.result.qty)-issueQty);
                    // $('#app_empname').val(data.result.emp_name_with_initial);
                }
            })
            } else {
                // $('#rate').val('');
                $('#stockqty').text('');
            }
        }

    </script>

    <script>
    $(document).ready(function () {
        $("#DivBatchNo").show();
        $("#assetvalue").change(function () {
            var assetvalue = $(this).val();
            if(assetvalue=="brandnew"){
                $("#DivBatchNo").show();
                $("#DivUsedQuality").hide();

                $("#batchno").prop("required", true);
                $("#usedquality").prop("required", false);
            }else if(assetvalue=="used"){
                $("#DivBatchNo").hide();
                $("#DivUsedQuality").show();

                $("#batchno").prop("required", false);
                $("#usedquality").prop("required", true);
            }
           

        });
    });

    function editChooseBatch_or_Used(assetvalue){
        if(assetvalue=="brandnew"){
                $("#DivBatchNo").show();
                $("#DivUsedQuality").hide();

                $("#batchno").prop("required", true);
                $("#usedquality").prop("required", false);
            }else if(assetvalue=="used"){
                $("#DivBatchNo").hide();
                $("#DivUsedQuality").show();

                $("#batchno").prop("required", false);
                $("#usedquality").prop("required", true);
            }
           
    }
    </script>

</body>

@endsection