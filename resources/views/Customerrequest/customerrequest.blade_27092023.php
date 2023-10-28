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
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" id="dataTable">
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
    </div>

    {{-- normal request model --}}
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
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="customer" id="customer" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="subcustomer" id="subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="area" id="area" class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="subregion_id" id="subregion_id">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="fromdate" id="fromdate" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="todate" id="todate" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small" id="tableorder">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($holidays as $holiday)
                                            <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                {{$holiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($holidays as $holiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($titles as $title)
                                        <tr>
                                            <th id="{{$title->id}}">
                                                {{$title->title}}
                                            </th>
                                            @foreach($holidays as $holiday)
                                            <td class="text-center">
                                                <input type="number" class="text-center holiday-input"
                                                    jobtitle_id="{{$title->id}}" shift_id="2"
                                                    holiday_id="{{$holiday->id}}" data-holiday="{{$holiday->name}}"
                                                    data-time="day"
                                                    style="width: 55px;border: none; background-color: transparent"
                                                    id="{{$title->title}}{{$holiday->name}}_day"
                                                    name="{{$title->title}}{{$holiday->name}}_day" value="">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" class="text-center holiday-input"
                                                    jobtitle_id="{{$title->id}}" shift_id="3"
                                                    holiday_id="{{$holiday->id}}" data-holiday="{{$holiday->name}}"
                                                    data-time="night"
                                                    style="width: 55px;border: none; background-color: transparent"
                                                    id="{{$title->title}}{{$holiday->name}}_night"
                                                    name="{{$title->title}}{{$holiday->name}}_night" value="">
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($holidays as $holiday)
                                            <th id="{{$holiday->name}}_daytotal" class="text-center requesttotal">0</th>
                                            <th id="{{$holiday->name}}_nighttotal" class="text-center requesttotal">0
                                            </th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">

                                <div class="form-group mt-2">
                                    <button onclick="assignInputFieldValues()" type="button" name="btncreateorder"
                                        id="btncreateorder" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Create Request</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- edit normal request model --}}
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
                        <div class="col-12">
                            <span id="form_result2"></span>
                            <form method="post" id="formTitle2" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="customer2" id="customer2" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="subcustomer2" id="subcustomer2"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="area2" id="area2" class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="subregion_id2" id="subregion_id2">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="fromdate2" id="fromdate2" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="todate2" id="todate2" value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small" id="tableorder2">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($holidays as $holiday)
                                            <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                {{$holiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($holidays as $holiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="requestdetaillist2">

                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($holidays as $holiday)
                                            <th id="{{$holiday->name}}_daytotal2" class="text-center requesttotal2">0
                                            </th>
                                            <th id="{{$holiday->name}}_nighttotal2" class="text-center requesttotal2">0
                                            </th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <input name="submitBtn2" type="submit" value="Save" id="submitBtn2" class="d-none">
                                <div class="form-group mt-2">
                                    <button onclick="assignInputFieldValues2()" type="button" name="btncreateorder2"
                                        id="btncreateorder2"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Update Request</button>
                                    <input type="hidden" name="hidden_id2" id="hidden_id2">

                                </div>
                            </form>
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

    {{-- normal request approvel model --}}
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
                        <div class="col-12">
                            <span id="form_result2"></span>
                            <form method="post" id="formTitle3" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="app_customer" id="app_customer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="app_subcustomer" id="app_subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                            @foreach($subcustomer as $subcustomers)
                                            <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="app_area" id="app_area" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Branch</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="app_subregion_id" id="app_subregion_id">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="app_fromdate" id="app_fromdate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="app_todate" id="app_todate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small" id="app_tableorder">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($holidays as $holiday)
                                            <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                {{$holiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($holidays as $holiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="app_requestdetaillist">

                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($holidays as $holiday)
                                            <th id="app_{{$holiday->name}}_daytotal"
                                                class="text-center approvelrequesttotal">0</th>
                                            <th id="app_{{$holiday->name}}_nighttotal"
                                                class="text-center approvelrequesttotal">0</th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <div class="modal-footer p-2">
                                    <button type="button" name="approve_button" id="approve_button"
                                        class="btn btn-warning px-3 btn-sm">Approve</button>
                                    <button type="button" class="btn btn-dark px-3 btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="app_level" id="app_level" value="1" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- normal request view model --}}
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
                        <div class="col-12">
                            <span id="form_result2"></span>
                            <form method="post" id="formTitle3" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="view_customer" id="view_customer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="view_subcustomer" id="view_subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                            @foreach($subcustomer as $subcustomers)
                                            <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="view_area" id="view_area" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Branch</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="view_subregion_id" id="view_subregion_id">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="view_fromdate" id="view_fromdate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="view_todate" id="view_todate" value="<?php echo date('Y-m-d') ?>"
                                            required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($holidays as $holiday)
                                            <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                {{$holiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($holidays as $holiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="view_requestdetaillist">

                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($holidays as $holiday)
                                            <th id="view_{{$holiday->name}}_daytotal"
                                                class="text-center viewrequesttotal">0</th>
                                            <th id="view_{{$holiday->name}}_nighttotal"
                                                class="text-center viewrequesttotal">0</th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                            </form>
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
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form method="post" id="special_formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="speacial_customer" id="speacial_customer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="speacial_subcustomer" id="speacial_subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="speacial_area" id="speacial_area"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="speacial_subregion_id" id="speacial_subregion_id">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_fromdate" id="speacial_fromdate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_todate" id="speacial_todate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small"
                                    id="speacial_tableorder">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($specialholidays as $specialholiday)
                                            <th colspan="2" id="speacial_{{$specialholiday->id}}" class="text-center">
                                                {{$specialholiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($specialholidays as $specialholiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($titles as $title)
                                        <tr>
                                            <th id="speacial_{{$title->id}}">
                                                {{$title->title}}
                                            </th>
                                            @foreach($specialholidays as $specialholiday)
                                            <td class="text-center">
                                                <input type="number" class="text-center speacial_holiday-input"
                                                    speacial_jobtitle_id="{{$title->id}}" speacial_shift_id="2"
                                                    speacial_holiday_id="{{$specialholiday->id}}"
                                                    speacial_data-holiday="{{$specialholiday->name}}"
                                                    speacial_data-time="day"
                                                    style="width: 150px;border: none; background-color: transparent; margin-right:-72px;margin-left: -72px"
                                                    id="speacial_{{$title->title}}{{$specialholiday->name}}_day"
                                                    name="speacial_{{$title->title}}{{$specialholiday->name}}_day"
                                                    value="">
                                            </td>
                                            <td class="text-center">
                                                <input type="number" class="text-center speacial_holiday-input"
                                                    speacial_jobtitle_id="{{$title->id}}" speacial_shift_id="3"
                                                    speacial_holiday_id="{{$specialholiday->id}}"
                                                    speacial_data-holiday="{{$specialholiday->name}}"
                                                    speacial_data-time="night"
                                                    style="width: 150px;border: none; background-color: transparent; margin-right:-72px;margin-left: -72px"
                                                    id="speacial_{{$title->title}}{{$specialholiday->name}}_night"
                                                    name="speacial_{{$title->title}}{{$specialholiday->name}}_night"
                                                    value="">
                                            </td>
                                            @endforeach
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($specialholidays as $specialholiday)
                                            <th id="speacial_{{$specialholiday->name}}_daytotal"
                                                class="text-center speacial_total">0</th>
                                            <th id="speacial_{{$specialholiday->name}}_nighttotal"
                                                class="text-center speacial_total">0</th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <input name="speacial_submitBtn" type="submit" value="Save" id="speacial_submitBtn"
                                    class="d-none">

                                <div class="form-group mt-2">
                                    <button onclick="speacial_assignInputFieldValues()" type="button"
                                        name="speacial_btncreateorder" id="speacial_btncreateorder"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Create Request</button>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- edit special model --}}
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
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form method="post" id="special_formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="speacial_customer2" id="speacial_customer2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="speacial_subcustomer2" id="speacial_subcustomer2"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="speacial_area2" id="speacial_area2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                        </select>
                                    </div>
                                    <input type="hidden" name="speacial_subregion_id2" id="speacial_subregion_id2">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_fromdate2" id="speacial_fromdate2"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="speacial_todate2" id="speacial_todate2"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small"
                                    id="speacial_tableorder2">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($specialholidays as $specialholiday)
                                            <th colspan="2" id="speacial_{{$specialholiday->id}}2" class="text-center">
                                                {{$specialholiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($specialholidays as $specialholiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="speacial_requestdetaillist2">

                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($specialholidays as $specialholiday)
                                            <th id="speacial_{{$specialholiday->name}}_daytotal2"
                                                class="text-center speacial_total2">0</th>
                                            <th id="speacial_{{$specialholiday->name}}_nighttotal2"
                                                class="text-center speacial_total2">0</th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <input name="speacial_submitBtn2" type="submit" value="Save" id="speacial_submitBtn2"
                                    class="d-none">

                                <div class="form-group mt-2">
                                    <button onclick="speacial_assignInputFieldValues2()" type="button"
                                        name="speacial_btncreateorder2" id="speacial_btncreateorder2"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Create Request</button>
                                </div>
                                <input type="hidden" name="speacial_hidden_id2" id="speacial_hidden_id2">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- special request approvel model --}}
    <div class="modal fade" id="special_approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="special_modal-title" id="staticBackdropLabel">Approve Client Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <span id="form_result2"></span>
                            <form method="post" id="special_formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Main Client*</label>
                                        <select name="special_app_customer" id="special_app_customer"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Client</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="special_app_subcustomer" id="special_app_subcustomer"
                                            class="form-control form-control-sm">
                                            <option value="">Select Sub Client</option>
                                            @foreach($subcustomer as $subcustomers)
                                            <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="special_app_area" id="special_app_area"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <input type="hidden" name="special_app_subregion_id" id="special_app_subregion_id">
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">From Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="special_app_fromdate" id="special_app_fromdate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                    <div class="col-4">
                                        <label class="small font-weight-bold text-dark">To Date*</label>
                                        <input type="date" class="form-control form-control-sm" placeholder=""
                                            name="special_app_todate" id="special_app_todate"
                                            value="<?php echo date('Y-m-d') ?>" required>
                                    </div>
                                </div>
                                <hr>
                                <table class="table table-striped table-bordered table-sm small"
                                    id="special_app_tableorder">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            @foreach($specialholidays as $holiday)
                                            <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                {{$holiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            @foreach($specialholidays as $holiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="special_app_requestdetaillist">

                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                            <td>Total:</td>
                                            @foreach($specialholidays as $holiday)
                                            <th id="special_app_{{$holiday->name}}_daytotal"
                                                class="text-center special_approvelrequesttotal">0</th>
                                            <th id="special_app_{{$holiday->name}}_nighttotal"
                                                class="text-center special_approvelrequesttotal">0</th>
                                            @endforeach
                                        </tr>
                                    </tfoot>
                                </table>
                                <br>
                                <div class="modal-footer p-2">
                                    <button type="button" name="special_approve_button" id="special_approve_button"
                                        class="btn btn-warning px-3 btn-sm">Approve</button>
                                    <button type="button" class="btn btn-dark px-3 btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                </div>
                                <input type="hidden" name="special_hidden_id" id="special_hidden_id" />
                                <input type="hidden" name="special_app_level" id="special_app_level" value="1" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

     {{-- special request view model --}}
    <div class="modal fade" id="special_viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
     <div class="modal-dialog modal-dialog-centered modal-xl">
         <div class="modal-content">
             <div class="modal-header p-2">
                 <h5 class="special_viewmodal-title" id="staticBackdropLabel">View Request Details</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="row">
                     <div class="col-12">
                         <span id="form_result2"></span>
                         <form method="post" id="special_formTitle3" class="form-horizontal">
                             {{ csrf_field() }}
                             <div class="form-row mb-1">
                                 <div class="col-4">
                                     <label class="small font-weight-bold text-dark">Main Client*</label>
                                     <select name="special_view_customer" id="special_view_customer"
                                         class="form-control form-control-sm" required>
                                         <option value="">Select Client</option>
                                         @foreach($customers as $customer)
                                         <option value="{{$customer->id}}">{{$customer->name}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="col-4">
                                     <label class="small font-weight-bold text-dark">Sub Client*</label>
                                     <select name="special_view_subcustomer" id="special_view_subcustomer"
                                         class="form-control form-control-sm">
                                         <option value="">Select Sub Client</option>
                                         @foreach($subcustomer as $subcustomers)
                                         <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="col-4">
                                     <label class="small font-weight-bold text-dark">Branch*</label>
                                     <select name="special_view_area" id="special_view_area" class="form-control form-control-sm"
                                         required>
                                         <option value="">Select Branch</option>
                                         @foreach($areas as $area)
                                         <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <input type="hidden" name="special_view_subregion_id" id="special_view_subregion_id">
                             </div>
                             <div class="form-row mb-1">
                                 <div class="col-4">
                                     <label class="small font-weight-bold text-dark">From Date*</label>
                                     <input type="date" class="form-control form-control-sm" placeholder=""
                                         name="special_view_fromdate" id="special_view_fromdate" value="<?php echo date('Y-m-d') ?>"
                                         required>
                                 </div>
                                 <div class="col-4">
                                     <label class="small font-weight-bold text-dark">To Date*</label>
                                     <input type="date" class="form-control form-control-sm" placeholder=""
                                         name="special_view_todate" id="special_view_todate" value="<?php echo date('Y-m-d') ?>"
                                         required>
                                 </div>
                             </div>
                             <hr>
                             <table class="table table-striped table-bordered table-sm small" id="special_view_tableorder">
                                 <thead>
                                     <tr>
                                         <th></th>
                                         @foreach($specialholidays as $holiday)
                                         <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                             {{$holiday->name}}
                                         </th>
                                         @endforeach
                                     </tr>
                                     <tr>
                                         <th>Rank Assignment</th>
                                         @foreach($specialholidays as $holiday)
                                         <th class="text-center">D</th>
                                         <th class="text-center">N</th>
                                         @endforeach
                                     </tr>
                                 </thead>
                                 <tbody id="special_view_requestdetaillist">

                                 </tbody>
                                 <tfoot>
                                     <tr style="font-weight: bold;font-size: 16px; background-color:#ffffbb">
                                         <td>Total:</td>
                                         @foreach($specialholidays as $holiday)
                                         <th id="special_view_{{$holiday->name}}_daytotal"
                                             class="text-center special_viewrequesttotal">0</th>
                                         <th id="special_view_{{$holiday->name}}_nighttotal"
                                             class="text-center special_viewrequesttotal">0</th>
                                         @endforeach
                                     </tr>
                                 </tfoot>
                             </table>
                         </form>
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

    // Normal request Insert part
    var titleArrays = {};

    function assignInputFieldValues() {
        $('.holiday-input').each(function () {
            var id = $(this).attr('id');
            var jobtitle_id = $(this).attr('jobtitle_id');
            var shift_id = $(this).attr('shift_id');
            var holiday_id = $(this).attr('holiday_id');
            var value = $(this).val();
            var title = id.split('_')[0];

            if (!titleArrays[title]) {
                titleArrays[title] = [];
            }

            if (value.trim() !== '') {
                var dataObject = {
                    id: id,
                    jobtitle: jobtitle_id,
                    shift: shift_id,
                    holiday: holiday_id,
                    value: value
                };
                titleArrays[title].push(dataObject);
            }
        });
    }

    // Normal request Edit part
    var titleArrays2 = {};

    function assignInputFieldValues2() {
        $('.holiday-input2').each(function () {
            var id = $(this).attr('id');
            var jobtitle_id = $(this).attr('jobtitle_id2');
            var shift_id = $(this).attr('shift_id2');
            var holiday_id = $(this).attr('holiday_id2');
            var value = $(this).val();
            var title = id.split('_')[0];

            if (!titleArrays2[title]) {
                titleArrays2[title] = [];
            }

            if (value.trim() !== '') {
                var dataObject = {
                    id: id,
                    jobtitle: jobtitle_id,
                    shift: shift_id,
                    holiday: holiday_id,
                    value: value
                };
                titleArrays2[title].push(dataObject);
            }
        });
    }

    // Special request Insert part
    var specialtitleArrays = {};

    function speacial_assignInputFieldValues() {
        $('.speacial_holiday-input').each(function () {
            var id = $(this).attr('id');
            var jobtitle_id = $(this).attr('speacial_jobtitle_id');
            var shift_id = $(this).attr('speacial_shift_id');
            var holiday_id = $(this).attr('speacial_holiday_id');
            var value = $(this).val();
            var title = id.split('_')[0];

            if (!specialtitleArrays[title]) {
                specialtitleArrays[title] = [];
            }

            if (value.trim() !== '') {
                var dataObject = {
                    id: id,
                    jobtitle: jobtitle_id,
                    shift: shift_id,
                    holiday: holiday_id,
                    value: value
                };
                specialtitleArrays[title].push(dataObject);
            }
        });
    }

    // Special request edit part
    var specialtitleArrays2 = {};

    function speacial_assignInputFieldValues2() {
        $('.speacial_holiday-input2').each(function () {
            var id = $(this).attr('id');
            var jobtitle_id = $(this).attr('speacial_jobtitle_id2');
            var shift_id = $(this).attr('speacial_shift_id2');
            var holiday_id = $(this).attr('speacial_holiday_id2');
            var value = $(this).val();
            var title = id.split('_')[0];

            if (!specialtitleArrays2[title]) {
                specialtitleArrays2[title] = [];
            }

            if (value.trim() !== '') {
                var dataObject = {
                    id: id,
                    jobtitle: jobtitle_id,
                    shift: shift_id,
                    holiday: holiday_id,
                    value: value
                };
                specialtitleArrays2[title].push(dataObject);
            }
        });
    }

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

        // normal request model popup
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

            var customer = $('#customer').val();
            var subcustomer = $('#subcustomer').val();
            var area = $('#area').val();
            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();
            var subregion_id = $('#subregion_id').val();

            if (customer == '' || area == '' || fromdate == '' || todate == '') {

                $("#submitBtn").click();
            } else {
                $('#btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DetailsArrays: titleArrays,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        fromdate: fromdate,
                        todate: todate,
                        subregion_id: subregion_id,

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
        });

        // request update
        $('#btncreateorder2').click(function () {

            var customer = $('#customer2').val();
            var subcustomer = $('#subcustomer2').val();
            var area = $('#area2').val();
            var fromdate = $('#fromdate2').val();
            var todate = $('#todate2').val();
            var subregion_id = $('#subregion_id2').val();
            var hidden_id = $('#hidden_id2').val();

            if (customer == '' || area == '' || fromdate == '' || todate == '') {

                $("#submitBtn2").click();
            } else {
                $('#btncreateorder2').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Updating');

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DetailsArrays: titleArrays2,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        fromdate: fromdate,
                        todate: todate,
                        hidden_id: hidden_id,
                        subregion_id: subregion_id,

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


        // special request insert
        $('#speacial_btncreateorder').click(function () {

            var customer = $('#speacial_customer').val();
            var subcustomer = $('#speacial_subcustomer').val();
            var area = $('#speacial_area').val();
            var fromdate = $('#speacial_fromdate').val();
            var todate = $('#speacial_todate').val();
            var subregion_id = $('#speacial_subregion_id').val();

            if (customer == '' || area == '' || fromdate == '' || todate == '') {

                $("#speacial_submitBtn").click();
            } else {
                $('#speacial_btncreateorder').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create special request');


                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DetailsArrays: specialtitleArrays,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        fromdate: fromdate,
                        todate: todate,
                        subregion_id: subregion_id,

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
        });

        // Special request update
        $('#speacial_btncreateorder2').click(function () {

            var customer = $('#speacial_customer2').val();
            var subcustomer = $('#speacial_subcustomer2').val();
            var area = $('#speacial_area2').val();
            var fromdate = $('#speacial_fromdate2').val();
            var todate = $('#speacial_todate2').val();
            var subregion_id = $('#speacial_subregion_id2').val();
            var hidden_id = $('#speacial_hidden_id2').val();

            if (customer == '' || area == '' || fromdate == '' || todate == '') {

                $("#speacial_submitBtn2").click();
            } else {
                $('#speacial_btncreateorder2').prop('disabled', true).html(
                    '<i class="fas fa-circle-notch fa-spin mr-2"></i> Update Special');


                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DetailsArrays: specialtitleArrays2,
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
                    getbranchandsubcustomerEdit(data.result.mainData.customer_id, data
                        .result.mainData.subcustomer_id, data.result.mainData
                        .customerbranch_id);
                    $('#fromdate2').val(data.result.mainData.fromdate);
                    $('#todate2').val(data.result.mainData.todate);
                    $('#subregion_id2').val(data.result.mainData.subregion_id);
                    $('#requestdetaillist2').html(data.result.requestdata);
                    calculateTotal2()

                    $('#hidden_id2').val(id);
                    $('.modal-title2').text('Edit Client Request');
                    $('#btncreateorder2').html('Update');
                    $('#formModal2').modal('show');


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
                    getbranchandsubcustomerspecialEdit(data.result.mainData.customer_id,
                        data
                        .result.mainData.subcustomer_id, data.result.mainData
                        .customerbranch_id);
                    $('#speacial_fromdate2').val(data.result.mainData.fromdate);
                    $('#speacial_todate2').val(data.result.mainData.todate);
                    $('#speacial_subregion_id2').val(data.result.mainData.subregion_id);
                    $('#speacial_requestdetaillist2').html(data.result.requestdata);
                    speacial_calculateTotal2();

                    $('#speacial_hidden_id2').val(id);
                    $('#speacial_btncreateorder2').html('Update');
                    $('#editspecialrequestformModal').modal('show');

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


        // normal request approvel part
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
                    calculateTotal3()

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
                    calculateTotal3();

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
                    calculateTotal3();

                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                }
            })

        });


        // special request approvel part
        // approve model
        var id_approve_special;
        // approve level 01 
        $(document).on('click', '.special_appL1', function () {
            id_approve_special = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customersprequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve_special
                },
                success: function (data) {
                    $('#special_app_customer').val(data.result.mainData.customer_id);
                    $('#special_app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#special_app_area').val(data.result.mainData.customerbranch_id);
                    $('#special_app_fromdate').val(data.result.mainData.fromdate);
                    $('#special_app_todate').val(data.result.mainData.todate);
                    $('#special_app_shift').val(data.result.mainData.shift_id);
                    $('#special_app_requestdetaillist').html(data.result.requestdata);
                    speacial_calculateTotal3()

                    $('#special_hidden_id').val(id_approve_special);
                    $('#special_app_level').val('1');
                    $('#special_approveconfirmModal').modal('show');

                }
            })


        });

        // approve level 02 
        $(document).on('click', '.special_appL2', function () {
            id_approve_special = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customersprequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve_special
                },
                success: function (data) {
                    $('#special_app_customer').val(data.result.mainData.customer_id);
                    $('#special_app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#special_app_area').val(data.result.mainData.customerbranch_id);
                    $('#special_app_fromdate').val(data.result.mainData.fromdate);
                    $('#special_app_todate').val(data.result.mainData.todate);
                    $('#special_app_shift').val(data.result.mainData.shift_id);
                    $('#special_app_requestdetaillist').html(data.result.requestdata);
                    speacial_calculateTotal3();

                    $('#special_hidden_id').val(id_approve_special);
                    $('#special_app_level').val('2');
                    $('#special_approveconfirmModal').modal('show');

                }
            })


        });

        // approve level 03 
        $(document).on('click', '.special_appL3', function () {
            id_approve_special = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customersprequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve_special
                },
                success: function (data) {
                    $('#special_app_customer').val(data.result.mainData.customer_id);
                    $('#special_app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#special_app_area').val(data.result.mainData.customerbranch_id);
                    $('#special_app_fromdate').val(data.result.mainData.fromdate);
                    $('#special_app_todate').val(data.result.mainData.todate);
                    $('#special_app_shift').val(data.result.mainData.shift_id);
                    $('#special_app_requestdetaillist').html(data.result.requestdata);
                    speacial_calculateTotal3();

                    $('#special_hidden_id').val(id_approve_special);
                    $('#special_app_level').val('3');
                    $('#special_approveconfirmModal').modal('show');

                }
            })


        });

        // View normal request
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
                    calculateTotal4();

                    $('#viewModal').modal('show');

                }
            })
        });

         // View special request
         $(document).on('click', '.specialview', function () {
            id_approve_special = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("customersprequestapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve_special
                },
                success: function (data) {
                    $('#special_view_customer').val(data.result.mainData.customer_id);
                    $('#special_view_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#special_view_area').val(data.result.mainData.customerbranch_id);
                    $('#special_view_fromdate').val(data.result.mainData.fromdate);
                    $('#special_view_todate').val(data.result.mainData.todate);
                    $('#special_view_shift').val(data.result.mainData.shift_id);
                    $('#special_view_requestdetaillist').html(data.result.requestdata);
                    speacial_calculateTotal4();

                    $('#special_viewModal').modal('show');

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


// normal request approvel
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

// special request approvel
        $('#special_approve_button').click(function () {
            var id_hidden = $('#special_hidden_id').val();
            var applevel = $('#special_app_level').val();
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
                        $('#special_approveconfirmModal').modal('hide');
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

{{-- normal request dropdown filtering part --}}
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
</script>

{{-- special request dropdown filtering part --}}
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
</script>

{{-- table footer employee counter part --}}
<script>
    // normal insert model
    function calculateTotal() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.holiday-input');

        inputFields.forEach(function (inputField) {
            var holidayName = inputField.getAttribute('data-holiday');
            var time = inputField.getAttribute('data-time');
            var inputValue = parseFloat(inputField.value) || 0;

            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }
        });

        for (var holidayName in holidayTotals) {
            document.getElementById(holidayName + '_daytotal').textContent = holidayTotals[holidayName].dayTotal;
            document.getElementById(holidayName + '_nighttotal').textContent = holidayTotals[holidayName].nightTotal;
        }
    }

    var inputFields = document.querySelectorAll('.holiday-input');
    inputFields.forEach(function (inputField) {
        inputField.addEventListener('keyup', calculateTotal);
    });


    // normal edit model
    function calculateTotal2() {

        var holidayTotals2 = {};

        var inputFields2 = document.querySelectorAll('.holiday-input2');

        inputFields2.forEach(function (inputField2) {
            var holidayName = inputField2.getAttribute('data-holiday2');
            var time2 = inputField2.getAttribute('data-time2');
            var inputValue2 = parseFloat(inputField2.value) || null;

            if (!holidayTotals2[holidayName]) {
                holidayTotals2[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time2 === 'day') {
                holidayTotals2[holidayName].dayTotal += inputValue2;
            } else if (time2 === 'night') {
                holidayTotals2[holidayName].nightTotal += inputValue2;
            }
        });

        for (var holidayName in holidayTotals2) {

            document.getElementById(holidayName + '_daytotal2').textContent = holidayTotals2[holidayName].dayTotal;
            document.getElementById(holidayName + '_nighttotal2').textContent = holidayTotals2[holidayName].nightTotal;
        }
    }

    // special insert model
    function speacial_calculateTotal() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.speacial_holiday-input');

        inputFields.forEach(function (inputField) {
            var holidayName = inputField.getAttribute('speacial_data-holiday');
            var time = inputField.getAttribute('speacial_data-time');
            var inputValue = parseFloat(inputField.value) || 0;

            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }
        });

        for (var holidayName in holidayTotals) {
            document.getElementById('speacial_' + holidayName + '_daytotal').textContent = holidayTotals[holidayName]
                .dayTotal;
            document.getElementById('speacial_' + holidayName + '_nighttotal').textContent = holidayTotals[holidayName]
                .nightTotal;
        }
    }

    var speacial_inputFields = document.querySelectorAll('.speacial_holiday-input');
    speacial_inputFields.forEach(function (speacial_inputField) {
        speacial_inputField.addEventListener('keyup', speacial_calculateTotal);
    });


    // special edit model
    function speacial_calculateTotal2() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.speacial_holiday-input2');

        inputFields.forEach(function (inputField) {
            var holidayName = inputField.getAttribute('speacial_data-holiday2');
            var time = inputField.getAttribute('speacial_data-time2');
            var inputValue = parseFloat(inputField.value) || 0;

            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }
        });

        for (var holidayName in holidayTotals) {
            document.getElementById('speacial_' + holidayName + '_daytotal2').textContent = holidayTotals[holidayName]
                .dayTotal;
            document.getElementById('speacial_' + holidayName + '_nighttotal2').textContent = holidayTotals[holidayName]
                .nightTotal;
        }
    }


    // normal request approvel view model
    function calculateTotal3() {
        var holidayTotals3 = {};

        var inputFields3 = document.querySelectorAll('.holiday-input3');

        inputFields3.forEach(function (inputField3) {
            var holidayName = inputField3.getAttribute('data-holiday3');
            var time3 = inputField3.getAttribute('data-time3');
            var inputValue3 = parseFloat(inputField3.value) || null;

            if (!holidayTotals3[holidayName]) {
                holidayTotals3[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time3 === 'day') {
                holidayTotals3[holidayName].dayTotal += inputValue3;
            } else if (time3 === 'night') {
                holidayTotals3[holidayName].nightTotal += inputValue3;
            }
        });

        for (var holidayName in holidayTotals3) {
            document.getElementById('app_' + holidayName + '_daytotal').textContent = holidayTotals3[holidayName]
                .dayTotal;
            document.getElementById('app_' + holidayName + '_nighttotal').textContent = holidayTotals3[holidayName]
                .nightTotal;
        }
    }

     // special request approvel view model
     function speacial_calculateTotal3() {
        var holidayTotals3 = {};

        var inputFields3 = document.querySelectorAll('.speacial_holiday-input3');

        inputFields3.forEach(function (inputField3) {
            var holidayName = inputField3.getAttribute('speacial_data-holiday3');
            var time3 = inputField3.getAttribute('speacial_data-time3');
            var inputValue3 = parseFloat(inputField3.value) || null;

            if (!holidayTotals3[holidayName]) {
                holidayTotals3[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time3 === 'day') {
                holidayTotals3[holidayName].dayTotal += inputValue3;
            } else if (time3 === 'night') {
                holidayTotals3[holidayName].nightTotal += inputValue3;
            }
        });

        for (var holidayName in holidayTotals3) {
            document.getElementById('special_app_' + holidayName + '_daytotal').textContent = holidayTotals3[holidayName]
                .dayTotal;
            document.getElementById('special_app_' + holidayName + '_nighttotal').textContent = holidayTotals3[holidayName]
                .nightTotal;
        }
    }

    // normal rewuest view model
    function calculateTotal4() {
        var holidayTotals3 = {};

        var inputFields3 = document.querySelectorAll('.holiday-input3');

        inputFields3.forEach(function (inputField3) {
            var holidayName = inputField3.getAttribute('data-holiday3');
            var time3 = inputField3.getAttribute('data-time3');
            var inputValue3 = parseFloat(inputField3.value) || null;

            if (!holidayTotals3[holidayName]) {
                holidayTotals3[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time3 === 'day') {
                holidayTotals3[holidayName].dayTotal += inputValue3;
            } else if (time3 === 'night') {
                holidayTotals3[holidayName].nightTotal += inputValue3;
            }
        });

        for (var holidayName in holidayTotals3) {
            document.getElementById('view_' + holidayName + '_daytotal').textContent = holidayTotals3[holidayName]
                .dayTotal;
            document.getElementById('view_' + holidayName + '_nighttotal').textContent = holidayTotals3[holidayName]
                .nightTotal;
        }
    }

     // spectial rewuest view model
     function speacial_calculateTotal4() {
        var holidayTotals3 = {};

        var inputFields3 = document.querySelectorAll('.speacial_holiday-input3');

        inputFields3.forEach(function (inputField3) {
            var holidayName = inputField3.getAttribute('speacial_data-holiday3');
            var time3 = inputField3.getAttribute('speacial_data-time3');
            var inputValue3 = parseFloat(inputField3.value) || null;

            if (!holidayTotals3[holidayName]) {
                holidayTotals3[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0
                };
            }

            if (time3 === 'day') {
                holidayTotals3[holidayName].dayTotal += inputValue3;
            } else if (time3 === 'night') {
                holidayTotals3[holidayName].nightTotal += inputValue3;
            }
        });

        for (var holidayName in holidayTotals3) {
            document.getElementById('special_view_' + holidayName + '_daytotal').textContent = holidayTotals3[holidayName]
                .dayTotal;
            document.getElementById('special_view_' + holidayName + '_nighttotal').textContent = holidayTotals3[holidayName]
                .nightTotal;
        }
    }
    // var inputFields2 = document.querySelectorAll('.holiday-input2');
    // inputFields2.forEach(function(inputField2) {
    //     inputField2.addEventListener('keyup', calculateTotal2);
    // });
</script>


<script>
    // normal request form reset
    $('#formModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#formTitle')[0].reset();

        // Reset the select options
        $('#customer, #subcustomer, #area').val('').trigger('change');
        $('#subregion_id').val('');
        //reset table field
        $('.holiday-input').val('');
        //reset table footercount
        $('.requesttotal').text('0');
        // Clear the result message
        $('#form_result').html('');
    });

    // normal request edit form reset
    $('#formModal2').on('hidden.bs.modal', function () {
        // Reset the form
        $('#formTitle2')[0].reset();

        // Reset the select options
        $('#customer2, #subcustomer2, #area2').val('').trigger('change');
        $('#subregion_id2').val('');
         //reset table field
         $('.holiday-input2').val('');
        //reset table footercount
        $('.requesttotal2').text('0');

        // Clear the result message
        $('#form_result2').html('');
    });

    // normal request approvel form reset
    $('#approveconfirmModal').on('hidden.bs.modal', function () {
        // Reset the select options
        $('#app_customer, #app_subcustomer, #app_area').val('').trigger('change');
        $('#app_subregion_id').val('');
         //reset table field
         $('.holiday-input3').val('');
        //reset table footercount
        $('.approvelrequesttotal').text('0');
    });

    // normal request view form reset
    $('#viewModal').on('hidden.bs.modal', function () {
      // Reset the select options
        $('#view_customer, #view_subcustomer, #view_area').val('').trigger('change');
        $('#view_subregion_id').val('');
         //reset table field
         $('.holiday-input3').val('');
        //reset table footercount
        $('.viewrequesttotal').text('0');
    });

    // Special request form reset
    $('#specialrequestformModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#special_formTitle')[0].reset();

        // Reset the select options
        $('#speacial_customer, #speacial_subcustomer, #speacial_area').val('').trigger('change');
        $('#speacial_subregion_id').val('');
        //reset table field
        $('.speacial_holiday-input').val('');
        //reset table footercount
        $('.speacial_total').text('0');

    });

    // Special request edit form reset
    $('#editspecialrequestformModal').on('hidden.bs.modal', function () {
        // Reset the form
        $('#special_formTitle2')[0].reset();

        // Reset the select options
        $('#speacial_customer2, #speacial_subcustomer2, #speacial_area2')
            .val('').trigger('change');
        $('#speacial_subregion_id2').val('');
        //reset table field
        $('.speacial_holiday-input2').val('');
        //reset table footercount
        $('.speacial_total2').text('0');
    });

     // special request approvel form reset
     $('#special_approveconfirmModal').on('hidden.bs.modal', function () {
        // Reset the select options
        $('#special_app_customer, #special_app_subcustomer, #special_app_area').val('').trigger('change');
        $('#special_app_subregion_id').val('');
         //reset table field
         $('.speacial_holiday-input3').val('');
        //reset table footercount
        $('.special_approvelrequesttotal').text('0');
    });

    // special request view form reset
    $('#special_viewModal').on('hidden.bs.modal', function () {
      // Reset the select options
        $('#special_view_customer, #special_view_subcustomer, #special_view_area').val('').trigger('change');
        $('#special_view_subregion_id').val('');
         //reset table field
         $('.speacial_holiday-input3').val('');
        //reset table footercount
        $('.special_viewrequesttotal').text('0');
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