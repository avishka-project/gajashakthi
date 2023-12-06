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
                        @if(in_array('Porder-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Create Purchase Order</button>
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
                                    <th>Date </th>
                                    <th>Supplier</th>
                                    <th>Total</th>
                                    <th>Confirm Status</th>
                                    <th>GRN Issue Status</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Add Stock</h5>
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
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="orderdate" name="orderdate"
                                            class="form-control form-control-sm" required onchange="getVatDateInputChange();">
                                    </div>

                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Deliver Date*</label>
                                        <input type="date" id="duedate" name="duedate"
                                            class="form-control form-control-sm" required>
                                    </div>
                                        <div class="col-3">
                                            <label class="small font-weight-bold text-dark">Supplier*</label>
                                            <select name="supplier" id="supplier" class="form-control form-control-sm"
                                                required>
                                                <option value="">Select Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
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
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Employee*</label><br>
                                        <select name="employee" id="employee"
                                            class="form-control form-control-sm custom-select-width">
                                            <option value="">Select Employee</option>

                                        </select>
                                    </div>
                            </div>              
                             
                                <hr>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="center-block fix-width scroll-inner">
                                        <table class="table table-striped table-bordered table-sm small nowrap display"  id="item-list">
                                            <colgroup>
                                                <col width="10%">
                                                <col width="25%">
                                                <col width="10%">
                                                <col width="10%">
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
                                                    <th class="px-1 py-1 text-center">Vat(%)</th>
                                                    <th class="px-1 py-1 text-center">Vat(Amount)</th>
                                                    <th class="px-1 py-1 text-center">Vat+Total</th>
                                                    <th class="px-1 py-1 text-center">Action</th>
                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="po-item" data-id="">
                                                    <td class="align-middle p-1">  
                                                        <input type="text" id="item_code" name="item_code" required class="text-center w-100 border-0 item_code" readonly/>
                                                    </td>
                                                    <td class="align-middle p-1">
                                                        <select id="item_name" name="item_name" required class="text-center w-100 border-0 item_name">
                                                            <option value="">Select Inventory Item</option>
                                                            @foreach($items as $item)
                                                            <option value="{{$item->id}}">{{$item->inventorylist_id}}-{{$item->name}}</option>
                                                            @endforeach
                                                            </select>
                                                    </td>
                                                    <td class="align-middle p-1">
                                                        <input type="text" class="text-center w-100 border-0" name="unit[]" id="unit"readonly/>
                                                    </td>
                                                    <td class="align-middle p-0 text-center">
                                                        <input type="number" class="text-center w-100 border-0" step="any" name="qty[]"/>
                                                    </td>                                                
                                                    <td class="align-middle p-1">
                                                        <input type="number" step="any" class="text-right w-100 border-0" name="unit_price[]"/>
                                                    </td>
                                                    <td class="align-middle p-1 text-right total-price">0</td>
                                                    <td class="align-middle p-1 text-right">
                                                        <input type="number" step="any" class="text-right w-100 border-0" name="vatprecentage[]" value="0"/>
                                                    </td>
                                                    <td id="vatamount" name="vatamount[]" class="align-middle p-1 text-right vatamount">0</td>
                                                    <td id="aftervat" name="aftervat[] "class="align-middle p-1 text-right aftervat">0</td>
                                                    <td class="align-middle p-1 text-center">
                                                        <button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button>
                                                    </td>

                                                    <input type="hidden" name="item_id[]">
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr class="bg-lightblue">
                                                    <tr>
                                                        <th class="p-1 text-right" colspan="8"><span><button class="btn btn btn-sm btn-flat btn-primary py-0 mx-1" type="button" id="add_row">Add Row</button></span> Sub Total</th>
                                                        <th class="p-1 text-right" id="sub_total">0</th>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="p-1 text-right" colspan="8">Discount</th>
                                                        <th><input type="number" step="any" name="discount_amount" id="discount_amount" class="border-light text-right" value="">
                                                        </th>
                                                        <th class="p-1" id="discount"></th>
                                                    </tr>
                                                    <tr>
                                                        {{-- <th class="p-1 text-right" colspan="5">Tax Inclusive (%) --}}
                                                        <input type="hidden" step="any" name="tax_percentage" class="border-light text-right" value="">
                                                        </th>
                                                        <th class="p-1"><input type="hidden" class="w-100 border-0 text-right" readonly value="" name="tax_amount"></th>
                                                        <th></th>
                                                    </tr>
                                                    <tr>
                                                        <th class="p-1 text-right" colspan="8">Total</th>
                                                        <th class="p-1 text-right" id="total">0</th>
                                                        <th></th>
                                                    </tr>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
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
                                    <button type="button" name="Btnupdatelist" id="Btnupdatelist"
                                        class="btn btn-primary btn-sm px-4 fa-pull-right" style="display:none;"><i
                                            class="fas fa-plus"></i>&nbsp;Update List</button>
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

    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="editmodal-title" id="staticBackdropLabel">Edit Purchase Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal" id="editformTitle">
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="edit_orderdate" name="edit_orderdate"
                                            class="form-control form-control-sm" required onchange="editgetVatDateInputChange();">
                                    </div>

                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Delivery Date*</label>
                                        <input type="date" id="edit_duedate" name="edit_duedate"
                                            class="form-control form-control-sm" required>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Supplier*</label>
                                        <select name="edit_supplier" id="edit_supplier"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Store*</label>
                                        <select name="edit_store" id="edit_store"
                                            class="form-control form-control-sm" required>
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">  
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Employee*</label><br>
                                        <select name="edit_employee" id="edit_employee"
                                            class="form-control form-control-sm custom-select-width">

                                        </select>
                                    </div>
                            </div>  
                                <br>
                                <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%" id="edit_tableorder">
                                <colgroup>
                                    <col width="10%">
                                    <col width="25%">
                                    <col width="10%">
                                    <col width="10%">
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
                                        <th class="px-1 py-1 text-center">Vat(%)</th>
                                        <th class="px-1 py-1 text-center">Vat(Amount)</th>
                                        <th class="px-1 py-1 text-center">Vat+Total</th>
                                        <th class="px-1 py-1 text-center">Action</th>
                                        
                                    </tr>
                                </thead>
                                <tbody id="edit_tableorderlist"></tbody>
                                <tfoot>
                                    <tr class="bg-lightblue">
                                        <tr>
                                            <th class="p-1 text-right" colspan="8">
                                                <button class="btn btn btn-sm btn-flat btn-primary py-0 mx-1" type="button" id="add_editrow">Add Row</button>
                                            <th></th>
                                        </tr>
                                        <tr style="font-weight: bold;font-size: 16px">
                                            <td colspan="8" class="text-right">Sub Total	:</td>
                                            <td id="edit_subtotal1" class="text-right">0</td>
                                        </tr>
                                        <tr style="font-weight: bold;font-size: 16px">
                                            <td colspan="8" class="text-right">Discount:</td>
                                            <td class="text-right"><input id="edit_discount1" onkeyup="editdiscount(this.value)"/></td>
                                        </tr>
                                        <tr style="font-weight: bold;font-size: 16px">
                                            <td colspan="8" class="text-right">Total:</td>
                                            <td id="edit_total1" class="text-right">0</td>
                                        </tr>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Remark*</label>
                                    <textarea type="text" id="edit_remark" name="edit_remark"
                                        class="form-control form-control-sm" ></textarea>
                                </div>
                            </div>
                        </div>
                        <input name="editsubmitBtn" type="submit" value="Save" id="editsubmitBtn" class="d-none">
                        <input type="hidden" name="edit_hidden_id" id="edit_hidden_id" />

                        </form>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="update_button" id="update_button"
                        class="btn btn-warning px-3 btn-sm">Update</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">Approve Purchase Order Details</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <form class="form-horizontal">
                                <div class="form-row mb-1">
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Order Date*</label>
                                        <input type="date" id="app_orderdate" name="app_orderdate"
                                            class="form-control form-control-sm" readonly>
                                    </div>

                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Delivery Date*</label>
                                        <input type="date" id="app_duedate" name="app_duedate"
                                            class="form-control form-control-sm" readonly>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Supplier*</label>
                                        <select name="app_supplier" id="app_supplier"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Supplier</option>
                                            @foreach($suppliers as $supplier)
                                            <option value="{{$supplier->id}}">{{$supplier->supplier_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Store*</label>
                                        <select name="app_store" id="app_store"
                                            class="form-control form-control-sm" readonly>
                                            <option value="">Select Store</option>
                                            @foreach($stores as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">  
                                    <div class="col-3">
                                        <label class="small font-weight-bold text-dark">Employee*</label><br>
                                        <select name="app_employee" id="app_employee"
                                            class="form-control form-control-sm custom-select-width" readonly>

                                        </select>
                                    </div>
                            </div>  
                                <br>
                              
                            <table class="table table-striped table-bordered table-sm small" id="app_tableorder">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>UOM</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Vat(%)</th>
                                        <th>Vat(Amount)</th>
                                        <th>Vat+Total</th>
                                    </tr>
                                </thead>
                                <tbody id="app_tableorderlist"></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 16px">
                                        <td colspan="8" class="text-right">Sub Total	:</td>
                                        <td id="app_subtotal" class="text-left">0</td>
                                    </tr>
                                    <tr style="font-weight: bold;font-size: 16px">
                                        <td colspan="8" class="text-right">Discount:</td>
                                        <td id="app_discount" class="text-left">0</td>
                                    </tr>
                                    <tr style="font-weight: bold;font-size: 16px">
                                        <td colspan="8" class="text-right">Total:</td>
                                        <td id="app_total" class="text-left">0</td>
                                    </tr>
                                </tfoot>
                            </table>

                            <div class="form-row mb-1">
                                <div class="col-12">
                                    <label class="small font-weight-bold text-dark">Remark*</label>
                                    <textarea type="text" id="app_remark" name="app_remark"
                                        class="form-control form-control-sm" readonly></textarea>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />

                        </form>
                    </div>
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

    <div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="modal-title" id="staticBackdropLabel">View Purchase Order</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <h3 id="porderno">Example</h3>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <div id="suppliername">Example</div>
                            <div id="suppliercontact">Example</div>
                            <div id="supplieremail">Example</div>
                            <div id="supplieraddress">Example</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-left">
                            <div id="employeename"></div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table-bordered table-sm small" id="view_tableorder">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Item Name</th>
                                        <th>UOM</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                        <th>Vat(%)</th>
                                        <th>Vat(Amount)</th>
                                        <th>Vat+Total</th>
                                    </tr>
                                </thead>
                                <tbody id="view_tableorderlist"></tbody>
                                <tfoot>
                                    <tr style="font-weight: bold;font-size: 16px">
                                        <td colspan="8" class="text-right">Sub Total	:</td>
                                        <td id="view_subtotal" class="text-left">0</td>
                                    </tr>
                                    <tr style="font-weight: bold;font-size: 16px">
                                        <td colspan="8" class="text-right">Discount:</td>
                                        <td id="view_discount" class="text-left">0</td>
                                    </tr>
                                    <tr style="font-weight: bold;font-size: 16px">
                                        <td colspan="8" class="text-right">Total:</td>
                                        <td id="view_total" class="text-left">0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="viewhidden_id" id="viewhidden_id" />
                <div class="modal-footer p-2">
                    <button type="button" name="print_button" id="print_button"
                        class="btn btn-primary px-3 btn-sm">Print</button>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection


@section('script')

<script>
    $(document).ready(function () {

        $("#inventorylink").addClass('navbtnactive');
        $('#corporate_link').addClass('active');


        $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                "url": "{!! route('porderlist') !!}",

            },
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'order_date',
                    name: 'order_date'
                },
                {
                    data: 'supplier_name',
                    name: 'supplier_name'
                },
                {
                    data: 'net_total',
                    name: 'net_total'
                },
                {
                    data: 'confirm_status',
                    name: 'confirm_status',
                    render: function (data, type, row) {
                        if (data == 0) {
                            return '<i style="color:red" class="fas fa-times"></i>&nbsp;&nbsp Confirm Order';
                        } else if (data == 1) {
                            return '<i style="color:green" class="fas fa-check"></i>&nbsp;&nbsp Confirm Order';
                        } else {
                            return '<i style="color:red" class="fas fa-ban"></i>&nbsp;&nbsp Reject Order';
                        }
                    }
                },
                {
                    data: 'grn_status',
                    name: 'grn_status',
                    render: function (data, type, row) {
                        if (data == 0) {
                            return '<i style="color:red" class="fas fa-times"></i>&nbsp;&nbsp Not issue GRN';
                        } else if (data == 1) {
                            return '<i style="color:green" class="fas fa-check"></i>&nbsp;&nbsp Issued';
                        } else {
                            return '<i style="color:red" class="fas fa-ban"></i>&nbsp;&nbsp Reject Order';
                        }
                    }
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
                [0, "desc"]
            ]
        });

        $('#create_record').click(function () {
            $('.modal-title').text('Create Purchase Order');
            $('#action_button').html('Add');
            $('#action').val('Add');
            $('#form_result').html('');
            $('#formTitle')[0].reset();

            $('#formModal').modal('show');
        });

        // insert part
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

                    $('.po-item').each(function () {
                        var rowData = {
                            itemCode: $(this).find("[name='item_code']").val(),
                            itemName: $(this).find("[name='item_name']").val(),
                            unit: $(this).find("[name='unit[]']").val(),
                            qty: $(this).find("[name='qty[]']").val(),
                            unitPrice: $(this).find("[name='unit_price[]']").val(),
                            total: $(this).find(".total-price").text(),
                            vatprecentage: $(this).find("[name='vatprecentage[]']").val(),
                            vatamount: $(this).find(".vatamount").text(),
                            aftervat: $(this).find(".aftervat").text()
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
                        var orderdate = $('#orderdate').val();
                        var duedate = $('#duedate').val();
                        var supplier = $('#supplier').val();
                        var store = $('#store').val();
                        var employee = $('#employee').val();
                        var remark = $('#remark').val();
                        var sub_total = parseFloat($('#sub_total').text());
                        var discount = parseFloat($('#discount_amount').val());
                        var total = parseFloat($('#total').text());
                        // console.log(orderdate,duedate,supplier,store,remark,total,sub_total,discount);
                        // console.log(rowDataArray);
                        action_url = "{{ route('porderinsert') }}";
                        $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DataArray: rowDataArray,
                        orderdate: orderdate,
                        duedate: duedate,
                        supplier: supplier,
                        store: store,
                        employee:employee,
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
        $("#update_button").click(function () {
            if (!$("#editformTitle")[0].checkValidity()) {
                // If the form is invalid, submit it. The form won't actually submit;
                // this will just cause the browser to display the native HTML5 error messages.
                $("#editsubmitBtn").click();
            } else {
                // $('#formsubmit').prop('disabled', true).html(
                // '<i class="fas fa-circle-notch fa-spin mr-2"></i> Create Order');
                const dataArray = [];
                var error = false;

                $('#edit_tableorderlist tr').each(function () {

                var rowData = {
                            itemCode: $(this).find("[name='edit_inventorylist_id[]']").val(),
                            itemName: $(this).find("[name='edit_inventorylist_select[]']").val(),
                            unit: $(this).find("[name='edit_uom[]']").val(),
                            qty: $(this).find("[name='edit_qty[]']").val(),
                            unitPrice: $(this).find("[name='edit_unit_price[]']").val(),
                            total: $(this).find("[name='edit_total[]']").val(),
                            vatprecentage: $(this).find("[name='edit_vatprecentage[]']").val(),
                            vatamount: $(this).find(".edit_vatamount").text(),
                            aftervat: $(this).find(".edit_aftervat").text(),
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
                            dataArray.push(rowData);
                        }

            });
            if (!error) {
                        var orderdate = $('#edit_orderdate').val();
                        var duedate = $('#edit_duedate').val();
                        var supplier = $('#edit_supplier').val();
                        var store = $('#edit_store').val();
                        var employee = $('#edit_employee').val();
                        var remark = $('#edit_remark').val();
                        var sub_total = parseFloat($('#edit_subtotal1').text());
                        var discount = parseFloat($('#edit_discount1').val());
                        var total = parseFloat($('#edit_total1').text());
                        var hidden_id = $('#edit_hidden_id').val();
                        // console.log(orderdate,duedate,supplier,store,employee,remark,total,sub_total,discount,hidden_id);
                        // console.log(dataArray);
                        action_url = "{{ route('porderupdate') }}";
                        $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        DataArray: dataArray,
                        orderdate: orderdate,
                        duedate: duedate,
                        supplier: supplier,
                        store: store,
                        employee:employee,
                        remark: remark,
                        sub_total: sub_total,
                        discount: discount,
                        net_total: total,
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
            else{
                alert("Please fill in all required fields.");
            }
            }
        });


        $('#btncreateorder').click(function () {

            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('porderinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                action_url = "{{ route('porderupdate') }}";
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
                var duedate = $('#duedate').val();
                var supplier = $('#supplier').val();
                var comment = $('#comment').val();
                var totalValue = parseFloat($('#totalField').text());
                var hidden_id = $('#hidden_id').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
                        orderdate: orderdate,
                        duedate: duedate,
                        supplier: supplier,
                        comment: comment,
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
                url: '{!! route("porderedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#edit_orderdate').val(data.result.mainData.order_date);
                    $('#edit_duedate').val(data.result.mainData.due_date);
                    $('#edit_supplier').val(data.result.mainData.supplier_id);
                    $('#edit_store').val(data.result.mainData.store_id);

                    var empid = data.result.mainData.empid;
                    var empname = (data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    var newOption = new Option(empname, empid, true, true);
                    $('#edit_employee').append(newOption).trigger('change');

                    $('#edit_remark').val(data.result.mainData.remark);
                    $('#edit_subtotal1').text(data.result.mainData.sub_total);
                    $('#edit_discount1').val(data.result.mainData.discount_amount);
                    $('#edit_total1').text(data.result.mainData.net_total);

                    $('#edit_tempsubtotal1').text(data.result.mainData.sub_total);
                    $('#edit_tempdiscount1').text(data.result.mainData.discount_amount);
                    $('#edit_temptotal1').text(data.result.mainData.net_total);

                    $('#edit_tableorderlist').html(data.result.requestdata);


                    // var valueToCheck = data.result.pay_by;

                    // if (valueToCheck == 1 ) {
                    //     $('#company').prop('checked', true);
                    // } else {
                    //      $('#branch').prop('checked', true);
                    // }

                    $('#edit_hidden_id').val(id);
                    $('#editModal').modal('show');
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
                url: '{!! route("porderdetailedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#item').val(data.result.item_id);
                    $('#rate').val(data.result.unit_price);
                    $('#qty').val(data.result.qty);
                    $('#oprderdetailsid').val(data.result.id);
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
            var detailid = $('#oprderdetailsid').val();

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
            porderid = $('#hidden_id').val();
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
                url: '{!! route("porderdetaildelete") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: rowid,
                    total: total,
                    cost: cost,
                    porderid: porderid
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
                url: '{!! route("porderdelete") !!}',
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
                url: '{!! route("porderdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_orderdate').val(data.result.mainData.order_date);
                    $('#app_duedate').val(data.result.mainData.due_date);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_store').val(data.result.mainData.store_id);

                    var empid = data.result.mainData.empid;
                    var empname = (data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    var newOption = new Option(empname, empid, true, true);
                    $('#app_employee').append(newOption).trigger('change');

                    $('#app_remark').val(data.result.mainData.remark);
                    $('#app_subtotal').text(data.result.mainData.sub_total);
                    $('#app_discount').text(data.result.mainData.discount_amount);
                    $('#app_total').text(data.result.mainData.net_total);
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
                url: '{!! route("porderdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_orderdate').val(data.result.mainData.order_date);
                    $('#app_duedate').val(data.result.mainData.due_date);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_store').val(data.result.mainData.store_id);

                    var empid = data.result.mainData.empid;
                    var empname = (data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    var newOption = new Option(empname, empid, true, true);
                    $('#app_employee').append(newOption).trigger('change');

                    $('#app_remark').val(data.result.mainData.remark);
                    $('#app_subtotal').text(data.result.mainData.sub_total);
                    $('#app_discount').text(data.result.mainData.discount_amount);
                    $('#app_total').text(data.result.mainData.net_total);
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
                url: '{!! route("porderdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_orderdate').val(data.result.mainData.order_date);
                    $('#app_duedate').val(data.result.mainData.due_date);
                    $('#app_supplier').val(data.result.mainData.supplier_id);
                    $('#app_store').val(data.result.mainData.store_id);

                    var empid = data.result.mainData.empid;
                    var empname = (data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    var newOption = new Option(empname, empid, true, true);
                    $('#app_employee').append(newOption).trigger('change');

                    $('#app_remark').val(data.result.mainData.remark);
                    $('#app_subtotal').text(data.result.mainData.sub_total);
                    $('#app_discount').text(data.result.mainData.discount_amount);
                    $('#app_total').text(data.result.mainData.net_total);
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
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("porderapprove") !!}',
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

    $('#reject_button').click(function () {
            var id_hidden = $('#hidden_id').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })
            $.ajax({
                url: '{!! route("porderreject") !!}',
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
        $('#viewhidden_id').val(id);
        $('#porderno').text('POD-' + id);
        var total = 0;

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("porderviewDetails") !!}',
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
            }
        })

        $.ajax({
                url: '{!! route("porderdetailapprovel_details") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#view_subtotal').text(data.result.mainData.sub_total);
                    $('#view_discount').text(data.result.mainData.discount_amount);
                    $('#view_total').text(data.result.mainData.net_total);
                    $('#view_tableorderlist').html(data.result.requestdata)

                    if(data.result.mainData.empid){
                        $('#employeename').text("Employee: "+data.result.mainData.service_no+"-"+data.result.mainData.emp_name_with_initial);
                    }
                    else{
                        $('#employeename').text(''); 
                    }
                    
                }
            })

        $('#viewModal').modal('show');

    });

    $('#print_button').click(function () {
    var id_hidden = $('#viewhidden_id').val();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{!! route("porderprint") !!}',
        type: 'POST',
        dataType: "json", // Set the response type to 'text' as you want to display the PDF in a new tab
        data: {
            id: id_hidden,
        },
        success: function (data) {
            // Create a Blob containing the PDF data
            var pdfBlob = base64toBlob(data.pdf, 'application/pdf');

            // Create a URL for the Blob
            var pdfUrl = URL.createObjectURL(pdfBlob);

            // Trigger a download of the PDF file in the browser
            var a = document.createElement('a');
            a.href = pdfUrl;
            a.download = 'Purchase_Order.pdf'; // Set the desired filename
            a.style.display = 'none';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
        },
                error: function () {
                    console.log('PDF request failed.');
                }
            });
        });



        function base64toBlob(base64Data, contentType) {
            contentType = contentType || '';
            var sliceSize = 1024;
            var byteCharacters = atob(base64Data);
            var byteArrays = [];

            for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
                var slice = byteCharacters.slice(offset, offset + sliceSize);
                var byteNumbers = new Array(slice.length);
                for (var i = 0; i < slice.length; i++) {
                    byteNumbers[i] = slice.charCodeAt(i);
                }
                var byteArray = new Uint8Array(byteNumbers);
                byteArrays.push(byteArray);
            }

            return new Blob(byteArrays, {
                type: contentType
            });
        }



    $('#supplier').change(function () {
        var supplierid = $(this).val();
        if (supplierid !== '') {
            $.ajax({
                url: '{!! route("pordergetitem", ["supplierid" => "id_supplier"]) !!}'.replace('id_supplier',supplierid),
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
            $('#item').empty().append('<option value="">Select Supplier</option>');
        }
    });

    function edit_SupplierItemget(supplierid){
        if (supplierid !== '') {
            $.ajax({
                url: '{!! route("pordergetitem", ["supplierid" => "id_supplier"]) !!}'.replace('id_supplier',supplierid),
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
            $('#item').empty().append('<option value="">Select Supplier</option>');
        }
    }

    // get purshase price in select item
    function getpurchaseprice() {
        var itemid = $('#item').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("pordergetpurchaseprice") !!}',
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


    // Call this function whenever you add or delete a row
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
	function rem_item(_this){
		_this.closest('tr').remove()
        calculate();
        updateEditTotal();
	}
	function calculate(){
		var _total = 0
		$('.po-item').each(function(){
			var qty = $(this).find("[name='qty[]']").val()
			var unit_price = $(this).find("[name='unit_price[]']").val()
			var row_total = 0;
			if(qty > 0 && unit_price > 0){
				row_total = parseFloat(qty) * parseFloat(unit_price)
			}
			$(this).find('.total-price').text(parseFloat(row_total))

        // vat calculate
        var vatprecentage = $(this).find("[name='vatprecentage[]']").val()
        var vatamount=row_total*(vatprecentage/100);
        $(this).find('.vatamount').text(parseFloat(vatamount))
        var aftervat=(parseFloat(row_total))+(parseFloat(vatamount));
        $(this).find('.aftervat').text(parseFloat(aftervat))
		})

		$('.aftervat').each(function(){
			var _price = $(this).text()
				_price = _price.replace(/\,/gi,'')
				_total += parseFloat(_price)
		})

		var discount_amount = 0
		if($('[name="discount_amount"]').val() > 0){
			discount_amount = $('[name="discount_amount"]').val()
		}
		// var net_amount = _total - discount_amount;
		// $('[name="discount_amount"]').val(parseFloat(discount_amount))
		// var tax_perc = 0
		// if($('[name="tax_percentage"]').val() > 0){
		// 	tax_perc = $('[name="tax_percentage"]').val()
		// }
		// var tax_amount = _total * (tax_perc/100);
		// $('[name="tax_amount"]').val(parseFloat(tax_amount))
		$('#sub_total').text(parseFloat(_total))
		$('#total').text(parseFloat(_total-discount_amount))
	}

	
	$(document).ready(function(){
        let rowCounter = 0;

        $(document).on("click", "#add_row", function () {
            if ($('#item_name').val()) {
        $('#item_name').prop('disabled', true);
    } 
    if ($('#item_code'+rowCounter)) {
        console.log($('#item_code'+rowCounter).val());
        $('#item_name'+rowCounter).prop('disabled', true);
    }
            rowCounter++;
    const newRow = `
        <tr class="po-item" data-id="">
            <td class="align-middle p-1">
                <input type="text" id="item_code${rowCounter}" name="item_code" required class="text-center w-100 border-0 item_code" readonly/>
            </td>
            <td class="align-middle p-1">
                <select id="item_name${rowCounter}" name="item_name" required class="text-center w-100 border-0 item_name">
                    <option value=""></option>
                </select>
            </td>
            <td class="align-middle p-1">
                <input type="text" class="text-center w-100 border-0" name="unit[]" id="unit${rowCounter}"readonly/>
            </td>
            <td class="align-middle p-0 text-center">
                <input type="number" class="text-center w-100 border-0" step="any" id="qty${rowCounter}" name="qty[]"/>
            </td>                                                
            <td class="align-middle p-1">
                <input type="number" step="any" class="text-right w-100 border-0" id="unit_price${rowCounter}" name="unit_price[]"/>
            </td>
            <td class="align-middle p-1 text-right total-price">0</td>
            <td class="align-middle p-1">
                <input type="number" step="any" class="text-right w-100 border-0" id="vatprecentage${rowCounter}" name="vatprecentage[]" value="0"/>
            </td>
            <td id="vatamount${rowCounter}" name="vatamount[]" class="align-middle p-1 text-right vatamount">0</td>
            <td id="aftervat${rowCounter}" name="aftervat[]" class="align-middle p-1 text-right aftervat">0</td>
            <td class="align-middle p-1 text-center">
                <button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button>
            </td>
        </tr>
    `;

    $('#item-list').append(newRow);

    // Initialize select2 for the new select box
    $(`#item_name${rowCounter}`).select2({
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            url: '{!! route("pordergetitemname") !!}',
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
                            text: item.inventorylist_id + '-' + item.name + ' ' + (item.uniform_size==null?" ":item.uniform_size+' "'),
                            id: item.id
                        };
                    })
                };
            }
        }
    }).on('select2:select', function (e) {
        const selectedItemId = e.params.data.id;
        getItemDetails1(selectedItemId, rowCounter);
    });

    const qtyInput = $(`#qty${rowCounter}`);
    const unitPriceInput = $(`#unit_price${rowCounter}`);
    const vatprecentage = $(`#vatprecentage${rowCounter}`);

    qtyInput.on('input keyup', function (e) {
        calculate();
    });

    unitPriceInput.on('input keyup', function (e) {
        calculate();
    });

    vatprecentage.on('input keyup', function (e) {
        calculate();
    });

    // Trigger keyup events initially to calculate when the values are changed
    qtyInput.trigger('keyup');
    unitPriceInput.trigger('keyup');
    vatprecentage.trigger('keyup');

    getVatDateInputChange();
   
});

		if($('#item-list .po-item').length > 0){
			$('#item-list .po-item').each(function(){
				var tr = $(this)
				tr.find('[name="qty[]"],[name="unit_price[]"],[name="vatprecentage[]"]').on('input keypress',function(e){
					calculate()
				})
				$('#item-list tfoot').find('[name="discount_amount"],[name="tax_percentage"]').on('input keypress',function(e){
					calculate()
				})
				tr.find('[name="qty[]"],[name="unit_price[]"]').trigger('keypress')
			})
		}else{
		$('#add_row').trigger('click')
		}
	})
</script>
<script>
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

                $('#item_name').select2({
            width: '100%',
            minimumInputLength: 1,
            ajax: {
                url: '{!! route("pordergetitemname") !!}',
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
                                text: item.inventorylist_id + '-' + item.name + ' ' + (item.uniform_size==null?" ":item.uniform_size+' "'),
                                id: item.id
                            };
                        })
                    };
                }
            }
        }).on('select2:select', function (e) {
            var selectedItemId = e.params.data.id;
            getItemDetails(selectedItemId);
        });
            });


   function getItemDetails(item_code){
    $.ajax({
            url: '{!! route("pordergetitemdetail") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: item_code
            },
            success: function (data) {
                $('#item_code').val(data.result[0].inventorylist_id);
                $('#unit').val(data.result[0].uom);
                // $('#app_empname').val(data.result.emp_name_with_initial);
            }
        })
   }


   function getItemDetails1(item_code, rowCounter) {
    // console.log(item_code,rowCounter);
    $.ajax({
        url: '{!! route("pordergetitemdetail") !!}',
        type: 'POST',
        dataType: "json",
        data: {
            id: item_code
        },
        success: function (data) {
            $('#item_code'+rowCounter).val(data.result[0].inventorylist_id);
            $('#unit'+rowCounter).val(data.result[0].uom);
        }
    });
}
 
// Edit parts

let editrowCounter =1000;
    // Function to add a new row
    function addNewRow() {
        const newRow = `
            <tr>
                <td><input style="width:100px" type="text" name="edit_inventorylist_id[]" id="inventorylist_id${editrowCounter}" value="" readonly></td>
                <td>
                    <select required name="edit_inventorylist_select[]" id="inventorylist_select${editrowCounter}" onchange="getItemeditDetails(this.value, ${editrowCounter})" size="1" onfocus="this.size = 8" onchange="this.blur()" onblur="this.size = 1; this.blur()">
                        <option value="">Select Inventory Item</option>
                        @foreach($items as $item)
                         <option value="{{$item->id}}">{{$item->inventorylist_id}}-{{$item->name}} {{($item->uniform_size==null?"":$item->uniform_size.'"')}}</option>
                        @endforeach
                    </select>
                </td>
                <td><input style="width:100px" type="text" name="edit_uom[]" id="uom${editrowCounter}" value="" readonly></td>
                <td><input style="width:150%" type="number" name="edit_qty[]" id="qty${editrowCounter}" value="0" onkeyup="editsum(this.value, ${editrowCounter})"></td>
                <td><input style="width:150%" type="number" name="edit_unit_price[]" id="unit_price${editrowCounter}" value="0" onkeyup="editsum(this.value, ${editrowCounter})"></td>
                <td><input style="width:150%" type="text" name="edit_total[]" id="total${editrowCounter}" value="0" readonly></td>  
                <td class="align-middle p-1">
                <input type="number" step="any" class="text-right w-100 border-0" id="edit_vatprecentage${editrowCounter}" name="edit_vatprecentage[]" value="0" onkeyup="editsum(this.value, ${editrowCounter})"/>
                </td>
                <td id="edit_vatamount${editrowCounter}" name="edit_vatamount[]" class="align-middle p-1 text-right edit_vatamount">0</td>
                <td id="edit_aftervat${editrowCounter}" name="edit_aftervat[]" class="align-middle p-1 text-right edit_aftervat">0</td>
                <td class="d-none"><input type="text" name="edit_insertstatus[]" id="edit_insertstatus${editrowCounter}" value="NewData"></td>  
                <td><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>  
            </tr>
        `;

        // Append the new row to the table
        $('#edit_tableorderlist').append(newRow);

        // Increment the row counter
        editrowCounter++;
        editgetVatDateInputChange();
    }

    // Attach a click event handler to the "Add Row" button
    $('#add_editrow').click(function () {
        addNewRow();
    });

    function getItemeditDetails(item_code, rowCounter) {
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

function editsum(value, rowCounter){
var qty = parseFloat($('#qty' + rowCounter).val());
var unitPrice = parseFloat($('#unit_price' + rowCounter).val());
var presubtotal = parseFloat($('#total' + rowCounter).val());

var total=qty*unitPrice;
$('#total' + rowCounter).val(total)

// calculate vat
        var vatprecentage = parseFloat($('#edit_vatprecentage' + rowCounter).val());
        var vatamount=total*(vatprecentage/100);
        $('#edit_vatamount' + rowCounter).text(vatamount);
        var aftervat=(parseFloat(total))+(parseFloat(vatamount));
        $('#edit_aftervat' + rowCounter).text(aftervat);

updateEditTotal();
}

function updateEditTotal(){
    let grandTotal = 0;
    $('td[name="edit_aftervat[]"]').each(function() {
    const value = parseFloat($(this).text()) || 0;
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

function updatefunc(){
 
}

</script>
<script>
    // get vat for insert model
    function getVatDateInputChange(){
        var value=$('#orderdate').val()
        // console.log(value);
        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    var ajaxRequest = $.ajax({
                        url: '{!! route("pordercashgetvat") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { date: value },
                    });

                    ajaxRequest.done(function (data) {
                        $('[name="vatprecentage[]"]').val(data.result);
                    });

                    ajaxRequest.then(function () {
                        calculate();
                    });

    }

     // get vat for edit model
     function editgetVatDateInputChange(){
        var value=$('#edit_orderdate').val()
        // console.log(value);
        $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    })

                    var ajaxRequest = $.ajax({
                        url: '{!! route("pordercashgetvat") !!}',
                        type: 'POST',
                        dataType: "json",
                        data: { date: value },
                    });

                    ajaxRequest.done(function (data) {
                        $('[name="edit_vatprecentage[]"]').val(data.result);
                    });

                    ajaxRequest.then(function () {
                        calculate();
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
        $('#edit_employee').select2({
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
</body>

@endsection