@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">

        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-user-tie"></i></div>
                <span><b>Employee Payment</b></span>
            </h1>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <div class="col-12">

                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>New Payment</button>

                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">

                        <table class="table table-striped table-bordered table-sm small" id="dataTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Sub Customer</th>
                                    <th>Branch</th>
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
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select name="customer" id="customer" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Customer</label>
                                        <select name="subcustomer" id="subcustomer" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Sub Customer</option>
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
                                        <label class="small font-weight-bold text-dark">Holiday Type</label>
                                        <select name="holiday" id="holiday" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Holiday</option>
                                            @foreach($holidays as $holiday)
                                            <option value="{{$holiday->id}}">{{$holiday->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Shift*</label>
                                        <select name="shift" id="shift" class="form-control form-control-sm" required>
                                            <option value="">Select Shift</option>
                                            @foreach($shifttypes as $shifttype)
                                            <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Job Title*</label>
                                        <select name="title" id="title" class="form-control form-control-sm" required>
                                            <option value="">Select Job Title</option>
                                            @foreach($titles as $title)
                                            <option value="{{$title->id}}">{{$title->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Company Rate*</label>
                                        <input type="number" id="companyrate" name="companyrate"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Guard Rate*</label>
                                        <input type="number" id="guardrate" name="guardrate"
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
                                        <th>Customer</th>
                                        <th>Holiday Type</th>
                                        <th>Shift</th>
                                        <th>Job Title</th>
                                        <th>Company Rate</th>
                                        <th>Guard Rate</th>
                                        <th class="d-none">CustomerID</th>
                                        <th class="d-none">HolidayTypeID</th>
                                        <th class="d-none">ShiftID</th>
                                        <th class="d-none">JobTitleID</th>

                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Payment</button>
                                {{-- <input type="hidden" name="hidden_id" id="hidden_id"> --}}

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
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Customer*</label>
                                        <select name="customer2" id="customer2" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Customer</option>
                                            @foreach($customers as $customer)
                                            <option value="{{$customer->id}}">{{$customer->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Sub Client*</label>
                                        <select name="subcustomer2" id="subcustomer2"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Sub Customer</option>
                                            @foreach($subcustomers as $subcustomer)
                                            <option value="{{$subcustomer->id}}">{{$subcustomer->sub_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Branch*</label>
                                        <select name="area2" id="area2" class="form-control form-control-sm" required>
                                            <option value="">Select Branch</option>
                                            @foreach($areas as $area)
                                            <option value="{{$area->id}}">{{$area->branch_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Holiday Type</label>
                                        <select name="holiday2" id="holiday2" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Holiday Type</option>
                                            @foreach($holidays as $holiday)
                                            <option value="{{$holiday->id}}">{{$holiday->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Shift*</label>
                                        <select name="shift2" id="shift2" class="form-control form-control-sm" required>
                                            <option value="">Select Shift</option>
                                            @foreach($shifttypes as $shifttype)
                                            <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>



                        <div class="col-8">
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
                                    <label class="small font-weight-bold text-dark">Company Rate*</label>
                                    <input type="number" id="companyrate2" name="companyrate2"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-row mb-1">
                                <div class="col">
                                    <label class="small font-weight-bold text-dark">Guard Rate*</label>
                                    <input type="number" id="guardrate2" name="guardrate2"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <button type="button" id="formsubmit2"
                                    class="btn btn-primary btn-sm px-4 float-right"><i class="fas fa-plus"></i>&nbsp;Add
                                    to list</button>
                                <input name="submitBtn2" type="submit" value="Save" id="submitBtn2" class="d-none">
                                <button type="button" name="Btnupdatelist" id="Btnupdatelist"
                                    class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;"><i
                                        class="fas fa-plus"></i>&nbsp;Update List</button>
                                <input type="hidden" name="requestdeiailsid" class="form-control form-control-sm"
                                    id="requestdeiailsid">
                            </div>
                            <br><br>

                            <table class="table table-striped table-bordered table-sm small" id="tableorder2">
                                <thead>
                                        <tr>
                                            <th>Job Title</th>
                                            <th>Company Rate</th>
                                            <th>Guard Rate</th>
                                            <th class="d-none">JobTitleID</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="requestdetaillist2"></tbody>
                                </table>
                                <div class="form-group mt-2">
                                    <button type="button" name="btncreateorder2" id="btncreateorder2"
                                        class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                            class="fas fa-plus"></i>&nbsp;Create Payment</button>
                                    <input type="hidden" name="hidden_id" id="hidden_id">

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
                        <button type="button" name="ok_button" id="ok_button"
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
                        <h5 class="modal-title" id="staticBackdropLabel">Approve Payment Details</h5>
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
                                            <label class="small font-weight-bold text-dark">Customer*</label>
                                            <select name="app_customer" id="app_customer"
                                                class="form-control form-control-sm" required>
                                                <option value="">Select Customer</option>
                                                @foreach($customers as $customer)
                                                <option value="{{$customer->id}}">{{$customer->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>


                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Sub Customer*</label>
                                            <select name="app_subcustomer" id="app_subcustomer"
                                                class="form-control form-control-sm" required>
                                                <option value="">Select Sub Customer</option>
                                                @foreach($subcustomers as $subcustomers)
                                                <option value="{{$subcustomers->id}}">{{$subcustomers->sub_name}}
                                                </option>
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
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Holiday Type</label>
                                            <select name="app_holiday" id="app_holiday"
                                                class="form-control form-control-sm" required>
                                                <option value="">Select Holiday Type</option>
                                                @foreach($holidays as $holiday)
                                                <option value="{{$holiday->id}}">{{$holiday->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col">
                                            <label class="small font-weight-bold text-dark">Shift*</label>
                                            <select name="app_shift" id="app_shift" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select Shift</option>
                                                @foreach($shifttypes as $shifttype)
                                                <option value="{{$shifttype->id}}">{{$shifttype->shift_name}}</option>
                                                @endforeach
                                            </select>
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
                                            <th>Job Title</th>
                                            <th>Company Rate</th>
                                            <th>Guard Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody id="app_requestdetaillist"></tbody>
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
        <!-- Modal Area End -->
</main>

@endsection


@section('script')

<script>
    $(document).ready(function () {


        $('#emppayment_link').addClass('active');
        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{!! route('displayemployeepayment') !!}",
            },
            columns: [{
                    data: 'id',
                    name: 'customerrequests.id'
                }, // Assuming the customer request ID column is 'id'
                {
                    data: 'customer_name',
                    name: 'customer_name'
                }, // Assuming the customer name column is 'name'
                {
                    data: 'subname',
                    name: 'subname'
                }, // Assuming the customer name column is 'name'
                {
                    data: 'branch',
                    name: 'branch'
                }, // Assuming the customer name column is 'name'
                {
                    data: 'shifts',
                    name: 'shifts'
                },
                {
                    data: 'holiday_type_name',
                    name: 'holiday_type_name'
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
                var customerID = $('#customer').val();
                var titleID = $('#title').val();
                var companyrate = $('#companyrate').val();
                var guardrate = $('#guardrate').val();
                var shiftID = $('#shift').val();
                var holidayID = $('#holiday').val();
                var customer = $("#customer option:selected").text();
                var title = $("#title option:selected").text();
                var shift = $("#shift option:selected").text();
                var holiday = $("#holiday option:selected").text();


                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + customer +
                    '</td><td>' + holiday + '</td><td>' + shift + '</td><td>' + title +
                    '</td><td class="text-center">' + companyrate +
                    '</td><td class="text-center">' + guardrate + '</td><td class="d-none">' +
                    customerID + '</td><td class="d-none">' + holidayID +
                    '</td><td class="d-none">' + shiftID + '</td><td class="d-none">' + titleID +
                    '</td></tr>');

                $('#title').val('');
                $('#companyrate').val('0');
                $('#guardrate').val('0');
            }
        });
        $('#tableorder').on('click', 'tr', function () {
            var r = confirm("Are you sure, You want to remove this product ? ");
            if (r == true) {
                $(this).closest('tr').remove();
            }
        });

        $("#formsubmit2").click(function () {
            // if (!$("#formTitle2")[0].checkValidity()) {
            //     // If the form is invalid, submit it. The form won't actually submit;
            //     // this will just cause the browser to display the native HTML5 error messages.
            //     $("#submitBtn2").click();
            // } else {
            // var customerID = $('#customer2').val();
            var titleID = $('#title2').val();
            var companyrate = $('#companyrate2').val();
            var guardrate = $('#guardrate2').val();
            var title = $("#title2 option:selected").text();


            $('#tableorder2> tbody:last').append('<tr class="pointer"><td>' + title + '</td><td>' +
                companyrate + '</td><td>' + guardrate + '</td><td class="d-none">' + titleID +
                '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
            );
            $('#title2').val('');
            $('#count2').val('0');
            // var shiftID = $('#shift2').val();
            // var holidayID = $('#holiday2').val();
            // var customer = $("#customer2 option:selected").text();
            // var shift = $("#shift2 option:selected").text();
            // var holiday = $("#holiday2 option:selected").text();

            // $('#tableorder2 > tbody:last').append('<tr class="pointer"><td>' + customer +
            //     '</td><td>' + holiday + '</td><td>' + shift + '</td><td>' + title +
            //     '</td><td class="text-center">' + companyrate +
            //     '</td><td class="text-center">' + guardrate + '</td><td class="d-none">' +
            //     customerID + '</td><td class="d-none">' + holidayID +
            //     '</td><td class="d-none">' + shiftID + '</td><td class="d-none">' + titleID +
            //     '</td></tr>');

            // $('#title2').val('');
            // $('#count2').val('0');



            //}
        });

        $('#create_record').click(function () {
            $('.modal-title').text('Create New Payment ');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');

            $('#formModal').modal('show');
        });


        $('#btncreateorder').click(function () {
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
                var holiday = $('#holiday').val();
                var shift = $('#shift').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        // year: year,
                        holiday: holiday,
                        shift: shift,

                    },
                    url: "{{ route('employeepaymentinsert') }}",
                    success: function (result) {
                        if (result.status == 1) {
                            $('#formModal').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 500);
                        }
                        action(result.action);
                    }
                });
            }
        });

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
                // var year = $('#year2').val();
                var holiday = $('#holiday2').val();
                var shift = $('#shift2').val();
                var hidden_id = $('#hidden_id').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        customer: customer,
                        subcustomer: subcustomer,
                        area: area,
                        holiday: holiday,
                        shift: shift,
                        hidden_id: hidden_id

                    },
                    url: "{{ route('employeepaymentupdate') }}",
                    success: function (result) {
                        if (result.status == 1) {
                            $('#formModal2').modal('hide');
                            setTimeout(function () {
                                location.reload();
                            }, 3000);
                        }
                    }
                });
            }
        });

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
                url: '{!! route("employeepaymentedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#customer2').val(data.result.mainData.customer_id);
                    $('#subcustomer2').val(data.result.mainData.subcustomer_id);
                    $('#area2').val(data.result.mainData.customerbranch_id);
                    $('#holiday2').val(data.result.mainData.holiday_type_id);
                    $('#shift2').val(data.result.mainData.shift_id);
                    $('#requestdetaillist2').html(data.result.requestdata);
                    // $('#guardrate2').val(data.result.guardrate);
                    // $('#companyrate2').val(data.result.companyrate);

                    $('#hidden_id').val(id);
                    $('.modal-title2').text('Edit Employee Payment');
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
                url: '{!! route("detailedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {id: id },
                success: function (data) {
                    $('#title2').val(data.result.job_title_id);
                    $('#companyrate2').val(data.result.companyrate);
                    $('#guardrate2').val(data.result.guardrate);
                    $('#requestdeiailsid').val(data.result.id);
                    $('#Btnupdatelist').show();
                    $('#formsubmit2').hide();
                }
            })
        });


        // request detail update list


        $(document).on("click", "#Btnupdatelist", function () {
            var titleID = $('#title2').val();
            var companyrate = $('#companyrate2').val();
            var guardrate = $('#guardrate2').val();
            var title = $("#title2 option:selected").text();
            var detailid = $('#requestdeiailsid').val();

            $("#tableorder2> tbody").find('input[name="hiddenid"]').each(function () {
                var hiddenid = $(this).val();
                if (hiddenid == detailid) {
                    $(this).parents("tr").remove();
                }
            });

            $('#tableorder2> tbody:last').append('<tr class="pointer"><td>' + title + '</td><td>' +
                companyrate + '</td><td>' + guardrate + '</td><td class="d-none">' + titleID +
                '</td><td class="d-none">Updated</td><td class="d-none">' +
                detailid +
                '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
            );


            $('#title2').val('');
            $('#companyrate2').val('');
            $('#guardrate2').val('');
            $('#Btnupdatelist').hide();
            $('#formsubmit2').show();
        });

        // Customer Delete 
        var employeepayments_id;
        $(document).on('click', '.delete', function () {
            employeepayments_id = $(this).attr('id');
            $('#confirmModal').modal('show');

        });

        $('#ok_button').click(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("employeepaymentdelete") !!}',
                type: 'POST',
                data: {
                    id: employeepayments_id
                },
                success: function (res) {
                    setTimeout(function () {
                        $('#confirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        alert('Data Deleted');
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
                url: '{!! route("employeepaymentapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_customer').val(data.result.mainData.customer_id);
                    $('#app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#app_area').val(data.result.mainData.customerbranch_id);
                    $('#app_holiday').val(data.result.mainData.holiday_type_id);
                    // $('#app_area').val(data.result.customerbranch_id);
                    // $('#app_title').val(data.result.job_title_id);
                    $('#app_shift').val(data.result.mainData.shift_id);
                    $('#app_requestdetaillist').html(data.result.requestdata);
                    // $('#app_count').val(data.result.count);


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
                url: '{!! route("employeepaymentapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_customer').val(data.result.mainData.customer_id);
                    $('#app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#app_area').val(data.result.mainData.customerbranch_id);
                    $('#app_holiday').val(data.result.holiday_id);
                    // $('#app_area').val(data.result.customerbranch_id);
                    // $('#app_title').val(data.result.job_title_id);
                    $('#app_shift').val(data.result.shift_id);
                    $('#app_requestdetaillist').html(data.result.requestdata);
                    // $('#app_count').val(data.result.count);

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
                url: '{!! route("employeepaymentapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_customer').val(data.result.mainData.customer_id);
                    $('#app_subcustomer').val(data.result.mainData.subcustomer_id);
                    $('#app_area').val(data.result.mainData.customerbranch_id);
                    $('#app_holiday').val(data.result.holiday_id);
                    // $('#app_area').val(data.result.customerbranch_id);
                    // $('#app_title').val(data.result.job_title_id);
                    $('#app_shift').val(data.result.shift_id);
                    $('#app_requestdetaillist').html(data.result.requestdata);
                    // $('#app_count').val(data.result.count);

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
                url: '{!! route("employeepaymentapprove") !!}',
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
                        alert('Data Approved');
                    }, 2000);
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
                    url: '/getsubcustomers/' + customerId,
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
        });

        //Get Branches
        $('#subcustomer').change(function () {
            var subcustomerId = $(this).val();
            if (subcustomerId !== '') {
                $.ajax({
                    url: '/getbranch/' + subcustomerId,
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
    // Disable the select element when the document is ready
    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('app_customer').disabled = true;
        document.getElementById('app_subcustomer').disabled = true;
        document.getElementById('app_area').disabled = true;
        document.getElementById('app_holiday').disabled = true;
        // document.getElementById('app_fromdate').disabled = true;
        // document.getElementById('app_todate').disabled = true;
        document.getElementById('app_shift').disabled = true;
    });
</script>
@endsection