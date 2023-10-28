<?php

namespace App\Http\Controllers;

use App\Grn;
use App\Grndetail;
use App\Porder;
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
        $items = DB::table('items')->select('items.*')
        ->whereIn('items.status', [1, 2])
        ->where('items.approve_status', 1)
        ->get();

        $suppliers = DB::table('suppliers')->select('suppliers.*')
        ->whereIn('suppliers.status', [1, 2])
        ->where('suppliers.approve_status', 1)
        ->get();

        $porders = DB::table('porders')->select('porders.*')
        ->whereIn('porders.status', [1, 2])
        ->where('porders.approve_status', 1)
        ->get();
        return view('GRN.grn', compact('items','suppliers','porders'));
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
    $grn->grn_date = $request->input('orderdate');
    $grn->batch_no = $request->input('batchno');
    $grn->total = $request->input('totalValue');
    $grn->remark = $request->input('comment');
    $grn->supplier_id = $request->input('supplier');
    $grn->porder_id = $request->input('porder');
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

    $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        $item = $rowtabledata['col_5'];
        $rate = $rowtabledata['col_2'];
        $qty = $rowtabledata['col_3'];
        $total = $rowtabledata['col_4'];

        $grndetail = new Grndetail();
        $grndetail->date = $date;
        $grndetail->item_id = $item;
        $grndetail->qty = $qty;
        $grndetail->unit_price = $rate;
        $grndetail->total = $total;
        $grndetail->grn_id = $requestID;
        $grndetail->status = '1';
        $grndetail->create_by = Auth::id();
        $grndetail->update_by = '0';
        $grndetail->save();

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
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                            $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('Approve-Level-02');
                    if($permission){
                        if($row->approve_01 == 1 && $row->approve_02 == 0){
                            $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                             $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('Approve-Level-03');
                    if($permission){
                        if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                            $btn .= ' <button name="appL3" id="'.$row->id.'" batch_no="'.$row->batch_no.'" porder_id="'.$row->porder_id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
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
       ->leftjoin('items', 'grndetails.item_id', '=', 'items.id')
       ->select('grndetails.*', 'items.item_name', DB::raw('(grndetails.id) AS grndetailsID'))
       ->where('grndetails.grn_id', $recordID)
       ->where('grndetails.status', 1)
       ->get(); 


       $htmlTable = '';
       foreach ($data as $row) {

        $total=$row->unit_price*$row->qty;
          
        $htmlTable .= '<tr>';
        $htmlTable .= '<td>' . $row->item_name . '</td>'; 
        $htmlTable .= '<td>' . $row->unit_price . '</td>'; 
        $htmlTable .= '<td>' . $row->qty . '</td>'; 
        $htmlTable .= '<td>' . $total . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
        $htmlTable .= '<td id ="actionrow"><button type="button" id="'.$row->grndetailsID.'" class="btnEditlist btn btn-primary btn-sm ">
            <i class="fas fa-pen"></i>
            </button>&nbsp;
            <button type="button" rowid="'.$row->grndetailsID.'" cost="'.$total.'" id="btnDeleterow"  class="btnDeletelist btn btn-danger btn-sm " >
            <i class="fas fa-trash-alt"></i>
            </button></td>'; 
        $htmlTable .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->grndetailsID.'"></td>'; 
        $htmlTable .= '</tr>';
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
            'batch_no' =>  $request->input('batchno'),
            'grn_date' =>  $request->input('orderdate'),
            'total' =>  $request->input('totalValue'),
            'remark' =>  $request->input('comment'),
            'supplier_id' =>  $request->input('supplier'),
            'porder_id' =>  $request->input('porder'),
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

        // DB::table('customerrequestdetails')
        // ->where('customerrequest_id', $hidden_id)
        // ->delete();
        $date=$request->input('orderdate');
        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            if($rowtabledata['col_6'] == "Updated"){
       
                $item = $rowtabledata['col_5'];
                $rate = $rowtabledata['col_2'];
                $qty = $rowtabledata['col_3'];
                $detailID = $rowtabledata['col_7'];
    
                $porderdetail = Grndetail::where('id', $detailID)->first();
                $porderdetail->grn_id = $hidden_id;
                $porderdetail->date = $date;
                $porderdetail->item_id = $item;
                $porderdetail->unit_price = $rate;
                $porderdetail->qty = $qty;
                $porderdetail->update_by = Auth::id();
                $porderdetail->save();

                
            }else if($rowtabledata['col_6'] == "NewData") {
                $item = $rowtabledata['col_5'];
                $rate = $rowtabledata['col_2'];
                $qty = $rowtabledata['col_3'];
                    if($item != 0){
                        $porderdetail = new Grndetail();
                        $porderdetail->grn_id = $hidden_id;
                        $porderdetail->date = $date;
                        $porderdetail->item_id = $item;
                        $porderdetail->unit_price = $rate;
                        $porderdetail->qty = $qty;
                        $porderdetail->status = '1';
                        $porderdetail->create_by = Auth::id();
                        $porderdetail->update_by = '0';
                        $porderdetail->save();
                    }
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
    $data = DB::table('grndetails')
       ->leftjoin('grns', 'grndetails.grn_id', '=', 'grns.id')
       ->leftjoin('items', 'grndetails.item_id', '=', 'items.id')
       ->select('grndetails.*', 'items.item_name', DB::raw('(grndetails.id) AS grndetailsID'))
       ->where('grndetails.grn_id', $recordID)
       ->where('grndetails.status', 1)
       ->get();   


   $htmlTable = '';
   foreach ($data as $row) {
      
    $total=$row->unit_price*$row->qty;

    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->item_name . '</td>'; 
    $htmlTable .= '<td>' . $row->unit_price . '</td>'; 
    $htmlTable .= '<td>' . $row->qty . '</td>'; 
    $htmlTable .= '<td>' . $total . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
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


    $id=$request->input('hidden_id');
    $batch_no=$request->input('batchno');

    $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        $item = $rowtabledata['col_5'];
        $rate = $rowtabledata['col_2'];
        $qty = $rowtabledata['col_3'];
        $total = $rowtabledata['col_4'];

       // Check if a matching record exists in the Stock table
    $existingStock = Stock::where('batch_no', $batch_no)
    ->where('item_id', $item)
    ->first();

    if ($existingStock) {
    // Update the stock quantity
    $existingStock->qty += $qty;
    $existingStock->save();
    } else {
    // Create a new stock record
    $newStock = new Stock();
    $newStock->batch_no = $batch_no;
    $newStock->qty = $qty;
    $newStock->item_id = $item;
    $newStock->status = '1';
    $newStock->save();
}
    
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
        ->leftjoin('items', 'grndetails.item_id', '=', 'items.id')
        ->select('grndetails.*','items.item_name AS item_name')
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

public function getitemcode(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('inventorylists')
    ->leftJoin('suppliers', 'items.supplier_id', '=', 'suppliers.id')
    ->select('suppliers.id AS supplierid','items.id AS itemid')
    ->where('suppliers.id', $id)
    ->get(); 

    foreach ($data as $row) {

        $supplierid=$row->supplierid;
        $itemid=$row->itemid;
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
    $data2= $supplierid.$itemid.$batchno;

    return response() ->json(['result'=> $data2]);
}
}
   
}