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
                                        <th>Item Code</th>
                                        <th>Item</th>
                                        <th>Unit Price</th>
                                        <th>Qty</th>
                                        <th class="text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="view_tableorderlist"></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 15px">
                                        <td class="text-right" colspan="4">Sub Total:</td>
                                        <td id="view_subtotalField" class="text-right">0</td>
                                    </tr>
                                    <tr style="font-weight: bold;font-size: 15px">
                                        <td class="text-right" colspan="4">Discount:</td>
                                        <td id="view_discountField" class="text-right">0</td>
                                    </tr>
                                    <tr style="font-weight: bold;font-size: 15px">
                                        <td class="text-right" colspan="4">Total:</td>
                                        <td id="view_totalField" class="text-right">0</td>
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
                        <div class="col-12">
                            <span id="form_result"></span>
                            <form method="post" id="formTitle" class="form-horizontal">
                                {{ csrf_field() }}
                               


                                <div class="form-row mb-1">
                                    <div class="col-5">
                                        <div class="form-row mb-1">
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Porder*</label>
                                            <select name="porder" id="porder" class="form-control form-control-sm">
                                                <option value="">Select Porder</option>
                                                @foreach($porders as $porder)
                                                <option value="{{$porder->id}}">POD-0000{{$porder->id}}</option>
                                                @endforeach
                                            </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Order Date*</label>
                                            <input type="date" id="orderdate" name="orderdate"
                                                class="form-control form-control-sm" required>
                                            </div>
                                        </div>
                                        <div class="form-row mb-1">
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Bill Date*</label>
                                                <input type="date" id="billdate" name="billdate"
                                                    class="form-control form-control-sm" required>
                                            </div>
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Batch No*</label>
                                            <input type="text" id="batchno" name="batchno"
                                                class="form-control form-control-sm" required readonly>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="col-5">
                                        <div class="form-row mb-1">
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Supplier*</label>
                                                <select name="supplier" id="supplier" class="form-control form-control-sm"
                                                    required>
                                                    <option value="">Select Supplier</option>
                                                    @foreach($suppliers as $supplier)
                                                    <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Terms*</label>
                                                <input type="text" id="terms" name="terms"
                                                    class="form-control form-control-sm" required readonly>
                                            </div>
                                        </div>
                                        <div class="form-row mb-1">
                                            <div class="col-6">
                                                <label class="small font-weight-bold text-dark">Store*</label>
                                                <select name="store" id="store" class="form-control form-control-sm" required>
                                                    <option value="">Select Store</option>
                                                    @foreach($stores as $store)
                                                    <option value="{{$store->id}}">{{$store->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-6">
                                            </div>
                                        </div>
                                    </div>
                                </div>                
                             
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="edit_tableorder">
                                            <colgroup>
                                                <col width="10%">
                                                <col width="30%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                                <col width="15%">
                                            </colgroup>
                                            <thead>
                                                <tr class="bg-navy disabled">
                                                    <th class="px-1 py-1 text-center">Item Code</th>
                                                    <th class="px-1 py-1 text-center">Item Name</th>
                                                    <th class="px-1 py-1 text-center">UOM</th>
                                                    <th class="px-1 py-1 text-center">Qty</th>
                                                    <th class="px-1 py-1 text-center">Price</th>
                                                    <th class="px-1 py-1 text-center">Total</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody id="edit_tableorderlist"></tbody>
                                            <tfoot>
                                                <tr class="bg-lightblue">
                                                    <tr>
                                                        <th class="p-1 text-right" colspan="5">
                                                            <button class="btn btn btn-sm btn-flat btn-primary py-0 mx-1" type="button" id="add_editrow">Add Row</button>
                                                        <th></th>
                                                    </tr>
                                                    <tr style="font-weight: bold;font-size: 16px">
                                                        <td colspan="5" class="text-right">Sub Total	:</td>
                                                        <td id="edit_subtotal1" class="text-left">0</td>
                                                    </tr>
                                                    <tr style="font-weight: bold;font-size: 16px">
                                                        <td colspan="5" class="text-right">Discount:</td>
                                                        <td class="text-left"><input type="number" id="edit_discount1" value="0" onkeyup="editdiscount(this.value)"/></td>
                                                    </tr>
                                                    <tr style="font-weight: bold;font-size: 16px">
                                                        <td colspan="5" class="text-right">Total:</td>
                                                        <td id="edit_total1" class="text-left">0</td>
                                                    </tr>
                                                </tr>
                                            </tfoot>
                                        </table>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="notes" class="control-label">Remark</label>
                                                <textarea name="remark" id="remark" cols="10" rows="4" class="form-control rounded-0"></textarea>
                                            </div>    
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-3">
                                    <button type="button" id="formsubmit" 
                                        class="btn btn-primary btn-sm px-4 float-right"><i
                                            class="fas fa-plus"></i>&nbsp;Add to list</button>
                                    <input name="submitBtn" type="submit" value="Save" id="submitBtn" class="d-none">
                                </div>
                                <input type="hidden" name="action" id="action" value="Add" />
                                <input type="hidden" name="hidden_id" id="hidden_id" />
                                <input type="hidden" name="oprderdetailsid" id="oprderdetailsid">
                                <input type="hidden" name="totalcost" id="totalcost">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="editmodal-title" id="staticBackdropLabel">Edit Good Receive Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <span id="editform_result"></span>
                        <form method="post" id="editformTitle" class="form-horizontal">
                            {{ csrf_field() }}
                           


                            <div class="form-row mb-1">
                                <div class="col-5">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Porder*</label>
                                        <select name="edit_porder" id="edit_porder" class="form-control form-control-sm" readonly>
                                            <option value="">Select Porder</option>
                                            @foreach($porders as $porder)
                                            <option value="{{$porder->id}}">POD-0000{{$porder->id}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="edit_orderdate" name="edit_orderdate"
                                            class="form-control form-control-sm" required>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Bill Date*</label>
                                            <input type="date" id="edit_billdate" name="edit_billdate"
                                                class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Batch No*</label>
                                        <input type="text" id="edit_batchno" name="edit_batchno"
                                            class="form-control form-control-sm" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-5">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Supplier*</label>
                                            <select name="edit_supplier" id="edit_supplier" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Terms*</label>
                                            <input type="text" id="edit_terms" name="edit_terms"
                                                class="form-control form-control-sm" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Store*</label>
                                            <select name="edit_store" id="edit_store" class="form-control form-control-sm" required>
                                                <option value="">Select Store</option>
                                                @foreach($stores as $store)
                                                <option value="{{$store->id}}">{{$store->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                        </div>
                                    </div>
                                </div>
                            </div>                
                         
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="edit_tableorder2">
                                        <colgroup>
                                            <col width="10%">
                                            <col width="30%">
                                            <col width="15%">
                                            <col width="15%">
                                            <col width="15%">
                                            <col width="15%">
                                        </colgroup>
                                        <thead>
                                            <tr class="bg-navy disabled">
                                                <th class="px-1 py-1 text-center">Item Code</th>
                                                <th class="px-1 py-1 text-center">Item Name</th>
                                                <th class="px-1 py-1 text-center">UOM</th>
                                                <th class="px-1 py-1 text-center">Qty</th>
                                                <th class="px-1 py-1 text-center">Price</th>
                                                <th class="px-1 py-1 text-center">Total</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="edit_tableorderlist2"></tbody>
                                        <tfoot>
                                            <tr class="bg-lightblue">
                                                <tr>
                                                    <th class="p-1 text-right" colspan="5">
                                                        <button class="btn btn btn-sm btn-flat btn-primary py-0 mx-1" type="button" id="editadd_editrow">Add Row</button>
                                                    <th></th>
                                                </tr>
                                                <tr style="font-weight: bold;font-size: 16px">
                                                    <td colspan="5" class="text-right">Sub Total	:</td>
                                                    <td id="edit_subtotal2" class="text-left">0</td>
                                                </tr>
                                                <tr style="font-weight: bold;font-size: 16px">
                                                    <td colspan="5" class="text-right">Discount:</td>
                                                    <td class="text-left"><input type="number" id="edit_discount2" value="0" onkeyup="editdiscount2(this.value)"/></td>
                                                </tr>
                                                <tr style="font-weight: bold;font-size: 16px">
                                                    <td colspan="5" class="text-right">Total:</td>
                                                    <td id="edit_total2" class="text-left">0</td>
                                                </tr>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="notes" class="control-label">Remark</label>
                                            <textarea name="edit_remark" id="edit_remark" cols="10" rows="4" class="form-control rounded-0"></textarea>
                                        </div>    
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-3"> 
                                <input name="submitBtn" type="submit" value="Save" id="updatesubmitBtn" class="d-none">
                                <button type="button" name="Btnupdatelist" id="Btnupdatelist"
                                    class="btn btn-primary btn-sm px-4 fa-pull-right"><i
                                        class="fas fa-plus"></i>&nbsp;Update List</button>
                            </div>
                            <input type="hidden" name="action" id="action" value="Add" />
                            <input type="hidden" name="edit_hidden_id" id="edit_hidden_id" />
                            <input type="hidden" name="oprderdetailsid" id="oprderdetailsid">
                            <input type="hidden" name="totalcost" id="totalcost">
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

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header p-2">
                <h5 class="approvemodal-title" id="staticBackdropLabel">Approve Good Receive Note</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <span id="approveform_result"></span>
                        <form method="post" id="approveformTitle" class="form-horizontal">
                            {{ csrf_field() }}
                           


                            <div class="form-row mb-1">
                                <div class="col-5">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Porder*</label>
                                        <select name="app_porder" id="app_porder" class="form-control form-control-sm" readonly>
                                            <option value="">Select Porder</option>
                                            @foreach($porders as $porder)
                                            <option value="{{$porder->id}}">POD-0000{{$porder->id}}</option>
                                            @endforeach
                                        </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="app_orderdate" name="app_orderdate"
                                            class="form-control form-control-sm" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Bill Date*</label>
                                            <input type="date" id="app_billdate" name="app_billdate"
                                                class="form-control form-control-sm" required readonly>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Batch No*</label>
                                        <input type="text" id="app_batchno" name="app_batchno"
                                            class="form-control form-control-sm" required readonly>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <div class="col-5">
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Supplier*</label>
                                            <select name="app_supplier" id="app_supplier" class="form-control form-control-sm"
                                                required readonly>
                                                <option value="">Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Terms*</label>
                                            <input type="text" id="app_terms" name="app_terms"
                                                class="form-control form-control-sm" required readonly>
                                        </div>
                                    </div>
                                    <div class="form-row mb-1">
                                        <div class="col-6">
                                            <label class="small font-weight-bold text-dark">Store*</label>
                                            <select name="app_store" id="app_store" class="form-control form-control-sm" readonly required>
                                                <option value="">Select Store</option>
                                                @foreach($stores as $store)
                                                <option value="{{$store->id}}">{{$store->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-6">
                                        </div>
                                    </div>
                                </div>
                            </div>                
                         
                            <hr>
                            <div class="row">
                                <div class="col-md-12">
                                    <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="app_tableorder">
                                        <colgroup>
                                            <col width="10%">
                                            <col width="30%">
                                            <col width="15%">
                                            <col width="15%">
                                            <col width="15%">
                                            <col width="15%">
                                        </colgroup>
                                        <thead>
                                            <tr class="bg-navy disabled">
                                                <th class="px-1 py-1 text-center">Item Code</th>
                                                <th class="px-1 py-1 text-center">Item Name</th>
                                                <th class="px-1 py-1 text-center">UOM</th>
                                                <th class="px-1 py-1 text-center">Qty</th>
                                                <th class="px-1 py-1 text-center">Price</th>
                                                <th class="px-1 py-1 text-center">Total</th>
                                                
                                            </tr>
                                        </thead>
                                        <tbody id="app_tableorderlist"></tbody>
                                        <tfoot>
                                            <tr class="bg-lightblue">
                                                <tr>
                                                    
                                                </tr>
                                                <tr style="font-weight: bold;font-size: 16px">
                                                    <td colspan="5" class="text-right">Sub Total	:</td>
                                                    <td id="app_subtotal" class="text-right">0</td>
                                                </tr>
                                                <tr style="font-weight: bold;font-size: 16px">
                                                    <td colspan="5" class="text-right">Discount:</td>
                                                    <td id="app_discount" class="text-right"></td>
                                                </tr>
                                                <tr style="font-weight: bold;font-size: 16px">
                                                    <td colspan="5" class="text-right">Total:</td>
                                                    <td id="app_total" class="text-right">0</td>
                                                </tr>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="notes" class="control-label">Remark</label>
                                            <textarea name="app_remark" id="app_remark" cols="10" rows="4" class="form-control rounded-0" readonly></textarea>
                                        </div>    
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer p-2">
                                <button type="button" name="approve_button" id="approve_button"
                                    class="btn btn-warning px-3 btn-sm">Approve</button>
                                <button type="button" class="btn btn-dark px-3 btn-sm" name="reject_button" id="reject_button" data-dismiss="modal">Reject</button>
                            </div>
                            <input type="hidden" name="app_porder_id" id="app_porder_id" />
                            <input type="hidden" name="batch_no" id="batch_no" />
                            <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                            <input type="hidden" name="app_level" id="app_level" value="1" />
                        </form>
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
// insert part
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
               // $('#formsubmit').prop('disabled', true).html(
                // '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');
                var rowDataArray = [];
                var error = false;

                $('#edit_tableorderlist tr').each(function () {

                var rowData = {
                            itemCode: $(this).find("[name='edit_inventorylist_id[]']").val(),
                            itemName: $(this).find("[name='edit_inventorylist_select[]']").val(),
                            unit: $(this).find("[name='edit_uom[]']").val(),
                            qty: $(this).find("[name='edit_qty[]']").val(),
                            unitPrice: $(this).find("[name='edit_unit_price[]']").val(),
                            total: $(this).find("[name='edit_total[]']").val(),
                            edit_insertstatus: $(this).find("[name='edit_insertstatus[]']").val(),
                            porderdetail_id: $(this).find("[name='porderdetail_id[]']").val()

                        };

                        // Check for empty fields
                        for (var key in rowData) {
                            if (rowData[key] === "") {
                                error = true;
                                $(this).find("[name='" + key + "']").addClass("is-invalid");
                            }
                        }

                        if (!error) {
                            $(this).find("input.is-invalid").removeClass("is-invalid");
                            rowDataArray.push(rowData);
                        }
                    });
                    if (!error) {
                        var porder = $('#porder').val();
                        var orderdate = $('#orderdate').val();
                        var billdate = $('#billdate').val();
                        var batchno = $('#batchno').val();
                        var supplier = $('#supplier').val();
                        var terms = $('#terms').val();
                        var store = $('#store').val();
                        var sub_total = parseFloat($('#edit_subtotal1').text());
                        var discount = parseFloat($('#edit_discount1').val());
                        var total = parseFloat($('#edit_total1').text());
                        var remark = $('#remark').val();
                        // console.log(porder,orderdate,billdate,batchno,supplier,terms,store,remark,sub_total,discount,total);
                        // console.log(rowDataArray);
                        action_url = "{{ route('grninsert') }}";
                        $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DataArray: rowDataArray,
                        porder: porder,
                        orderdate: orderdate,
                        billdate: billdate,
                        batchno: batchno,
                        supplier: supplier,
                        terms: terms,
                        store: store,
                        remark: remark,
                        sub_total: sub_total,
                        discount: discount,
                        net_total: total,

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


                    } else {
                        alert("Please fill in all required fields.");
                    }
            }
        });


        // update part
        $("#Btnupdatelist").click(function () {
            if (!$("#editformTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#updatesubmitBtn").click();
            } else {
               // $('#formsubmit').prop('disabled', true).html(
                // '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');
                var rowDataArray = [];
                var error = false;

                $('#edit_tableorderlist2 tr').each(function () {

                var rowData = {
                            itemCode: $(this).find("[name='edit1_inventorylist_id[]']").val(),
                            itemName: $(this).find("[name='edit1_inventorylist_select[]']").val(),
                            unit: $(this).find("[name='edit1_uom[]']").val(),
                            qty: $(this).find("[name='edit1_qty[]']").val(),
                            unitPrice: $(this).find("[name='edit1_unit_price[]']").val(),
                            total: $(this).find("[name='edit1_total[]']").val(),
                            edit_insertstatus: $(this).find("[name='edit1_insertstatus[]']").val(),
                            grndetail_id: $(this).find("[name='edit1_grndetail_id[]']").val(),
                            porderdetail_id: $(this).find("[name='edit1_porderdetail_id[]']").val()

                        };

                        // Check for empty fields
                        for (var key in rowData) {
                            if (rowData[key] === "") {
                                error = true;
                                $(this).find("[name='" + key + "']").addClass("is-invalid");
                            }
                        }

                        if (!error) {
                            $(this).find("input.is-invalid").removeClass("is-invalid");
                            rowDataArray.push(rowData);
                        }
                    });
                    if (!error) {
                        var porder = $('#edit_porder').val();
                        var orderdate = $('#edit_orderdate').val();
                        var billdate = $('#edit_billdate').val();
                        var batchno = $('#edit_batchno').val();
                        var supplier = $('#edit_supplier').val();
                        var terms = $('#edit_terms').val();
                        var store = $('#edit_store').val();
                        var sub_total = parseFloat($('#edit_subtotal2').text());
                        var discount = parseFloat($('#edit_discount2').val());
                        var total = parseFloat($('#edit_total2').text());
                        var remark = $('#edit_remark').val();
                        var hidden_id = $('#edit_hidden_id').val();
                        // console.log(porder,orderdate,billdate,batchno,supplier,terms,store,remark,sub_total,discount,total,hidden_id);
                        // console.log(rowDataArray);
                        action_url = "{{ route('grnupdate') }}";
                        $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DataArray: rowDataArray,
                        porder: porder,
                        orderdate: orderdate,
                        billdate: billdate,
                        batchno: batchno,
                        supplier: supplier,
                        terms: terms,
                        store: store,
                        remark: remark,
                        sub_total: sub_total,
                        discount: discount,
                        net_total: total,
                        hidden_id:hidden_id,

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
                            $('#editformTitle')[0].reset();
                            //$('#titletable').DataTable().ajax.reload();
                            window.location.reload(); // Use window.location.reload()
                        }

                        $('#editform_result').html(html);
                        // resetfield();

                    }
                });


                    } else {
                        alert("Please fill in all required fields.");
                    }
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
            var porder_id = $(this).attr('porder_id');
            resetfield();

            if(porder_id=='' || porder_id==null){
                $('#editform_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("grneditwithoutporder") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
            }).done(function(data) {
                    $('#edit_porder').val(data.result.mainData.porder_id);
                    $('#edit_orderdate').val(data.result.mainData.grn_date);
                    $('#edit_billdate').val(data.result.mainData.bill_date);
                    $('#edit_batchno').val(data.result.mainData.batch_no);
                    $('#edit_supplier').val(data.result.mainData.supplier_id);
                    $('#edit_terms').val(data.result.mainData.terms);
                    $('#edit_store').val(data.result.mainData.store_id);
                    $('#edit_remark').val(data.result.mainData.remark);

                    $('#edit_tableorderlist2').html(data.result.requestdata);
                    $('#edit_discount2').val(data.result.mainData.discount);
                    updateEditTotal1();

                    $('#edit_hidden_id').val(id);
                    $('#editadd_editrow').show();
                    $('#editModal').modal('show');
                });
            }
            else{
                $('#editform_result').html('');
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
            }).done(function(data) {
                    $('#edit_porder').val(data.result.mainData.porder_id);
                    $('#edit_orderdate').val(data.result.mainData.grn_date);
                    $('#edit_billdate').val(data.result.mainData.bill_date);
                    $('#edit_batchno').val(data.result.mainData.batch_no);
                    $('#edit_supplier').val(data.result.mainData.supplier_id);
                    $('#edit_terms').val(data.result.mainData.terms);
                    $('#edit_store').val(data.result.mainData.store_id);
                    $('#edit_remark').val(data.result.mainData.remark);

                    $('#edit_tableorderlist2').html(data.result.requestdata);
                    $('#edit_discount2').val(data.result.mainData.discount);
                    updateEditTotal1();

                    $('#edit_hidden_id').val(id);
                    $('#editadd_editrow').hide();
                    $('#editModal').modal('show');
                });
            }
        });

        function resetfield() {
            $('#edit_orderdate').val('');
            $('#edit_billdate').val('');
            $('#edit_batchno').val('');
            $('#edit_porder').val('');
            $('#edit_supplier').val('');
            $('#edit_terms').val('');
            $('#edit_store').val('');
            $('#edit_remark').val('');
        }

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
                    $('#app_porder').val(data.result.mainData.porder_id);
                    $('#app_orderdate').val(data.result.mainData.grn_date);
                    $('#app_billdate').val(data.result.mainData.bill_date);
                    $('#app_batchno').val(data.result.mainData.batch_no);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_terms').val(data.result.mainData.terms);
                    $('#app_store').val(data.result.mainData.store_id);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    $('#app_subtotal').text(data.result.mainData.sub_total.toFixed(2));
                    $('#app_discount').text(data.result.mainData.discount.toFixed(2));
                    $('#app_total').text(data.result.mainData.total.toFixed(2));

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
                url: '{!! route("grndetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_porder').val(data.result.mainData.porder_id);
                    $('#app_orderdate').val(data.result.mainData.grn_date);
                    $('#app_billdate').val(data.result.mainData.bill_date);
                    $('#app_batchno').val(data.result.mainData.batch_no);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_terms').val(data.result.mainData.terms);
                    $('#app_store').val(data.result.mainData.store_id);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    $('#app_subtotal').text(data.result.mainData.sub_total.toFixed(2));
                    $('#app_discount').text(data.result.mainData.discount.toFixed(2));
                    $('#app_total').text(data.result.mainData.total.toFixed(2));

                    $('#app_hidden_id').val(id_approve);
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
                    $('#app_porder').val(data.result.mainData.porder_id);
                    $('#app_orderdate').val(data.result.mainData.grn_date);
                    $('#app_billdate').val(data.result.mainData.bill_date);
                    $('#app_batchno').val(data.result.mainData.batch_no);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_terms').val(data.result.mainData.terms);
                    $('#app_store').val(data.result.mainData.store_id);
                    $('#app_remark').val(data.result.mainData.remark);

                    $('#app_tableorderlist').html(data.result.requestdata);
                    $('#app_subtotal').text(data.result.mainData.sub_total.toFixed(2));
                    $('#app_discount').text(data.result.mainData.discount.toFixed(2));
                    $('#app_total').text(data.result.mainData.total.toFixed(2));

                    $('#app_hidden_id').val(id_approve);
                    $('#app_level').val('3');
                    $('#approveconfirmModal').modal('show');
                }
            })


        });


        $('#approve_button').click(function () {
            var id_hidden = $('#app_hidden_id').val();
            var applevel = $('#app_level').val();
            var porder_id = $('#app_porder').val();
            var batchno = $('#app_batchno').val();
            var store_id = $('#app_store').val();
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
                        // alert('Data Approved');
                    }, 2000);
                    location.reload()
                }
            })

            if (applevel == 3) {
                updateStock(store_id,batchno);
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
                        // alert('Data Rejected');
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
                columns: [
                    {
                        data: 'itemcode',
                        name: 'itemcode'
                    },
                    {
                        data: 'inventoryname',
                        render: function (data, type, row) {
                            var inventoryname = row.inventoryname;
                            var uniform_size = (row.uniform_size==null?"":row.uniform_size+'"');
                            return inventoryname + ' ' + uniform_size;
                        }
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
                        data: 'total',
                        name: 'total',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                // Format the number to 2 decimal places and align it to the right
                                return parseFloat(data).toFixed(2);
                            }
                            return data;
                        },
                        className: 'text-right' // Apply a CSS class for right alignment
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
                    $('#view_subtotalField').text((data.result[0].sub_total).toFixed(2)),
                    $('#view_discountField').text((data.result[0].discount).toFixed(2)),
                    $('#view_totalField').text((data.result[0].total).toFixed(2)),
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

    $(document).ready(function () {
        var porderid = $('#porder').val();
        if (porderid == '') {
            $('#supplier').change(function () {
                    var supplierID = $(this).val();

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
                            $('#terms').val(data[0]);
                        }
                })
                    });
        }
    });

    $('#porder').change(function () {
        var porderid = $(this).val();
        if (porderid !== '') {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
            url: '{!! route("grnporderdetails") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: porderid
            },
        }).done(function(data) {
            $('#supplier').val(data.result.mainData.supplier_name).prop('disabled', true);
            $('#terms').val(data.result.mainData.payment_terms);
            $('#store').val(data.result.mainData.storename).prop('disabled', true);
            getbatchno(data.result.mainData.supplier_name);
            $('#edit_discount1').val(data.result.mainData.discount_amount);
            $('#edit_tableorderlist').html(data.result.requestdata);

            $('#add_editrow').hide();
            $('#edit_discount1').css('border', 'none').prop('readonly', false);

            updateEditTotal();
        });
        } else {
                    $('#edit_tableorderlist').empty();
                    $('#edit_subtotal1').text(0);
                    $('#edit_total1').text(0);

                    $('#supplier').val('').prop('disabled', false);
                    $('#terms').val('');
                    $('#store').val('').prop('disabled', false);
                    $('#batchno').val('');
                    $('#add_editrow').show();
                    $('#edit_discount1').val(0).css('border', '1px solid').prop('readonly', false);


                    $('#supplier').change(function () {
                    var supplierID = $(this).val();

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
                            $('#terms').val(data[0]);
                        }
                })
                    });

                }
    });

    
    let editrowCounter =1000;
    // Function to add a new row
    function addNewRow() {
        const newRow = `
            <tr>
                <td><input style="width:100px;border:none;" type="text" name="edit_inventorylist_id[]" id="inventorylist_id${editrowCounter}" value="" readonly></td>
                <td>
                    <select required name="edit_inventorylist_select[]" id="inventorylist_select${editrowCounter}" onchange="getItemeditDetails(this.value, ${editrowCounter})" size="1" onfocus="this.size = 8" onchange="this.blur()" onblur="this.size = 1; this.blur()">
                        <option value="">Select Inventory Item</option>
                        @foreach($items as $item)
                         <option value="{{$item->id}}">{{$item->inventorylist_id}}-{{$item->name}} {{($item->uniform_size==null?"":$item->uniform_size.'"')}}</option>
                        @endforeach
                    </select>
                </td>
                <td><input style="width:100px;border:none;" type="text" name="edit_uom[]" id="uom${editrowCounter}" value="" readonly></td>
                <td><input style="width:100px" type="number" name="edit_qty[]" id="qty${editrowCounter}" value="0" onkeyup="editsum(this.value, ${editrowCounter})"></td>
                <td><input style="width:100%" type="number" name="edit_unit_price[]" id="unit_price${editrowCounter}" value="0" onkeyup="editsum(this.value, ${editrowCounter})"></td>
                <td><input style="width:100%;border:none;" type="text" name="edit_total[]" id="total${editrowCounter}" value="" readonly></td>  
                <td class="d-none"><input type="text" name="edit_insertstatus[]" id="edit_insertstatus${editrowCounter}" value="NewData"></td>  
                <td class="d-none"><input type="text" name="porderdetail_id[]" id="porderdetail_id${editrowCounter1}" value="0"></td>  
                <td><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>  
            </tr>
        `;

        // Append the new row to the table
        $('#edit_tableorderlist').append(newRow);

        // Increment the row counter
        editrowCounter++;
    }

    // Attach a click event handler to the "Add Row" button
    $('#add_editrow').click(function () {
        addNewRow();
    });

    function getItemeditDetails(item_code, rowCounter) {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
    // console.log(item_code, rowCounter);
    $.ajax({
        url: '{!! route("pordergetitemdetail") !!}',
        type: 'POST',
        dataType: "json",
        data: {
            id: item_code
        },
        success: function (data) {
            $('#inventorylist_id' + rowCounter).val(data.result[0].inventorylist_id);
            $('#uom' + rowCounter).val(data.result[0].uom);
        }
    });
}

    


    function updateStock(store_id,batch_no) {
        // update stocks

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
            //  console.log(store_id,batch_no);
            $.ajax({
                method: "POST",
                dataType: "json",
                data: {
                    _token: '{{ csrf_token() }}',
                    tableData: jsonObj,
                    batchno: batch_no,
                    store_id: store_id,

                },
                url: '{!! route("grnstockupdate") !!}',
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

    function getbatchno(supplierID) {

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
    // insert part calculation
    function editsum(value, rowCounter){
    var qty = parseFloat($('#qty' + rowCounter).val());
var unitPrice = parseFloat($('#unit_price' + rowCounter).val());
var presubtotal = parseFloat($('#total' + rowCounter).val());

var total=qty*unitPrice;
$('#total' + rowCounter).val(total)

updateEditTotal();
}

function updateEditTotal(){
    let grandTotal = 0;
    $('input[name="edit_total[]"]').each(function() {
        const value = parseFloat($(this).val()) || 0;
        grandTotal += value;
    });

    $('#edit_subtotal1').text(grandTotal);

    var discount = parseFloat($('#edit_discount1').val());
    var newsubtotal = parseFloat($('#edit_subtotal1').text());
    var newdiscount = newsubtotal-discount;
    $('#edit_total1').text(newdiscount);
}

function editdiscount(value){
    var subtotal=parseFloat($('#edit_subtotal1').text());
var nettotal=subtotal-value;
$('#edit_total1').text(nettotal);
}
    



// edit row add
let editrowCounter1 =1000;
    // Function to add a new row
    function editaddNewRow() {
        const newRow = `
            <tr>
                <td><input style="width:100px;border:none;" type="text" name="edit1_inventorylist_id[]" id="edit1_inventorylist_id${editrowCounter1}" value="" readonly></td>
                <td>
                    <select required name="edit1_inventorylist_select[]" id="edit1_inventorylist_select${editrowCounter1}" onchange="getItemeditDetailsEdit(this.value, ${editrowCounter1})" size="1" onfocus="this.size = 8" onchange="this.blur()" onblur="this.size = 1; this.blur()">
                        <option value="">Select Inventory Item</option>
                        @foreach($items as $item)
                         <option value="{{$item->id}}">{{$item->inventorylist_id}}-{{$item->name}} {{($item->uniform_size==null?"":$item->uniform_size.'"')}}</option>
                        @endforeach
                    </select>
                </td>
                <td><input style="width:100px;border:none;" type="text" name="edit1_uom[]" id="edit1_uom${editrowCounter1}" value="" readonly></td>
                <td><input style="width:100px" type="number" name="edit1_qty[]" id="edit1_qty${editrowCounter1}" value="0" onkeyup="editsum1(this.value, ${editrowCounter1})"></td>
                <td><input style="width:100%" type="number" name="edit1_unit_price[]" id="edit1_unit_price${editrowCounter1}" value="0" onkeyup="editsum1(this.value, ${editrowCounter1})"></td>
                <td><input style="width:100%;border:none;" type="text" name="edit1_total[]" id="edit1_total${editrowCounter1}" value="" readonly></td>  
                <td class="d-none"><input type="text" name="edit1_insertstatus[]" id="edit1_insertstatus${editrowCounter1}" value="NewData"></td>  
                <td class="d-none"><input type="text" name="edit1_porderdetail_id[]" id="edit1_porderdetail_id${editrowCounter1}" value="0"></td>  
                <td><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>  
            </tr>
        `;

        // Append the new row to the table
        $('#edit_tableorderlist2').append(newRow);

        // Increment the row counter
        editrowCounter1++;
    }

    // Attach a click event handler to the "Add Row" button
    $('#editadd_editrow').click(function () {
        editaddNewRow();
    });

// edit details get
    function getItemeditDetailsEdit(item_code, rowCounter) {
        $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
    // console.log(item_code, rowCounter);
    $.ajax({
        url: '{!! route("pordergetitemdetail") !!}',
        type: 'POST',
        dataType: "json",
        data: {
            id: item_code
        },
        success: function (data) {
            $('#edit1_inventorylist_id' + rowCounter).val(data.result[0].inventorylist_id);
            $('#edit1_uom' + rowCounter).val(data.result[0].uom);
        }
    });
}
    
// edit part calculation
function editsum1(value, rowCounter){
    var qty = parseFloat($('#edit1_qty' + rowCounter).val());
var unitPrice = parseFloat($('#edit1_unit_price' + rowCounter).val());
var presubtotal = parseFloat($('#edit1_total' + rowCounter).val());

var total=qty*unitPrice;
$('#edit1_total' + rowCounter).val(total)

updateEditTotal1();
}

function updateEditTotal1(){
    let grandTotal = 0;
    $('input[name="edit1_total[]"]').each(function() {
        const value = parseFloat($(this).val()) || 0;
        grandTotal += value;
    });

    $('#edit_subtotal2').text(grandTotal);

    var discount = parseFloat($('#edit_discount2').val());
    var newsubtotal = parseFloat($('#edit_subtotal2').text());
    var newdiscount = newsubtotal-discount;
    $('#edit_total2').text(newdiscount);
}

function editdiscount2(value){
    var subtotal=parseFloat($('#edit_subtotal2').text());
var nettotal=subtotal-value;
$('#edit_total2').text(nettotal);
}


function rem_item(_this){
		_this.closest('tr').remove()
        updateEditTotal();
        updateEditTotal1();
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