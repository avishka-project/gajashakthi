@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
        <div class="page-header-content py-3">
            <h1 class="page-header-title">
                <div class="page-header-icon"><i class="fas fa-truck"></i></div>
                <span>Good Receive Note</span>
            </h1>
        </div>
        </div>
    </div>
    <br>
    <div class="container-fluid">
        <div class="card">
            <div class="card-body p-0 p-2">
                <div class="row">
                    <style>
                        .alert {
                            transition: opacity 0.5s ease-out;
                        }
                    </style>
                    @if(session('success'))
                    <div style="margin-left: 10px" class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Create Good Receive Note</button>
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
                                    <th>Batch No</th>
                                    <th>Supplier</th>
                                    <th>Invoice No</th>
                                    <th>Dispatch No</th>
                                    <th>Total</th>
                                    <th>Approved Status</th>
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
    <div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">View Good Recieve Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-right">
                            <h3 id="porderno"></h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <div id="suppliername"></div>
                            <div id="suppliercontact"></div>
                            <div id="supplieremail"></div>
                            <div id="supplieraddress"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12" style="font-weight: bold">
                            <div id="invoiceno"></div>
                            <div id="dispatchno"></div>
                            <div id="viewbatchno"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th>Total</th>
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


    <div class="modal fade" id="formModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Create Good Receive Note</h5>
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
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="orderdate" name="orderdate"
                                            class="form-control form-control-sm" required>
                                    </div>

                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Purchase Order*</label>
                                        <select name="porder" id="porder" class="form-control form-control-sm">
                                            <option value="">Select Purchase Order</option>
                                            @foreach($porders as $porder)
                                            <option value="{{$porder->id}}">POD-0000{{$porder->id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Supplier*</label>
                                        <select name="supplier" id="supplier" class="form-control form-control-sm"
                                            onchange="getbatchno();itemsWithoutPorder()" required>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Comment*</label>
                                        <textarea type="text" id="comment" name="comment"
                                            class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                               
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Store*</label>
                                        <select name="item" id="item" class="form-control form-control-sm" required>
                                            <option value="">Select Store</option>
                                            {{-- @foreach($items as $item)
                                            <option value="{{$item->id}}">{{$item->item_name}}</option>
                                            @endforeach --}}
                                        </select>
                                    </div>
                                </div>
                                <hr>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Item*</label>
                                        <select name="item" id="item" class="form-control form-control-sm"
                                            onchange="getpurchaseprice();itemsPriceWithoutPorder()" required>
                                            <option value="">Select Item</option>

                                        </select>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Rate*</label>
                                        <input type="number" id="rate" name="rate" class="form-control form-control-sm"
                                            required>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">QTY*</label>
                                        <input type="number" id="qty" name="qty" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Batch No*</label>
                                        <input type="text" id="batchno" name="batchno"
                                            class="form-control form-control-sm" required readonly>
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
                                <input type="hidden" name="grndetailsid" id="grndetailsid">
                                <input type="hidden" name="totalcost" id="totalcost">
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
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 18px">
                                        <td colspan="3">Total:</td>
                                        <td id="totalField" class="text-left">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create Good Receive Note</button>

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
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Good Receive Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-4">
                            <form class="form-horizontal">
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="app_orderdate" name="app_orderdate"
                                            class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Purchase Order*</label>
                                        <select name="app_porder" id="app_porder" class="form-control form-control-sm"
                                        readonly>
                                            <option value="">Select Purchase Order</option>
                                            @foreach($porders as $porder)
                                            <option value="{{$porder->id}}">POD-0000{{$porder->id}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Supplier*</label>
                                        <select name="app_supplier" id="app_supplier"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Comment*</label>
                                        <textarea type="text" id="app_comment" name="app_comment"
                                            class="form-control form-control-sm" readonly></textarea>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Batch No*</label>
                                        <input type="text" id="app_batchno" name="app_batchno"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                </div>
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

                            <input type="hidden" name="app_porder_id" id="app_porder_id" />
                            <input type="hidden" name="batch_no" id="batch_no" />
                            <input type="hidden" name="hidden_id" id="hidden_id" />
                            <input type="hidden" name="app_level" id="app_level" value="1" />
                        </div>
                        </form>
                    </div>
                    <div class="modal-footer p-2">
                        <button type="button" name="approve_button" id="approve_button"
                            class="btn btn-warning px-3 btn-sm">Approve</button>
                        <button type="button" class="btn btn-dark px-3 btn-sm" name="reject_button" id="reject_button" data-dismiss="modal">Reject</button>
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
        $('#grn_pruchaseinfodrop').addClass('show');
        $('#grn_link').addClass('active');

        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('grnlist') !!}",

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'grn_date',
                    name: 'grn_date'
                },
                {
                    data: 'batch_no',
                    name: 'batch_no'
                },
                {
                    data: 'supplier_name',
                    name: 'supplier_name'
                },
                {
                    data: 'invoice_no',
                    name: 'invoice_no'
                },
                {
                    data: 'dispatch_no',
                    name: 'dispatch_no'
                },
                {
                    data: 'total',
                    name: 'total'
                },
                {
                    data: 'confirm_status',
                    name: 'confirm_status',
                    render: function (data, type, row) {
                        if (data == 0) {
                            return '<i style="color:red" class="fas fa-times"></i>&nbsp;&nbsp Not Approved GRN';
                        } else if (data == 1) {
                            return '<i style="color:green" class="fas fa-check"></i>&nbsp;&nbsp Approved GRN';
                        } else {
                            return '<i style="color:red" class="fas fa-ban"></i>&nbsp;&nbsp Reject GRN';
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

        $('#create_record').click(function () {
            $('.modal-title').text('Create Good Receive Note');
            $('#action_button').html('Add');
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
                    '</td><td class="text-left">' + Rate + '</td><td class="text-left">' + QTy +
                    '</td><td class="text-left">' + total +
                    '</td><td class="d-none">' + ItemID +
                    '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
                );

                updateTotalSum();

                $('#item').val('');
                $('#rate').val('');
                $('#qty').val('');
            }
        });

        $('#btncreateorder').click(function () {

            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('grninsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('grnupdate') }}";
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

                var orderdate = $('#orderdate').val();
                var porder = $('#porder').val();
                var supplier = $('#supplier').val();
                var comment = $('#comment').val();
                var batchno = $('#batchno').val();
                var totalValue = parseFloat($('#totalField').text());
                var hidden_id = $('#hidden_id').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        orderdate: orderdate,
                        porder: porder,
                        supplier: supplier,
                        comment: comment,
                        batchno: batchno,
                        totalValue: totalValue,
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
            resetfield();

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("grnedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#orderdate').val(data.result.mainData.grn_date);
                    $('#porder').val(data.result.mainData.porder_id);
                    $('#supplier').val(data.result.mainData.supplier_id);
                    $('#comment').val(data.result.mainData.remark);
                    $('#batchno').val(data.result.mainData.batch_no);
                    edit_porderItemget(data.result.mainData.porder_id);
                    edit_supplierItemget(data.result.mainData.porder_id,data.result.mainData.supplier_id);

                    $('#tableorderlist').html(data.result.requestdata);
                    updateTotalSum();

                    // var valueToCheck = data.result.pay_by;

                    // if (valueToCheck == 1 ) {
                    //     $('#company').prop('checked', true);
                    // } else {
                    //      $('#branch').prop('checked', true);
                    // }

                    $('#hidden_id').val(id);
                    $('.modal-title').text('Edit Purchase Order');
                    $('#action_button').html('Edit');
                    $('#btncreateorder').html('Update Purchase Order');
                    $('#action').val('Edit');
                    $('#formModal').modal('show');
                }
            })
        });

        function resetfield() {
            $('#orderdate').val('');
            $('#duedate').val('');
            $('#supplier').val('');
            $('#comment').val('');
        }


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
                url: '{!! route("grndetailedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#item').val(data.result.item_id);
                    $('#rate').val(data.result.unit_price);
                    $('#qty').val(data.result.qty);
                    $('#grndetailsid').val(data.result.id);
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
            var detailid = $('#grndetailsid').val();

            var total = (Rate * Qty)

            $("#tableorder> tbody").find('input[name="hiddenid"]').each(function () {
                var hiddenid = $(this).val();
                if (hiddenid == detailid) {
                    $(this).parents("tr").remove();
                }
            });

            $('#tableorder> tbody:last').append('<tr class="pointer"><td>' + Item +
                '</td><td>' + Rate + '</td><td>' + Qty + '</td><td>' + total +
                '</td><td class="d-none">' + ItemID +
                '</td><td class="d-none">Updated</td><td class="d-none">' +
                detailid +
                '</td><td id ="actionrow"><button type="button" id="' + detailid + '" class="btnEditlist btn btn-primary btn-sm "><i class="fas fa-pen"></i></button>&nbsp;<button type="button" id="' + detailid + '" class="btnDeletelist btn btn-danger btn-sm " ><i class="fas fa-trash-alt"></i></button></td>' +
                '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'+ detailid + '"></td></tr>'
            );
            updateTotalSum();

            $('#item').val('');
            $('#rate').val('');
            $('#qty').val('');
            $('#Btnupdatelist').hide();
            $('#formsubmit').show();
        });

        //   details delete
        var rowid
        var total
        var cost
        var porderid
        $(document).on('click', '.btnDeletelist', function () {
            rowid = $(this).attr('rowid');
            cost = $(this).attr('cost');
            total = $('#totalcost').val();
            grnid = $('#hidden_id').val();
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
                url: '{!! route("grndetaildelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: rowid,
                    total: total,
                    cost: cost,
                    grnid: grnid
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
                url: '{!! route("grndelete") !!}',
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
                url: '{!! route("grndetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_orderdate').val(data.result.mainData.grn_date);
                    $('#app_porder').val(data.result.mainData.porder_id);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_comment').val(data.result.mainData.remark);
                    $('#app_batchno').val(data.result.mainData.batch_no);
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
                url: '{!! route("grndetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_orderdate').val(data.result.mainData.grn_date);
                    $('#app_porder').val(data.result.mainData.porder_id);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_comment').val(data.result.mainData.remark);
                    $('#app_batchno').val(data.result.mainData.batch_no);
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
            batch_no = $(this).attr('batch_no');
            porder_id = $(this).attr('porder_id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("grndetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve,
                },
                success: function (data) {
                    $('#app_orderdate').val(data.result.mainData.grn_date);
                    $('#app_porder').val(data.result.mainData.porder_id);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_comment').val(data.result.mainData.remark);
                    $('#app_batchno').val(data.result.mainData.batch_no);
                    $('#app_tableorderlist').html(data.result.requestdata);
                    ApproveTotalSum();
                    $('#hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');

                    $('#batch_no').val(batch_no);
                    $('#app_porder_id').val(porder_id);
                }
            })


        });


        $('#approve_button').click(function () {
            var id_hidden = $('#hidden_id').val();
            var applevel = $('#app_level').val();
            var porder_id = $('#app_porder_id').val();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("grnapprove") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                    applevel: applevel,
                    porder_id: porder_id
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

            if (applevel == 3) {
                updateStock();
            }

        });

        $('#reject_button').click(function () {
            var id_hidden = $('#hidden_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("grnreject") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_hidden,
                },
                success: function (data) { //alert(data);
                    setTimeout(function () {
                        $('#approveconfirmModal').modal('hide');
                        $('#dataTable').DataTable().ajax.reload();
                        alert('Data Rejected');
                    }, 2000);
                    location.reload()
                }
            })

        });

        $(document).on('click', '.view', function () {
            var id = $(this).attr('id');
            $('#porderno').text('POD-0000' + id);
            var total = 0;

            $('#view_tableorder').DataTable({

                processing: true,
                serverSide: true,
                ajax: {
                    "url": "{!! route('grnview') !!}",
                    "data": {
                        id: id,
                        _token: "{{ csrf_token() }}"
                    },

                },
                searching: false,
                lengthChange: false,
                paging: false,
                info: false,
                columns: [{
                        data: 'id'
                    },
                    {
                        data: 'item_name',
                        name: 'item_name'
                    },
                    {
                        data: 'unit_price',
                        name: 'unit_price'
                    },
                    {
                        data: 'qty',
                        name: 'qty'
                    },
                    {
                        data: 'qty',
                        render: function (data, type, row) {
                            var cost = row.unit_price * row.qty;
                            // console.log(cost);
                            total = total + cost;
                            $('#view_totalField').text('Rs. ' + (total / 2).toFixed(2));
                            return row.unit_price * row.qty;
                        }
                    },

                ],
                "bDestroy": true,
                "order": [
                    [2, "desc"]
                ]
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("grnviewDetails") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#suppliername').text(data.result[0].supplier_name);

                    var contacts = '';
                    data.result[0].contacts.forEach(function (contact) {
                        contacts += contact.contact + ' / ';
                    });
                    contacts = contacts.slice(0, -2);
                    $('#suppliercontact').text(contacts);

                    var emails = '';
                    data.result[0].contacts.forEach(function (email) {
                        emails += email.email + ' / ';
                    });
                    emails = emails.slice(0, -2);
                    $('#supplieremail').text(emails);

                    $('#supplieraddress').text(data.result[0].address1 + ', ' + data.result[0]
                        .address2 + ', ' + data.result[0].city);

                        $('#invoiceno').text(data.result[0].invoice_no?'Invoice No: '+data.result[0].invoice_no:'Invoice No: ');
                        $('#dispatchno').text(data.result[0].dispatch_no?'Dispatch No: '+data.result[0].dispatch_no:'Dispatch No: ');
                        $('#viewbatchno').text('Batch No: '+data.result[0].batch_no); 
                }
            })

            $('#viewModal').modal('show');

        });

    });

    $('#porder').change(function () {
        var porderid = $(this).val();
        if (porderid !== '') {
            $.ajax({
                url: '{!! route("getsupplier", ["porderid" => "id_porder"]) !!}'.replace('id_porder',porderid),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#supplier').empty().append(
                        '<option value="">Select Supplier</option>');
                    $.each(data, function (index, supplier) {
                        $('#supplier').append('<option value="' + supplier.id + '">' +
                            supplier.supplier_name + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#supplier').empty().append('<option value="">Select Supplier</option>');
        }
    });

    $('#porder').change(function () {
        var porderid = $(this).val();
        if (porderid !== '') {
            $.ajax({
                url: '{!! route("getitem", ["porderid" => "id_porder"]) !!}'.replace('id_porder',porderid),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#item').empty().append(
                        '<option value="">Select Item</option>');
                    $.each(data, function (index, item) {
                        $('#item').append('<option value="' + item.id + '">' +
                            item.item_name + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
            $('#supplier').empty().append('<option value="">Select Item</option>');
        }
    });

    // get purshase price in select item
    function getpurchaseprice() {
        var itemid = $('#item').val();
        var porder = $('#porder').val();

        if (porder !== "") {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("getpurchasepricetogrn") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: itemid
                },
                success: function (data) {
                    $('#rate').val(data.result.unit_price);
                    $('#qty').val(data.result.qty);
                    // $('#app_empname').val(data.result.emp_name_with_initial);
                }
            })
        }
    }

    // Calculate and update the total sum in the table footer
    function updateTotalSum() {
        var totalSum = 0;

        $('#tableorder tbody tr').each(function () {
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
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
            var totalCell = $(this).find('td:eq(3)'); // Assuming total is in the 4th cell
            var totalValue = parseFloat(totalCell.text());

            if (!isNaN(totalValue)) {
                totalSum += totalValue;
            }
        });

        $('#app_totalField').text(totalSum.toFixed(2));
    }


    function updateStock() {
        // update stocks
        var batch_no = $('#batch_no').val();
        var id = $('#hidden_id').val();

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

            $.ajax({
                method: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    tableData: jsonObj,
                    batchno: batch_no,
                    hidden_id: id,

                },
                url: '{!! route("stockupdate") !!}',
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

    function getbatchno() {
        var supplierID = $('#supplier').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("getbatchno") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: supplierID
            },
            success: function (data) {
                $('#batchno').val('CU-' + data.result);
            }
        })
    }

    function itemsWithoutPorder() {
        var porder = $('#porder').val();
        var supplier = $('#supplier').val();

        if (porder == "") {

            if (supplier !== '') {
                $.ajax({
                    url: '{!! route("getitemwithoutporder", ["supplier" => "id_supplier"]) !!}'.replace('id_supplier',supplier),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, item) {
                            $('#item').append('<option value="' + item.id + '">' +
                                item.item_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            } else {
                $('#supplier').empty().append('<option value="">Select Item</option>');
            }

        }

    }

    function itemsPriceWithoutPorder() {
        var porder = $('#porder').val();
        var itemid = $('#item').val();
        $('#rate').val('');
        $('#qty').val('');

        if (porder == "") {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("getpricewithoutporder") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: itemid
                },
                success: function (data) {
                    $('#rate').val(data.result.purches_price);
                    // $('#app_empname').val(data.result.emp_name_with_initial);
                }
            })
        }

    }

    function edit_porderItemget(porderid){
        if (porderid !== '') {
            $.ajax({
                url: '{!! route("edit_porderItemget", ["porderid" => "id_porder"]) !!}'.replace('id_porder',porderid),
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('#item').empty().append(
                        '<option value="">Select Item</option>');
                    $.each(data, function (index, item) {
                        $('#item').append('<option value="' + item.id + '">' +
                            item.item_name + '</option>');
                    });
                },
                error: function (xhr, status, error) {
                    console.error(error);
                }
            });
        } else {
                $.ajax({
                    url: '{!! route("getitemwithoutporder", ["supplier" => "id_supplier"]) !!}'.replace('id_supplier',supplier),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, item) {
                            $('#item').append('<option value="' + item.id + '">' +
                                item.item_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
        }
    }
    function edit_supplierItemget(porderid,supplier){
        if (porderid == null) {
                $.ajax({
                    url: '{!! route("getitemwithoutporder", ["supplier" => "id_supplier"]) !!}'.replace('id_supplier',supplier),
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        $('#item').empty().append(
                            '<option value="">Select Item</option>');
                        $.each(data, function (index, item) {
                            $('#item').append('<option value="' + item.id + '">' +
                                item.item_name + '</option>');
                        });
                    },
                    error: function (xhr, status, error) {
                        console.error(error);
                    }
                });
            }
    }

    function productDelete(row) {
        $(row).closest('tr').remove();
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
        $("#issuetype").change(function () {
            var selectedOption = $(this).val();
            if (selectedOption === "location") {
                $("#locationDiv").show();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").hide();
                $("#Paymenttype").hide();
            } else if (selectedOption === "employee") {
                $("#locationDiv").hide();
                $("#employeeDiv").show();
                $("#selectTypeFirst").hide();
                $("#Paymenttype").show();
            } else {
                $("#locationDiv").hide();
                $("#employeeDiv").hide();
                $("#selectTypeFirst").show();
                $("#Paymenttype").hide();
            }
        });
    });
</script>
</body>

@endsection