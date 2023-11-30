@extends('layouts.app')

@section('content')

<main>
    <div class="page-header page-header-light bg-white shadow">
        <div class="container-fluid">
            <div class="page-header-content py-3">
                <h1 class="page-header-title">
                    <div class="page-header-icon"><i class="fas fa-store"></i></div>
                    <span>Inventory List</span>
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
                        @if(in_array('InventoryList-create',$userPermissions))
                        <button type="button" class="btn btn-outline-primary btn-sm fa-pull-right" name="create_record"
                            id="create_record"><i class="fas fa-plus mr-2"></i>Add Inventory List</button>
                            @endif
                    </div>
                    <div class="col-12">
                        <hr class="border-dark">
                    </div>
                    <div class="col-12">
                        <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" style="width: 100%"
                                id="dataTable">
                                <thead>
                                    <tr>
                                        <th># </th>
                                        <th>Item Code</th>
                                        <th>Name</th>
                                        <th>Inventory Type</th>
                                        <th>UOM </th>
                                        <th>Specification</th>
                                        <th>Re order Level</th>
                                        <th>Re order Quantity</th>
                                        <th>Remarks</th>
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
                    <h5 class="modal-title" id="staticBackdropLabel">Add Inventory List</h5>
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
                                        <label class="small font-weight-bold text-dark">Inventory Type*</label>
                                        <select name="inventorytype" id="inventorytype" class="form-control form-control-sm" onclick="getItemCode();"
                                            required>
                                            <option value="">Select Type</option>
                                            @foreach($inventorytypes as $inventorytype)
                                            <option value="{{$inventorytype->id}}">{{$inventorytype->inventory_type}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">UOM*</label>
                                        <select name="uom" id="uom" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select UOM</option>
                                            <option value="Pair">Pair</option>
                                            <option value="each(ea)">each(ea)</option>
                                            <option value="Packet">Packet</option>
                                            <option value="Kg">Kg</option>
                                            <option value="Box(12)">Box(12)</option>
                                            <option value="Bundle">Bundle</option>
                                            <option value="Roll">Roll</option>
                                        </select>
                                    </div>
                                </div>
                                <div id="uniformsizeDiv" class="form-row mb-1" style="display: none;">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Uniform Size*</label>
                                        <select name="uniformsize" id="uniformsize" class="form-control form-control-sm"
                                            required>
                                            <option value="">Select Size</option>
                                            <option value="20">20</option>
                                            <option value="21">21</option>
                                            <option value="22">22</option>
                                            <option value="23">23</option>
                                            <option value="24">24</option>
                                            <option value="25">25</option>
                                            <option value="26">26</option>
                                            <option value="27">27</option>
                                            <option value="28">28</option>
                                            <option value="29">29</option>
                                            <option value="30">30</option>
                                            <option value="32">31</option>
                                            <option value="32">32</option>
                                            <option value="33">33</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Name*</label>
                                        <input type="text" id="name" name="name" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>
                               
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Specification*</label>
                                        <input type="text" id="specification" name="specification" class="form-control form-control-sm"
                                            required>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Re order Level*</label>
                                        <input type="number" id="reorder_level" name="reorder_level" class="form-control form-control-sm"
                                        required>
                                    </div>
                                    <div class="col-6">
                                        <label class="small font-weight-bold text-dark">Re order Quantity*</label>
                                        <input type="number" id="reorder_qty" name="reorder_qty" class="form-control form-control-sm"
                                        required>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Remarks*</label>
                                        <textarea type="text" id="remark" name="remark" class="form-control form-control-sm"></textarea>
                                    </div>
                                </div>
                                <div class="form-row mb-1">
                                    <div class="col-12">
                                        <label class="small font-weight-bold text-dark">Item Code*</label>
                                        <input type="text" id="item_code" name="item_code" class="form-control form-control-sm"
                                            required readonly>
                                    </div>
                                </div>
                                <input type="hidden" name="checktypeselction" id="checktypeselction" value="unlock" readonly>
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

                            </form>
                        </div>
                        <div class="col-8">
                            <div class="center-block fix-width scroll-inner">
                            <table class="table table-striped table-bordered table-sm small nowrap display" id="tableorder">
                                <thead>
                                    <tr>
                                        <th>Item Code</th>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>UOM</th>
                                        <th>Specification</th>
                                        <th>Re Order Level</th>
                                        <th>Re Order Qty</th>
                                        <th>Remark</th>
                                        <th class="d-none">TypeID</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tableorderlist"></tbody>
                            </table>
                            <div class="form-group mt-2">
                                <button type="button" name="btncreateorder" id="btncreateorder"
                                    class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i
                                        class="fas fa-plus"></i>&nbsp;Create</button>

                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="EditformModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered">
       <div class="modal-content">
           <div class="modal-header p-2">
               <h5 class="Editmodal-title" id="staticBackdropLabel">Edit Inventory List</h5>
               <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span>
               </button>
           </div>
           <div class="modal-body">
               <div class="row">
                   <div class="col">
                       <span id="form_result1"></span>
                       <form method="post" id="formTitle1" class="form-horizontal">
                           {{ csrf_field() }}
                           <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Inventory Type*</label>
                                <select name="edit_inventorytype" id="edit_inventorytype" class="form-control form-control-sm"
                                    required readonly disabled>
                                    <option value="">Select Type</option>
                                    @foreach($inventorytypes as $inventorytype)
                                    <option value="{{$inventorytype->id}}">{{$inventorytype->inventory_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">UOM*</label>
                                <select name="edit_uom" id="edit_uom" class="form-control form-control-sm"
                                    required>
                                    <option value="">Select UOM</option>
                                    <option value="Pair">Pair</option>
                                    <option value="each(ea)">each(ea)</option>
                                    <option value="Packet">Packet</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Box(12)">Box(12)</option>
                                    <option value="Bundle">Bundle</option>
                                    <option value="Roll">Roll</option>
                                </select>
                            </div>
                        </div>
                        <div id="edit_uniformsizeDiv" class="form-row mb-1" style="display: none;">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Uniform Size*</label>
                                <select name="edit_uniformsize" id="edit_uniformsize" class="form-control form-control-sm"
                                    required>
                                    <option value="">Select Size</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="32">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Item Code*</label>
                                    <input type="text" id="edit_item_code" name="edit_item_code" class="form-control form-control-sm"
                                        required readonly>
                                </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Name*</label>
                                <input type="text" id="edit_name" name="edit_name" class="form-control form-control-sm"
                                    required>
                            </div>
                        </div>
                       
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Specification*</label>
                                <input type="text" id="edit_specification" name="edit_specification" class="form-control form-control-sm"
                                    required>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Re order Level*</label>
                                <input type="number" id="edit_reorder_level" name="edit_reorder_level" class="form-control form-control-sm"
                                required>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Re order Quantity*</label>
                                <input type="number" id="edit_reorder_qty" name="edit_reorder_qty" class="form-control form-control-sm"
                                required>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Remarks*</label>
                                <textarea type="text" id="edit_remark" name="edit_remark" class="form-control form-control-sm"></textarea>
                            </div>
                        </div>
                 
                           <div class="form-group mt-3">
                               <button type="button" name="action_button" id="action_button" class="btn btn-outline-primary btn-sm fa-pull-right px-4"><i class="fas fa-plus"></i>&nbsp;Edit</button>
                           </div>
                           <input type="hidden" name="edithidden_id" id="edithidden_id" />
                         
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

    <div class="modal fade" id="approveconfirmModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="approvelmodal-title" id="staticBackdropLabel">Approve Inventory List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Inventory Type*</label>
                                <select name="app_inventorytype" id="app_inventorytype" class="form-control form-control-sm"
                                    required readonly>
                                    <option value="">Select Type</option>
                                    @foreach($inventorytypes as $inventorytype)
                                    <option value="{{$inventorytype->id}}">{{$inventorytype->inventory_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">UOM*</label>
                                <select name="app_uom" id="app_uom" class="form-control form-control-sm"
                                    required readonly>
                                    <option value="">Select UOM</option>
                                    <option value="Pair">Pair</option>
                                    <option value="each(ea)">each(ea)</option>
                                    <option value="Packet">Packet</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Box(12)">Box(12)</option>
                                    <option value="Bundle">Bundle</option>
                                    <option value="Roll">Roll</option>
                                </select>
                            </div>
                        </div>
                        <div id="app_uniformsizeDiv" class="form-row mb-1" style="display: none;">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Uniform Size*</label>
                                <select name="app_uniformsize" id="app_uniformsize" class="form-control form-control-sm"
                                    required readonly>
                                    <option value="">Select Size</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="32">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Item Code*</label>
                                    <input type="text" id="app_item_code" name="app_item_code" class="form-control form-control-sm"
                                        required readonly>
                                </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Name*</label>
                                <input type="text" id="app_name" name="app_name" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                        </div>
                       
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Specification*</label>
                                <input type="text" id="app_specification" name="app_specification" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Re order Level*</label>
                                <input type="number" id="app_reorder_level" name="app_reorder_level" class="form-control form-control-sm"
                                required readonly>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Re order Quantity*</label>
                                <input type="number" id="app_reorder_qty" name="app_reorder_qty" class="form-control form-control-sm"
                                required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Remarks*</label>
                                <textarea type="text" id="app_remark" name="app_remark" class="form-control form-control-sm" readonly></textarea>
                            </div>
                        </div>
                        <input type="hidden" name="app_hidden_id" id="app_hidden_id" />
                        <input type="hidden" name="app_level" id="app_level" value="1" />

                    </form>
                </div>
                <div class="modal-footer p-2">
                    <button type="button" name="approve_button" id="approve_button"
                        class="btn btn-warning px-3 btn-sm">Approve</button>
                    <button type="button" class="btn btn-dark px-3 btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    {{-- view modal --}}
    <div class="modal fade" id="viewModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <h5 class="approvelmodal-title" id="staticBackdropLabel">View Inventory List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Inventory Type*</label>
                                <select name="view_inventorytype" id="view_inventorytype" class="form-control form-control-sm"
                                    required readonly>
                                    <option value="">Select Type</option>
                                    @foreach($inventorytypes as $inventorytype)
                                    <option value="{{$inventorytype->id}}">{{$inventorytype->inventory_type}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">UOM*</label>
                                <select name="view_uom" id="view_uom" class="form-control form-control-sm"
                                    required readonly>
                                    <option value="">Select UOM</option>
                                    <option value="Pair">Pair</option>
                                    <option value="each(ea)">each(ea)</option>
                                    <option value="Packet">Packet</option>
                                    <option value="Kg">Kg</option>
                                    <option value="Box(12)">Box(12)</option>
                                    <option value="Bundle">Bundle</option>
                                    <option value="Roll">Roll</option>
                                </select>
                            </div>
                        </div>
                        <div id="view_uniformsizeDiv" class="form-row mb-1" style="display: none;">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Uniform Size*</label>
                                <select name="view_uniformsize" id="view_uniformsize" class="form-control form-control-sm"
                                    required readonly>
                                    <option value="">Select Size</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                    <option value="24">24</option>
                                    <option value="25">25</option>
                                    <option value="26">26</option>
                                    <option value="27">27</option>
                                    <option value="28">28</option>
                                    <option value="29">29</option>
                                    <option value="30">30</option>
                                    <option value="32">31</option>
                                    <option value="32">32</option>
                                    <option value="33">33</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                                <div class="col-6">
                                    <label class="small font-weight-bold text-dark">Item Code*</label>
                                    <input type="text" id="view_item_code" name="view_item_code" class="form-control form-control-sm"
                                        required readonly>
                                </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Name*</label>
                                <input type="text" id="view_name" name="view_name" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                        </div>
                       
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Specification*</label>
                                <input type="text" id="view_specification" name="view_specification" class="form-control form-control-sm"
                                    required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Re order Level*</label>
                                <input type="number" id="view_reorder_level" name="view_reorder_level" class="form-control form-control-sm"
                                required readonly>
                            </div>
                            <div class="col-6">
                                <label class="small font-weight-bold text-dark">Re order Quantity*</label>
                                <input type="number" id="view_reorder_qty" name="view_reorder_qty" class="form-control form-control-sm"
                                required readonly>
                            </div>
                        </div>
                        <div class="form-row mb-1">
                            <div class="col-12">
                                <label class="small font-weight-bold text-dark">Remarks*</label>
                                <textarea type="text" id="view_remark" name="view_remark" class="form-control form-control-sm" readonly></textarea>
                            </div>
                        </div>
                    </form>
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
        $('#inventorydrop').addClass('show');
        $('#inventory_list_link').addClass('active');

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
                url: scripturl + '/inventorylist.php',

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
                    title: 'Inventory List Details',
                    text: '<i class="fas fa-file-csv mr-2"></i> CSV',
                },
                {
                    extend: 'print',
                    title: 'Inventory List Details',
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
                    "data": "inventorylist_id",
                    "className": 'text-dark'
                },

                {
                    "data": "name",
                    "className": 'text-dark'
                },
                {
                    "data": "inventory_type",
                    "className": 'text-dark'
                },
                {
                    "data": "uom",
                    "className": 'text-dark'
                },
                {
                    "data": "specification",
                    "className": 'text-dark'
                },

                {
                    "data": "re_order_level",
                    "className": 'text-dark'
                },
                {
                    "data": "re_order_quantity",
                    "className": 'text-dark'
                },
                {
                    "data": "remarks",
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
                        if (editcheck) {
                            if (full['approve_status']==0) {
                                button += ' <button name="edit" id="' + full['id'] + '" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';                      
                            }
                            else{
                                button += ' <button name="view" id="' + full['id'] + '" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';                      
                            }
                        }
                        if (statuscheck) {
                                if (full['status'] == 1) {
                                    button += ' <a href="inventoryliststatus/' + full['id'] + '/2 " onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                                } else {
                                    button += '&nbsp;<a href="inventoryliststatus/' + full['id'] + '/1 "  onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                                }
                        }
                        if (deletecheck) {
                            button += ' <button name="delete" id="' + full['id'] + '" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
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
            $('.modal-title').text('Add New Inventory List');
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
                var TypeID = $('#inventorytype').val();
                var Uom = $('#uom').val();
                var Name = $('#name').val();
                var Specification = $('#specification').val();
                var Reorder_level = $('#reorder_level').val();
                var Reorder_qty = $('#reorder_qty').val();
                var Remark = $('#remark').val();
                var Id = $('#item_code').val();
                var uniformsize = $('#uniformsize').val();


                var Type = $("#inventorytype option:selected").text();


                $('#tableorder > tbody:last').append('<tr class="pointer"><td>' + Id +
                    '</td><td>' + Name +
                    '</td><td>' + Type +
                    '</td><td>' + Uom +
                    '</td><td>' + Specification +
                    '</td><td>' + Reorder_level +
                    '</td><td>' + Reorder_qty +
                    '</td><td>' + Remark +
                    '</td><td class="d-none">' + TypeID +
                    '</td><td class="d-none">' + uniformsize +
                    '</td><td class="d-none">NewData</td><td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td></tr>'
                );

                $('#uom').val('');
                $('#name').val('');
                $('#specification').val('');
                $('#reorder_level').val('');
                $('#reorder_qty').val('');
                $('#remark').val('');
                $('#uniformsize').val('');

                var currentId = Id;

                var prefix = currentId.slice(0, -4);
                var numericPart = parseInt(currentId.slice(-4), 10);
                var newNumericPart = numericPart + 1;

                var newId = prefix + newNumericPart.toString().padStart(4, '0');
                $('#item_code').val(newId);

                $('#checktypeselction').val("locked");

            }
        });


        $('#btncreateorder').click(function () {

            var action_url = '';

            if ($('#action').val() == 'Add') {
                action_url = "{{ route('inventorylistinsert') }}";
            }
            if ($('#action').val() == 'Edit') {
                // action_url = "{{ route('storelistupdate') }}";
            }

            $('#btncreateorder').prop('disabled', true).html(
                '<i class="fas fa-circle-notch fa-spin mr-2"></i> Creating');

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

                var hidden_id = $('#hidden_id').val();

                $.ajax({
                    method: "POST",
                    dataType: "json",
                    data: {
                        _token: '{{ csrf_token() }}',
                        tableData: jsonObj,
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

            $('#form_result').html('');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("inventorylistedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id
                },
                success: function (data) {
                    $('#edit_inventorytype').val(data.result.inventory_type_id);
                    $('#edit_uom').val(data.result.uom);
                    $('#edit_uniformsize').val(data.result.uniform_size);
                    $('#edit_item_code').val(data.result.inventorylist_id);
                    $('#edit_name').val(data.result.name);
                    $('#edit_specification').val(data.result.specification);
                    $('#edit_reorder_level').val(data.result.re_order_level);
                    $('#edit_reorder_qty').val(data.result.re_order_quantity);
                    $('#edit_remark').val(data.result.remarks);
                    edituniformSizeshow(data.result.inventory_type_id);

                    $('#edithidden_id').val(id);
                    $('#EditformModal').modal('show');
                }
            })
        });
// update
$('#action_button').click(function ()  {
            var id = $('#edithidden_id').val();

            var inventorytype = $('#edit_inventorytype').val();
            var uom = $('#edit_uom').val();
            var uniformsize = $('#edit_uniformsize').val();
            var itemcode = $('#edit_item_code').val();
            var name = $('#edit_name').val();
            var spec = $('#edit_specification').val();
            var recorderlevel = $('#edit_reorder_level').val();
            var recoderqty = $('#edit_reorder_qty').val();
            var remark = $('#edit_remark').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("inventorylistupdate") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    hidden_id: id,
                    inventorytype: inventorytype,
                    uom: uom,
                    uniformsize: uniformsize,
                    itemcode: itemcode,
                    name: name,
                    spec: spec,
                    recorderlevel: recorderlevel,
                    recoderqty: recoderqty,
                    remark: remark,
                },
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
                            $('#formTitle1')[0].reset();
                            //$('#titletable').DataTable().ajax.reload();
                            window.location.reload(); // Use window.location.reload()
                        }

                        $('#form_result1').html(html);
                        // resetfield();

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
                url: '{!! route("inventorylistdelete") !!}',
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
                url: '{!! route("inventorylistedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_inventorytype').val(data.result.inventory_type_id);
                    $('#app_uom').val(data.result.uom);
                    $('#app_uniformsize').val(data.result.uniform_size);
                    $('#app_item_code').val(data.result.inventorylist_id);
                    $('#app_name').val(data.result.name);
                    $('#app_specification').val(data.result.specification);
                    $('#app_reorder_level').val(data.result.re_order_level);
                    $('#app_reorder_qty').val(data.result.re_order_quantity);
                    $('#app_remark').val(data.result.remarks);
                    approvaluniformSizeshow(data.result.inventory_type_id);
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
                url: '{!! route("inventorylistedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_inventorytype').val(data.result.inventory_type_id);
                    $('#app_uom').val(data.result.uom);
                    $('#app_uniformsize').val(data.result.uniform_size);
                    $('#app_item_code').val(data.result.inventorylist_id);
                    $('#app_name').val(data.result.name);
                    $('#app_specification').val(data.result.specification);
                    $('#app_reorder_level').val(data.result.re_order_level);
                    $('#app_reorder_qty').val(data.result.re_order_quantity);
                    $('#app_remark').val(data.result.remarks);
                    approvaluniformSizeshow(data.result.inventory_type_id);
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
                url: '{!! route("inventorylistedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#app_inventorytype').val(data.result.inventory_type_id);
                    $('#app_uom').val(data.result.uom);
                    $('#app_uniformsize').val(data.result.uniform_size);
                    $('#app_item_code').val(data.result.inventorylist_id);
                    $('#app_name').val(data.result.name);
                    $('#app_specification').val(data.result.specification);
                    $('#app_reorder_level').val(data.result.re_order_level);
                    $('#app_reorder_qty').val(data.result.re_order_quantity);
                    $('#app_remark').val(data.result.remarks);
                    approvaluniformSizeshow(data.result.inventory_type_id);
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
                url: '{!! route("inventorylistapprove") !!}',
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
        });
    });

     // View 
     $(document).on('click', '.view', function () {
            id_approve = $(this).attr('id');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            })

            $.ajax({
                url: '{!! route("inventorylistedit") !!}',
                type: 'POST',
                dataType: "json",
                data: {
                    id: id_approve
                },
                success: function (data) {
                    $('#view_inventorytype').val(data.result.inventory_type_id);
                    $('#view_uom').val(data.result.uom);
                    $('#view_uniformsize').val(data.result.uniform_size);
                    $('#view_item_code').val(data.result.inventorylist_id);
                    $('#view_name').val(data.result.name);
                    $('#view_specification').val(data.result.specification);
                    $('#view_reorder_level').val(data.result.re_order_level);
                    $('#view_reorder_qty').val(data.result.re_order_quantity);
                    $('#view_remark').val(data.result.remarks);
                    viewuniformSizeshow(data.result.inventory_type_id);

                    $('#viewModal').modal('show');

                }
            })


        });

    function getItemCode() {
        var inventorytype = $('#inventorytype').val();
        var checktypeselction = $('#checktypeselction').val();

        if(checktypeselction=="unlock"){
            $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $.ajax({
            url: '{!! route("inventorylistGetItemCode") !!}',
            type: 'POST',
            dataType: "json",
            data: {
                id: inventorytype
            },
            success: function (data) {
                    $('#item_code').val(data.result);
               
            }
        })
        }else{
            $('#inventorytype').prop('disabled', true);
        }
       
    }

    function productDelete(row) {
        $(row).closest('tr').remove();
    }

    function deactive_confirm() {
        return confirm("Are you sure you want to deactive this?");
    }

    function active_confirm() {
        return confirm("Are you sure you want to active this?");
    }
</script>
<script>
    // insert modal
    document.addEventListener("DOMContentLoaded", function() {
        var inventorytypeSelect = document.getElementById("inventorytype");
        var uniformsizeDiv = document.getElementById("uniformsizeDiv");
        var uniformsizeSelect = document.getElementById("uniformsize");
    
        inventorytypeSelect.addEventListener("change", function() {
            if (inventorytypeSelect.value === "1" || inventorytypeSelect.value === "2") {
                uniformsizeDiv.style.display = "block";
                uniformsizeSelect.setAttribute("required", "required");
            } else {
                uniformsizeDiv.style.display = "none";
                uniformsizeSelect.removeAttribute("required");
            }
        });
    
        // Trigger the change event initially to set the initial state
        inventorytypeSelect.dispatchEvent(new Event("change"));
    });


    // approvel modal
    function approvaluniformSizeshow(inventorytypeSelect){
        var uniformsizeDiv = document.getElementById("app_uniformsizeDiv");
        var uniformsizeSelect = document.getElementById("app_uniformsize");
            if (inventorytypeSelect == "1" || inventorytypeSelect == "2") {
                uniformsizeDiv.style.display = "block";
                uniformsizeSelect.setAttribute("required", "required");
            } else {
                uniformsizeDiv.style.display = "none";
                uniformsizeSelect.removeAttribute("required");
            }

        }

        // edit modal
    function edituniformSizeshow(inventorytypeSelect){
        var uniformsizeDiv = document.getElementById("edit_uniformsizeDiv");
        var uniformsizeSelect = document.getElementById("edit_uniformsize");
            if (inventorytypeSelect == "1" || inventorytypeSelect == "2") {
                uniformsizeDiv.style.display = "block";
                uniformsizeSelect.setAttribute("required", "required");
            } else {
                uniformsizeDiv.style.display = "none";
                uniformsizeSelect.removeAttribute("required");
            }

        }

        // view modal
    function viewuniformSizeshow(inventorytypeSelect){
        var uniformsizeDiv = document.getElementById("view_uniformsizeDiv");
        var uniformsizeSelect = document.getElementById("view_uniformsize");
            if (inventorytypeSelect == "1" || inventorytypeSelect == "2") {
                uniformsizeDiv.style.display = "block";
                uniformsizeSelect.setAttribute("required", "required");
            } else {
                uniformsizeDiv.style.display = "none";
                uniformsizeSelect.removeAttribute("required");
            }

        }
    </script>
    
@endsection