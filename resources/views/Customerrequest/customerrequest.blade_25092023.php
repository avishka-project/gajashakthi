@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-user-tie"></i></div>
                    <span>Authorized Cadre</span>
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

                        <button type="button" class="btn btn-outline-danger btn-sm fa-pull-right" name="special_request"
                            id="special_request" style="margin-left: 10px;"><i class="fas fa-plus mr-2"></i>Special
                            Request</button>
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>New Request</button>
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">

                        <table class="table table-striped table-bordered table-sm small" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Client</th>
                                    <th>Sub Client</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Branch</th>
                                    <th>Request Type</th>
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
    <!-- Modal Area Start -->
    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel"></h5>
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
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
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

                                    <input type="hidden" name="subregion_id" id="subregion_id">

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="fromdate" id="fromdate" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="todate" id="todate" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                {{-- <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark"><i
                                                class="fa fa-exclamation-triangle" aria-hidden="true"
                                                style="color: red;"></i> Please Add at least One month range*</label>
                                    </div>
                                </div> --}}
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Day Type*</label>
                                        <select name="holidaytype" id="holidaytype" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Type</option>
                                            @foreach($holidays as $holiday)
                                            <option value="{{$holiday->id}}">{{$holiday->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Shift*</label>
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
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="title" id="title" class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                            @foreach($titles as $title)
                                            <option value="{{$title->id}}">{{$title->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Count*</label>
                                        <input type="number" id="count" name="count"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" id="formsubmit"
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                </div>
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                <thead>
                                    <tr>
                                        <th>Day Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                        <th class="d-none">HolidayID</th>
                                        <th class="d-none">ShiftID</th>
                                        <th class="d-none">JobTitleID</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 18px">
                                        <td colspan="3">Total:</td>
                                        <td id="totalField" class="text-center">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <br>
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
    <!-- Modal Area Start -->
    <div class="modal fade" id="formModal2" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title2" id="staticBackdropLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result2"></span>
                            <form method="post" id="formTitle2" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="customer2" id="customer2" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="subcustomer2" id="subcustomer2"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="area2" id="area2" class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>

                                        </select>
                                    </div>
                                    <input type="hidden" name="subregion_id2" id="subregion_id2">

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="fromdate2" id="fromdate2" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="todate2" id="todate2" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                {{-- <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark"><i
                                                class="fa fa-exclamation-triangle" aria-hidden="true"
                                                style="color: red;"></i> Please Add at least One month range*</label>
                                    </div>
                                </div> --}}
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Day Type*</label>
                                        <select name="holidaytype2" id="holidaytype2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Type</option>
                                            @foreach($holidays as $holiday)
                                            <option value="{{$holiday->id}}">{{$holiday->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Shift*</label>
                                        <select name="shift2" id="shift2" class="form-control form-control-sm" required>
                                            <option value="">Select Shift</option>
                                            @foreach($shifttypes as $shifttype)
                                            <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="title2" id="title2" class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                            @foreach($titles as $title)
                                            <option value="{{$title->id}}">{{$title->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Count*</label>
                                        <input type="number" id="count2" name="count2"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" id="formsubmit2"
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add
                                        to list</button>
                                    <input name="submitBtn2" type="submit" value="Save" id="submitBtn2" class="d-none">
                                    <button type="button" name="Btnupdatelist" id="Btnupdatelist"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;"><i
                                            class="fas fa-plus"></i>&nbsp;Update List</button>
                                    <input type="hidden" name="requestdeiailsid" class="form-control form-control-sm"
                                        id="requestdeiailsid">
                                </div>
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="tableorder2">
                                <thead>
                                    <tr>
                                        <th>Day Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                        <th class="d-none">HolidayID</th>
                                        <th class="d-none">ShiftID</th>
                                        <th class="d-none">JobTitleID</th>
                                        <th>Action</th>

                                    </tr>
                                </thead>
                                <tbody id="requestdetaillist2"></tbody>
                            </table>
                            <br>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder2" id="btncreateorder2"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Request</button>
                                <input type="hidden" name="hidden_id2" id="hidden_id2">


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

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Client Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result2"></span>
                            <form method="post" id="formTitle3" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="app_customer" id="app_customer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="app_subcustomer" id="app_subcustomer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Sub Client</option>
                                            @foreach($subcustomer as $subcustomers)
                                            <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="app_area" id="app_area" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Branch</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="app_fromdate" id="app_fromdate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="app_todate" id="app_todate" value="<?php echo date('Y-m-d') ?>"
                                            required>
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
                                        <th>Day Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody id="app_requestdetaillist"></tbody>
                            </table>
                            <br>
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

    {{-- view model --}}
    <div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="viewmodal-title" id="staticBackdropLabel">View Request Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result2"></span>
                            <form method="post" id="formTitle3" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="view_customer" id="view_customer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="view_subcustomer" id="view_subcustomer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Sub Client</option>
                                            @foreach($subcustomer as $subcustomers)
                                            <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="view_area" id="view_area" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Branch</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="view_fromdate" id="view_fromdate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="view_todate" id="view_todate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>

                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                                <thead>
                                    <tr>
                                        <th>Day Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                    </tr>
                                </thead>
                                <tbody id="view_requestdetaillist"></tbody>
                            </table>
                            <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- special request model --}}
    <div class="modal fade" id="specialrequestformModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="specialrequestmodal-title" id="staticBackdropLabel">Special Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result"></span>
                            <form method="post" id="special_formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="speacial_customer" id="speacial_customer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="speacial_subcustomer" id="speacial_subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="speacial_area" id="speacial_area"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="speacial_subregion_id" id="speacial_subregion_id">

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_fromdate" id="speacial_fromdate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_todate" id="speacial_todate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                {{-- <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark"><i
                                                class="fa fa-exclamation-triangle" aria-hidden="true"
                                                style="color: red;"></i> Please Add at least One month range*</label>
                                    </div>
                                </div> --}}
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Day Type*</label>
                                        <select name="speacial_holidaytype" id="speacial_holidaytype"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Type</option>
                                            @foreach($specialholidays as $specialholiday)
                                            <option value="{{$specialholiday->id}}">{{$specialholiday->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Shift*</label>
                                        <select name="speacial_shift" id="speacial_shift"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Shift</option>
                                            @foreach($shifttypes as $shifttype)
                                            <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="speacial_title" id="speacial_title"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                            @foreach($titles as $title)
                                            <option value="{{$title->id}}">{{$title->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Count*</label>
                                        <input type="number" id="speacial_count" name="speacial_count"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" id="speacial_formsubmit"
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="speacial_submitBtn" type="submit" value="Save" id="speacial_submitBtn"
                                        class="d-none">
                                </div>
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="speacial_tableorder">
                                <thead>
                                    <tr>
                                        <th>Day Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                        <th class="d-none">HolidayID</th>
                                        <th class="d-none">ShiftID</th>
                                        <th class="d-none">JobTitleID</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 18px">
                                        <td colspan="3">Total:</td>
                                        <td id="speacial_totalField" class="text-center">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <br>

                            <div class="form-group mt-2">
                                <button type="button" name="speacial_btncreateorder" id="speacial_btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Request</button>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editspecialrequestformModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="editspecialrequestmodal-title" id="staticBackdropLabel">Edit Special Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <span id="form_result"></span>
                            <form method="post" id="special_formTitle2" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="speacial_customer2" id="speacial_customer2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="speacial_subcustomer2" id="speacial_subcustomer2"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="speacial_area2" id="speacial_area2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="speacial_subregion_id2" id="speacial_subregion_id2">

                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_fromdate2" id="speacial_fromdate2"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_todate2" id="speacial_todate2"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                {{-- <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark"><i
                                                class="fa fa-exclamation-triangle" aria-hidden="true"
                                                style="color: red;"></i> Please Add at least One month range*</label>
                                    </div>
                                </div> --}}
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Day Type*</label>
                                        <select name="speacial_holidaytype2" id="speacial_holidaytype2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Type</option>
                                            @foreach($specialholidays as $specialholiday)
                                            <option value="{{$specialholiday->id}}">{{$specialholiday->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Shift*</label>
                                        <select name="speacial_shift2" id="speacial_shift2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Shift</option>
                                            @foreach($shifttypes as $shifttype)
                                            <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="speacial_title2" id="speacial_title2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                            @foreach($titles as $title)
                                            <option value="{{$title->id}}">{{$title->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Count*</label>
                                        <input type="number" id="speacial_count2" name="speacial_count2"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" id="speacial_formsubmit2"
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="speacial_submitBtn2" type="submit" value="Save"
                                        id="speacial_submitBtn2" class="d-none">
                                    <button type="button" name="speacial_Btnupdatelist" id="speacial_Btnupdatelist"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;"><i
                                            class="fas fa-plus"></i>&nbsp;Update List</button>
                                    <input type="hidden" name="speacial_requestdeiailsid"
                                        class="form-control form-control-sm" id="speacial_requestdeiailsid">
                                </div>
                            </form>
                        </div>
                        <div class="col-8">
                            <table class="table table-striped table-bordered table-sm small" id="speacial_tableorder2">
                                <thead>
                                    <tr>
                                        <th>Day Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Count</th>
                                        <th class="d-none">HolidayID</th>
                                        <th class="d-none">ShiftID</th>
                                        <th class="d-none">JobTitleID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="speacial_requestdetaillist2"></tbody>
                            </table>
                            <br>

                            <div class="form-group mt-2">
                                <button type="button" name="speacial_btncreateorder2" id="speacial_btncreateorder2"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Request</button>
                                <input type="hidden" name="speacial_hidden_id2" id="speacial_hidden_id2">

                            </div>
                        </div>
                    </div>
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
                            <h4 class="font-weight-normal">Are you sure you want to remove this data?</h4>
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
    <!-- Modal Area End -->
</main>

@endsection


@section('script')

<script>
    $(function () {
        $("#type").change(function () {
            if ($(this).val() == 1) {
                $("#select_date").show();
                $("#select_month").hide();
            } else if ($(this).val() == 2) {
                $("#select_month").show();
                $("#select_date").hide();
            }
        });
    });



    $(document).ready(function () {

        $('#clientmanagement').prop('aria-expanded', 'true').removeClass('collapsed');
        $('#collapscustomer').addClass('show');
        $('#cusreqest_link').addClass('active');


        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route('displaycustomerrequest') !!}",
            },
            columns: [{
                    data: 'id',
                    name: 'customerrequests.id'
                }, // Assuming the customer request ID column is 'id'
                {
                    data: 'customername',
                    name: 'customername'
                }, // Assuming the customer name column is 'name'
                {
                    data: 'sub_name',
                    name: 'sub_name'
                },
                {
                    data: 'fromdate',
                    name: 'fromdate'
                },
                {
                    data: 'todate',
                    name: 'todate'
                },
                {
                    data: 'branch_name',
                    name: 'branch_name'
                }, // Assuming the subregion name column is 'subregion'
                {
                    data: 'requeststatus',
                    name: 'requeststatus',
                    orderable: false,
                    searchable: false,
                    render: function (data, type, row) {
                        if (data == "Special") {
                            return '<div style="color: red;">' + data + '</div>';
                        } else {
                            return data;
                        }

                    }
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
                [2, "desc"]
            ]
        });

        $("#formsubmit").click(function () {
            if (!$("#formTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn").click();
            } else {
                var holidayID = $('#holidaytype').val();
                var titleID = $('#title').val();
                var count = $('#count').val();
                var shiftID = $('#shift').val();
                var holiday = $("#holidaytype option:selected").text();
                var title = $("#title option:selected").text();
                var shift = $("#shift option:selected").text();

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + holiday +
                    '</td>><td>' + shift +
                    '</td><td>' + title +
                    '</td><td class="text-center">' + count + '</td><td class="d-none">' +
                    holidayID +
                    '</td><td class="d-none">' + shiftID + '</td><td class="d-none">' + titleID +
                    '</td></tr>');

                updateTotalcount();

                $('#holidaytype').val('');
                $('#shift').val('');
                $('#title').val('');
                $('#count').val('');
            }
        });
        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();
            }
        });

        $("#formsubmit2").click(function () {

            if (!$("#formTitle2")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#submitBtn2").click();
            } else {

                var holidayID = $('#holidaytype2').val();
                var shiftID = $('#shift2').val();
                var titleID = $('#title2').val();
                var count = $('#count2').val();
                var holiday = $("#holidaytype2 option:selected").text();
                var shift = $("#shift2 option:selected").text();
                var title = $("#title2 option:selected").text();

                $('#tableorder2> tbody:last').append('<tr class="pointer"><td>' + holiday +
                    '</td><td>' + shift + '</td><td>' + title + '</td><td>' +
                    count + '</td><td class="d-none">' + holidayID +
                    '</td><td class="d-none">' + shiftID +
                    '</td><td class="d-none">' + titleID +
                    '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
                );
                $('#holidaytype2').val('');
                $('#shift2').val('');
                $('#title2').val('');
                $('#count2').val('');
            }
        });

        // special rewuest
        $("#speacial_formsubmit").click(function () {
            if (!$("#special_formTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#speacial_submitBtn").click();
            } else {
                var holidayID = $('#speacial_holidaytype').val();
                var titleID = $('#speacial_title').val();
                var count = $('#speacial_count').val();
                var shiftID = $('#speacial_shift').val();
                var holiday = $("#speacial_holidaytype option:selected").text();
                var title = $("#speacial_title option:selected").text();
                var shift = $("#speacial_shift option:selected").text();

                $('#speacial_tableorder > tbody:last').append('<tr class="pointer"><td>' + holiday +
                    '</td>><td>' + shift +
                    '</td><td>' + title +
                    '</td><td class="text-center">' + count + '</td><td class="d-none">' +
                    holidayID +
                    '</td><td class="d-none">' + shiftID + '</td><td class="d-none">' + titleID +
                    '</td></tr>');

                specialupdateTotalcount();

                $('#speacial_holidaytype').val('');
                $('#speacial_shift').val('');
                $('#speacial_title').val('');
                $('#speacial_count').val('');
            }
        });
        $('#speacial_tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this request ? ");
            if (r == true) {
                $(this).closest('tr').remove();
                specialupdateTotalcount();
            }
        });


        $("#speacial_formsubmit2").click(function () {
            if (!$("#special_formTitle2")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#speacial_submitBtn2").click();
            } else {
                var holidayID = $('#speacial_holidaytype2').val();
                var titleID = $('#speacial_title2').val();
                var count = $('#speacial_count2').val();
                var shiftID = $('#speacial_shift2').val();
                var holiday = $("#speacial_holidaytype2 option:selected").text();
                var title = $("#speacial_title2 option:selected").text();
                var shift = $("#speacial_shift2 option:selected").text();

                $('#speacial_tableorder2> tbody:last').append('<tr class="pointer"><td>' + holiday +
                    '</td><td>' + shift + '</td><td>' + title + '</td><td>' +
                    count + '</td><td class="d-none">' + holidayID +
                    '</td><td class="d-none">' + shiftID +
                    '</td><td class="d-none">' + titleID +
                    '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
                );


                $('#speacial_holidaytype2').val('');
                $('#speacial_shift2').val('');
                $('#speacial_title2').val('');
                $('#speacial_count2').val('');
            }
        });



        $('#create_record').click(function () {
            $('.modal-title').text('Create New Request');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');

            $('#formModal').modal('show');
        });

        // special request model popup
        $('#special_request').click(function () {
            $('#specialrequestformModal').modal('show');
        });


        // normal requset insert
        $('#btncreateorder').click(function () {
            var totalField = $('#totalField').text();
            // var staffcount = $('#staffcount').val();

            if (totalField != 0) {
                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

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

                    var customer = $('#customer').val();
                    var subcustomer = $('#subcustomer').val();
                    var area = $('#area').val();
                    var fromdate = $('#fromdate').val();
                    var todate = $('#todate').val();
                    // var shift = $('#shift').val();
                    var subregion_id = $('#subregion_id').val();
                    // var stafflist = $('#stafflist').val();


                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tableData: jsonObj,
                            customer: customer,
                            subcustomer: subcustomer,
                            area: area,
                            fromdate: fromdate,
                            todate: todate,
                            subregion_id: subregion_id,
                            // stafflist: stafflist,

                        },
                        url: "{{ route('insert') }}",
                        success: function (result) {
                            if (result.status == 1) {
                                location.reload();
                                $('#formModal').modal('hide');
                            }
                            action(result.action);
                        }
                    });
                }
            } else {
                alert('Please add request.');
            }
        });


        // special request insert
        $('#speacial_btncreateorder').click(function () {
            var totalField = $('#speacial_totalField').text();

            if (totalField != 0) {
                $('#speacial_btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create special request');

                var tbody = $("#speacial_tableorder tbody");

                if (tbody.children().length > 0) {
                    var jsonObj = [];
                    $("#speacial_tableorder tbody tr").each(function () {
                        var item = {};
                        $(this).find('td').each(function (col_idx) {
                            item["col_" + (col_idx + 1)] = $(this).text();
                        });
                        jsonObj.push(item);
                    });

                    var customer = $('#speacial_customer').val();
                    var subcustomer = $('#speacial_subcustomer').val();
                    var area = $('#speacial_area').val();
                    var fromdate = $('#speacial_fromdate').val();
                    var todate = $('#speacial_todate').val();
                    // var shift = $('#shift').val();
                    var subregion_id = $('#speacial_subregion_id').val();
                    // var stafflist = $('#stafflist').val();


                    $.ajax({
                        method: "POST",
                        dataType: "json",
                        data: {
                            _token: '{{ csrf_token() }}',
                            tableData: jsonObj,
                            customer: customer,
                            subcustomer: subcustomer,
                            area: area,
                            fromdate: fromdate,
                            todate: todate,
                            subregion_id: subregion_id,
                            // stafflist: stafflist,

                        },
                        url: "{{ route('specialrequestinsert') }}",
                        success: function (result) {
                            if (result.status == 1) {
                                location.reload();
                                $('#specialrequestformModal').modal('hide');
                            }
                            action(result.action);
                        }
                    });
                }
            } else {
                alert('Please add request.');
            }
        });

        // request update
        $('#btncreateorder2').click(function () {
            $('#btncreateorder2').prop('disabled', true).html(
                '<i class="fas fa-circle-notch fa-spin mr-2"></i> Update Order');

            var tbody = $("#tableorder2 tbody");

            if (tbody.children().length > 0) {
                var jsonObj = [];
                $("#tableorder2 tbody tr").each(function () {
                    var item = {};
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });

                var customer = $('#customer2').val();
                var subcustomer = $('#subcustomer2').val();
                var area = $('#area2').val();
                var fromdate = $('#fromdate2').val();
                var todate = $('#todate2').val();
                var subregion_id = $('#subregion_id2').val();
                var hidden_id = $('#hidden_id2').val();
                // var stafflist = $('#stafflist2').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        fromdate: fromdate,
                        todate: todate,
                        hidden_id: hidden_id,
                        subregion_id: subregion_id,
                        // stafflist: stafflist,

                    },
                    url: "{{ route('customerrequestupdate') }}",
                    success: function (result) {
                        if (result.status == 1) {
                            location.reload();
                            $('#formModal2').modal('hide');
                        }
                    }
                });
            }
        });


        // Special request update
        $('#speacial_btncreateorder2').click(function () {
            $('#speacial_btncreateorder2').prop('disabled', true).html(
                '<i class="fas fa-circle-notch fa-spin mr-2"></i> Update Special');

            var tbody = $("#speacial_tableorder2 tbody");

            if (tbody.children().length > 0) {
                var jsonObj = [];
                $("#speacial_tableorder2 tbody tr").each(function () {
                    var item = {};
                    $(this).find('td').each(function (col_idx) {
                        item["col_" + (col_idx + 1)] = $(this).text();
                    });
                    jsonObj.push(item);
                });

                var customer = $('#speacial_customer2').val();
                var subcustomer = $('#speacial_subcustomer2').val();
                var area = $('#speacial_area2').val();
                var fromdate = $('#speacial_fromdate2').val();
                var todate = $('#speacial_todate2').val();
                var subregion_id = $('#speacial_subregion_id2').val();
                var hidden_id = $('#speacial_hidden_id2').val();
                // var stafflist = $('#stafflist2').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        fromdate: fromdate,
                        todate: todate,
                        hidden_id: hidden_id,
                        subregion_id: subregion_id,
                        // stafflist: stafflist,

                    },
                    url: "{{ route('customerspecialrequestupdate') }}",
                    success: function (result) {
                        if (result.status == 1) {
                            location.reload();
                            $('#editspecialrequestformModal').modal('hide');
                        }
                    }
                });
            }
        });



        // request edit part
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $('#hidden_id2').val(id);

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customerrequestedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#customer2').val(data.result.mainData.customer_id);
                    // $('#subcustomer2').val(data.result.mainData.subcustomer_id);
                    // $('#area2').val(data.result.mainData.customerbranch_id);
                    getbranchandsubcustomerEdit(data.result.mainData.customer_id, data
                        .result.mainData.subcustomer_id, data.result.mainData
                        .customerbranch_id);
                    $('#fromdate2').val(data.result.mainData.fromdate);
                    $('#todate2').val(data.result.mainData.todate);
                    $('#subregion_id2').val(data.result.mainData.subregion_id);
                    // getstafftoedit(data.result.mainData.subregion_id)
                    $('#requestdetaillist2').html(data.result.requestdata);
                    // $('#staffrequestdetaillist').html(data.result.staffrequestdetaillist);

                    $('#hidden_id2').val(id);
                    $('.modal-title2').text('Edit Client Request');
                    $('#btncreateorder2').html('Update');
                    $('#formModal2').modal('show');


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
                url: '{!! route("requestdetailedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#holidaytype2').val(data.result.holiday_id);
                    $('#shift2').val(data.result.shift_id);
                    $('#title2').val(data.result.job_title_id);
                    $('#count2').val(data.result.count);
                    $('#requestdeiailsid').val(data.result.id);
                    $('#Btnupdatelist').show();
                    $('#formsubmit2').hide();
                }
            })
        });

        // request detail update list

        $(document).on("click", "#Btnupdatelist", function () {
            var holidayID = $('#holidaytype2').val();
            var shiftID = $('#shift2').val();
            var titleID = $('#title2').val();
            var count = $('#count2').val();
            var holiday = $("#holidaytype2 option:selected").text();
            var shift = $("#shift2 option:selected").text();
            var title = $("#title2 option:selected").text();
            var detailid = $('#requestdeiailsid').val();



            $("#tableorder2> tbody").find('input[name="hiddenid"]').each(function () {
                var hiddenid = $(this).val();
                if (hiddenid == detailid) {
                    $(this).parents("tr").remove();
                }
            });

            $('#tableorder2> tbody:last').append('<tr class="pointer"><td>' + holiday + '</td><td>' +
                shift + '</td><td>' + title + '</td><td>' +
                count + '</td><td class="d-none">' + holidayID +
                '</td><td class="d-none">' + shiftID +
                '</td><td class="d-none">' + titleID +
                '</td><td class="d-none">Updated</td><td class="d-none">' +
                detailid +
                '</td><td id ="actionrow"><button type="button" id="' + detailid +
                '" class="btnEditlist btn btn-primary btn-sm "><i class="fas fa-pen"></i></button>&nbsp;<button type="button" id="' +
                detailid +
                '" class="btnDeletelist btn btn-danger btn-sm " ><i class="fas fa-trash-alt"></i></button></td>' +
                '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="' +
                detailid + '"></td></tr>'

            );


            $('#holidaytype2').val('');
            $('#shift2').val('');
            $('#title2').val('');
            $('#count2').val('');
            $('#Btnupdatelist').hide();
            $('#formsubmit2').show();
        });



        //   details delete
        var rowid
        var rowToDelete
        $(document).on('click', '.btnDeletelist', function () {
            rowToDelete = $(this).closest('tr');
            rowid = $(this).attr('id');
            $('#confirmModal2').modal('show');

        });

        $('#ok_button2').click(function () {
            rowToDelete.remove();
            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customerrequestdetaildelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: rowid,
                },
                beforeSend: function () {
                    $('#ok_button2').text('Deleting...');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#confirmModal2').modal('hide');
                    }, 2000);
                }
            })
        });


        // special request edit part
        $(document).on('click', '.specialedit', function () {
            var id = $(this).attr('id');
            $('#speacial_hidden_id2').val(id);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("specialcustomerrequestedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#speacial_customer2').val(data.result.mainData.customer_id);
                    // $('#subcustomer2').val(data.result.mainData.subcustomer_id);
                    // $('#area2').val(data.result.mainData.customerbranch_id);
                    getbranchandsubcustomerspecialEdit(data.result.mainData.customer_id,
                        data
                        .result.mainData.subcustomer_id, data.result.mainData
                        .customerbranch_id);
                    $('#speacial_fromdate2').val(data.result.mainData.fromdate);
                    $('#speacial_todate2').val(data.result.mainData.todate);
                    $('#speacial_subregion_id2').val(data.result.mainData.subregion_id);
                    // getstafftoedit(data.result.mainData.subregion_id)
                    $('#speacial_requestdetaillist2').html(data.result.requestdata);
                    // $('#staffrequestdetaillist').html(data.result.staffrequestdetaillist);

                    $('#speacial_hidden_id2').val(id);
                    $('#speacial_btncreateorder2').html('Update');
                    $('#editspecialrequestformModal').modal('show');


                }
            })


        });

        // spetal request detail edit
        $(document).on('click', '.btnSpecialEditlist', function () {
            var id = $(this).attr('id');
            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("requestdetailedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#speacial_holidaytype2').val(data.result.holiday_id);
                    $('#speacial_shift2').val(data.result.shift_id);
                    $('#speacial_title2').val(data.result.job_title_id);
                    $('#speacial_count2').val(data.result.count);
                    $('#speacial_requestdeiailsid').val(data.result.id);
                    $('#speacial_Btnupdatelist').show();
                    $('#speacial_formsubmit2').hide();
                }
            })
        });

        // spetial request detail update list

        $(document).on("click", "#speacial_Btnupdatelist", function () {
            var holidayID = $('#speacial_holidaytype2').val();
            var shiftID = $('#speacial_shift2').val();
            var titleID = $('#speacial_title2').val();
            var count = $('#speacial_count2').val();
            var holiday = $("#speacial_holidaytype2 option:selected").text();
            var shift = $("#speacial_shift2 option:selected").text();
            var title = $("#speacial_title2 option:selected").text();
            var detailid = $('#speacial_requestdeiailsid').val();



            $("#speacial_tableorder2> tbody").find('input[name="hiddenid"]').each(function () {
                var hiddenid = $(this).val();
                if (hiddenid == detailid) {
                    $(this).parents("tr").remove();
                }
            });

            $('#speacial_tableorder2> tbody:last').append('<tr class="pointer"><td>' + holiday +
                '</td><td>' +
                shift + '</td><td>' + title + '</td><td>' +
                count + '</td><td class="d-none">' + holidayID +
                '</td><td class="d-none">' + shiftID +
                '</td><td class="d-none">' + titleID +
                '</td><td class="d-none">Updated</td><td class="d-none">' +
                detailid +
                '</td><td id ="actionrow"><button type="button" id="' + detailid +
                '" class="btnSpecialEditlist btn btn-primary btn-sm "><i class="fas fa-pen"></i></button>&nbsp;<button type="button" id="' +
                detailid +
                '" class="btnSpecialDeletelist btn btn-danger btn-sm " ><i class="fas fa-trash-alt"></i></button></td>' +
                '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="' +
                detailid + '"></td></tr>'

            );


            $('#speacial_holidaytype2').val('');
            $('#speacial_shift2').val('');
            $('#speacial_title2').val('');
            $('#speacial_count2').val('');
            $('#speacial_Btnupdatelist').hide();
            $('#speacial_formsubmit2').show();
        });



        //  spetial details delete
        var rowid
        var rowToDelete
        $(document).on('click', '.btnSpecialDeletelist', function () {
            rowToDelete = $(this).closest('tr');
            rowid = $(this).attr('id');
            $('#confirmModal3').modal('show');

        });

        $('#ok_button3').click(function () {
            rowToDelete.remove();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customerrequestdetaildelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: rowid,
                },
                beforeSend: function () {
                    $('#ok_button3').text('Deleting...');
                },
                success: function (data) {
                    setTimeout(function () {
                        $('#confirmModal3').modal('hide');
                    }, 2000);
                    location.reload()
                }
            })
        });


        // Customer Delete 
        var customerrequest_id;
        $(document).on('click', '.delete', function () {
            customerrequest_id = $(this).attr('id');
            $('#confirmModal').modal('show');

        });

        $('#ok_button').click(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("delete") !!}',
                type: 'POST',
                data: {
                    id: customerrequest_id
                },
                success: function (res) {
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        // s('Data Deleted');
                    }, 2000);
                    location.reload()
                },
                error: function (res) {
                    // alert(data);
                }
            });
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
                url: '{!! route("customerrequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_customer').val(data.result.mainData.customer_id);
                    $('#app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#app_area').val(data.result.mainData.customerbranch_id);
                    $('#app_fromdate').val(data.result.mainData.fromdate);
                    $('#app_todate').val(data.result.mainData.todate);
                    $('#app_shift').val(data.result.mainData.shift_id);
                    $('#app_requestdetaillist').html(data.result.requestdata);
                    // $('#app_staffrequestdetaillist').html(data.result.staffrequestdetaillist);

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
                url: '{!! route("customerrequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_customer').val(data.result.mainData.customer_id);
                    $('#app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#app_area').val(data.result.mainData.customerbranch_id);
                    $('#app_fromdate').val(data.result.mainData.fromdate);
                    $('#app_todate').val(data.result.mainData.todate);
                    $('#app_shift').val(data.result.mainData.shift_id);
                    $('#app_requestdetaillist').html(data.result.requestdata);
                    // $('#app_staffrequestdetaillist').html(data.result.staffrequestdetaillist);

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
                url: '{!! route("customerrequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_customer').val(data.result.mainData.customer_id);
                    $('#app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#app_area').val(data.result.mainData.customerbranch_id);
                    $('#app_fromdate').val(data.result.mainData.fromdate);
                    $('#app_todate').val(data.result.mainData.todate);
                    $('#app_shift').val(data.result.mainData.shift_id);
                    $('#app_requestdetaillist').html(data.result.requestdata);
                    // $('#app_staffrequestdetaillist').html(data.result.staffrequestdetaillist);

                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })


        });


        // View request
        $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customerrequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#view_customer').val(data.result.mainData.customer_id);
                    $('#view_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#view_area').val(data.result.mainData.customerbranch_id);
                    $('#view_fromdate').val(data.result.mainData.fromdate);
                    $('#view_todate').val(data.result.mainData.todate);
                    $('#view_shift').val(data.result.mainData.shift_id);
                    $('#view_requestdetaillist').html(data.result.requestdata);
                    // $('#app_staffrequestdetaillist').html(data.result.staffrequestdetaillist);

                    $('#viewModal').modal('show');

                }
            })


        });

// print pdf
$(document).on('click', '.viewDocument', function () {
            id_approve = $(this).attr('id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{!! route("customerrequestdocument") !!}',
        type: 'POST',
        dataType: "json", // Set the response type to 'text' as you want to display the PDF in a new tab
        data: {
            id: id_approve,
        },
        success: function (data) {
                    if (data.success) {
                        // Open the PDF URL in a new tab
                        window.open(data.url, '_blank');
                    } else {
                        console.log('Error opening the PDF.');
                    }
                },
                error: function () {
                    console.log('AJAX request failed.');
                }
    });
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
                url: '{!! route("customerrequestapprove") !!}',
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
                    }, 500);
                    location.reload()
                }
            })
        });
        
    });

    function productDelete(ctl) {
        $(ctl).parents("tr").remove();
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

        // edit part insert filtering
        $('#customer2').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                        .replace('id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#subcustomer2').empty().append(
                            '<option value="">Select Sub Customer</option>');
                        $.each(data, function (index, subCustomer) {
                            $('#subcustomer2').append('<option value="' +
                                subCustomer
                                .id + '">' + subCustomer.sub_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#subcustomer2').empty().append('<option value="">Select Sub Customer</option>');
            }

            //Get branch
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                        'id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area2').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area2').append('<option value="' + branch.id +
                                '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#area2').empty().append('<option value="">Select Branch</option>');
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

        // get subcustomer filtering about branch
        $('#area').change(function () {
            var branch = $('#area').val();
            var subcustomerId = $('#subcustomer').val();
            $.ajax({
                url: '{!! route("getsubcustomerbranchfilter", ["areaId" => "id_branch"]) !!}'
                    .replace('id_branch', branch),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var subcustomer = data.subcustomer_id;
                    $('#subcustomer').val(subcustomer);
                    // getstaff(subregionId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $('#subcustomer').empty().append(
                        '<option value="">Select Sub Customer</option>');
                }
            });
        });


        // edit part filering
        //Get branch filtering about subcustomer
        $('#subcustomer2').change(function () {
            var customerId = $('#customer2').val();
            var subcustomerId = $('#subcustomer2').val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                        .replace('id_subcustomer', subcustomerId).replace('id_customer',
                            customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#area2').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area2').append('<option value="' + branch.id +
                                '">' +
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
                        $('#area2').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#area2').append('<option value="' + branch.id +
                                '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });
        // get subcustomer filtering about branch
        $('#area2').change(function () {
            var branch = $('#area2').val();
            var subcustomerId = $('#subcustomer2').val();
            $.ajax({
                url: '{!! route("getsubcustomerbranchfilter", ["areaId" => "id_branch"]) !!}'
                    .replace('id_branch', branch),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var subcustomer = data.subcustomer_id;
                    $('#subcustomer2').val(subcustomer);
                    // getstaff(subregionId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $('#subcustomer2').empty().append(
                        '<option value="">Select Sub Customer</option>');
                }
            });
        });

        $('#area').change(function () {
            //Get subregion_id
            var areaId = $(this).val();
            if (areaId !== '') {
                $.ajax({
                    url: '{!! route("getsubregion_id", ["areaId" => "id_area"]) !!}'.replace(
                        'id_area', areaId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var subregionId = data.subregion_id;
                        $('#subregion_id').val(subregionId);
                        // getstaff(subregionId);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                // Handle the case when no area is selected
            }

        });


        //Get subregion_id on edit mode
        $('#area2').change(function () {
            var areaId = $(this).val();
            if (areaId !== '') {
                $.ajax({
                    url: '{!! route("getsubregion_id", ["areaId" => "id_area"]) !!}'.replace(
                        'id_area', areaId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var subregionId = data.subregion_id;
                        $('#subregion_id2').val(subregionId);
                        // getstafftoedit(subregionId)
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                // Handle the case when no area is selected
            }
        });

    });


    //Get Sub Customer on edit mode
    function getbranchandsubcustomerEdit(customerId, subCustomerId, branchId) {
        if (customerId !== '') {
            $.ajax({
                url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                    .replace('id_customer', customerId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#subcustomer2').empty().append(
                        '<option value="">Select Sub Customer</option>');
                    $.each(data, function (index, subCustomer) {
                        $('#subcustomer2').append('<option value="' + subCustomer
                            .id + '">' + subCustomer.sub_name + '</option>');
                    });
                    $('#subcustomer2').val(subCustomerId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#subcustomer2').empty().append('<option value="">Select Sub Customer</option>');
        }

        //Get branch on edit mode
        if (customerId !== '') {
            $.ajax({
                url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                    'id_customer', customerId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#area2').empty().append(
                        '<option value="">Select Branch</option>');
                    $.each(data, function (index, branch) {
                        $('#area2').append('<option value="' + branch.id + '">' +
                            branch.branch_name + '</option>');
                    });
                    $('#area2').val(branchId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#area2').empty().append('<option value="">Select Branch</option>');
        }

        //Get branch filtering about subcustomer
        if (subCustomerId !== null) {
            $.ajax({
                url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                    .replace('id_subcustomer', subCustomerId).replace('id_customer', customerId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#area2').empty().append(
                        '<option value="">Select Branch</option>');
                    $.each(data, function (index, branch) {
                        $('#area2').append('<option value="' + branch.id + '">' +
                            branch.branch_name + '</option>');
                    });
                    $('#area2').val(branchId);
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
                    $('#area2').empty().append(
                        '<option value="">Select Branch</option>');
                    $.each(data, function (index, branch) {
                        $('#area2').append('<option value="' + branch.id + '">' +
                            branch.branch_name + '</option>');
                    });
                    $('#area2').val(branchId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
    };



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
{{-- special request --}}
<script>
    $(document).ready(function () {
        //Get Sub Customer
        $('#speacial_customer').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                        .replace('id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#speacial_subcustomer').empty().append(
                            '<option value="">Select Sub Customer</option>');
                        $.each(data, function (index, subCustomer) {
                            $('#speacial_subcustomer').append('<option value="' +
                                subCustomer
                                .id + '">' + subCustomer.sub_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#speacial_subcustomer').empty().append(
                    '<option value="">Select Sub Customer</option>');
            }

            //Get branch
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                        'id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#speacial_area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#speacial_area').append('<option value="' + branch
                                .id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#speacial_area').empty().append('<option value="">Select Branch</option>');
            }
        });

        // get subcustomer filtering about branch
        $('#speacial_area').change(function () {
            var branch = $('#speacial_area').val();
            var subcustomerId = $('#speacial_subcustomer').val();
            $.ajax({
                url: '{!! route("getsubcustomerbranchfilter", ["areaId" => "id_branch"]) !!}'
                    .replace('id_branch', branch),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var subcustomer = data.subcustomer_id;
                    $('#speacial_subcustomer').val(subcustomer);
                    // getstaff(subregionId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $('#speacial_subcustomer').empty().append(
                        '<option value="">Select Sub Customer</option>');
                }
            });
        });


        // edit partget subcustomer filtering about branch
        $('#speacial_area2').change(function () {
            var branch = $('#speacial_area2').val();
            var subcustomerId = $('#speacial_subcustomer2').val();
            $.ajax({
                url: '{!! route("getsubcustomerbranchfilter", ["areaId" => "id_branch"]) !!}'
                    .replace('id_branch', branch),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var subcustomer = data.subcustomer_id;
                    $('#speacial_subcustomer2').val(subcustomer);
                    // getstaff(subregionId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    $('#speacial_subcustomer2').empty().append(
                        '<option value="">Select Sub Customer</option>');
                }
            });
        });

        // edit part insert filtering
        $('#speacial_customer2').change(function () {
            var customerId = $(this).val();
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                        .replace('id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#speacial_subcustomer2').empty().append(
                            '<option value="">Select Sub Customer</option>');
                        $.each(data, function (index, subCustomer) {
                            $('#speacial_subcustomer2').append('<option value="' +
                                subCustomer
                                .id + '">' + subCustomer.sub_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#speacial_subcustomer2').empty().append(
                    '<option value="">Select Sub Customer</option>');
            }

            //Get branch
            if (customerId !== '') {
                $.ajax({
                    url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                        'id_customer', customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#speacial_area2').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#speacial_area2').append('<option value="' + branch
                                .id +
                                '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#speacial_area2').empty().append('<option value="">Select Branch</option>');
            }
        });


        //Get branch filtering about subcustomer
        $('#speacial_subcustomer').change(function () {
            var customerId = $('#speacial_customer').val();
            var subcustomerId = $('#speacial_subcustomer').val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                        .replace('id_subcustomer', subcustomerId).replace('id_customer',
                            customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#speacial_area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#speacial_area').append('<option value="' + branch
                                .id + '">' +
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
                        $('#speacial_area').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#speacial_area').append('<option value="' + branch
                                .id + '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });

        // edit part filering
        //Get branch filtering about subcustomer
        $('#speacial_subcustomer2').change(function () {
            var customerId = $('#speacial_customer2').val();
            var subcustomerId = $('#speacial_subcustomer2').val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                        .replace('id_subcustomer', subcustomerId).replace('id_customer',
                            customerId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#speacial_area2').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#speacial_area2').append('<option value="' + branch
                                .id +
                                '">' +
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
                        $('#speacial_area2').empty().append(
                            '<option value="">Select Branch</option>');
                        $.each(data, function (index, branch) {
                            $('#speacial_area2').append('<option value="' + branch
                                .id +
                                '">' +
                                branch.branch_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
        });


        $('#speacial_area').change(function () {
            //Get subregion_id
            var areaId = $(this).val();
            if (areaId !== '') {
                $.ajax({
                    url: '{!! route("getsubregion_id", ["areaId" => "id_area"]) !!}'.replace(
                        'id_area', areaId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var subregionId = data.subregion_id;
                        $('#speacial_subregion_id').val(subregionId);
                        // getstaff(subregionId);
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                // Handle the case when no area is selected
            }

        });


        //Get subregion_id on edit mode
        $('#speacial_area2').change(function () {
            var areaId = $(this).val();
            if (areaId !== '') {
                $.ajax({
                    url: '{!! route("getsubregion_id", ["areaId" => "id_area"]) !!}'.replace(
                        'id_area', areaId),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var subregionId = data.subregion_id;
                        $('#speacial_subregion_id2').val(subregionId);
                        // getstafftoedit(subregionId)
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                // Handle the case when no area is selected
            }
        });

    });


    //Get Sub Customer on edit mode
    function getbranchandsubcustomerspecialEdit(customerId, subCustomerId, branchId) {
        if (customerId !== '') {
            $.ajax({
                url: '{!! route("getsubcustomers", ["customerId" => "id_customer"]) !!}'
                    .replace('id_customer', customerId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#speacial_subcustomer2').empty().append(
                        '<option value="">Select Sub Customer</option>');
                    $.each(data, function (index, subCustomer) {
                        $('#speacial_subcustomer2').append('<option value="' + subCustomer
                            .id + '">' + subCustomer.sub_name + '</option>');
                    });
                    $('#speacial_subcustomer2').val(subCustomerId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#speacial_subcustomer2').empty().append('<option value="">Select Sub Customer</option>');
        }

        //Get branch on edit mode
        if (customerId !== '') {
            $.ajax({
                url: '{!! route("getbranch", ["customerId" => "id_customer"]) !!}'.replace(
                    'id_customer', customerId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#speacial_area2').empty().append(
                        '<option value="">Select Branch</option>');
                    $.each(data, function (index, branch) {
                        $('#speacial_area2').append('<option value="' + branch.id + '">' +
                            branch.branch_name + '</option>');
                    });
                    $('#speacial_area2').val(branchId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#speacial_area2').empty().append('<option value="">Select Branch</option>');
        }

        //Get branch filtering about subcustomer
        if (subCustomerId !== null) {
            $.ajax({
                url: '{!! route("getbranchsubcustomerfilter", ["subcustomerId" => "id_subcustomer", "customerId" => "id_customer"]) !!}'
                    .replace('id_subcustomer', subCustomerId).replace('id_customer', customerId),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#speacial_area2').empty().append(
                        '<option value="">Select Branch</option>');
                    $.each(data, function (index, branch) {
                        $('#speacial_area2').append('<option value="' + branch.id + '">' +
                            branch.branch_name + '</option>');
                    });
                    $('#speacial_area2').val(branchId);
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
                    $('#speacial_area2').empty().append(
                        '<option value="">Select Branch</option>');
                    $.each(data, function (index, branch) {
                        $('#speacial_area2').append('<option value="' + branch.id + '">' +
                            branch.branch_name + '</option>');
                    });
                    $('#speacial_area2').val(branchId);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        }
    };




    // Calculate and update the total sum in the table footer
    function specialupdateTotalcount() {
        var totalSum = 0;

        $('#speacial_tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#speacial_totalField').text(totalSum);
    }
</script>
<script>
        // Reset form inputs and select options when the modal is closed Request form
        $('#formModal2').on('hidden.bs.modal', function () {
            // Reset the form
            $('#formTitle2')[0].reset();
            
            // Reset the select options
            $('#customer2, #subcustomer2, #area2, #holidaytype2, #shift2, #title2').val('').trigger('change');
            $('#subregion_id2').val('');
            
            // Clear the result message
            $('#form_result2').html('');
        });

        // Special request form reset
        $('#editspecialrequestformModal').on('hidden.bs.modal', function () {
            // Reset the form
            $('#special_formTitle2')[0].reset();
            
            // Reset the select options
            $('#speacial_customer2, #speacial_subcustomer2, #speacial_area2, #speacial_holidaytype2, #speacial_shift2, #speacial_title2').val('').trigger('change');
            $('#speacial_subregion_id2').val('');
        });
</script>

<script>
    // Disable the select element when the document is ready
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('app_customer').disabled = true;
        document.getElementById('app_subcustomer').disabled = true;
        document.getElementById('app_area').disabled = true;
        document.getElementById('app_fromdate').disabled = true;
        document.getElementById('app_todate').disabled = true;
        document.getElementById('app_shift').disabled = true;
    });
</script>


@endsection