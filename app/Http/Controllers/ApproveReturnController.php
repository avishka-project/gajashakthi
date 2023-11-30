<?php

namespace App\Http\Controllers;

use App\ApproveReturn;
use App\Commen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Issue;
use App\Return_stock;
use App\Returndetail;
use App\Returnlist;
use Auth;
use Carbon\Carbon;
use DB;

class ApproveReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $approvel01permission = 0;
        $approvel02permission = 0;
        $approvel03permission = 0;

        $listpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        $statuspermission = 0;
        
        if (in_array('Approve-Level-01', $userPermissions)) {
            $approvel01permission = 1;
        } 
        if (in_array('Approve-Level-02', $userPermissions)) {
            $approvel02permission = 1;
        } 
        if (in_array('Approve-Level-03', $userPermissions)) {
            $approvel03permission = 1;
        } 
        if (in_array('ApproveReturn-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('ApproveReturn-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('ApproveReturn-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('ApproveReturn-delete', $userPermissions)) {
            $deletepermission = 1;
        } 

        $locations = DB::table('customerbranches')->select('customerbranches.*')
        ->whereIn('customerbranches.status', [1, 2])
        ->where('customerbranches.approve_status', 1)
        ->get();


        $departments = DB::table('departments')->select('departments.*')
        // ->whereIn('branches.status', [1, 2])
        // ->where('branches.approve_status', 1)
        ->get();

        $employees = DB::table('employees')->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])
        // ->where('employees.approve_status', 1)
        ->get();

        return view('Returnlist.approvereturn',compact('locations','departments','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','employees','userPermissions'));
    }
 


   public function appreturn(Request $request){
    $commen= new Commen();
    $userPermissions = $commen->Allpermission();
    if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('returnlists')
        ->select('returnlists.*')
        ->where('returnlists.id', $id)
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
    $data = DB::table('returndetails')
    ->leftjoin('inventorylists', 'returndetails.item_id', '=', 'inventorylists.id')
    ->select('returndetails.*', 'inventorylists.inventorylist_id', 'inventorylists.name AS inventorylistname','inventorylists.uniform_size', DB::raw('(returndetails.id) AS issuedetailsID'))
    ->where('returndetails.return_id', $recordID)
    ->where('returndetails.status', 1)
    ->get(); 
 
    $storelists = DB::table('storelists')->select('storelists.*')
    ->whereIn('storelists.status', [1, 2])
    ->where('storelists.approve_status', 1)
    ->get();
 
    $htmlTable = '';
    $count = 1;
    foreach ($data as $row) {
       
     $htmlTable .= '<tr>';
     $htmlTable .= '<td id="itemname">' . $row->inventorylist_id . '-' . $row->inventorylistname . ' '.($row->uniform_size==null?'':$row->uniform_size.'"').'</td>'; 
     $htmlTable .= '<td id="rate">' . $row->rate . '</td>'; 
     $htmlTable .= '<td id="qty">' . $row->qty . '</td>'; 
     $htmlTable .= '<td id="total">' . $row->total . '</td>'; 
     $htmlTable .= '<td>';
     $htmlTable .= '<input style="border:none" readonly type="text" id="assetvalue' . $count . '" name="assetvalue' . $count . '" value="'. $row->asset_value .'">%';
     $htmlTable .= '</td>';   
     $htmlTable .= '<td>';
     $htmlTable .= '<select style="border:none" readonly id="stock' . $count . '" name="stock' . $count . '">
                     <option value="">Select Location</option>';
                     foreach ($storelists as $storelist) {
                         $selected = ($storelist->id == $row->storelist_id) ? 'selected' : '';
     $htmlTable .= '<option value="' . $storelist->id . '" '.$selected.'>' . $storelist->name . '</option>';}
     $htmlTable .= '</select>';
     $htmlTable .= '</td>';        
     $htmlTable .= '<td class="d-none" id="itemid">' . $row->item_id . '</td>'; 
     $htmlTable .= '<td class="d-none">ExistingData</td>'; 
     $htmlTable .= '</tr>';
 
     $count++;
   }

   return $htmlTable;

}

public function edit(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('ApproveReturn-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('returnlists')
    ->select('returnlists.*')
    ->where('returnlists.id', $id)
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
   $data = DB::table('returndetails')
   ->leftjoin('inventorylists', 'returndetails.item_id', '=', 'inventorylists.id')
   ->select('returndetails.*', 'inventorylists.inventorylist_id', 'inventorylists.name AS inventorylistname','inventorylists.uniform_size', DB::raw('(returndetails.id) AS issuedetailsID'))
   ->where('returndetails.return_id', $recordID)
   ->where('returndetails.status', 1)
   ->get(); 

   $storelists = DB::table('storelists')->select('storelists.*')
   ->whereIn('storelists.status', [1, 2])
   ->where('storelists.approve_status', 1)
   ->get();

   $htmlTable = '';
   $count = 1;
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td id="itemname">' . $row->inventorylist_id . '-' . $row->inventorylistname . ' '.($row->uniform_size==null?'':$row->uniform_size.'"').'</td>'; 
    $htmlTable .= '<td id="rate">' . $row->rate . '</td>'; 
    $htmlTable .= '<td id="qty">' . $row->qty . '</td>'; 
    $htmlTable .= '<td id="total">' . $row->total . '</td>'; 
    $htmlTable .= '<td>';
    $htmlTable .= '<input type="text" id="assetvalue' . $count . '" name="assetvalue' . $count . '" value="'. $row->asset_value .'"> %';
    $htmlTable .= '</td>';   
    $htmlTable .= '<td>';
    $htmlTable .= '<select id="stock' . $count . '" name="stock' . $count . '">
                    <option value="">Select Location</option>';
                    foreach ($storelists as $storelist) {
                        $selected = ($storelist->id == $row->storelist_id) ? 'selected' : '';
    $htmlTable .= '<option value="' . $storelist->id . '" '.$selected.'>' . $storelist->name . '</option>';}
    $htmlTable .= '</select>';
    $htmlTable .= '</td>';        
    $htmlTable .= '<td class="d-none" id="itemid">' . $row->item_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';

    $count++;
   }

   return $htmlTable;

}

public function update(Request $request){
    $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('ApproveReturn-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    $id = $request->input('hidden_id');

    DB::table('returndetails')
        ->where('return_id', $id)
        ->delete();

    $tableDataArray = $request->input('tableDataArray');

    foreach ($tableDataArray as $rowtableDataArray) {
        $item = $rowtableDataArray['item'];
        $rate = $rowtableDataArray['rate'];
        $qty = $rowtableDataArray['qty'];
        $total = $rowtableDataArray['total'];
        $storelist_id = $rowtableDataArray['stockId'];
        $asset_value = $rowtableDataArray['assetvalue'];

        $returndetail = new Returndetail();
        $returndetail->return_id = $id;
        $returndetail->item_id = $item;
        $returndetail->rate = $rate;
        $returndetail->qty = $qty;
        $returndetail->total = $total;
        $returndetail->storelist_id = $storelist_id;
        $returndetail->asset_value = $asset_value;
        $returndetail->status = '1';
        $returndetail->create_by = Auth::id();
        $returndetail->update_by = '0';
        $returndetail->save();
    }



    return response()->json(['success' => 'Return is successfully Updated']);
    // return response()->json(['status' => 1, 'message' => 'Issue is successfully Inserted']);
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
        Returnlist::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Return is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Returnlist::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Return is successfully Approved']);

     }else{
        $form_data = array(
            'confirm_status' =>  '1',
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Returnlist::findOrFail($id)
        ->update($form_data);

        // update issue table return_status
        $issue_id = Request('issue_id');
        $form_data1 = array(
            'return_status' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Issue::findOrFail($issue_id)
        ->update($form_data1);

        // add item to return stock
        $tableDataArray = $request->input('tableDataArray');

        foreach ($tableDataArray as $rowtableDataArray) {
            $item = $rowtableDataArray['item'];
            $rate = $rowtableDataArray['rate'];
            $qty = $rowtableDataArray['qty'];
            $total = $rowtableDataArray['total'];
            $storelist_id = $rowtableDataArray['stockId'];
            $asset_value = $rowtableDataArray['assetvalue'];
    
            $returnstock = new Return_stock();
            $returnstock->quality_percentage = $asset_value;
            $returnstock->item_id = $item;
            $returnstock->unit_price = $rate;
            $returnstock->qty = $qty;
            $returnstock->store_id = $storelist_id;
            $returnstock->status = '1';
            $returnstock->create_by = Auth::id();
            $returnstock->update_by = '0';
            $returnstock->save();
        }

       return response()->json(['success' => 'Return is successfully Approved']);
      }
}

public function delete(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('ApproveReturn-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Returnlist::findOrFail($id)
    ->update($form_data);

// issue addreturn_status Change to NO
$issue_id = Request('issue_id');
    $form_data1 = array(
        'add_to_return' =>  'No',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Issue::findOrFail($issue_id)
    ->update($form_data1);

    return response()->json(['success' => 'Return Details is successfully Deleted']);

}
}
