<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Grn;
use App\Grndetail;
use App\inventory_list_price_summary;
use App\Porder;
use App\Porderdetail;
use App\Purchase_grn_bill;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\Datatables\Facades\Datatables;

class GRNcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $items = DB::table('inventorylists')->select('inventorylists.*')
        ->whereIn('inventorylists.status', [1, 2])
        // ->where('inventorylists.approve_status', 1)
        ->get();

        $stores = DB::table('storelists')->select('storelists.*')
        ->whereIn('storelists.status', [1, 2])
        ->where('storelists.approve_status', 1)
        ->get();

        $suppliers = DB::table('suppliers')->select('suppliers.*')
        ->whereIn('suppliers.status', [1, 2])
        ->where('suppliers.approve_status', 1)
        ->get();

        $porders = DB::table('porders')
        ->leftjoin('porderdetails', 'porderdetails.porder_id', '=', 'porders.id')
        ->select('porders.*')
        ->whereIn('porders.status', [1, 2])
        ->where('porders.approve_status', 1)
        ->whereColumn('porderdetails.qty', '!=', 'grn_issue_qty')
        ->groupBy('porders.id')
        // ->where('porders.grn_status', 0)
        ->get();
        return view('GRN.grn', compact('items','suppliers','porders','stores','userPermissions'));
    }

    public function getsupplier($porderid)
    {
        $suppliers = DB::table('suppliers')
        ->leftjoin('porders', 'porders.supplier_id', '=', 'suppliers.id')
        ->select('suppliers.*')
        ->where('porders.id', '=', $porderid)->get();
    
        return response()->json($suppliers);
    }

    public function getitem($porderid)
    {
        $items = DB::table('porders')
        ->leftjoin('porderdetails', 'porderdetails.porder_id', '=', 'porders.id')
        ->leftjoin('items', 'porderdetails.item_id', '=', 'items.id')
        ->select('items.*')
        ->where('porders.id', '=', $porderid)->get();
    
        return response()->json($items);
    }

    public function getitemwithoutporder($supplier)
    {
        $items = DB::table('items')
        ->select('items.*')
        ->whereIn('items.status', [1, 2])
        ->where('items.approve_status', 1)
        ->where('items.supplier_id', '=', $supplier)->get();
    
        return response()->json($items);
    }

    public function edit_porderItemget($porderid)
    {
        $items = DB::table('porders')
        ->leftjoin('porderdetails', 'porderdetails.porder_id', '=', 'porders.id')
        ->leftjoin('items', 'porderdetails.item_id', '=', 'items.id')
        ->select('items.*')
        ->where('porders.id', '=', $porderid)->get();
    
        return response()->json($items);
    }

    public function getpurchasepricetogrn(Request $request){

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('porderdetails')
        ->select('porderdetails.*')
        ->where('porderdetails.item_id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
}

public function getpricewithoutporder(Request $request){

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('items')
    ->select('items.*')
    ->where('items.id', $id)
    ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}

public function insert(Request $request){

    $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Grn-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

    $grn = new Grn();
    $grn->porder_id = $request->input('porder');
    $grn->grn_date = $request->input('orderdate');
    $grn->bill_date = $request->input('billdate');
    $grn->batch_no = $request->input('batchno');
    $grn->supplier_id = $request->input('supplier');
    $grn->terms = $request->input('terms');
    $grn->store_id = $request->input('store');
    $grn->employee_id = $request->input('employee');
    $grn->sub_total = $request->input('sub_total');
    $grn->discount = $request->input('discount');
    $grn->total = $request->input('net_total');
    $grn->remark = $request->input('remark');
   
    $grn->confirm_status = '0';
    $grn->status = '1';
    $grn->approve_status = '0';
    $grn->approve_01 = '0';
    $grn->approve_02 = '0';
    $grn->approve_03 = '0';
    $grn->create_by = Auth::id();
    $grn->update_by = '0';
    $grn->save();

    $requestID = $grn->id;
    $date=$request->input('orderdate');
    $batch_no=$request->input('batchno');

    $DataArray = $request->input('DataArray');
    foreach ($DataArray as $rowDataArray) {
        $itemid= $rowDataArray['itemName'];
        $qty = $rowDataArray['qty'];
        $unitPrice = $rowDataArray['unitPrice'];
        $total = $rowDataArray['total'];
        $vat_precentage = $rowDataArray['vat_precentage'];
        $vat_amount = $rowDataArray['vat_amount'];
        $aftervat = $rowDataArray['aftervat'];
        $detailID = $rowDataArray['porderdetail_id'];

        $grndetail = new Grndetail();
        $grndetail->date = $date;
        $grndetail->item_id = $itemid;
        $grndetail->qty = $qty;
        $grndetail->unit_price = $unitPrice;
        $grndetail->total = $total;
        $grndetail->vat_precentage = $vat_precentage;
        $grndetail->vat_amount = $vat_amount;
        $grndetail->total_after_vat = $aftervat;
        $grndetail->grn_id = $requestID;
        $grndetail->porderdetail_id = $detailID;
        $grndetail->status = '1';
        $grndetail->create_by = Auth::id();
        $grndetail->update_by = '0';
        $grndetail->save();

    }

    // update porder qty
    foreach ($DataArray as $rowDataArray) {
        if($rowDataArray['edit_insertstatus'] == "ExistingData"){
            $qty = $rowDataArray['qty'];
            $detailID = $rowDataArray['porderdetail_id'];

            $data = DB::table('porderdetails')
            ->select('porderdetails.*')
            ->where('porderdetails.id', $detailID)
            ->where('porderdetails.status', 1)
            ->get(); 
            $orderqty = '';
            foreach ($data as $row) {
             $orderqty=$row->grn_issue_qty;
            }
            $newqty=$orderqty+ $qty;
            $porderdetail = Porderdetail::where('id', $detailID)->first();
            $porderdetail->grn_issue_qty = $newqty;
            $porderdetail->update_by = Auth::id();
            $porderdetail->save();
        }
    }



    return response()->json(['success' => 'GRN Order is successfully Inserted']);
    // return response()->json(['status' => 1, 'message' => 'Issue is successfully Inserted']);
}

public function requestlist()
{
    $types = DB::table('grns')
        ->leftjoin('suppliers', 'grns.supplier_id', '=', 'suppliers.id')
        ->select('grns.*','suppliers.supplier_name AS supplier_name')
        ->whereIn('grns.status', [1, 2])
        ->get();

        return Datatables::of($types)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $btn = '';
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();

            if(in_array('Grn-create',$userPermissions)){
            $btn .= ' <button title="Bill" name="bill" id="'.$row->id.'" class="bill btn btn-outline-warning btn-sm"
            role="button"><i class="fas fa-file-invoice"></i></button>';
            }
                    if(in_array('Approve-Level-01',$userPermissions)){
                        if($row->approve_01 == 0){
                            $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" porder_id="'.$row->porder_id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                            $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }
                    if(in_array('Approve-Level-02',$userPermissions)){
                        if($row->approve_01 == 1 && $row->approve_02 == 0){
                            $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" porder_id="'.$row->porder_id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                             $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }

                    if(in_array('Approve-Level-03',$userPermissions)){
                        if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                            $btn .= ' <button name="appL3" id="'.$row->id.'" batch_no="'.$row->batch_no.'" porder_id="'.$row->porder_id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" porder_id="'.$row->porder_id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                             $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }

                        if($row->approve_03 == 1 ){
                       $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm"
                       role="button"><i class="fa fa-eye"></i></button>';
                       }
                      
                    // $permission = $user->can('Porder-edit');
                    // if($permission){
                    //     $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                    // }

                    if(in_array('Grn-status',$userPermissions)){
                        if($row->status == 1){
                            $btn .= ' <a href="'.route('grnstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                        }else{
                            $btn .= '&nbsp;<a href="'.route('grnstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                        }
                    }
                    // $permission = $user->can('Porder-delete');
                    // if($permission){
                    //     $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                    // }
          
            return $btn;
        })
       
        ->rawColumns(['action'])
        ->make(true);

    }

    public function edit(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Grn-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('grns')
        ->leftjoin('employees', 'grns.employee_id', '=', 'employees.id')
        ->select('grns.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('grns.id', $id)
        ->get(); 

        $requestlist = $this->reqestcountlist($id); 
    
        $responseData = array(
            'mainData' => $data[0],
            'requestdata' => $requestlist,
        );

        return response() ->json(['result'=>  $responseData]);
    }
    }

    private function reqestcountlist($id){

        $recordID =$id ;
    $data = DB::table('grndetails')
    ->leftjoin('grns', 'grndetails.grn_id', '=', 'grns.id')
    ->leftjoin('inventorylists', 'grndetails.item_id', '=', 'inventorylists.id')
    ->select('grndetails.*', 'inventorylists.name','inventorylists.inventorylist_id','inventorylists.id AS inven_id','inventorylists.uom', DB::raw('(grndetails.id) AS grndetailsID'))
    ->where('grndetails.grn_id', $recordID)
    ->where('grndetails.status', 1)
    ->get();  

    $data1 = DB::table('grns')
    ->join('porders', 'grns.porder_id', '=', 'porders.id')
    ->join('porderdetails', 'porderdetails.porder_id', '=', 'porders.id')
    ->select('porderdetails.qty AS PorderQty')
    ->where('grns.id', $recordID)
    ->where('grns.status', 1)
    ->get();  

    $dataArray = [];

    foreach ($data1 as $row) {
        $dataArray[] = [
            'PorderQty' => $row->PorderQty,
        ];
    }
    // dd($dataArray);

    $inventoryListData = DB::table('inventorylists')
     ->select('inventorylists.*')
     ->whereIn('inventorylists.status', [1, 2])
    //  ->where('inventorylists.approve_status', 1)
     ->get();  

   $uniqueIdentifier = 1;
   $count=0;
   $htmlTable = '';
   foreach ($data as $row) {
      
    $total = $row->unit_price * $row->qty;
     // Generate a unique identifier

     $htmlTable .= '<tr>';
     $htmlTable .= '<td><input style="width:100px;border: none;" type="text" name="edit1_inventorylist_id[]" id="edit1_inventorylist_id' . $uniqueIdentifier . '" value="' . $row->inventorylist_id . '" readonly></td>';
     $htmlTable .= '<td>';
     $htmlTable .= '<select required name="edit1_inventorylist_select[]" id="edit1_inventorylist_select' . $uniqueIdentifier . '" size="1" onfocus="this.size = 8"  onblur="this.size = 1; this.blur()" onchange="getItemeditDetailsEdit(this.value, '.$uniqueIdentifier.')">';
    foreach ($inventoryListData as $inventory) {
         $selected = ($inventory->id == $row->inven_id) ? 'selected' : '';
         $htmlTable .= '<option value="' . $inventory->id . '" '.$selected.' >' . $inventory->inventorylist_id . ' - ' . $inventory->name  . ' ' . ($inventory->uniform_size==null?"":$inventory->uniform_size.'"') . '</option>';
     }
 
     $htmlTable .= '</select>';
     $htmlTable .= '</td>';
     $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit1_uom[]" id="edit1_uom' . $uniqueIdentifier . '" value="' . $row->uom . '" readonly></td>';
     $htmlTable .= '<td><span style="color:red">(' . $dataArray[$count]['PorderQty'] .')</span><input style="width:70%;" type="number" name="edit1_qty[]" id="edit1_qty' . $uniqueIdentifier . '" value="' . ($row->qty) . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')"></td>';
     $htmlTable .= '<td style="width:20%;"><input style="border: none;width:100%;" type="number" name="edit1_unit_price[]" id="edit1_unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')" readonly></td>';
     $htmlTable .= '<td style="width:20%;"><input style="width:100%;border: none;" type="text" name="edit1_total[]" id="edit1_total' . $uniqueIdentifier . '" value="' .($row->total). '" readonly></td>';
     $htmlTable .= '<td style="width:20%;"><input style="width:100%;border: none;" type="text" name="edit1_vat[]" id="edit1_vat' . $uniqueIdentifier . '" value="' .($row->vat_precentage). '" readonly></td>';
     $htmlTable .= '<td style="width:20%;"><input style="width:100%;border: none;text-align:right" type="text" name="edit1_vatamount[]" id="edit1_vatamount' . $uniqueIdentifier . '" value="' .($row->vat_amount). '" readonly></td>';
     $htmlTable .= '<td style="width:10%;"><input style="width:100%;border: none;text-align:right" type="text" name="edit1_aftervat[]" id="edit1_aftervat' . $uniqueIdentifier . '" value="' .($row->total_after_vat). '" readonly></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_insertstatus[]" id="edit1_insertstatus' . $uniqueIdentifier . '" value="PorderExistingData"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_grndetail_id[]" id="edit1_grndetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_porderdetail_id[]" id="edit1_porderdetail_id' . $uniqueIdentifier . '" value="' . $row->porderdetail_id . '"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_preqty[]" id="edit1_preqty' . $uniqueIdentifier . '" value="' . $row->qty . '"></td>';
     $htmlTable .= '</tr>';

    $uniqueIdentifier++;
    $count++;
   }

       return $htmlTable;

   }

   public function editwithoutporder(Request $request){
   
    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Grn-edit', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('grns')
    ->leftjoin('employees', 'grns.employee_id', '=', 'employees.id')
    ->select('grns.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
    ->where('grns.id', $id)
    ->get(); 

    $requestlist = $this->reqestcountlist2($id); 

    $responseData = array(
        'mainData' => $data[0],
        'requestdata' => $requestlist,
    );

    return response() ->json(['result'=>  $responseData]);
}
}

private function reqestcountlist2($id){

    $recordID =$id ;
$data = DB::table('grndetails')
->leftjoin('grns', 'grndetails.grn_id', '=', 'grns.id')
->leftjoin('inventorylists', 'grndetails.item_id', '=', 'inventorylists.id')
->select('grndetails.*', 'inventorylists.name','inventorylists.inventorylist_id','inventorylists.id AS inven_id','inventorylists.uom', DB::raw('(grndetails.id) AS grndetailsID'))
->where('grndetails.grn_id', $recordID)
->where('grndetails.status', 1)
->get();  

// dd($dataArray);

$inventoryListData = DB::table('inventorylists')
 ->select('inventorylists.*')
 ->whereIn('inventorylists.status', [1, 2])
//  ->where('inventorylists.approve_status', 1)
 ->get();  

$uniqueIdentifier = 1;
$count=0;
$htmlTable = '';
foreach ($data as $row) {
  
$total = $row->unit_price * $row->qty;
 // Generate a unique identifier

 $htmlTable .= '<tr>';
 $htmlTable .= '<td><input style="width:100px;border: none;" type="text" name="edit1_inventorylist_id[]" id="edit1_inventorylist_id' . $uniqueIdentifier . '" value="' . $row->inventorylist_id . '" readonly></td>';
 $htmlTable .= '<td>';
 $htmlTable .= '<select required name="edit1_inventorylist_select[]" id="edit1_inventorylist_select' . $uniqueIdentifier . '" size="1" onfocus="this.size = 8"  onblur="this.size = 1; this.blur()" onchange="getItemeditDetailsEdit(this.value, '.$uniqueIdentifier.')">';
foreach ($inventoryListData as $inventory) {
     $selected = ($inventory->id == $row->inven_id) ? 'selected' : '';
     $htmlTable .= '<option value="' . $inventory->id . '" '.$selected.' >' . $inventory->inventorylist_id . ' - ' . $inventory->name  . ' ' . ($inventory->uniform_size==null?"":$inventory->uniform_size.'"') . '</option>';
 }

 $htmlTable .= '</select>';
 $htmlTable .= '</td>';
 $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit1_uom[]" id="edit1_uom' . $uniqueIdentifier . '" value="' . $row->uom . '" readonly></td>';
 $htmlTable .= '<td style="width:20%;"><span style="color:red">(' . $row->qty .')</span><input style="width:100%;" type="number" name="edit1_qty[]" id="edit1_qty' . $uniqueIdentifier . '" value="' . ($row->qty) . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')"></td>';
 $htmlTable .= '<td style="width:20%;"><span style="color:red">(' . $row->unit_price .')</span><input style="width:100%;" type="number" name="edit1_unit_price[]" id="edit1_unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')"></td>';
 $htmlTable .= '<td style="width:20%;"><input style="width:100%;border: none;" type="text" name="edit1_total[]" id="edit1_total' . $uniqueIdentifier . '" value="' .($row->total). '"></td>';
 $htmlTable .= '<td><input style="width:100%;border: none;" type="text" name="edit1_vat[]" id="edit1_vat' . $uniqueIdentifier . '" value="' .($row->vat_precentage). '" readonly></td>';
 $htmlTable .= '<td style="width:20%;"><input style="width:100%;border: none;text-align:right" type="text" name="edit1_vatamount[]" id="edit1_vatamount' . $uniqueIdentifier . '" value="' .($row->vat_amount). '" readonly></td>';
 $htmlTable .= '<td style="width:10%;"><input style="width:100%;border: none;text-align:right" type="text" name="edit1_aftervat[]" id="edit1_aftervat' . $uniqueIdentifier . '" value="' .($row->total_after_vat). '" readonly></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_insertstatus[]" id="edit1_insertstatus' . $uniqueIdentifier . '" value="ExistingData"></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_grndetail_id[]" id="edit1_grndetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_porderdetail_id[]" id="edit1_porderdetail_id' . $uniqueIdentifier . '" value="0"></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_preqty[]" id="edit1_preqty' . $uniqueIdentifier . '" value="' . $row->qty . '"></td>';
 $htmlTable .= '<td><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>';
 $htmlTable .= '</tr>';

$uniqueIdentifier++;
$count++;
}

   return $htmlTable;

}

   public function editlist(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('grndetails')
                ->select('grndetails.*')
                ->where('grndetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}

public function update(Request $request){
    
    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Grn-edit', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
   
        $current_date_time = Carbon::now()->toDateTimeString();

        $hidden_id = $request->input('hidden_id');

        $id =  $request->hidden_id ;
        $form_data = array(
            'grn_date' => $request->input('orderdate'),
            'bill_date' => $request->input('billdate'),
            'batch_no' => $request->input('batchno'),
            'supplier_id' => $request->input('supplier'),
            'terms' => $request->input('terms'),
            'store_id' => $request->input('store'),
            'employee_id' => $request->input('employee'),
            'sub_total' => $request->input('sub_total'),
            'discount' => $request->input('discount'),
            'total' => $request->input('net_total'),
            'remark' => $request->input('remark'),
                'confirm_status' =>  '0',
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time, 
            );
    
            Grn::findOrFail($id)
        ->update($form_data);

        DB::table('grndetails')
        ->where('grndetails.grn_id', $hidden_id)
        ->delete();
        $date=$request->input('orderdate');
        $tableData = $request->input('tableData');

        $DataArray = $request->input('DataArray');
        foreach ($DataArray as $rowDataArray) {
            $itemid= $rowDataArray['itemName'];
            $qty = $rowDataArray['qty'];
            $unitPrice = $rowDataArray['unitPrice'];
            $total = $rowDataArray['total'];
            $vat_precentage = $rowDataArray['vat_precentage'];
            $vat_amount = $rowDataArray['vat_amount'];
            $aftervat = $rowDataArray['aftervat'];
            $detailID = $rowDataArray['porderdetail_id'];
    
            $grndetail = new Grndetail();
            $grndetail->date = $date;
            $grndetail->item_id = $itemid;
            $grndetail->qty = $qty;
            $grndetail->unit_price = $unitPrice;
            $grndetail->total = $total;
            $grndetail->vat_precentage = $vat_precentage;
            $grndetail->vat_amount = $vat_amount;
            $grndetail->total_after_vat = $aftervat;
            $grndetail->grn_id = $hidden_id;
            $grndetail->porderdetail_id = $detailID;
            $grndetail->status = '1';
            $grndetail->create_by = Auth::id();
            $grndetail->update_by = '0';
            $grndetail->save();
    
        }
      // update porder qty
      foreach ($DataArray as $rowDataArray) {
        if($rowDataArray['edit_insertstatus'] == "PorderExistingData"){
            $qty = $rowDataArray['qty'];
            $preqty = $rowDataArray['preqty'];
            $detailID = $rowDataArray['porderdetail_id'];

            $data = DB::table('porderdetails')
            ->select('porderdetails.*')
            ->where('porderdetails.id', $detailID)
            ->where('porderdetails.status', 1)
            ->get(); 
            $orderqty = '';
            foreach ($data as $row) {
             $orderqty=$row->grn_issue_qty;
            }
            $restoreqty=$orderqty-$preqty;
            $newqty=$restoreqty+$qty;

            $porderdetail = Porderdetail::where('id', $detailID)->first();
            $porderdetail->grn_issue_qty = $newqty;
            $porderdetail->update_by = Auth::id();
            $porderdetail->save();
        }
    }
    
    return response()->json(['success' => 'GRN Order is Successfully Updated']);
}


public function approvel_details(Request $request){
    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    } 

    $id = Request('id');
    if (request()->ajax()){
        $data = DB::table('grns')
        ->leftjoin('employees', 'grns.employee_id', '=', 'employees.id')
        ->select('grns.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('grns.id', $id)
        ->get(); 

    $requestlist = $this->app_reqestcountlist($id); 

    $responseData = array(
        'mainData' => $data[0],
        'requestdata' => $requestlist,
    );

return response() ->json(['result'=>  $responseData]);
}
}

private function app_reqestcountlist($id){

    $recordID =$id ;
    $recordID =$id ;
$data = DB::table('grndetails')
->leftjoin('grns', 'grndetails.grn_id', '=', 'grns.id')
->leftjoin('inventorylists', 'grndetails.item_id', '=', 'inventorylists.id')
->select('grndetails.*', 'inventorylists.name','inventorylists.uniform_size','inventorylists.inventorylist_id','inventorylists.id AS inven_id','inventorylists.uom', DB::raw('(grndetails.id) AS grndetailsID'))
->where('grndetails.grn_id', $recordID)
->where('grndetails.status', 1)
->get();  
 

   $htmlTable = '';
   foreach ($data as $row) {
      
    $total=$row->unit_price*$row->qty;

    $htmlTable .= '<tr>';
    $htmlTable .= '<td class="text-center">' . $row->inventorylist_id . '</td>'; 
    $htmlTable .= '<td class="text-center">' . $row->name  . ' ' . ($row->uniform_size==null?"":$row->uniform_size.'"') . '</td>'; 
    $htmlTable .= '<td class="text-center">' . $row->uom . '</td>'; 
    $htmlTable .= '<td class="text-center">' . $row->qty . '</td>'; 
    $htmlTable .= '<td class="text-right">' . number_format($row->unit_price, 2) . '</td>';
    $htmlTable .= '<td class="text-right">' .  number_format($total,2) . '</td>'; 
    $htmlTable .= '<td class="text-right">' .  number_format($row->vat_precentage,1) . '</td>'; 
    $htmlTable .= '<td class="text-right">' .  number_format($row->vat_amount,2) . '</td>'; 
    $htmlTable .= '<td class="text-right">' .  number_format($row->total_after_vat,2) . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->inven_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function delete(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Grn-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Grn::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'GRN Order is successfully Deleted']);

}


public function approve(Request $request){

    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    } 
   
    $id = Request('id');
     $applevel = Request('applevel');
     $current_date_time = Carbon::now()->toDateTimeString();

     if($applevel == 1){
        
        $form_data = array(
            'approve_01' =>  '1',
            'approve_01_time' => $current_date_time,
            'approve_01_by' => Auth::id(),
        );
        Grn::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'GRN Order is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Grn::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'GRN Order is successfully Approved']);

     }else{
        $form_data = array(
            'confirm_status' =>  '1',
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Grn::findOrFail($id)
        ->update($form_data);

        $id = Request('porder_id');
        $form_data1 = array(
            'grn_status' =>  '1',
        );
        Porder::findOrFail($id)
        ->update($form_data1);

       return response()->json(['success' => 'GRN Order is successfully Approved']);
      }
}

public function reject(Request $request){

    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    } 
      
    $id = Request('id');
     $current_date_time = Carbon::now()->toDateTimeString();
        
        $form_data = array(
            'confirm_status' =>  '2',
        );
        Grn::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'GRN Order is successfully Reject']);

}

public function stockupdate(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Approve-Level-03', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


    $store_id=$request->input('store_id');
    $batch_no=$request->input('batchno');

    $tableData = $request->input('tableData');
    
    $assetvalue="brandnew";
    foreach ($tableData as $rowtabledata) {
        $item = $rowtabledata['col_10'];
        $qty = $rowtabledata['col_4'];
        $unit_price = $rowtabledata['col_5'];
        $itemname = $rowtabledata['col_2'];

    $newStock = new Stock();
    $newStock->batch_no = $batch_no;
    $newStock->qty = $qty;
    $newStock->unit_price = $unit_price;
    $newStock->item_id = $item;
    $newStock->store_id = $store_id;
    $newStock->status = '1';
    $newStock->create_by = Auth::id();
    $newStock->save();

    $pricesummary = new inventory_list_price_summary();
    $pricesummary->asset_value = $assetvalue;
    $pricesummary->item_id = $item;
    $pricesummary->item_name = $itemname;
    $pricesummary->unit_price = $unit_price;
    $pricesummary->status = '1';
    $pricesummary->create_by = Auth::id();
    $pricesummary->save();
    }
    return response()->json(['success' => 'Stock Updated']);

}




public function status($id,$statusid){

        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Grn-status', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 

    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Grn::findOrFail($id)
        ->update($form_data);

        return redirect()->route('grn');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Grn::findOrFail($id)
        ->update($form_data);

        return redirect()->route('grn');
    }

}


public function deletelist(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Grn-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 
    
        $id = Request('id');

    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Grndetail::findOrFail($id)
    ->update($form_data);

    $id = Request('grnid');
    $cost = Request('cost');
    $total = Request('total');

    $newtotal=$total-$cost;

    $form_data1 = array(
        'total' =>  $newtotal,
    );
    Grn::findOrFail($id)
    ->update($form_data1);

    
    return response()->json(['success' => 'GRN is successfully Deleted']);

}


public function view(Request $request)
{

    $id = $request->input('id');

        $types = DB::table('grndetails')
        ->leftjoin('inventorylists', 'grndetails.item_id', '=', 'inventorylists.id')
        ->select('grndetails.*','inventorylists.id AS inventoryid','inventorylists.inventorylist_id AS itemcode','inventorylists.name AS inventoryname','inventorylists.uniform_size AS uniform_size')
        ->whereIn('grndetails.status', [1, 2])
        ->where('grndetails.grn_id', $id)
        ->get();
    

        return Datatables::of($types)
        ->addIndexColumn()
        ->make(true);
}

public function viewDetails(Request $request){
    $id = $request->input('id');

    $data = DB::table('grns')
        ->leftJoin('suppliers', 'grns.supplier_id', '=', 'suppliers.id')
        ->select('grns.*', 'suppliers.*','grns.id AS grnid')
        ->where('grns.id', $id)
        ->get();

    $suppliers = [];
    foreach ($data as $order) {
        $supplier = (array) $order; // Convert the object to an array

        // Retrieve all contact details for the current supplier
        $contacts = DB::table('suppliercontacts')
            ->where('suppliercontacts.supplier_id', $order->supplier_id)
            ->select('suppliercontacts.*')
            ->get();

        $supplier['contacts'] = $contacts; // Add contacts to the supplier array
        $suppliers[] = $supplier; // Add the supplier array to the suppliers array
    }

    return response()->json(['result' => $suppliers,'maindata' =>$data]);
}

public function getbatchno(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('suppliers')
    ->select('suppliers.id AS supplierid')
    ->where('suppliers.id', $id)
    ->get(); 

    foreach ($data as $row) {

        $supplierid=$row->supplierid;
    }

    $data1 = DB::table('grns')
    ->select('grns.*')
    ->get(); 
    $rowCount = count($data1);

    if ($rowCount === 0) {
        $batchno=date('dmY').'001';
    }
    else{
        $count='000'.($rowCount+1);
        $count=substr($count, -3);
        $batchno=date('dmY').$count;
    }
    $data2= $supplierid.$batchno;

    $terms = DB::table('suppliers')
    ->select('suppliers.payment_terms AS payment_terms')
    ->where('suppliers.id', $id)
    ->get(); 
    $payment_terms='';
    foreach ($terms as $row) {

        $payment_terms=$row->payment_terms;
    }

    return response() ->json(['result'=> $data2,$payment_terms]);
}
}

    public function porderdetails(Request $request){

            $id = Request('id');
            if (request()->ajax()){
            $porders = DB::table('porders')
            ->leftjoin('employees', 'porders.employee_id', '=', 'employees.id')
            ->leftjoin('suppliers', 'porders.supplier_id', '=', 'suppliers.id')
            ->leftjoin('storelists', 'porders.store_id', '=', 'storelists.id')
            ->select('porders.*','suppliers.id AS supplier_name','suppliers.payment_terms AS payment_terms','storelists.id AS storename','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
            ->where('porders.id', '=', $id)->get();

        $requestlist = $this->porderdetailsTablelist($id); 
    
        $responseData = array(
            'mainData' => $porders[0],
            'requestdata' => $requestlist,
        );

        return response() ->json(['result'=>  $responseData]);
    }
    }
    

    private function porderdetailsTablelist($id){

        $recordID =$id ;
    $data = DB::table('porderdetails')
    ->leftjoin('porders', 'porderdetails.porder_id', '=', 'porders.id')
    ->leftjoin('inventorylists', 'porderdetails.inventorylist_id', '=', 'inventorylists.id')
    ->select('porderdetails.*', 'inventorylists.name','inventorylists.inventorylist_id','inventorylists.id AS inven_id','inventorylists.uom', DB::raw('(porderdetails.id) AS porderdetailsID'))
    ->where('porderdetails.porder_id', $recordID)
    ->where('porderdetails.status', 1)
    ->get();  

    $inventoryListData = DB::table('inventorylists')
     ->select('inventorylists.*')
     ->whereIn('inventorylists.status', [1, 2])
    //  ->where('inventorylists.approve_status', 1)
     ->get();  

   $uniqueIdentifier = 1;
   $htmlTable = '';
   foreach ($data as $row) {
      
    $total = $row->unit_price * $row->qty;
     // Generate a unique identifier

     $htmlTable .= '<tr>';
     $htmlTable .= '<td><input style="width:100px;border: none;" type="text" name="edit_inventorylist_id[]" id="inventorylist_id' . $uniqueIdentifier . '" value="' . $row->inventorylist_id . '" readonly></td>';
     $htmlTable .= '<td>';
     $htmlTable .= '<select readonly required style="border: none; -webkit-appearance: none; -moz-appearance: none; appearance: none;" name="edit_inventorylist_select[]" id="inventorylist_select' . $uniqueIdentifier . '" size="1" onfocus="this.size = 8"  onblur="this.size = 1; this.blur()" onchange="getItemeditDetails(this.value, '.$uniqueIdentifier.')">';
    foreach ($inventoryListData as $inventory) {
         $selected = ($inventory->id == $row->inven_id) ? 'selected' : '';
         $htmlTable .= '<option value="' . $inventory->id . '" '.$selected.' >' . $inventory->inventorylist_id . ' - ' . $inventory->name  . ' ' . ($inventory->uniform_size==null?"":$inventory->uniform_size.'"') . '</option>';
     }
 
     $htmlTable .= '</select>';
     $htmlTable .= '</td>';
     $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit_uom[]" id="uom' . $uniqueIdentifier . '" value="' . $row->uom . '" readonly></td>';
     $htmlTable .= '<td style="width:120%;"><span style="color:red">(' . ($row->qty - $row->grn_issue_qty) . ')</span><input style="width:65%;" type="number" name="edit_qty[]" id="qty' . $uniqueIdentifier . '" value="' . ($row->qty - $row->grn_issue_qty) . '" onkeyup="editsum(this.value, '.$uniqueIdentifier.')" onclick="editsum(this.value, '.$uniqueIdentifier.')"></td>';
     $htmlTable .= '<td><input style="border: none;width:100px;text-align:right" type="number" name="edit_unit_price[]" id="unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum(this.value, '.$uniqueIdentifier.')"></td>';
     $htmlTable .= '<td><input style="width:100px;border: none;text-align:right" type="text" name="edit_total[]" id="total' . $uniqueIdentifier . '" value="' .(($row->qty - $row->grn_issue_qty) * $row->unit_price). '" readonly></td>';
     $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit_vat[]" id="vat' . $uniqueIdentifier . '" value="' .($row->vat_precentage). '" readonly></td>';
     $htmlTable .= '<td style="width:20%;"><input style="width:100%;border: none;text-align:right" type="text" name="edit_vatamount[]" id="vatamount' . $uniqueIdentifier . '" value="' .($row->vat_amount). '" readonly></td>';
     $htmlTable .= '<td style="width:10%;"><input style="width:100%;border: none;text-align:right" type="text" name="edit_aftervat[]" id="aftervat' . $uniqueIdentifier . '" value="' .($row->total_after_vat). '" readonly></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit_insertstatus[]" id="edit_insertstatus' . $uniqueIdentifier . '" value="ExistingData"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="porderdetail_id[]" id="porderdetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
     $htmlTable .= '<td><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>';
     $htmlTable .= '</tr>';

    $uniqueIdentifier++;
   }

       return $htmlTable;

   }

   public function grnbillrefnoget(Request $request){
    $porder_id = Request('porder_id');
    $grn_id = Request('grn_id');


    $data1 = DB::table('purchase_grn_bills')
    ->select('purchase_grn_bills.*')
    ->where('purchase_grn_bills.purchase_id', $porder_id)
    ->where('purchase_grn_bills.grn_id', $grn_id)
    ->get(); 
    $rowCount = count($data1);
    $refno='';
    if ($rowCount == 1) {
        $refno=date('dmY').'1';
        foreach ($data1 as $row) {

            $refno=$row->refno;
        }
    }
    else{

        $refno=date('dmY'). $porder_id.$grn_id;
    } 

    return response() ->json(['result'=> $refno]);
}



   public function grnbillprint(Request $request){
    $porder_id=$request->input('pid');
    $grn_id=$request->input('gid');
    $ref=$request->input('ref');
    $memo=$request->input('memo');

    $data1 = DB::table('purchase_grn_bills')
    ->select('purchase_grn_bills.*')
    ->where('purchase_grn_bills.purchase_id', $porder_id)
    ->where('purchase_grn_bills.grn_id', $grn_id)
    ->get(); 
    $rowCount = count($data1);
    if ($rowCount == 0) {
        $purchase_grn_bill = new Purchase_grn_bill();
        $purchase_grn_bill->purchase_id = $request->input('pid');
        $purchase_grn_bill->grn_id = $request->input('gid');
        $purchase_grn_bill->refno = $request->input('ref');
        $purchase_grn_bill->created_by = Auth::id();
        $purchase_grn_bill->save();
    }

    $id = $request->input('id');

    $types = DB::table('grndetails')
    ->leftjoin('inventorylists', 'grndetails.item_id', '=', 'inventorylists.id')
    ->select('grndetails.*','inventorylists.name','inventorylists.inventorylist_id','inventorylists.uom','inventorylists.uniform_size')
    ->whereIn('grndetails.status', [1, 2])
    ->where('grndetails.grn_id', $id)
    ->get();


    $data = DB::table('grns')
        ->leftJoin('suppliers', 'grns.supplier_id', '=', 'suppliers.id')
        ->select('grns.*','grns.id as grnid','grns.porder_id as porder_id', 'suppliers.*','suppliers.supplier_name as supplier_name','suppliers.address1 as address1','suppliers.address2 as address2','suppliers.city as city')
        ->where('grns.id', $id)
        ->get();

    $suppliers = [];
    $contactNumbers = [];
    $emails = [];
    $tblinvoice='';
    foreach ($data as $order) {
        $supplier = (array) $order; // Convert the object to an array

        // Retrieve all contact details for the current supplier
        $contacts = DB::table('suppliercontacts')
            ->where('suppliercontacts.supplier_id', $order->supplier_id)
            ->select('suppliercontacts.*')
            ->get();

            foreach ($contacts as $contact) {
                $contactNumbers[] = $contact->contact;
                $emails[] = $contact->email;
            }
        

        $supplier['contacts'] = $contacts; // Add contacts to the supplier array
        $suppliers[] = $supplier; // Add the supplier array to the suppliers array
    }

    $date='';
    $suppliername='';
    $supplieraddress='';
    $porderid='';
    $totalcost='';
    foreach ($data as $grnlist) {
        $date = $grnlist->grn_date;
        $billdate = $grnlist->bill_date;
        $terms = $grnlist->terms;
        $suppliername = $grnlist->supplier_name;
        $supplieraddress = $grnlist->address1.', '.$grnlist->address2.', '.$grnlist->city;
        $grnid = $grnlist->grnid;
        $porderid = $grnlist->porder_id;
        $sub_total = $grnlist->sub_total;
        $discount_amount = $grnlist->discount;
        $totalcost = $grnlist->total;
    }
    $count = 1;
    
    foreach ($types as $rowlist) {
        $tblinvoice.='
        <tr>
        <td style="font-size:10px; border:1px solid black; text-align:center;" class="text-center">'. $count.'</td>
        <td style="font-size:10px; border:1px solid black; text-align:center;" class="text-center">'.$rowlist->inventorylist_id.'</td>
        <td colspan="4" style="padding-left: 5px;font-size:10px; border:1px solid black; text-align:left;" class="text-center">'.$rowlist->name.' '.($rowlist->uniform_size==null?"":$rowlist->uniform_size.'"').'</td>
        <td colspan="2" style="font-size:10px; border:1px solid black; text-align:center;" class="text-center">'.$rowlist->uom.'</td>
        <td style="font-size:10px; border:1px solid black; text-align:center;" class="text-right">'.$rowlist->qty.'</td>
        <td colspan="2" style="font-size:10px; border:1px solid black; text-align:right;" class="text-right">'.number_format(($rowlist->unit_price), 2).'</td>
        <td colspan="2" style="font-size:10px; border:1px solid black; text-align:right;" class="totalrawcost text-right">'.number_format(($rowlist->qty * $rowlist->unit_price), 2).'</td>    
        <td style="font-size:10px; border:1px solid black; text-align:right;" class="text-right">'.number_format(($rowlist->vat_precentage), 1).'</td>
        <td colspan="2" style="font-size:10px; border:1px solid black; text-align:right;" class="text-right">'.number_format(($rowlist->vat_amount), 2).'</td>
        <td colspan="2" style="font-size:10px; border:1px solid black; text-align:right;" class="text-right">'.number_format(($rowlist->total_after_vat), 2).'</td>         
    </tr>
        
        ';
        $count++;
    }


    $contactview = '';
    foreach ($contactNumbers as $conlist) {
        $contactview .= $conlist . '/';
    } 
    $contactview = rtrim($contactview, '/');

    
    $emailview = '';
    foreach ($emails as $emaillist) {
        $emailview .= $emaillist . '/';
    } 
    $emailview = rtrim($emailview, '/');

    $html='';

$html ='

<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  
  
    <title>Purchase order</title>
    <style media="print">
        * {
            font-family: "Fira Mono", monospace;
        }

        table,
        tr,
        th,
        td {
            font-family: "Fira Mono", monospace;
        }

        img {
            width: 200px;
            height: 100px;
        }

        @page {
            size: 80mm 100mm;
            /* Set the print size width to 80mm and height to 100mm */
        }

        body {
            width: 80mm;
            /* Set the body width to 80mm */
        }

        #DivIdToPrint {
            border: 1px solid black;
            padding: 10px;
            width: 100%;
            /* Set the div width to 100% */
        }
    </style>
    <style>
        * {
            font-family: "Fira Mono", monospace;
        }

        table,
        tr,
        th,
        td {
            font-family: "Fira Mono", monospace;
        }

        img {
            width: 100px;
            height: 100px;
        }

        #DivIdToPrint {
            width: 100%;
            /* Set the div width to 100% */
        }
    </style>
    <style>
        * {
            font-family: "Cutive Mono", monospace;
        }

        table,
        tr,
        th,
        td {
            font-family: "Cutive Mono", monospace;
        }

        img {
            width: 100px;
            height: 100px;
        }
    </style>
</head>

<body>
    <div id="DivIdToPrint">
        <table style="width:100%;">
            <tr >
            
                <td style="padding-left: 35%; font-size:6px;" colspan="3">
                    <h6 class="font-weight-light" style="margin-top:0;margin-bottom:0;">
                        
                    </h6>
                </td>
            </tr>
            <tr>
            <td style="text-align: left;width:20px;"><img id="logo" src="./images/logogaja.png" width="50" alt="Logo"></td>
            <td style="text-align: center; font-size:14px; padding-top: 5px; padding-right: 120px" colspan="2">
            <h3 style="margin-left:0px;">Gajashakthi Security Service (Pvt) Ltd</h3>
            <h5 class="font-weight-light">Bill</h5>
            </td>
        </tr>
            <tr>
                <td colspan="2"  style="text-align: left; font-size:12px;">Date: '.$date.'</td>
                <td style="padding-left:130px; font-size:12px;text-align: right;">GRN No: GRN-'. $grnid.'</td>
            </tr>
            <tr>
                <td  colspan="2" style="text-align: left; font-size:12px;">Supplier: '. $suppliername.'</td>
                <td style="padding-left:130px; font-size:12px;text-align: right;">Purchase No: PO-'. $porderid.'</td>
            </tr>
            <tr>
            <td colspan="2" style="text-align: left; font-size:12px;">Contact No: '.$contactview.'</td>
            <td style="padding-left:130px; font-size:12px;text-align: right;">Bill Date: '. $billdate.'</td>
        </tr>
        <tr>
        <td colspan="2" style="text-align: left; font-size:12px;">Email: '. $emailview.'</td>
        <td style="padding-left:130px; font-size:12px;text-align: right;">Ref.No: '.$ref.'</td>
    </tr>
    <tr>
    <td colspan="2" style="text-align: left; font-size:12px;">Address: '.$supplieraddress.'</td>
    <td style="padding-left:130px; font-size:12px;text-align: right;">Terms: '. $terms.'</td>
</tr>
            <tr>
                <td style="text-align: center; margin-bottom:50px" colspan="3">
                    <table class="tg" style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
                        <tr style="text-align:right; font-weight:bold; font-size:5px;">
                        <td style="text-align: center; font-size:14px;border:1px solid black;">#</td>
                            <td style="text-align: center; font-size:14px;border:1px solid black;">Item Code</td>
                            <td colspan="4" style="text-align: center; font-size:14px;border:1px solid black;">Item Name</td>
                            <td colspan="2" style="text-align: center; font-size:12px;border:1px solid black;">UOM</td>
                            <td style="text-align: center; font-size:12px;border:1px solid black;">Qty</td>
                            <td colspan="2" style="text-align: center; font-size:12px;border:1px solid black;">Unite Price</td>
                            <td colspan="2" style="text-align: center; font-size:12px;border:1px solid black;">Total</td>
                            <td style="text-align: center; font-size:12px;border:1px solid black;">Vat(%)</td>
                            <td colspan="2" style="text-align: center; font-size:12px;border:1px solid black;">Vat (Amount)</td>
                            <td colspan="2" style="text-align: center; font-size:12px;border:1px solid black;">Vat + Total</td>
                        </tr>
                        <tbody>
                            '.$tblinvoice.'
                        </tbody>
                        <tfoot>
                        <tr>
                        <td colspan="16" style="text-align: right;border:1px solid black;text-align:right;font-size:11px;" class="text-right"><b>Sub Total : </b></td>
                        <td colspan="2" style="text-align: right;border:1px solid black;text-align:right;font-size:11px;" class="text-right"><b>'.number_format(($sub_total),2).'</b></td>
                        </tr>
                        <tr>
                        <td colspan="16" style="text-align: right;border:1px solid black;text-align:right;font-size:11px;" class="text-right"><b>Discount : </b></td>
                        <td colspan="2" style="text-align: right;border:1px solid black;text-align:right;font-size:11px;" class="text-right"><b>'.number_format(($discount_amount),2).'</b></td>
                        </tr>
                        <tr>
                        <td colspan="16" style="text-align: right;border:1px solid black;text-align:right;font-size:11px;" class="text-right"><b>Total : </b></td>
                        <td colspan="2" style="text-align: right;border:1px solid black;text-align:right;font-size:11px;" class="text-right"><b>'.number_format(($totalcost),2).'</b></td>
                        </tr>
                        </tfoot>
                    </table>
                </td>
            </tr>
            <tr>
                <td style=font-size: 12px; padding-top: 5px; padding-right: 10px" colspan="2">

                </td>
            </tr>

            <tr>
            <td colspan="3" style="padding-top: 5px; font-size:12px;">'.$memo.'</td>
        </tr>

            <tr>
            <td colspan="3" style="padding-top: 5px; font-size:12px;">Checked By : -----------------</td>
        </tr>

            <tr>
            <td style=font-size: 12px; padding-top: 5px; padding-right: 10px" colspan="2">

            </td>
        </tr>
            <tr style="margin-top:5px;">
                <td style="text-align: center; font-size:7px; padding-top: 10px; padding-right: 50px" colspan="3">
                    <span style="font-size: 7px; font-weight: bold;">Thank You! Come
                        again!</span><br>
            </tr>
           
        </tr>
        </table>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
</body>

</html>';

// echo $html;

// $pdf = PDF::loadHTML($html);

// // Generate a unique filename
// $filename = 'Purchase_Order_' . uniqid() . '.pdf';

// // Save the PDF with the custom filename to the default storage path
// $pdf->save(public_path('storage/purchase_order_pdf/' . $filename));

// // Generate the URL to the PDF file
// $pdfUrl = asset('storage/purchase_order_pdf/' . $filename);

// // Open a new tab with the PDF URL using JavaScript
// echo '<script>window.open("' . $pdfUrl . '", "_blank");</script>';
// // return response()->json(['success' => true, 'url' => $pdfUrl]);

$pdf = PDF::loadHTML($html)->setPaper('legal', 'portrait');
$pdfContent = $pdf->output();

$pdfBase64 = base64_encode($pdfContent);

$responseData = [
    'pdf' => $pdfBase64,
    'message' => 'PDF generated successfully',
];

// Return the JSON response
return response()->json($responseData);

}
   
}