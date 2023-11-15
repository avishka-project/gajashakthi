<?php

namespace App\Http\Controllers;

use App\Grn;
use App\Grndetail;
use App\inventory_list_price_summary;
use App\Porder;
use App\Porderdetail;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class GRNcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
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

        $porders = DB::table('porders')->select('porders.*')
        ->whereIn('porders.status', [1, 2])
        ->where('porders.approve_status', 1)
        // ->where('porders.grn_status', 0)
        ->get();
        return view('GRN.grn', compact('items','suppliers','porders','stores'));
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
    $user = Auth::user();
    $permission =$user->can('Grn-create');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   

    $user = Auth::user();

    $grn = new Grn();
    $grn->porder_id = $request->input('porder');
    $grn->grn_date = $request->input('orderdate');
    $grn->bill_date = $request->input('billdate');
    $grn->batch_no = $request->input('batchno');
    $grn->supplier_id = $request->input('supplier');
    $grn->terms = $request->input('terms');
    $grn->store_id = $request->input('store');
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
        $detailID = $rowDataArray['porderdetail_id'];

        $grndetail = new Grndetail();
        $grndetail->date = $date;
        $grndetail->item_id = $itemid;
        $grndetail->qty = $qty;
        $grndetail->unit_price = $unitPrice;
        $grndetail->total = $total;
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
            $user = Auth::user();

                    $permission = $user->can('Approve-Level-01');
                    if($permission){
                        if($row->approve_01 == 0){
                            $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" porder_id="'.$row->porder_id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                            $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('Approve-Level-02');
                    if($permission){
                        if($row->approve_01 == 1 && $row->approve_02 == 0){
                            $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" porder_id="'.$row->porder_id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                             $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('Approve-Level-03');
                    if($permission){
                        if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                            $btn .= ' <button name="appL3" id="'.$row->id.'" batch_no="'.$row->batch_no.'" porder_id="'.$row->porder_id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" porder_id="'.$row->porder_id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                             $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }

                    $permission = $user->can('Grn-edit');
                    if ($permission) {
                        if($row->approve_03 == 1 ){
                       $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm"
                       role="button"><i class="fa fa-eye"></i></button>';
                       }
                    }

                    // $permission = $user->can('Porder-edit');
                    // if($permission){
                    //     $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                    // }

                $permission = $user->can('Grn-status');
                    if($permission){
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
        $user = Auth::user();
        $user = Auth::user();
        $permission =$user->can('Porder-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('grns')
        ->select('grns.*')
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
     $htmlTable .= '<td><input style="border: none;width:70%;" type="number" name="edit1_unit_price[]" id="edit1_unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')" readonly></td>';
     $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit1_total[]" id="edit1_total' . $uniqueIdentifier . '" value="' .($row->total). '" readonly></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_insertstatus[]" id="edit1_insertstatus' . $uniqueIdentifier . '" value="PorderExistingData"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_grndetail_id[]" id="edit1_grndetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit1_porderdetail_id[]" id="edit1_porderdetail_id' . $uniqueIdentifier . '" value="' . $row->porderdetail_id . '"></td>';
     $htmlTable .= '</tr>';

    $uniqueIdentifier++;
    $count++;
   }

       return $htmlTable;

   }

   public function editwithoutporder(Request $request){
    $user = Auth::user();
    $user = Auth::user();
    $permission =$user->can('Porder-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('grns')
    ->select('grns.*')
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
 $htmlTable .= '<td><span style="color:red">(' . $row->qty .')</span><input style="width:70%;" type="number" name="edit1_qty[]" id="edit1_qty' . $uniqueIdentifier . '" value="' . ($row->qty) . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')"></td>';
 $htmlTable .= '<td><span style="color:red">(' . $row->unit_price .')</span><input style="width:70%;" type="number" name="edit1_unit_price[]" id="edit1_unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum1(this.value, '.$uniqueIdentifier.')"></td>';
 $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit1_total[]" id="edit1_total' . $uniqueIdentifier . '" value="' .($row->total). '"></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_insertstatus[]" id="edit1_insertstatus' . $uniqueIdentifier . '" value="ExistingData"></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_grndetail_id[]" id="edit1_grndetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
 $htmlTable .= '<td class="d-none"><input type="text" name="edit1_porderdetail_id[]" id="edit1_porderdetail_id' . $uniqueIdentifier . '" value="0"></td>';
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
    $user = Auth::user();
   
    $permission =$user->can('Grn-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
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
            $detailID = $rowDataArray['porderdetail_id'];
    
            $grndetail = new Grndetail();
            $grndetail->date = $date;
            $grndetail->item_id = $itemid;
            $grndetail->qty = $qty;
            $grndetail->unit_price = $unitPrice;
            $grndetail->total = $total;
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
            $detailID = $rowDataArray['porderdetail_id'];

            $porderdetail = Porderdetail::where('id', $detailID)->first();
            $porderdetail->grn_issue_qty = $qty;
            $porderdetail->update_by = Auth::id();
            $porderdetail->save();
        }
    }
    
    return response()->json(['success' => 'GRN Order is Successfully Updated']);
}


public function approvel_details(Request $request){
    $user = Auth::user();
    $permission =$user->can('Grn-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

    $id = Request('id');
    if (request()->ajax()){
        $data = DB::table('grns')
        ->select('grns.*')
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
    $htmlTable .= '<td class="d-none">' . $row->inven_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function delete(Request $request){

    $user = Auth::user();
  
    $permission =$user->can('Grn-delete');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
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

    $user = Auth::user();
   
   
    $permission =$user->can('Approve-Level-01');
    $permission =$user->can('Approve-Level-02');
    $permission =$user->can('Approve-Level-03');

    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
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

    $user = Auth::user();
   
   
    $permission =$user->can('Approve-Level-01');
    $permission =$user->can('Approve-Level-02');
    $permission =$user->can('Approve-Level-03');

    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
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
    $user = Auth::user();
    $permission =$user->can('Approve-Level-03');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   

    $user = Auth::user();


    $store_id=$request->input('store_id');
    $batch_no=$request->input('batchno');

    $tableData = $request->input('tableData');
    $assetvalue="brandnew";
    foreach ($tableData as $rowtabledata) {
        $item = $rowtabledata['col_7'];
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
    $user = Auth::user();
   
   
    $permission =$user->can('Grn-status');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
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

    $user = Auth::user();
  
    $permission =$user->can('Grn-delete');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
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
        ->select('grns.*', 'suppliers.*')
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

    return response()->json(['result' => $suppliers,$data]);
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
        $user = Auth::user();
        $permission =$user->can('Porder-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

            $id = Request('id');
            if (request()->ajax()){
            $porders = DB::table('porders')
            ->leftjoin('suppliers', 'porders.supplier_id', '=', 'suppliers.id')
            ->leftjoin('storelists', 'porders.store_id', '=', 'storelists.id')
            ->select('porders.*','suppliers.id AS supplier_name','suppliers.payment_terms AS payment_terms','storelists.id AS storename')
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
     $htmlTable .= '<td><span style="color:red">(' . ($row->qty - $row->grn_issue_qty) . ')</span><input style="width:70%;" type="number" name="edit_qty[]" id="qty' . $uniqueIdentifier . '" value="' . ($row->qty - $row->grn_issue_qty) . '" onkeyup="editsum(this.value, '.$uniqueIdentifier.')" ></td>';
     $htmlTable .= '<td><input style="border: none;width:70%;" type="number" name="edit_unit_price[]" id="unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum(this.value, '.$uniqueIdentifier.')"></td>';
     $htmlTable .= '<td><input style="width:70%;border: none;" type="text" name="edit_total[]" id="total' . $uniqueIdentifier . '" value="' .(($row->qty - $row->grn_issue_qty) * $row->unit_price). '" readonly></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit_insertstatus[]" id="edit_insertstatus' . $uniqueIdentifier . '" value="ExistingData"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="porderdetail_id[]" id="porderdetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
     $htmlTable .= '<td><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>';
     $htmlTable .= '</tr>';

    $uniqueIdentifier++;
   }

       return $htmlTable;

   }
   
}