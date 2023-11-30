<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Porder;
use App\Porderdetail;
use \PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Pordercontroller extends Controller
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
        return view('GRN.porder', compact('items','suppliers','stores','userPermissions'));
    }

    public function getpurchaseprice(Request $request){

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('items')
        ->select('items.*')
        ->where('items.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
}

public function getitem($supplierid)
    {
        $items = DB::table('items')
        ->select('items.*')
        ->whereIn('items.status', [1, 2])
        ->where('items.approve_status', 1)
        ->where('items.supplier_id', '=', $supplierid)->get();
    
        return response()->json($items);
    }

public function insert(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Porder-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

    $porder = new Porder();
    $porder->order_date = $request->input('orderdate');
    $porder->due_date = $request->input('duedate');
    $porder->supplier_id = $request->input('supplier');
    $porder->store_id = $request->input('store');
    $porder->employee_id = $request->input('employee');
    $porder->remark = $request->input('remark');
    $porder->sub_total = $request->input('sub_total');
    $porder->discount_amount = $request->input('discount');
    $porder->net_total = $request->input('net_total');
    $porder->grn_status = '0';
    $porder->confirm_status = '0';
    $porder->status = '1';
    $porder->approve_status = '0';
    $porder->approve_01 = '0';
    $porder->approve_02 = '0';
    $porder->approve_03 = '0';
    $porder->create_by = Auth::id();
    $porder->update_by = '0';
    $porder->save();

    $requestID = $porder->id;

    $DataArray = $request->input('DataArray');

    foreach ($DataArray as $rowDataArray) {
        $itemid= $rowDataArray['itemName'];
        $qty = $rowDataArray['qty'];
        $unitPrice = $rowDataArray['unitPrice'];
        $total = $rowDataArray['total'];
        $vatprecentage = $rowDataArray['vatprecentage'];
        $vatamount = $rowDataArray['vatamount'];
        $aftervat = $rowDataArray['aftervat'];

        $porderdetail = new Porderdetail();
        $porderdetail->porder_id = $requestID;
        $porderdetail->inventorylist_id = $itemid;
        $porderdetail->qty = $qty;
        $porderdetail->grn_issue_qty = '0';
        $porderdetail->unit_price = $unitPrice;
        $porderdetail->total = $total;
        $porderdetail->vat_precentage = $vatprecentage;
        $porderdetail->vat_amount = $vatamount;
        $porderdetail->total_after_vat = $aftervat;
        $porderdetail->status = '1';
        $porderdetail->create_by = Auth::id();
        $porderdetail->update_by = '0';
        $porderdetail->save();
    }
    return response()->json(['success' => 'Purchase Order is successfully Inserted']);
    // return response()->json(['status' => 1, 'message' => 'Issue is successfully Inserted']);
}

public function requestlist()
{
    $types = DB::table('porders')
        ->leftjoin('suppliers', 'porders.supplier_id', '=', 'suppliers.id')
        ->select('porders.*','suppliers.supplier_name AS supplier_name')
        ->whereIn('porders.status', [1, 2])
        ->get();

        return Datatables::of($types)
        ->addIndexColumn()
        ->addColumn('action', function ($row) {
            $btn = '';
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();

                    if(in_array('Approve-Level-01',$userPermissions)){
                        if($row->approve_01 == 0){
                            $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                        }
                    }
                    if(in_array('Approve-Level-02',$userPermissions)){
                        if($row->approve_01 == 1 && $row->approve_02 == 0){
                            $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                        }
                    }
                    if(in_array('Approve-Level-03',$userPermissions)){
                        if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                            $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                        }
                    }

                        if($row->approve_03 == 1 ){
                       $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm"
                       role="button"><i class="fa fa-eye"></i></button>';
                       }

                       if(in_array('Porder-edit',$userPermissions)){
                        $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                    }

                    if(in_array('Porder-status',$userPermissions)){
                        if($row->status == 1){
                            $btn .= ' <a href="'.route('porderstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                        }else{
                            $btn .= '&nbsp;<a href="'.route('porderstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                        }
                    }
                    if(in_array('Porder-delete',$userPermissions)){
                        $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                    }
          
            return $btn;
        })
       
        ->rawColumns(['action'])
        ->make(true);

    }


    public function edit(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Porder-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('porders')
        ->leftjoin('employees', 'porders.employee_id', '=', 'employees.id')
        ->select('porders.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('porders.id', $id)
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
     $htmlTable .= '<td><input style="width:100px" type="text" name="edit_inventorylist_id[]" id="inventorylist_id' . $uniqueIdentifier . '" value="' . $row->inventorylist_id . '" readonly></td>';
     $htmlTable .= '<td>';
     $htmlTable .= '<select required name="edit_inventorylist_select[]" id="inventorylist_select' . $uniqueIdentifier . '" size="1" onfocus="this.size = 8"  onblur="this.size = 1; this.blur()" onchange="getItemeditDetails(this.value, '.$uniqueIdentifier.')">';
    foreach ($inventoryListData as $inventory) {
         $selected = ($inventory->id == $row->inven_id) ? 'selected' : '';
         $htmlTable .= '<option value="' . $inventory->id . '" '.$selected.' >' . $inventory->inventorylist_id . ' - ' . $inventory->name  . ' ' . ($inventory->uniform_size==null?"":$inventory->uniform_size.'"') . '</option>';
     }
 
     $htmlTable .= '</select>';
     $htmlTable .= '</td>';
     $htmlTable .= '<td><input style="width:100px" type="text" name="edit_uom[]" id="uom' . $uniqueIdentifier . '" value="' . $row->uom . '" readonly></td>';
     $htmlTable .= '<td><input style="width:150%" type="number" name="edit_qty[]" id="qty' . $uniqueIdentifier . '" value="' . $row->qty . '" onkeyup="editsum(this.value, '.$uniqueIdentifier.')"></td>';
     $htmlTable .= '<td><input style="width:150%" type="number" name="edit_unit_price[]" id="unit_price' . $uniqueIdentifier . '" value="' . $row->unit_price . '" onkeyup="editsum(this.value, '.$uniqueIdentifier.')"></td>';
     $htmlTable .= '<td><input style="width:150%" type="text" name="edit_total[]" id="total' . $uniqueIdentifier . '" value="' . $total . '" readonly></td>';
     $htmlTable .= '<td id="edit_vatprecentage' . $uniqueIdentifier . '" name="edit_vatprecentage[]" class="align-middle p-1 text-right edit_vatprecentage">' . $row->vat_precentage . '</td>';
     $htmlTable .= '<td id="edit_vatamount' . $uniqueIdentifier . '" name="edit_vatamount[]" class="align-middle p-1 text-right edit_vatamount">' . $row->vat_amount . '</td>';
     $htmlTable .= '<td id="edit_aftervat' . $uniqueIdentifier . '" name="edit_aftervat[]" class="align-middle p-1 text-right edit_aftervat">' . $row->total_after_vat . '</td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="edit_insertstatus[]" id="edit_insertstatus' . $uniqueIdentifier . '" value="ExistingData"></td>';
     $htmlTable .= '<td class="d-none"><input type="text" name="porderdetail_id[]" id="porderdetail_id' . $uniqueIdentifier . '" value="' . $row->id . '"></td>';
     $htmlTable .= '<td id ="actionrow"><button class="btn btn-sm btn-danger py-0" type="button" onclick="rem_item($(this))"><i class="fa fa-times"></i></button></td>'; 
     $htmlTable .= '</tr>';

    $uniqueIdentifier++;
   }

       return $htmlTable;

   }
   public function editlist(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('porderdetails')
                ->select('porderdetails.*')
                ->where('porderdetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}

public function update(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Porder-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
   
        $current_date_time = Carbon::now()->toDateTimeString();

        $hidden_id = $request->input('hidden_id');


        $id =  $request->hidden_id ;
        $form_data = array(
            'order_date' =>  $request->input('orderdate'),
            'due_date' =>  $request->input('duedate'),
            'supplier_id' =>  $request->input('supplier'),
            'store_id' =>  $request->input('store'),
            'employee_id' =>  $request->input('employee'),
            'remark' =>  $request->input('remark'),
            'sub_total' =>  $request->input('sub_total'),
            'discount_amount' =>  $request->input('discount'),
            'net_total' =>  $request->input('net_total'),
                'confirm_status' =>  '0',
                'grn_status' =>  '0',
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time, 
            );
    
            Porder::findOrFail($id)
        ->update($form_data);

        DB::table('porderdetails')
        ->where('porder_id', $hidden_id)
        ->delete();

        $porderid =  $request->hidden_id ;
        $DataArray = $request->input('DataArray');

    foreach ($DataArray as $rowDataArray) {
        $itemid= $rowDataArray['itemName'];
        $qty = $rowDataArray['qty'];
        $unitPrice = $rowDataArray['unitPrice'];
        $total = $rowDataArray['total'];
        $vatprecentage = $rowDataArray['vatprecentage'];
        $vatamount = $rowDataArray['vatamount'];
        $aftervat = $rowDataArray['aftervat'];

        $porderdetail = new Porderdetail();
        $porderdetail->porder_id = $porderid;
        $porderdetail->inventorylist_id = $itemid;
        $porderdetail->qty = $qty;
        $porderdetail->grn_issue_qty = '0';
        $porderdetail->unit_price = $unitPrice;
        $porderdetail->total = $total;
        $porderdetail->vat_precentage = $vatprecentage;
        $porderdetail->vat_amount = $vatamount;
        $porderdetail->total_after_vat = $aftervat;
        $porderdetail->status = '1';
        $porderdetail->create_by = Auth::id();
        $porderdetail->update_by = '0';
        $porderdetail->save();
    }
    
        
    
    
    return response()->json(['success' => 'Purchase Order is Successfully Updated']);
}


public function approvel_details(Request $request){
    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    } 

    $id = Request('id');
    if (request()->ajax()){
        $data = DB::table('porders')
        ->leftjoin('employees', 'porders.employee_id', '=', 'employees.id')
        ->select('porders.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('porders.id', $id)
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
    $data = DB::table('porderdetails')
    ->leftjoin('porders', 'porderdetails.porder_id', '=', 'porders.id')
    ->leftjoin('inventorylists', 'porderdetails.inventorylist_id', '=', 'inventorylists.id')
    ->select('porderdetails.*', 'inventorylists.name','inventorylists.uniform_size','inventorylists.inventorylist_id','inventorylists.uom', DB::raw('(porderdetails.id) AS porderdetailsID'))
    ->where('porderdetails.porder_id', $recordID)
    ->where('porderdetails.status', 1)
    ->get();  


   $htmlTable = '';
   foreach ($data as $row) {
      
    $total=$row->unit_price*$row->qty;

    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->inventorylist_id . '</td>'; 
    $htmlTable .= '<td>' . $row->name . ' ' . ($row->uniform_size==null?"":$row->uniform_size.'"') . '</td>'; 
    $htmlTable .= '<td>' . $row->uom . '</td>'; 
    $htmlTable .= '<td>' . $row->qty . '</td>'; 
    $htmlTable .= '<td>' . $row->unit_price . '</td>'; 
    $htmlTable .= '<td>' . $total . '</td>'; 
    $htmlTable .= '<td>' . $row->vat_precentage . '</td>';
    $htmlTable .= '<td>' . $row->vat_amount . '</td>';
    $htmlTable .= '<td>' . $row->total_after_vat . '</td>';
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function delete(Request $request){

        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Porder-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
        $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Porder::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'Purchase Order is successfully Deleted']);

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
        Porder::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Purchase Order is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Porder::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Purchase Order is successfully Approved']);

     }else{
        $form_data = array(
            'confirm_status' =>  '1',
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Porder::findOrFail($id)
        ->update($form_data);

       return response()->json(['success' => 'Purchase Order is successfully Approved']);
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
        Porder::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Purchase Order is successfully Reject']);

}


public function status($id,$statusid){

        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Porder-status', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 

    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Porder::findOrFail($id)
        ->update($form_data);

        return redirect()->route('porder');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Porder::findOrFail($id)
        ->update($form_data);

        return redirect()->route('porder');
    }

}


public function deletelist(Request $request){

        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Porder-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 
    
        $id = Request('id');

    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Porderdetail::findOrFail($id)
    ->update($form_data);

    $id = Request('porderid');
    $cost = Request('cost');
    $total = Request('total');

    $newtotal=$total-$cost;

    $form_data1 = array(
        'sub_total' =>  $newtotal,
        'net_total' =>  $newtotal,
    );
    Porder::findOrFail($id)
    ->update($form_data1);

    
    return response()->json(['success' => 'Purchase Order is successfully Deleted']);

}


public function view(Request $request)
{

    $id = $request->input('id');

        $types = DB::table('porderdetails')
        ->leftjoin('items', 'porderdetails.item_id', '=', 'items.id')
        ->select('porderdetails.*','items.item_name AS item_name')
        ->whereIn('porderdetails.status', [1, 2])
        ->where('porderdetails.porder_id', $id)
        ->get();
    

        return Datatables::of($types)
        ->addIndexColumn()
        ->make(true);
}

public function viewDetails(Request $request){
    $id = $request->input('id');

    $data = DB::table('porders')
        ->leftJoin('suppliers', 'porders.supplier_id', '=', 'suppliers.id')
        ->select('porders.*', 'suppliers.*')
        ->where('porders.id', $id)
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

    return response()->json(['result' => $suppliers]);
}

public function pordercashgetvat(Request $request){

    $date = Request('date');

        $data = DB::table('vats')
        ->select('vats.*')
        ->where('vats.approve_status', 1)
        ->whereIn('vats.status', [1, 2])
        ->whereDate('fromdate', '<=', $date)
        ->where(function ($query) use ($date) {
            $query->whereDate('todate', '>=', $date)
                ->orWhereNull('todate');
        })
        ->get();

    if ($data->count() > 0) {
        // Return the vat value of the first matching row
        return response()->json(['result' => $data[0]->vat]);
    } else {
        // No matching row found
        return response()->json(['result' => 0]);
    }

}

public function porderprint(Request $request){
    $id = $request->input('id');

    $types = DB::table('porderdetails')
    ->leftjoin('inventorylists', 'porderdetails.inventorylist_id', '=', 'inventorylists.id')
    ->select('porderdetails.*','inventorylists.name','inventorylists.inventorylist_id','inventorylists.uom','inventorylists.uniform_size')
    ->whereIn('porderdetails.status', [1, 2])
    ->where('porderdetails.porder_id', $id)
    ->get();


    $data = DB::table('porders')
        ->leftJoin('suppliers', 'porders.supplier_id', '=', 'suppliers.id')
        ->select('porders.*','porders.id as porderid', 'suppliers.*','suppliers.supplier_name as supplier_name','suppliers.address1 as address1','suppliers.address2 as address2','suppliers.city as city')
        ->where('porders.id', $id)
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
    foreach ($data as $porderlist) {
        $date = $porderlist->order_date;
        $suppliername = $porderlist->supplier_name;
        $supplieraddress = $porderlist->address1.', '.$porderlist->address2.', '.$porderlist->city;
        $porderid = $porderlist->porderid;
        $sub_total = $porderlist->sub_total;
        $discount_amount = $porderlist->discount_amount;
        $totalcost = $porderlist->net_total;
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
            <h5 class="font-weight-light">Purchase order</h5>
            </td>
        </tr>
            <tr>
                <td  style="text-align: left; font-size:12px;">Date: '.$date.'</td>
                <td colspan="2" style="padding-left:130px; font-size:14px;text-align: right;">Purchase No: POD-'. $porderid.'</td>
            </tr>
            <tr>
                <td colspan="3" style="text-align: left; font-size:12px;">Supplier: '. $suppliername.'</td>
            </tr>
            <tr>
            <td colspan="3" style="text-align: left; font-size:12px;">Contact No: '.$contactview.'</td>
        </tr>
        <tr>
        <td colspan="3" style="text-align: left; font-size:12px;">Email: '. $emailview.'</td>
    </tr>
    <tr>
    <td colspan="3" style="text-align: left; font-size:12px;">Address: '.$supplieraddress.'</td>
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
            <td colspan="3" style="padding-top: 40px; font-size:12px;">Checked By : -----------------</td>
        </tr>

            <tr>
            <td style=font-size: 12px; padding-top: 5px; padding-right: 10px" colspan="2">

            </td>
        </tr>
            <tr style="margin-top:5px;">
                <td style="text-align: center; font-size:7px; padding-top: 10px; padding-right: 50px" colspan="3">
                    <span style="font-size: 7px; font-weight: bold;">Thank You! Come
                        again!</span><br>
                    <span style="font-size:7px;">Important Notice : In case of
                        returns, return the
                        bill within 7 days.</span><br></td>
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


public function pordergetitemdetail(Request $request){
    
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Porder-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('inventorylists')
    ->select('inventorylists.*')
    ->where('inventorylists.id', $id)
    ->get(); 

    return response() ->json(['result'=>  $data]);
}

}
public function pordergetitemname(Request $request) {
    $searchTerm = $request->input('search');

    $matchingData = DB::table('inventorylists')
    ->where(function ($query) use ($searchTerm) {
        $query->where('name', 'like', '%' . $searchTerm . '%')
        ->orWhere('inventorylist_id', 'like', '%' . $searchTerm . '%')
        ->whereIn('status', [1, 2])
        ->where('approve_status', 1);
    })
    ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    }
}

}


