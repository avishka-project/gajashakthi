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
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-users"></i></div>
                <span>Issue</span>
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
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Issue</button>
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <table class="table table-striped table-bordered table-sm small" id="dataTable">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Issuing</th>
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
                                                <option value="1">Location1</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="employeeDiv" style="display: none;" class="col-12">
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Employee*</label><br>
                                            <select name="employee" id="employee"
                                                class="form-control form-control-sm custom-select-width">
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
                                        <input type="month" id="month" name="month" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>

                                <div id="PaymenttypeDiv" style="display: none;">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Issue Type</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="issuetype"
                                                    id="freeIssueRadio" value="free">
                                                <label class="form-check-label" for="freeIssueRadio">
                                                    Free Issue
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="issuetype"
                                                    id="paidRadio" value="paid">
                                                <label class="form-check-label" for="paidRadio">
                                                    Paid
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Payment Type</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymenttype"
                                                    id="cashRadio" value="cash">
                                                <label class="form-check-label" for="cashRadio">
                                                    Cash
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymenttype"
                                                    id="loanRadio" value="loan">
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
                                        <label class="small font-weight-bold text-dark">Item*</label>
                                        <select name="item" id="item" class="form-control form-control-sm" required>
                                            <option value="">Select Item</option>
                                            @foreach($items as $item)
                                            <option value="{{$item->id}}">{{$item->item_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">Rate*</label>
                                        <input type="number" id="rate" name="rate" class="form-control form-control-sm"
                                            required>
                                    </div>
                                    <div class="col">
                                        <label class="small font-weight-bold text-dark">QTY*</label>
                                        <input type="number" id="qty" name="qty" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="button" id="formsubmit"
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                    <button type="button" name="Btnupdatelist" id="Btnupdatelist" class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;" ><i class="fas fa-plus"></i>&nbsp;Update List</button>
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
                                        <th>Rate</th>
                                        <th>QTy</th>
                                        <th>Total</th>
                                        <th class="d-none">ItemID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableorderlist"></tbody>
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
                    <button type="button" name="ok_button2" id="ok_button2" class="btn btn-danger px-3 btn-sm">OK</button>
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
                                            <option value="employee">Employee</option>
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row mb-1">
                                    <div id="app_locationDiv" style="display: none;" class="col-12">
                                        <div class="col-12">
                                            <label class="small font-weight-bold text-dark">Location*</label>
                                            <select name="app_location" id="app_location" class="form-control form-control-sm" readonly>
                                                <option value="">Select Location</option>
                                                <option value="1">Location1</option>
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
                                                <option value="{{$employee->id}}">{{$employee->emp_id}} -
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
                                        <input type="month" id="app_month" name="app_month" class="form-control form-control-sm"
                                        readonly>
                                    </div>
                                </div>
                                <div id="app_PaymenttypeDiv" style="display: none;">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Issue Type</label>
                                            <input type="text" id="app_issuetype" name="app_issuetype" class="form-control form-control-sm"
                                            readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Payment Type</label>
                                            <input type="text" id="app_paymenttype" name="app_paymenttype" class="form-control form-control-sm"
                                            readonly>
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
                                        <th>Rate</th>
                                        <th>QTy</th>
                                        <th>Total</th>
                                        <th class="d-none">ItemID</th>
                                        {{-- <th>Action</th> --}}
                                    </tr>
                                </thead>
                                <tbody id="app_tableorderlist"></tbody>
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

        $('#issue_link').addClass('active');

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('issuelist') !!}",

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'issuing',
                    name: 'issuing'
                },
                {
                    data: 'emp_name_with_initial',
                    name: 'emp_name_with_initial'
                },
                {
                    data: 'location_id',
                    name: 'location_id'
                },
                {
                    data: 'month',
                    name: 'month'
                },
                {
                    data: 'issue_type',
                    name: 'issue_type'
                },
                {
                    data: 'payment_type',
                    name: 'payment_type'
                },
                {
                    data: 'remark',
                    name: 'remark'
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

                var total = (Rate * QTy)

                var Item = $("#item option:selected").text();

                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + Item +
                    '</td><td>' + Rate + '</td><td>' + QTy + '</td><td>' + total +
                    '</td><td class="d-none">' + ItemID + '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');

                $('#item').val('');
                $('#rate').val('');
                $('#qty').val('');
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

                var issuing = $('#issuing').val();
                var location = $('#location').val();
                var employee = $('#employee').val();
                var month = $('#month').val();
                var issuetype = $('input[name="issuetype"]:checked').val();
                var paymenttype = $('input[name="paymenttype"]:checked').val();
                var remark = $('#remark').val();
                var hidden_id = $('#hidden_id').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        issuing: issuing,
                        location: location,
                        employee: employee,
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


                    if(data.result.mainData.issuing=="employee"){
                        $('#employee').val(data.result.mainData.employee_id);
                    }
                    else{
                        $('#location').val(data.result.mainData.location_id);
                    }
                   
                    $('#month').val(data.result.mainData.month);
                    $('#remark').val(data.result.mainData.remark);

                    issueTypeValue = (data.result.mainData.issue_type);
                    paymentTypeValue = (data.result.mainData.payment_type);
                    selectTypeInEdit();

                    $('#tableorderlist').html(data.result.requestdata);

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
                    data: {id: id },
                    success: function (data) {                     
                        $('#item').val(data.result.item_id);
                        $('#rate').val(data.result.rate);
                        $('#qty').val(data.result.qty);
                        $('#issuedeiailsid').val(data.result.id);
                        $('#Btnupdatelist').show();
                        $('#formsubmit').hide();
                    }
                })
            });

            // request detail update list
          
              
            $(document).on("click", "#Btnupdatelist", function () {
                  var ItemID = $('#item').val();
                  var Rate = $('#rate').val();
                  var Qty = $('#qty').val();
                  var Item = $("#item option:selected").text();
                  var detailid = $('#issuedeiailsid').val();

                  var total = (Rate * Qty)

                  $("#tableorder> tbody").find('input[name="hiddenid"]').each(function () {
                    var hiddenid = $(this).val();
                      if (hiddenid == detailid) {
                          $(this).parents("tr").remove();
                      }
                  });

                  $('#tableorder> tbody:last').append('<tr class="pointer"><td>' + Item +
                    '</td><td>' + Rate + '</td><td>' + Qty + '</td><td>' + total +
                    '</td><td class="d-none">' + ItemID + '</td><td class="d-none">Updated</td><td class="d-none">' +
                      detailid + '</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>');


                  $('#item').val('');
                  $('#rate').val('');
                  $('#qty').val('');
                  $('#Btnupdatelist').hide();
                  $('#formsubmit').show();
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
                        alert('Data Deleted');
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

                    if(data.result.mainData.issuing=="employee"){
                        $('#app_employee').val(data.result.mainData.employee_id);
                        $('#app_issuetype').val(data.result.mainData.issue_type);
                        $('#app_paymenttype').val(data.result.mainData.payment_type);
                    }
                    else{
                        $('#app_location').val(data.result.mainData.location_id);
                    }
                   
                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);

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

                    if(data.result.mainData.issuing=="employee"){
                        $('#app_employee').val(data.result.mainData.employee_id);
                        $('#app_issuetype').val(data.result.mainData.issue_type);
                        $('#app_paymenttype').val(data.result.mainData.payment_type);
                    }
                    else{
                        $('#app_location').val(data.result.mainData.location_id);
                    }
                   
                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);

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

                    if(data.result.mainData.issuing=="employee"){
                        $('#app_employee').val(data.result.mainData.employee_id);
                        $('#app_issuetype').val(data.result.mainData.issue_type);
                        $('#app_paymenttype').val(data.result.mainData.payment_type);
                    }
                    else{
                        $('#app_location').val(data.result.mainData.location_id);
                    }
                   
                    $('#app_month').val(data.result.mainData.month);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);

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
                        alert('Data Approved');
                    }, 2000);
                    location.reload()
                }
            })
        });

        function selectTypeInEdit(){
        var freeRadio = document.querySelector('input[value="free"]');
        var paidRadio = document.querySelector('input[value="paid"]');

        var cashRadio = document.querySelector('input[value="cash"]');
        var loanRadio = document.querySelector('input[value="loan"]');

            if (issueTypeValue === 'free') {
                freeRadio.checked = true;
            } else if (issueTypeValue === 'paid') {
                paidRadio.checked = true;
            }

            if (paymentTypeValue === 'cash') {
                cashRadio.checked = true;
            } else if (paymentTypeValue === 'loan') {
                loanRadio.checked = true;
            }
    }


        function resetfield(){
            $('#issuing').val('');
            $('#location').val('');
            $('#employee').val('');
            $('#month').val('');
            $('input[name="issuetype"]').prop('checked', false);
            $('input[name="paymenttype"]').prop('checked', false);
            $('#remark').val('');
        }
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
        $("#selectTypeFirst").show();
        $("#issuing").change(function () {
            var selectedOption = $(this).val();
            if (selectedOption === "location") {
                $("#locationDiv").show();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").hide();
                $("#PaymenttypeDiv").hide();
            } else if (selectedOption === "employee") {
                $("#locationDiv").hide();
                $("#employeeDiv").show();
                $("#selectTypeFirst").hide();
                $("#PaymenttypeDiv").show();
            } else {
                $("#locationDiv").hide();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").show();
                $("#PaymenttypeDiv").hide();
            }
        });
    });

function app_issuingChanges(app_issuing){
    
            if (app_issuing === "location") {
                $("#app_locationDiv").show();
                $("#app_employeeDiv").hide();
                $("#app_selectTypeFirst").hide();
                $("#app_PaymenttypeDiv").hide();
            } else if (app_issuing === "employee") {
                $("#app_locationDiv").hide();
                $("#app_employeeDiv").show();
                $("#app_selectTypeFirst").hide();
                $("#app_PaymenttypeDiv").show();
            } else {
                $("#app_locationDiv").hide();
                $("#app_employeeDiv").hide();
                $("#app_selectTypeFirst").show();
                $("#app_PaymenttypeDiv").hide();
            }
    
}


function edit_issuingChanges(issuing){
    
    if (issuing === "location") {
        $("#locationDiv").show();
        $("#employeeDiv").hide();
        $("#selectTypeFirst").hide();
        $("#PaymenttypeDiv").hide();
    } else if (issuing === "employee") {
        $("#locationDiv").hide();
        $("#employeeDiv").show();
        $("#selectTypeFirst").hide();
        $("#PaymenttypeDiv").show();
    } else {
        $("#locationDiv").hide();
        $("#employeeDiv").hide();
        $("#selectTypeFirst").show();
        $("#PaymenttypeDiv").hide();
    }

}

</script>
<script>
    // Initialize Select2 on the select element
    $(document).ready(function () {
        $('#employee').select2({
            width: '100%' // Set the width to 100% of its parent container
        });
    });
</script>






</body>

@endsection