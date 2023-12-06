@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            @include('layouts.corporate_nav_bar')
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">
                        @if(in_array('Quotation-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record" onclick="getdocno();"><i class="fas fa-plus mr-2"></i>Add New Quotation</button>
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
                                    <th>Date</th>
                                    <th>Document No</th>
                                    <th>Client Name</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Add New Quotation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1"> 
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Date*</label>
                                        <input type="date" id="date" name="date" class="form-control form-control-sm"
                                            required onchange="getVatDateInputChange();">
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Document No*</label>
                                        <input type="text" id="documentno" name="documentno"
                                            class="form-control form-control-sm" required readonly>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Client Name*</label><br>
                                        <input type="text" id="clientname" name="clientname"
                                            class="form-control form-control-sm" required>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Client Address*</label>
                                        <textarea id="address" name="address" class="form-control form-control-sm"
                                            required></textarea>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">From*</label>
                                        <input type="date" id="fromdate" name="fromdate"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">To*</label>
                                        <input type="date" id="todate" name="todate"
                                            class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-2">
                                    </div>
                                    <div class="col-2">
                                        <label class="small font-weight-bold text-dark">Holidays*</label>
                                        <input type="number" id="holidays" name="holidays"
                                            class="form-control form-control-sm" required value="0">
                                    </div>
                                    <div class="col-2">
                                        <label class="small font-weight-bold text-dark">Special Holidays*</label>
                                        <input type="number" id="specialholidays" name="specialholidays"
                                            class="form-control form-control-sm" required value="0">
                                    </div>
                                </div>
                                <hr>
                                <div class="center-block fix-width scroll-inner">
                                    <table class="table table-striped table-bordered table-sm small nowrap display"
                                        id="tableorder">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Shift Rate</th>
                                                <th></th>
                                                @foreach($holidays as $holiday)
                                                <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                    {{$holiday->name}}
                                                </th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <th>Rank Assignment</th>
                                                <th></th>
                                                <th></th>
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
                                                <td class="text-center">
                                                    <input type="number" class="text-center rate-input"
                                                        jobtitle_id="{{$title->id}}"
                                                        style="width: 70px;border: none; background-color: transparent"
                                                        id="{{$title->id}}_shiftrate" name="{{$title->id}}_shiftrate"
                                                        value="">
                                                </td>
                                                <td>
                                                    <input type="number" class="text-center rate-input"
                                                        jobtitle_id="{{$title->id}}"
                                                        style="width: 70px;border: none; background-color: transparent"
                                                        id="{{$title->id}}_salaryrate" name="{{$title->id}}_salaryrate"
                                                        value="" disabled>
                                                </td>
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
                                            <tr style="font-weight: bold;">
                                                <td class="text-right">Total Shift:</td>
                                                <td></td>
                                                <td></td>
                                                @foreach($holidays as $holiday)
                                                <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                                    id="{{$holiday->name}}_daytotal" class="text-center requesttotal">0
                                                </th>
                                                <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                                    id="{{$holiday->name}}_nighttotal" class="text-center requesttotal">
                                                    0
                                                </th>
                                                @endforeach
                                            </tr>
                                            <tr class="line-break">
                                                <td colspan="5">&nbsp;</td>
                                            </tr>
                                            <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                                <td class="text-right">Price Per Shift:</td>
                                                <td></td>
                                                <td></td>
                                                @foreach($holidays as $holiday)
                                                <th id="{{$holiday->name}}_daypricepershift"
                                                    class="text-center pricepershift">0</th>
                                                <th id="{{$holiday->name}}_nightpricepershift"
                                                    class="text-center pricepershift">0
                                                </th>
                                                @endforeach
                                            </tr>
                                            <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                                <td class="text-right">Price Per Day:</td>
                                                <td></td>
                                                <td></td>
                                                @foreach($holidays as $holiday)
                                                <th></th>
                                                <th id="{{$holiday->name}}_priceperday" class="text-center priceperday">
                                                    0
                                                </th>
                                                @endforeach
                                            </tr>
                                           
                                        </tfoot>

                                    </table>
                                    <div class="form-group mt-3">
                                        <button type="button" onclick="calculatPrice()" name="calculatebtn" id="calculatebtn"
                                            class="btn btn-outline-secondary btn-sm fa-pull-right px-4">Calculate</button>
                                    </div>
                                </div>
                                
                                <hr>
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark"><b>Summary / Total
                                                Price*</b></label>
                                        <div class="center-block fix-width scroll-inner">
                                            <table
                                                class="table table-striped table-bordered table-sm small nowrap display"
                                                style="width: 100%;color: black;background-color: lightskyblue;" id="price_summary">
                                                <tbody>
                                                    <tr>
                                                        <td></td>
                                                        <td>Monthly</td>
                                                        <td>Yearly</td>
                                                        <td>Period</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Price</td>
                                                        <td id="monthlyprice"></td>
                                                        <td id="yearlyprice"></td>
                                                        <td id="periodprice"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>SSCL (<input type="text" id="sscl" name="sscl" value="0"
                                                                style="width: 27px;border: none; background-color: transparent">%)
                                                        </td>
                                                        <td id="monthlysscl"></td>
                                                        <td id="yearlysscl"></td>
                                                        <td id="periodsscl"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>VAT (<input type="text" id="vat" name="vat" value="0"
                                                                style="width: 27px;border: none; background-color: transparent">%)
                                                        </td>
                                                        <td id="monthlyvat"></td>
                                                        <td id="yearlyvat"></td>
                                                        <td id="periodvat"></td>
                                                    </tr>
                                                    <tr>
                                                        <td>Final Price</td>
                                                        <td id="monthlyfinalprice"></td>
                                                        <td id="yearlyfinalprice"></td>
                                                        <td id="periodfinalprice"></td>
                                                    </tr>
                                                </tbody>

                                                <tbody>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mt-3">
                                    <button type="button" onclick="assignInputFieldValues()" name="btncreateorder" id="btncreateorder"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Add</button>
                                </div>
                                <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- edit form --}}
<div class="modal fade" id="formModal2" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="modal-title2" id="staticBackdropLabel">Edit New Quotation</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <span id="form_result2"></span>
                        <form method="post" id="formTitle2" class="form-horizontal">
                            {{ csrf_field() }}
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Date*</label>
                                    <input type="date" id="date2" name="date2" class="form-control form-control-sm"
                                        required onchange="getVatDateInputChange();">
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Document No*</label>
                                    <input type="text" id="documentno2" name="documentno2"
                                        class="form-control form-control-sm" required readonly>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Client Name*</label><br>
                                    <input type="text" id="clientname2" name="clientname2"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">Client Address*</label>
                                    <textarea id="address2" name="address2" class="form-control form-control-sm"
                                        required></textarea>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">From*</label>
                                    <input type="date" id="fromdate2" name="fromdate2"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="col-3">
                                    <label class="small font-weight-bold text-dark">To*</label>
                                    <input type="date" id="todate2" name="todate2"
                                        class="form-control form-control-sm" required>
                                </div>

                                <div class="col-2">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">Holidays*</label>
                                    <input type="number" id="holidays2" name="holidays2"
                                        class="form-control form-control-sm" required value="0" onclick="calculateTotal2();">
                                </div>
                                <div class="col-2">
                                    <label class="small font-weight-bold text-dark">Special Holidays*</label>
                                    <input type="number" id="specialholidays2" name="specialholidays2"
                                        class="form-control form-control-sm" required value="0" onclick="calculateTotal2();">
                                </div>
                            </div>
                            <hr>
                            <div class="center-block fix-width scroll-inner">
                                <table class="table table-striped table-bordered table-sm small nowrap display"
                                    id="tableorder2">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Shift Rate</th>
                                            <th></th>
                                            @foreach($holidays as $holiday)
                                            <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                                {{$holiday->name}}
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <th>Rank Assignment</th>
                                            <th></th>
                                            <th></th>
                                            @foreach($holidays as $holiday)
                                            <th class="text-center">D</th>
                                            <th class="text-center">N</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody id="requestdetaillist2">
                                    </tbody>
                                    <tfoot>
                                        <tr style="font-weight: bold;">
                                            <td class="text-right">Total Shift:</td>
                                            <td></td>
                                            <td></td>
                                            @foreach($holidays as $holiday)
                                            <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                                id="{{$holiday->name}}_daytotal2" class="text-center requesttotal">0
                                            </th>
                                            <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                                id="{{$holiday->name}}_nighttotal2" class="text-center requesttotal">
                                                0
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr class="line-break">
                                            <td colspan="5">&nbsp;</td>
                                        </tr>
                                        <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                            <td class="text-right">Price Per Shift:</td>
                                            <td></td>
                                            <td></td>
                                            @foreach($holidays as $holiday)
                                            <th id="{{$holiday->name}}_daypricepershift2"
                                                class="text-center pricepershift">0</th>
                                            <th id="{{$holiday->name}}_nightpricepershift2"
                                                class="text-center pricepershift">0
                                            </th>
                                            @endforeach
                                        </tr>
                                        <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                            <td class="text-right">Price Per Day:</td>
                                            <td></td>
                                            <td></td>
                                            @foreach($holidays as $holiday)
                                            <th></th>
                                            <th id="{{$holiday->name}}_priceperday2" class="text-center priceperday">
                                                0
                                            </th>
                                            @endforeach
                                        </tr>
                                    </tfoot>

                                </table>
                                <div class="form-group mt-3">
                                    <button type="button" onclick="calculatPrice2()" name="calculatebtn2" id="calculatebtn2"
                                        class="btn btn-outline-secondary btn-sm fa-pull-right px-4">Calculate</button>
                                </div>
                            </div>
                            
                            <hr>
                            <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark"><b>Summary / Total
                                            Price*</b></label>
                                    <div class="center-block fix-width scroll-inner">
                                        <table
                                            class="table table-striped table-bordered table-sm small nowrap display"
                                            style="width: 100%;color: black;background-color: lightskyblue;" id="price_summary">
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td>Monthly</td>
                                                    <td>Yearly</td>
                                                    <td>Period</td>
                                                </tr>
                                                <tr>
                                                    <td>Price</td>
                                                    <td id="monthlyprice2"></td>
                                                    <td id="yearlyprice2"></td>
                                                    <td id="periodprice2"></td>
                                                </tr>
                                                <tr>
                                                    <td>SSCL (<input type="text" id="sscl2" name="sscl2" value="0"
                                                            style="width: 27px;border: none; background-color: transparent">%)
                                                    </td>
                                                    <td id="monthlysscl2"></td>
                                                    <td id="yearlysscl2"></td>
                                                    <td id="periodsscl2"></td>
                                                </tr>
                                                <tr>
                                                    <td>VAT (<input type="text" id="vat2" name="vat2" value="0"
                                                            style="width: 27px;border: none; background-color: transparent">%)
                                                    </td>
                                                    <td id="monthlyvat2"></td>
                                                    <td id="yearlyvat2"></td>
                                                    <td id="periodvat2"></td>
                                                </tr>
                                                <tr>
                                                    <td>Final Price</td>
                                                    <td id="monthlyfinalprice2"></td>
                                                    <td id="yearlyfinalprice2"></td>
                                                    <td id="periodfinalprice2"></td>
                                                </tr>
                                            </tbody>

                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group mt-3">
                                <button type="button" onclick="assignInputFieldValues2()" name="btncreateorder2" id="btncreateorder2"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Add</button>
                            </div>
                            <input name="submitBtn2" type="submit" value="Save" id="submitBtn2" class="d-none">
                            <input type="hidden" name="action" id="action" value="Add" />
                            <input type="hidden" name="hidden_id2" id="hidden_id2" />

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Approvel form --}}
<div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
        <div class="modal-header p-2">
            <h5 class="app_modal-title" id="staticBackdropLabel">Approvel New Quotation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <div class="row">
                <div class="col">
                    <span id="app_form_result"></span>
                    <form method="post" id="app_formTitle" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Date*</label>
                                <input type="date" id="app_date" name="app_date" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Document No*</label>
                                <input type="text" id="app_documentno" name="app_documentno"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Client Name*</label><br>
                                <input type="text" id="app_clientname" name="app_clientname"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Client Address*</label>
                                <textarea id="app_address" name="app_address" class="form-control form-control-sm"
                                    required readonly></textarea>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">From*</label>
                                <input type="date" id="app_fromdate" name="app_fromdate"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">To*</label>
                                <input type="date" id="app_todate" name="app_todate"
                                    class="form-control form-control-sm" required readonly>
                            </div>

                            <div class="col-2">
                            </div>
                            <div class="col-2">
                                <label class="small font-weight-bold text-dark">Holidays*</label>
                                <input type="number" id="app_holidays" name="app_holidays"
                                    class="form-control form-control-sm" required value="0" readonly>
                            </div>
                            <div class="col-2">
                                <label class="small font-weight-bold text-dark">Special Holidays*</label>
                                <input type="number" id="app_specialholidays" name="app_specialholidays"
                                    class="form-control form-control-sm" required value="0" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display"
                                id="app_tableorder">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Shift Rate</th>
                                        <th></th>
                                        @foreach($holidays as $holiday)
                                        <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                            {{$holiday->name}}
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Rank Assignment</th>
                                        <th></th>
                                        <th></th>
                                        @foreach($holidays as $holiday)
                                        <th class="text-center">D</th>
                                        <th class="text-center">N</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody id="app_requestdetaillist">
                                </tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;">
                                        <td class="text-right">Total Shift:</td>
                                        <td></td>
                                        <td></td>
                                        @foreach($holidays as $holiday)
                                        <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                            id="app_{{$holiday->name}}_daytotal" class="text-center requesttotal">0
                                        </th>
                                        <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                            id="app_{{$holiday->name}}_nighttotal" class="text-center requesttotal">
                                            0
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr class="line-break">
                                        <td colspan="5">&nbsp;</td>
                                    </tr>
                                    <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                        <td class="text-right">Price Per Shift:</td>
                                        <td></td>
                                        <td></td>
                                        @foreach($holidays as $holiday)
                                        <th id="app_{{$holiday->name}}_daypricepershift"
                                            class="text-center pricepershift">0</th>
                                        <th id="app_{{$holiday->name}}_nightpricepershift"
                                            class="text-center pricepershift">0
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                        <td class="text-right">Price Per Day:</td>
                                        <td></td>
                                        <td></td>
                                        @foreach($holidays as $holiday)
                                        <th></th>
                                        <th id="app_{{$holiday->name}}_priceperday" class="text-center priceperday">
                                            0
                                        </th>
                                        @endforeach
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                        
                        <hr>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark"><b>Summary / Total
                                        Price*</b></label>
                                <div class="center-block fix-width scroll-inner">
                                    <table
                                        class="table table-striped table-bordered table-sm small nowrap display"
                                        style="width: 100%;color: black;background-color: lightskyblue;" id="price_summary">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td>Monthly</td>
                                                <td>Yearly</td>
                                                <td>Period</td>
                                            </tr>
                                            <tr>
                                                <td>Price</td>
                                                <td id="app_monthlyprice"></td>
                                                <td id="app_yearlyprice"></td>
                                                <td id="app_periodprice"></td>
                                            </tr>
                                            <tr>
                                                <td>SSCL (<input type="text" id="app_sscl" name="app_sscl" value="0"
                                                        style="width: 27px;border: none; background-color: transparent">%)
                                                </td>
                                                <td id="app_monthlysscl"></td>
                                                <td id="app_yearlysscl"></td>
                                                <td id="app_periodsscl"></td>
                                            </tr>
                                            <tr>
                                                <td>VAT (<input type="text" id="app_vat" name="app_vat" value="0"
                                                        style="width: 27px;border: none; background-color: transparent">%)
                                                </td>
                                                <td id="app_monthlyvat"></td>
                                                <td id="app_yearlyvat"></td>
                                                <td id="app_periodvat"></td>
                                            </tr>
                                            <tr>
                                                <td>Final Price</td>
                                                <td id="app_monthlyfinalprice"></td>
                                                <td id="app_yearlyfinalprice"></td>
                                                <td id="app_periodfinalprice"></td>
                                            </tr>
                                        </tbody>

                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
 
                        <input type="hidden" name="app_level" id="app_level" value="1" />
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />

                        <div class="modal-footer p-2">
                            <button type="button" name="approve_button" id="approve_button"
                                class="btn btn-warning px-3 btn-sm">Approve</button>
                            <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

{{-- view form --}}
<div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
aria-labelledby="staticBackdropLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
        <div class="modal-header p-2">
            <h5 class="view_modal-title" id="staticBackdropLabel">View New Quotation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" id="modalContent">
            <div class="row">
                <div class="col">
                    <span id="view_form_result"></span>
                    <form method="post" id="app_formTitle" class="form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Date*</label>
                                <input type="date" id="view_date" name="view_date" style="border: none;" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Document No*</label>
                                <input type="text" id="view_documentno" name="view_documentno" style="border: none;"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">Client Name*</label><br>
                                <input type="text" id="view_clientname" name="view_clientname" style="border: none;"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Client Address*</label>
                                <textarea id="view_address" name="view_address" style="border: none;" class="form-control form-control-sm"
                                    required readonly></textarea>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">From*</label>
                                <input type="date" id="view_fromdate" name="view_fromdate" style="border: none;"
                                    class="form-control form-control-sm" required readonly>
                            </div>
                            <div class="col-3">
                                <label class="small font-weight-bold text-dark">To*</label>
                                <input type="date" id="view_todate" name="view_todate" style="border: none;"
                                    class="form-control form-control-sm" required readonly>
                            </div>

                            <div class="col-2">
                            </div>
                            <div class="col-2">
                                <label class="small font-weight-bold text-dark">Holidays*</label>
                                <input type="number" id="view_holidays" name="view_holidays" style="border: none;"
                                    class="form-control form-control-sm" required value="0" readonly>
                            </div>
                            <div class="col-2">
                                <label class="small font-weight-bold text-dark">Special Holidays*</label>
                                <input type="number" id="view_specialholidays" name="view_specialholidays" style="border: none;"
                                    class="form-control form-control-sm" required value="0" readonly>
                            </div>
                        </div>
                        <hr>
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display"
                                id="view_tableorder">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Shift Rate</th>
                                        <th></th>
                                        @foreach($holidays as $holiday)
                                        <th colspan="2" id="{{$holiday->id}}" class="text-center">
                                            {{$holiday->name}}
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr>
                                        <th>Rank Assignment</th>
                                        <th></th>
                                        <th></th>
                                        @foreach($holidays as $holiday)
                                        <th class="text-center">D</th>
                                        <th class="text-center">N</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody id="view_requestdetaillist">
                                </tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;">
                                        <td class="text-right">Total Shift:</td>
                                        <td></td>
                                        <td></td>
                                        @foreach($holidays as $holiday)
                                        <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                            id="view_{{$holiday->name}}_daytotal" class="text-center requesttotal">0
                                        </th>
                                        <th style="border-bottom: 1px solid rgb(5, 5, 5);"
                                            id="view_{{$holiday->name}}_nighttotal" class="text-center requesttotal">
                                            0
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr class="line-break">
                                        <td style="padding-top: 25px" colspan="5">&nbsp;</td>
                                    </tr>
                                    <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                        <td class="text-right">Price Per Shift:</td>
                                        <td></td>
                                        <td></td>
                                        @foreach($holidays as $holiday)
                                        <th id="view_{{$holiday->name}}_daypricepershift"
                                            class="text-center pricepershift">0</th>
                                        <th id="view_{{$holiday->name}}_nightpricepershift"
                                            class="text-center pricepershift">0
                                        </th>
                                        @endforeach
                                    </tr>
                                    <tr style="font-weight: bold;background-color: darkseagreen;color: black;">
                                        <td class="text-right">Price Per Day:</td>
                                        <td></td>
                                        <td></td>
                                        @foreach($holidays as $holiday)
                                        <th></th>
                                        <th id="view_{{$holiday->name}}_priceperday" class="text-center priceperday">
                                            0
                                        </th>
                                        @endforeach
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                        
                        <hr>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark"><b>Summary / Total
                                        Price*</b></label>
                                <div class="center-block fix-width scroll-inner">
                                    <table
                                        class="table table-striped table-bordered table-sm small nowrap display"
                                        style="width: 100%;color: black;background-color: lightskyblue;" id="price_summary">
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td>Monthly</td>
                                                <td>Yearly</td>
                                                <td>Period</td>
                                            </tr>
                                            <tr>
                                                <td>Price</td>
                                                <td id="view_monthlyprice"></td>
                                                <td id="view_yearlyprice"></td>
                                                <td id="view_periodprice"></td>
                                            </tr>
                                            <tr>
                                                <td>SSCL (<input type="text" id="view_sscl" name="view_sscl" value="0"
                                                        style="width: 27px;border: none; background-color: transparent">%)
                                                </td>
                                                <td id="view_monthlysscl"></td>
                                                <td id="view_yearlysscl"></td>
                                                <td id="view_periodsscl"></td>
                                            </tr>
                                            <tr>
                                                <td>VAT (<input type="text" id="view_vat" name="view_vat" value="0"
                                                        style="width: 27px;border: none; background-color: transparent">%)
                                                </td>
                                                <td id="view_monthlyvat"></td>
                                                <td id="view_yearlyvat"></td>
                                                <td id="view_periodvat"></td>
                                            </tr>
                                            <tr>
                                                <td>Final Price</td>
                                                <td id="view_monthlyfinalprice"></td>
                                                <td id="view_yearlyfinalprice"></td>
                                                <td id="view_periodfinalprice"></td>
                                            </tr>
                                        </tbody>

                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
 
                        <input type="hidden" name="view_hidden_id" id="view_hidden_id" />
                    </form>
                </div>
            </div>
        </div>
        <div class="modal-footer p-2">
            <button type="button" name="print_button" id="print_button"
                class="btn btn-warning px-3 btn-sm" onclick="printModal()">Print</button>
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

   

    <!-- Modal Area End -->
</main>

@endsection


@section('script')

<script>

// insert array part
var titleArrays = {};
var rateArrays = {};
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

        $('.rate-input').each(function () {
        var id = $(this).attr('id');
        var jobtitle_id = $(this).attr('jobtitle_id');
        var rate_type = id.split('_')[1]; // Extract 'shiftrate' or 'salaryrate'
        var value = $(this).val();
        var title = id.split('_')[0];

        if (!rateArrays[title]) {
            rateArrays[title] = [];
        }

            var dataObject = {
                id: id,
                jobtitle: jobtitle_id,
                rateType: rate_type, // 'shiftrate' or 'salaryrate'
                value: value
            };
            rateArrays[title].push(dataObject);

    });
// console.log(titleArrays);
// console.log(rateArrays);
}

// edit array part
var titleArrays2 = {};
var rateArrays2 = {};
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

        $('.rate-input2').each(function () {
        var id = $(this).attr('id');
        var jobtitle_id = $(this).attr('jobtitle_id2');
        var rate_type = $(this).attr('rate_type2');
        var value = $(this).val();
        var title = id.split('_')[0];

        if (!rateArrays2[title]) {
            rateArrays2[title] = [];
        }

            var dataObject = {
                id: id,
                jobtitle: jobtitle_id,
                rateType: rate_type, // 'shiftrate' or 'salaryrate'
                value: value
            };
            rateArrays2[title].push(dataObject);

    });
// console.log(titleArrays2);
// console.log(rateArrays2);
}
    $(document).ready(function () {
        var approvel01 = {{$approvel01permission}};
        var approvel02 = {{$approvel02permission}};
        var approvel03 = {{$approvel03permission}};

        var listcheck = {{$listpermission}};
        var editcheck = {{$editpermission}};
        var statuscheck = {{$statuspermission}};
        var deletecheck = {{$deletepermission}};

        $("#companylink").addClass('navbtnactive');
   $('#corporate_link').addClass('active');

        $('#dataTable').DataTable({
                "destroy": true,
                "processing": true,
                "serverSide": true,
                ajax: {
                    url: scripturl + '/quotationlist.php',

                    type: "POST", // you can use GET
                    data: {
                        },
                    
                },
                dom: "<'row'<'col-sm-5'B><'col-sm-2'l><'col-sm-5'f>>" + "<'row'<'col-sm-12'tr>>" + "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            responsive: true,
            lengthMenu: [
                [10, 25, 50, -1],
                [10, 25, 50, 'All'],
            ],
            "buttons": [
                { extend: 'csv', className: 'btn btn-success btn-sm', title: 'Quotation', text: '<i class="fas fa-file-csv mr-2"></i> CSV', },
                { 
                    extend: 'print', 
                    title: 'Quotation',
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
                        "data": "date",
                        "className": 'text-dark'
                    },
                    {
                        "data": "document_no",
                        "className": 'text-dark'
                    }, 
                    {
                        "data": "client_name",
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
                                    button += ' <a href="quotationstatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="quotationstatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
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



        $('#create_record').click(function () {
            $('.modal-title').text('Add New Quotation');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();

            $('#formModal').modal('show');
        });


            $('#btncreateorder').click(function () {
            var documentno = $('#documentno').val();
            var date = $('#date').val();
            var clientname = $('#clientname').val();
            var address = $('#address').val();
            var fromdate = $('#fromdate').val();
            var todate = $('#todate').val();
            var holidays = $('#holidays').val();
            var specialholidays = $('#specialholidays').val();

            if (date == '' || clientname == '' || address == '' || fromdate == '' || todate == '') {

                $("#submitBtn").click();
                titleArrays = {};
                rateArrays = {};
            } else {
            //   console.log(titleArrays);
            // console.log(rateArrays);

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DetailsArrays: titleArrays,
                        RateArrays: rateArrays,
                        documentno:documentno,
                        date: date,
                        clientname: clientname,
                        address: address,
                        fromdate: fromdate,
                        todate: todate,
                        holidays: holidays,
                        specialholidays: specialholidays,

                    },
                    url: "{{ route('quotationinsert') }}",
                    success: function (result) {
                        if (result.status == 1) {
                            location.reload();
                            $('#formModal').modal('hide');
                        }
                        else if(result.status == 2){
                            $('#btncreateorder').prop('disabled', false).html(
                    '<i class="fas fa-plus"></i> Create Order');
                    alert(result.message);
                        }
                        action(result.action);
                    }
                });
            }
            });

              // request edit part
        $(document).on('click', '.edit', function () {
            var id = $(this).attr('id');
            $('#hidden_id2').val(id);

            $('#form_result2').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("quotationedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#date2').val(data.result.mainData.date);
                    $('#documentno2').val(data.result.mainData.document_no);
                    $('#clientname2').val(data.result.mainData.client_name);
                    $('#address2').val(data.result.mainData.client_address);
                    $('#fromdate2').val(data.result.mainData.fromdate);
                    $('#todate2').val(data.result.mainData.todate);
                    $('#holidays2').val(data.result.mainData.holidays);
                    $('#specialholidays2').val(data.result.mainData.special_holidays);

                    $('#requestdetaillist2').html(data.result.requestdata);

                    $('#hidden_id2').val(id);
                    $('.modal-title2').text('Edit New Quotation');
                    $('#btncreateorder2').html('Update');
                    $('#formModal2').modal('show');
                    editgetVatDateInputChange();
                    

                }
            })


        });

        // update btn
        $('#btncreateorder2').click(function () {
            var documentno = $('#documentno2').val();
            var date = $('#date2').val();
            var clientname = $('#clientname2').val();
            var address = $('#address2').val();
            var fromdate = $('#fromdate2').val();
            var todate = $('#todate2').val();
            var holidays = $('#holidays2').val();
            var specialholidays = $('#specialholidays2').val();
            var hidden_id = $('#hidden_id2').val();

            if (date == '' || clientname == '' || address == '' || fromdate == '' || todate == '') {

                $("#submitBtn2").click();
                titleArrays2 = {};
                rateArrays2 = {};
            } else {
              console.log(titleArrays2);
            console.log(rateArrays2);

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DetailsArrays: titleArrays2,
                        RateArrays: rateArrays2,
                        documentno:documentno,
                        date: date,
                        clientname: clientname,
                        address: address,
                        fromdate: fromdate,
                        todate: todate,
                        holidays: holidays,
                        specialholidays: specialholidays,
                        hidden_id: hidden_id,

                    },
                    url: "{{ route('quotationupdate') }}",
                    success: function (result) {
                        if (result.status == 1) {
                            location.reload();
                            $('#formModal').modal('hide');
                        }
                        else if(result.status == 2){
                            $('#btncreateorder2').prop('disabled', false).html(
                    '<i class="fas fa-plus"></i> Create Order');
                    alert(result.message);
                        }
                        action(result.action);
                    }
                });
            }
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
                url: '{!! route("quotationdelete") !!}',
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
                    }, 100);
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
                url: '{!! route("quotationapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_date').val(data.result.mainData.date);
                    $('#app_documentno').val(data.result.mainData.document_no);
                    $('#app_clientname').val(data.result.mainData.client_name);
                    $('#app_address').val(data.result.mainData.client_address);
                    $('#app_fromdate').val(data.result.mainData.fromdate);
                    $('#app_todate').val(data.result.mainData.todate);
                    $('#app_holidays').val(data.result.mainData.holidays);
                    $('#app_specialholidays').val(data.result.mainData.special_holidays);

                    $('#app_requestdetaillist').html(data.result.requestdata);

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('1');
                    $('#approveconfirmModal').modal('show');
                    app_getVatDateInputChange();

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
                url: '{!! route("quotationapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                   
                    $('#app_date').val(data.result.mainData.date);
                    $('#app_documentno').val(data.result.mainData.document_no);
                    $('#app_clientname').val(data.result.mainData.client_name);
                    $('#app_address').val(data.result.mainData.client_address);
                    $('#app_fromdate').val(data.result.mainData.fromdate);
                    $('#app_todate').val(data.result.mainData.todate);
                    $('#app_holidays').val(data.result.mainData.holidays);
                    $('#app_specialholidays').val(data.result.mainData.special_holidays);

                    $('#app_requestdetaillist').html(data.result.requestdata);

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('2');
                    $('#approveconfirmModal').modal('show');
                    app_getVatDateInputChange();

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
                url: '{!! route("quotationapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                  
                    $('#app_date').val(data.result.mainData.date);
                    $('#app_documentno').val(data.result.mainData.document_no);
                    $('#app_clientname').val(data.result.mainData.client_name);
                    $('#app_address').val(data.result.mainData.client_address);
                    $('#app_fromdate').val(data.result.mainData.fromdate);
                    $('#app_todate').val(data.result.mainData.todate);
                    $('#app_holidays').val(data.result.mainData.holidays);
                    $('#app_specialholidays').val(data.result.mainData.special_holidays);

                    $('#app_requestdetaillist').html(data.result.requestdata);

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');
                    app_getVatDateInputChange();

                }
            })


        });



        $('#approve_button').click(function () {
            var id_hidden = $('#app_hidden_id').val();
            var applevel = $('#app_level').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("quotationapprove") !!}',
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
                    }, 200);
                    location.reload()
                }
            })
        });
    });

// view modal
    $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("quotationview_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                   
                    $('#view_date').val(data.result.mainData.date);
                    $('#view_documentno').val(data.result.mainData.document_no);
                    $('#view_clientname').val(data.result.mainData.client_name);
                    $('#view_address').val(data.result.mainData.client_address);
                    $('#view_fromdate').val(data.result.mainData.fromdate);
                    $('#view_todate').val(data.result.mainData.todate);
                    $('#view_holidays').val(data.result.mainData.holidays);
                    $('#view_specialholidays').val(data.result.mainData.special_holidays);

                    $('#view_requestdetaillist').html(data.result.requestdata);

                    $('#view_hidden_id').val(id_approve);
                    $('#view_level').val('3');
                    $('#viewModal').modal('show');
                    view_getVatDateInputChange();

                }
            })


        });

        

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }

    
    function getdocno() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("quotationdocno") !!}',
            type: 'POST',
            dataType: "json",
            // data: {
            //     id: supplierID
            // },
            success: function (data) {
                $('#documentno').val('QUO-' + data.result);

            }
        })
    }
</script>
<script>
    // get vat for insert model
    function getVatDateInputChange(){
        var value=$('#date').val()
        // console.log(value);
        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    var ajaxRequest = $.ajax({
                        url: '{!! route("quotationgetvat") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { date: value },
                    });

                    ajaxRequest.done(function (data) {
                        $('#vat').val(data.vat_result)
                        $('#sscl').val(data.sscl_result)
                    });

                    ajaxRequest.then(function () {
                        // calculate();
                    });

    }

     // get vat for edit model
     function editgetVatDateInputChange(){
        var value=$('#date2').val()
        // console.log(value);
        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    var ajaxRequest = $.ajax({
                        url: '{!! route("quotationgetvat") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { date: value },
                    });

                    ajaxRequest.done(function (data) {
                        $('#vat2').val(data.vat_result)
                        $('#sscl2').val(data.sscl_result)
                    });

                    ajaxRequest.then(function () {
                        calculateTotal2();
                    });

    }

    // get vat for approvel model
    function app_getVatDateInputChange(){
        var value=$('#app_date').val()
        // console.log(value);
        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    var ajaxRequest = $.ajax({
                        url: '{!! route("quotationgetvat") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { date: value },
                    });

                    ajaxRequest.done(function (data) {
                        $('#app_vat').val(data.vat_result)
                        $('#app_sscl').val(data.sscl_result)
                    });

                    ajaxRequest.then(function () {
                        app_calculateTotal();
                    });

    }

    // get vat for view model
    function view_getVatDateInputChange(){
        var value=$('#view_date').val()
        // console.log(value);
        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    var ajaxRequest = $.ajax({
                        url: '{!! route("quotationgetvat") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { date: value },
                    });

                    ajaxRequest.done(function (data) {
                        $('#view_vat').val(data.vat_result)
                        $('#view_sscl').val(data.sscl_result)
                    });

                    ajaxRequest.then(function () {
                        view_calculateTotal();
                    });

    }

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

        // edit model emp select
        $('#employee2').select2({
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
</script>
<script>
    // normal insert model
    function calculateTotal() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.holiday-input');

        inputFields.forEach(function (inputField) {
            var jobtitle = inputField.getAttribute('jobtitle_id');
            var holidayName = inputField.getAttribute('data-holiday');
            var time = inputField.getAttribute('data-time');
            var inputValue = parseFloat(inputField.value) || 0;


            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0,
                    daypriceTotal: 0,
                    nightpriceTotal: 0,
                    totalshift:0,
                    daycostTotal: 0,
                    nightcostTotal: 0,
                    totalcostshift:0,
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }

//------------------------------------------------------------------------------------------------------------------------------------
            // Calculate price_per_shift
            var shiftvalue = $('#' + jobtitle + '_shiftrate').val();
            var price_per_shift = shiftvalue * inputValue;
            
            // Accumulate price_per_shift for each holiday
            if (time === 'day') {
                holidayTotals[holidayName].daypriceTotal += price_per_shift;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightpriceTotal += price_per_shift;
            }

           // Update day and night price per shift (assuming these are HTML elements)
            $('#' + holidayName + '_daypricepershift').text(holidayTotals[holidayName].daypriceTotal);
            $('#' + holidayName + '_nightpricepershift').text(holidayTotals[holidayName].nightpriceTotal);

            // Calculate total shift
            var totalShift = holidayTotals[holidayName].daypriceTotal + holidayTotals[holidayName].nightpriceTotal;
            holidayTotals[holidayName].totalshift = totalShift;

//------------------------------------------------------------------------------------------------------------------------------------


        });
        // console.log(holidayTotals);

        for (var holidayName in holidayTotals) {
            document.getElementById(holidayName + '_daytotal').textContent = holidayTotals[holidayName].dayTotal;
            document.getElementById(holidayName + '_nighttotal').textContent = holidayTotals[holidayName].nightTotal;
//----------------------------------------------------------------------------------------------------------------------------------------------------------
            document.getElementById(holidayName + '_daypricepershift').textContent = holidayTotals[holidayName].daypriceTotal.toFixed(2);
            document.getElementById(holidayName + '_nightpricepershift').textContent = holidayTotals[holidayName].nightpriceTotal.toFixed(2);

            document.getElementById(holidayName + '_priceperday').textContent = holidayTotals[holidayName].totalshift.toFixed(2);
//---------------------------------------------------------------------------------------------------------------------------------------------------------

        }
    }

    var inputFields = document.querySelectorAll('.holiday-input');
    inputFields.forEach(function (inputField) {
        inputField.addEventListener('keyup', calculateTotal);
    });


function calculatPrice(){
   // total sales calculation
    var holidays = parseInt($('#holidays').val());
    var specialholidays = parseInt($('#specialholidays').val());

    var totaldays=holidays+specialholidays;
    var monthlyprice=0;

    var holidaytotalprice = 0;
    var specialdaytotalprice = 0;
    var weekdaytotalprice = 0;
    var saturdaytotalprice = 0;
    var sundaytotalprice = 0;
   
    holidaytotalprice = parseFloat(document.getElementById('Holidays_priceperday').textContent);
    specialdaytotalprice = parseFloat(document.getElementById('Special Days_priceperday').textContent);
    weekdaytotalprice = parseFloat(document.getElementById('Normal Week Days_priceperday').textContent);
    saturdaytotalprice = parseFloat(document.getElementById('Saturdays_priceperday').textContent);
    sundaytotalprice = parseFloat(document.getElementById('Sundays_priceperday').textContent);

    // monthly sales calculaton
    var weekdaymonthlyprice=(weekdaytotalprice*(22-totaldays)).toFixed(2);
    var saturdaymonthlyprice=(saturdaytotalprice*(4)).toFixed(2);
    var sundaymonthlyprice=(sundaytotalprice*(4)).toFixed(2);
    var holidaymonthlyprice=(holidaytotalprice*(holidays)).toFixed(2);
    var specialholidaymonthlyprice=(specialdaytotalprice*(specialholidays)).toFixed(2);

    var totalmonthlysale=parseFloat(weekdaymonthlyprice)+parseFloat(saturdaymonthlyprice)+parseFloat(sundaymonthlyprice)+parseFloat(holidaymonthlyprice)+parseFloat(specialholidaymonthlyprice);
    totalmonthlysale.toFixed(2);
    $('#monthlyprice').text(totalmonthlysale.toFixed(2));

     // yearly sales calculaton
     var totalyearlysale=totalmonthlysale*12;
     $('#yearlyprice').text(totalyearlysale.toFixed(2));

      // range sales calculaton
      var fromdate = new Date($('#fromdate').val());
      var todate = new Date($('#todate').val());
      var timeDifference = todate - fromdate;
      var yearsDifference = timeDifference / (365 * 24 * 60 * 60 * 1000);
      var totalrangesale = yearsDifference * totalyearlysale;
      $('#periodprice').text(totalrangesale.toFixed(2));

    // sscl calculate
    var sscl = $('#sscl').val();
    var monthlyssl=(totalmonthlysale)*sscl/100;
    var yearlysscl=(totalyearlysale)*sscl/100;
    var rangesscl=(monthlyssl)*24;

    $('#monthlysscl').text(monthlyssl.toFixed(2));
    $('#yearlysscl').text(yearlysscl.toFixed(2));
    $('#periodsscl').text(rangesscl.toFixed(2));
    
    //vat calculation
    var vat = $('#vat').val();
    var monthlvat=(totalmonthlysale)*vat/100;
    var yearlyvat=(totalyearlysale)*vat/100;
    var rangevat=(monthlvat)*24;

    $('#monthlyvat').text(monthlvat.toFixed(2));
    $('#yearlyvat').text(yearlyvat.toFixed(2));
    $('#periodvat').text(rangevat.toFixed(2));

    //final price calculation
    var monthlyfinalprice=totalmonthlysale+monthlyssl+monthlvat;
    var yearlyfinalprice=totalyearlysale+yearlysscl+yearlyvat;
    var periodfinalprice=totalrangesale+rangesscl+rangevat;

    $('#monthlyfinalprice').text(monthlyfinalprice.toFixed(2));
    $('#yearlyfinalprice').text(yearlyfinalprice.toFixed(2));
    $('#periodfinalprice').text(periodfinalprice.toFixed(2));

      //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------


}
</script>

<script>
    // normal update model
    function calculateTotal2() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.holiday-input2');

        inputFields.forEach(function (inputField) {
            var jobtitle = inputField.getAttribute('jobtitle_id2');
            var holidayName = inputField.getAttribute('data-holiday2');
            var time = inputField.getAttribute('data-time2');
            var inputValue = parseFloat(inputField.value) || 0;


            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0,
                    daypriceTotal: 0,
                    nightpriceTotal: 0,
                    totalshift:0,
                    daycostTotal: 0,
                    nightcostTotal: 0,
                    totalcostshift:0,
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }

//------------------------------------------------------------------------------------------------------------------------------------
            // Calculate price_per_shift
            var shiftvalue = $('#' + jobtitle + '_shiftrate2').val();
            var price_per_shift = shiftvalue * inputValue;
            
            // Accumulate price_per_shift for each holiday
            if (time === 'day') {
                holidayTotals[holidayName].daypriceTotal += price_per_shift;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightpriceTotal += price_per_shift;
            }

           // Update day and night price per shift (assuming these are HTML elements)
            $('#' + holidayName + '_daypricepershift2').text(holidayTotals[holidayName].daypriceTotal);
            $('#' + holidayName + '_nightpricepershift2').text(holidayTotals[holidayName].nightpriceTotal);

            // Calculate total shift
            var totalShift = holidayTotals[holidayName].daypriceTotal + holidayTotals[holidayName].nightpriceTotal;
            holidayTotals[holidayName].totalshift = totalShift;

//------------------------------------------------------------------------------------------------------------------------------------
        
   

        });
        // console.log(holidayTotals);

        for (var holidayName in holidayTotals) {
            document.getElementById(holidayName + '_daytotal2').textContent = holidayTotals[holidayName].dayTotal;
            document.getElementById(holidayName + '_nighttotal2').textContent = holidayTotals[holidayName].nightTotal;
//----------------------------------------------------------------------------------------------------------------------------------------------------------
            document.getElementById(holidayName + '_daypricepershift2').textContent = holidayTotals[holidayName].daypriceTotal.toFixed(2);
            document.getElementById(holidayName + '_nightpricepershift2').textContent = holidayTotals[holidayName].nightpriceTotal.toFixed(2);

            document.getElementById(holidayName + '_priceperday2').textContent = holidayTotals[holidayName].totalshift.toFixed(2);
//---------------------------------------------------------------------------------------------------------------------------------------------------------

        }
        calculatPrice2();
    }

  

function calculatPrice2(){
   // total sales calculation
    var holidays = parseInt($('#holidays2').val());
    var specialholidays = parseInt($('#specialholidays2').val());

    var totaldays=holidays+specialholidays;
    var monthlyprice=0;

    var holidaytotalprice = 0;
    var specialdaytotalprice = 0;
    var weekdaytotalprice = 0;
    var saturdaytotalprice = 0;
    var sundaytotalprice = 0;
   
    holidaytotalprice = parseFloat(document.getElementById('Holidays_priceperday2').textContent);
    specialdaytotalprice = parseFloat(document.getElementById('Special Days_priceperday2').textContent);
    weekdaytotalprice = parseFloat(document.getElementById('Normal Week Days_priceperday2').textContent);
    saturdaytotalprice = parseFloat(document.getElementById('Saturdays_priceperday2').textContent);
    sundaytotalprice = parseFloat(document.getElementById('Sundays_priceperday2').textContent);

    // monthly sales calculaton
    var weekdaymonthlyprice=(weekdaytotalprice*(22-totaldays)).toFixed(2);
    var saturdaymonthlyprice=(saturdaytotalprice*(4)).toFixed(2);
    var sundaymonthlyprice=(sundaytotalprice*(4)).toFixed(2);
    var holidaymonthlyprice=(holidaytotalprice*(holidays)).toFixed(2);
    var specialholidaymonthlyprice=(specialdaytotalprice*(specialholidays)).toFixed(2);

    var totalmonthlysale=parseFloat(weekdaymonthlyprice)+parseFloat(saturdaymonthlyprice)+parseFloat(sundaymonthlyprice)+parseFloat(holidaymonthlyprice)+parseFloat(specialholidaymonthlyprice);
    totalmonthlysale.toFixed(2);
    $('#monthlyprice2').text(totalmonthlysale.toFixed(2));

     // yearly sales calculaton
     var totalyearlysale=totalmonthlysale*12;
     $('#yearlyprice2').text(totalyearlysale.toFixed(2));

      // range sales calculaton
      var fromdate = new Date($('#fromdate2').val());
      var todate = new Date($('#todate2').val());
      var timeDifference = todate - fromdate;
      var yearsDifference = timeDifference / (365 * 24 * 60 * 60 * 1000);
      var totalrangesale = yearsDifference * totalyearlysale;
      $('#periodprice2').text(totalrangesale.toFixed(2));

    // sscl calculate
    var sscl = $('#sscl2').val();
    var monthlyssl=(totalmonthlysale)*sscl/100;
    var yearlysscl=(totalyearlysale)*sscl/100;
    var rangesscl=(monthlyssl)*24;

    $('#monthlysscl2').text(monthlyssl.toFixed(2));
    $('#yearlysscl2').text(yearlysscl.toFixed(2));
    $('#periodsscl2').text(rangesscl.toFixed(2));
    
    //vat calculation
    var vat = $('#vat2').val();
    var monthlvat=(totalmonthlysale)*vat/100;
    var yearlyvat=(totalyearlysale)*vat/100;
    var rangevat=(monthlvat)*24;

    $('#monthlyvat2').text(monthlvat.toFixed(2));
    $('#yearlyvat2').text(yearlyvat.toFixed(2));
    $('#periodvat2').text(rangevat.toFixed(2));

    //final price calculation
    var monthlyfinalprice=totalmonthlysale+monthlyssl+monthlvat;
    var yearlyfinalprice=totalyearlysale+yearlysscl+yearlyvat;
    var periodfinalprice=totalrangesale+rangesscl+rangevat;

    $('#monthlyfinalprice2').text(monthlyfinalprice.toFixed(2));
    $('#yearlyfinalprice2').text(yearlyfinalprice.toFixed(2));
    $('#periodfinalprice2').text(periodfinalprice.toFixed(2));

      //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

}
</script>

{{-- approvel model calculations --}}
<script>
    function app_calculateTotal() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.app_holiday-input');

        inputFields.forEach(function (inputField) {
            var jobtitle = inputField.getAttribute('app_jobtitle_id');
            var holidayName = inputField.getAttribute('app_data-holiday');
            var time = inputField.getAttribute('app_data-time');
            var inputValue = parseFloat(inputField.value) || 0;


            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0,
                    daypriceTotal: 0,
                    nightpriceTotal: 0,
                    totalshift:0,
                    daycostTotal: 0,
                    nightcostTotal: 0,
                    totalcostshift:0,
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }

//------------------------------------------------------------------------------------------------------------------------------------
            // Calculate price_per_shift
            var shiftvalue = $('#app_' + jobtitle + '_shiftrate').val();
            var price_per_shift = shiftvalue * inputValue;
            
            // Accumulate price_per_shift for each holiday
            if (time === 'day') {
                holidayTotals[holidayName].daypriceTotal += price_per_shift;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightpriceTotal += price_per_shift;
            }

           // Update day and night price per shift (assuming these are HTML elements)
            $('#app_' + holidayName + '_daypricepershift').text(holidayTotals[holidayName].daypriceTotal);
            $('#app_' + holidayName + '_nightpricepershift').text(holidayTotals[holidayName].nightpriceTotal);

            // Calculate total shift
            var totalShift = holidayTotals[holidayName].daypriceTotal + holidayTotals[holidayName].nightpriceTotal;
            holidayTotals[holidayName].totalshift = totalShift;

//------------------------------------------------------------------------------------------------------------------------------------


        });
        // console.log(holidayTotals);

        for (var holidayName in holidayTotals) {
            document.getElementById('app_'+holidayName + '_daytotal').textContent = holidayTotals[holidayName].dayTotal;
            document.getElementById('app_'+holidayName + '_nighttotal').textContent = holidayTotals[holidayName].nightTotal;
//----------------------------------------------------------------------------------------------------------------------------------------------------------
            document.getElementById('app_'+holidayName + '_daypricepershift').textContent = holidayTotals[holidayName].daypriceTotal.toFixed(2);
            document.getElementById('app_'+holidayName + '_nightpricepershift').textContent = holidayTotals[holidayName].nightpriceTotal.toFixed(2);

            document.getElementById('app_'+holidayName + '_priceperday').textContent = holidayTotals[holidayName].totalshift.toFixed(2);
//---------------------------------------------------------------------------------------------------------------------------------------------------------

        }
        app_calculatPrice();
    }

  

function app_calculatPrice(){
   // total sales calculation
    var holidays = parseInt($('#app_holidays').val());
    var specialholidays = parseInt($('#app_specialholidays').val());

    var totaldays=holidays+specialholidays;
    var monthlyprice=0;

    var holidaytotalprice = 0;
    var specialdaytotalprice = 0;
    var weekdaytotalprice = 0;
    var saturdaytotalprice = 0;
    var sundaytotalprice = 0;
   
    holidaytotalprice = parseFloat(document.getElementById('app_Holidays_priceperday').textContent);
    specialdaytotalprice = parseFloat(document.getElementById('app_Special Days_priceperday').textContent);
    weekdaytotalprice = parseFloat(document.getElementById('app_Normal Week Days_priceperday').textContent);
    saturdaytotalprice = parseFloat(document.getElementById('app_Saturdays_priceperday').textContent);
    sundaytotalprice = parseFloat(document.getElementById('app_Sundays_priceperday').textContent);

    // monthly sales calculaton
    var weekdaymonthlyprice=(weekdaytotalprice*(22-totaldays)).toFixed(2);
    var saturdaymonthlyprice=(saturdaytotalprice*(4)).toFixed(2);
    var sundaymonthlyprice=(sundaytotalprice*(4)).toFixed(2);
    var holidaymonthlyprice=(holidaytotalprice*(holidays)).toFixed(2);
    var specialholidaymonthlyprice=(specialdaytotalprice*(specialholidays)).toFixed(2);

    var totalmonthlysale=parseFloat(weekdaymonthlyprice)+parseFloat(saturdaymonthlyprice)+parseFloat(sundaymonthlyprice)+parseFloat(holidaymonthlyprice)+parseFloat(specialholidaymonthlyprice);
    totalmonthlysale.toFixed(2);
    $('#app_monthlyprice').text(totalmonthlysale.toFixed(2));

     // yearly sales calculaton
     var totalyearlysale=totalmonthlysale*12;
     $('#app_yearlyprice').text(totalyearlysale.toFixed(2));

      // range sales calculaton
      var fromdate = new Date($('#app_fromdate').val());
      var todate = new Date($('#app_todate').val());
      var timeDifference = todate - fromdate;
      var yearsDifference = timeDifference / (365 * 24 * 60 * 60 * 1000);
      var totalrangesale = yearsDifference * totalyearlysale;
      $('#app_periodprice').text(totalrangesale.toFixed(2));

    // sscl calculate
    var sscl = $('#app_sscl').val();
    var monthlyssl=(totalmonthlysale)*sscl/100;
    var yearlysscl=(totalyearlysale)*sscl/100;
    var rangesscl=(monthlyssl)*24;

    $('#app_monthlysscl').text(monthlyssl.toFixed(2));
    $('#app_yearlysscl').text(yearlysscl.toFixed(2));
    $('#app_periodsscl').text(rangesscl.toFixed(2));
    
    //vat calculation
    var vat = $('#app_vat').val();
    var monthlvat=(totalmonthlysale)*vat/100;
    var yearlyvat=(totalyearlysale)*vat/100;
    var rangevat=(monthlvat)*24;

    $('#app_monthlyvat').text(monthlvat.toFixed(2));
    $('#app_yearlyvat').text(yearlyvat.toFixed(2));
    $('#app_periodvat').text(rangevat.toFixed(2));

    //final price calculation
    var monthlyfinalprice=totalmonthlysale+monthlyssl+monthlvat;
    var yearlyfinalprice=totalyearlysale+yearlysscl+yearlyvat;
    var periodfinalprice=totalrangesale+rangesscl+rangevat;

    $('#app_monthlyfinalprice').text(monthlyfinalprice.toFixed(2));
    $('#app_yearlyfinalprice').text(yearlyfinalprice.toFixed(2));
    $('#app_periodfinalprice').text(periodfinalprice.toFixed(2));

      //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

}
</script>

{{-- view model calculations --}}
<script>
    function view_calculateTotal() {
        var holidayTotals = {};

        var inputFields = document.querySelectorAll('.view_holiday-input');

        inputFields.forEach(function (inputField) {
            var jobtitle = inputField.getAttribute('view_jobtitle_id');
            var holidayName = inputField.getAttribute('view_data-holiday');
            var time = inputField.getAttribute('view_data-time');
            var inputValue = parseFloat(inputField.value) || 0;


            if (!holidayTotals[holidayName]) {
                holidayTotals[holidayName] = {
                    dayTotal: 0,
                    nightTotal: 0,
                    daypriceTotal: 0,
                    nightpriceTotal: 0,
                    totalshift:0,
                    daycostTotal: 0,
                    nightcostTotal: 0,
                    totalcostshift:0,
                };
            }

            if (time === 'day') {
                holidayTotals[holidayName].dayTotal += inputValue;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightTotal += inputValue;
            }

//------------------------------------------------------------------------------------------------------------------------------------
            // Calculate price_per_shift
            var shiftvalue = $('#view_' + jobtitle + '_shiftrate').val();
            var price_per_shift = shiftvalue * inputValue;
            
            // Accumulate price_per_shift for each holiday
            if (time === 'day') {
                holidayTotals[holidayName].daypriceTotal += price_per_shift;
            } else if (time === 'night') {
                holidayTotals[holidayName].nightpriceTotal += price_per_shift;
            }

           // Update day and night price per shift (assuming these are HTML elements)
            $('#view_' + holidayName + '_daypricepershift').text(holidayTotals[holidayName].daypriceTotal);
            $('#view_' + holidayName + '_nightpricepershift').text(holidayTotals[holidayName].nightpriceTotal);

            // Calculate total shift
            var totalShift = holidayTotals[holidayName].daypriceTotal + holidayTotals[holidayName].nightpriceTotal;
            holidayTotals[holidayName].totalshift = totalShift;

//------------------------------------------------------------------------------------------------------------------------------------
          

        });
        // console.log(holidayTotals);

        for (var holidayName in holidayTotals) {
            document.getElementById('view_'+holidayName + '_daytotal').textContent = holidayTotals[holidayName].dayTotal;
            document.getElementById('view_'+holidayName + '_nighttotal').textContent = holidayTotals[holidayName].nightTotal;
//----------------------------------------------------------------------------------------------------------------------------------------------------------
            document.getElementById('view_'+holidayName + '_daypricepershift').textContent = holidayTotals[holidayName].daypriceTotal.toFixed(2);
            document.getElementById('view_'+holidayName + '_nightpricepershift').textContent = holidayTotals[holidayName].nightpriceTotal.toFixed(2);

            document.getElementById('view_'+holidayName + '_priceperday').textContent = holidayTotals[holidayName].totalshift.toFixed(2);
//---------------------------------------------------------------------------------------------------------------------------------------------------------

        }
        view_calculatPrice();
    }

  

function view_calculatPrice(){
   // total sales calculation
    var holidays = parseInt($('#view_holidays').val());
    var specialholidays = parseInt($('#view_specialholidays').val());

    var totaldays=holidays+specialholidays;
    var monthlyprice=0;

    var holidaytotalprice = 0;
    var specialdaytotalprice = 0;
    var weekdaytotalprice = 0;
    var saturdaytotalprice = 0;
    var sundaytotalprice = 0;
   
    holidaytotalprice = parseFloat(document.getElementById('view_Holidays_priceperday').textContent);
    specialdaytotalprice = parseFloat(document.getElementById('view_Special Days_priceperday').textContent);
    weekdaytotalprice = parseFloat(document.getElementById('view_Normal Week Days_priceperday').textContent);
    saturdaytotalprice = parseFloat(document.getElementById('view_Saturdays_priceperday').textContent);
    sundaytotalprice = parseFloat(document.getElementById('view_Sundays_priceperday').textContent);

    // monthly sales calculaton
    var weekdaymonthlyprice=(weekdaytotalprice*(22-totaldays)).toFixed(2);
    var saturdaymonthlyprice=(saturdaytotalprice*(4)).toFixed(2);
    var sundaymonthlyprice=(sundaytotalprice*(4)).toFixed(2);
    var holidaymonthlyprice=(holidaytotalprice*(holidays)).toFixed(2);
    var specialholidaymonthlyprice=(specialdaytotalprice*(specialholidays)).toFixed(2);

    var totalmonthlysale=parseFloat(weekdaymonthlyprice)+parseFloat(saturdaymonthlyprice)+parseFloat(sundaymonthlyprice)+parseFloat(holidaymonthlyprice)+parseFloat(specialholidaymonthlyprice);
    totalmonthlysale.toFixed(2);
    $('#view_monthlyprice').text(totalmonthlysale.toFixed(2));

     // yearly sales calculaton
     var totalyearlysale=totalmonthlysale*12;
     $('#view_yearlyprice').text(totalyearlysale.toFixed(2));

      // range sales calculaton
      var fromdate = new Date($('#view_fromdate').val());
      var todate = new Date($('#view_todate').val());
      var timeDifference = todate - fromdate;
      var yearsDifference = timeDifference / (365 * 24 * 60 * 60 * 1000);
      var totalrangesale = yearsDifference * totalyearlysale;
      $('#view_periodprice').text(totalrangesale.toFixed(2));

    // sscl calculate
    var sscl = $('#view_sscl').val();
    var monthlyssl=(totalmonthlysale)*sscl/100;
    var yearlysscl=(totalyearlysale)*sscl/100;
    var rangesscl=(monthlyssl)*24;

    $('#view_monthlysscl').text(monthlyssl.toFixed(2));
    $('#view_yearlysscl').text(yearlysscl.toFixed(2));
    $('#view_periodsscl').text(rangesscl.toFixed(2));
    
    //vat calculation
    var vat = $('#view_vat').val();
    var monthlvat=(totalmonthlysale)*vat/100;
    var yearlyvat=(totalyearlysale)*vat/100;
    var rangevat=(monthlvat)*24;

    $('#view_monthlyvat').text(monthlvat.toFixed(2));
    $('#view_yearlyvat').text(yearlyvat.toFixed(2));
    $('#view_periodvat').text(rangevat.toFixed(2));

    //final price calculation
    var monthlyfinalprice=totalmonthlysale+monthlyssl+monthlvat;
    var yearlyfinalprice=totalyearlysale+yearlysscl+yearlyvat;
    var periodfinalprice=totalrangesale+rangesscl+rangevat;

    $('#view_monthlyfinalprice').text(monthlyfinalprice.toFixed(2));
    $('#view_yearlyfinalprice').text(yearlyfinalprice.toFixed(2));
    $('#view_periodfinalprice').text(periodfinalprice.toFixed(2));

      //-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------

}
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>



<script>
    function printModal() {
        var date=$('#view_date').val()
   // Options for html2pdf
  var pdfOptions = {
    filename: 'Quotation('+date+').pdf',
    image: { type: 'png', quality: 1.0 },
    html2canvas: { scale: 2 },
    jsPDF: { unit: 'mm', format: 'a4', orientation: 'landscape' }
  };

  // Use html2pdf to convert the modal content to PDF
  html2pdf().from(document.getElementById('modalContent')).set(pdfOptions).save();
    }

</script>



@endsection