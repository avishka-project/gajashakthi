<?php

namespace App\Http\Controllers;

use App\Commen;
use App\inventory_list_price_summary;
use App\Issue;
use App\Issuedetail;
use App\Return_stock;
use App\Returnstock;
use App\Stock;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Issuecontroller extends Controller
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
        if (in_array('Issue-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('Issue-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('Issue-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('Issue-delete', $userPermissions)) {
            $deletepermission = 1;
        } 

        $items = DB::table('inventorylists')->select('inventorylists.*')
        ->whereIn('inventorylists.status', [1, 2])
        ->where('inventorylists.approve_status', 1)
        ->get();

        $stores = DB::table('storelists')->select('storelists.*')
        ->whereIn('storelists.status', [1, 2])
        ->where('storelists.approve_status', 1)
        ->get();

        $employees = DB::table('employees')->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])
        // ->where('employees.approve_status', 1)
        ->get();

        $locations = DB::table('customerbranches')->select('customerbranches.*')
        ->whereIn('customerbranches.status', [1, 2])
        ->where('customerbranches.approve_status', 1)
        ->get();

        $departments = DB::table('departments')->select('departments.*')
        // ->whereIn('branches.status', [1, 2])
        // ->where('branches.approve_status', 1)
        ->get();

        return view('Issues.issue', compact('items','employees','locations','departments','stores','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    }

    public function insert(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Issue-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
            $current_date_time = Carbon::now()->toDateTimeString();

        $issue = new Issue();
        $issue->issuing = $request->input('issuing');
        $issue->location_id = $request->input('location');
        $issue->department_id = $request->input('department');
        $issue->employee_id = $request->input('employee');
        $issue->date = $request->input('month');
        $issue->issue_type = $request->input('issuetype');
        $issue->payment_type = $request->input('paymenttype');
        $issue->remark = $request->input('remark');
        $issue->add_to_return = 'No';
        $issue->return_status = '0';
        $issue->status = '1';
        $issue->approve_status = '0';
        $issue->approve_01 = '0';
        $issue->approve_02 = '0';
        $issue->approve_03 = '0';
        $issue->create_by = Auth::id();
        $issue->update_by = '0';
        $issue->save();

        $requestID = $issue->id;

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            $item = $rowtabledata['col_6'];
            $rate = $rowtabledata['col_3'];
            $qty = $rowtabledata['col_4'];
            $total = $rowtabledata['col_5'];
            $storelist_id = $rowtabledata['col_8'];
            $asset_value = $rowtabledata['col_7'];
            $grnstock_id = $rowtabledata['col_9'];
            $returnstock_id = $rowtabledata['col_10'];

            $stock_id='';
            if($grnstock_id!=='' && $returnstock_id==''){
                $stock_id=$grnstock_id;

            }
            else if($returnstock_id!=='' && $grnstock_id==''){
                $stock_id=$returnstock_id;

            }


            $issuedetail = new Issuedetail();
            $issuedetail->issue_id = $requestID;
            $issuedetail->item_id = $item;
            $issuedetail->rate = $rate;
            $issuedetail->qty = $qty;
            $issuedetail->total = $total;
            $issuedetail->storelist_id = $storelist_id;
            $issuedetail->asset_value = $asset_value;
            $issuedetail->stock_id = $stock_id;
            $issuedetail->status = '1';
            $issuedetail->create_by = Auth::id();
            $issuedetail->update_by = '0';
            $issuedetail->save();
        }
        return response()->json(['success' => 'Issue is successfully Inserted']);
        // return response()->json(['status' => 1, 'message' => 'Issue is successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('issues')
            ->leftjoin('employees', 'issues.employee_id', '=', 'employees.id')
            ->leftjoin('branches', 'issues.location_id', '=', 'branches.id')
            ->select('issues.*','employees.emp_name_with_initial AS emp_name_with_initial','branches.location AS location')
            ->whereIn('issues.status', [1, 2])
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
                        
                           if(in_array('Issue-edit',$userPermissions)){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        if(in_array('Issue-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('issuestatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('issuestatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Issue-delete',$userPermissions)){
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
            if (!in_array('Issue-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('issues')
        ->leftjoin('customerbranches', 'issues.location_id', '=', 'customerbranches.id')
        ->select('issues.*','customerbranches.id AS locationid','customerbranches.branch_name AS locationname')
        ->where('issues.id', $id)
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
       $data = DB::table('issuedetails')
       ->leftjoin('inventorylists', 'issuedetails.item_id', '=', 'inventorylists.id')
       ->select('issuedetails.*', 'inventorylists.inventorylist_id', 'inventorylists.name AS inventorylistname','inventorylists.uniform_size', DB::raw('(issuedetails.id) AS issuedetailsID'))
       ->where('issuedetails.issue_id', $recordID)
       ->where('issuedetails.status', 1)
       ->get(); 


       $htmlTable = '';
       foreach ($data as $row) {
          
        $htmlTable .= '<tr>';
        $htmlTable .= '<td>' . $row->inventorylist_id . '-' . $row->inventorylistname . ' '.($row->uniform_size==null?'':$row->uniform_size.'"').'</td>'; 
        $htmlTable .= '<td>' . $row->asset_value . '</td>'; 
        $htmlTable .= '<td>' . $row->rate . '</td>'; 
        $htmlTable .= '<td>' . $row->qty . '</td>'; 
        $htmlTable .= '<td>' . $row->total . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->asset_value . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->storelist_id . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->stock_id . '</td>'; 
        $htmlTable .= '<td class="d-none"></td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->issuedetailsID . '</td>'; 
        $htmlTable .= '<td id ="actionrow"><button type="button" id="'.$row->issuedetailsID.'" class="btnEditlist btn btn-primary btn-sm ">
            <i class="fas fa-pen"></i>
            </button>&nbsp;
            <button type="button" rowid="'.$row->issuedetailsID.'" id="btnDeleterow"  class="btnDeletelist btn btn-danger btn-sm " >
            <i class="fas fa-trash-alt"></i>
            </button></td>'; 
        $htmlTable .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->issuedetailsID.'"></td>'; 
        $htmlTable .= '</tr>';
       }

       return $htmlTable;

   }
   public function editlist(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('issuedetails')
                ->select('issuedetails.*')
                ->where('issuedetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}



    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Issue-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

            $hidden_id = $request->input('hidden_id');

            $id =  $request->hidden_id ;
            $form_data = array(
                'issuing' =>  $request->input('issuing'),
                'location_id' =>  $request->input('location'),
                'department_id' =>  $request->input('department'),
                'employee_id' =>  $request->input('editempid'),
                'date' =>  $request->input('month'),
                'issue_type' =>  $request->input('issuetype'),
                'payment_type' =>  $request->input('paymenttype'),
                'remark' =>  $request->input('remark'),
                    'approve_status' =>  '0',
                    'approve_01' =>  '0',
                    'approve_02' =>  '0',
                    'approve_03' =>  '0',
                    'update_by' => Auth::id(),
                    'updated_at' => $current_date_time, 
                );
        
                Issue::findOrFail($id)
            ->update($form_data);
    
            // DB::table('customerrequestdetails')
            // ->where('customerrequest_id', $hidden_id)
            // ->delete();
    
            $tableData = $request->input('tableData');
    
            foreach ($tableData as $rowtabledata) {
                if($rowtabledata['col_11'] == "Updated"){
           
                    $item = $rowtabledata['col_6'];
                    $rate = $rowtabledata['col_3'];
                    $qty = $rowtabledata['col_4'];
                    $total = $rowtabledata['col_5'];
                    $asset_value = $rowtabledata['col_7'];
                    $storelist_id = $rowtabledata['col_8'];
                    $grnstock_id = $rowtabledata['col_9'];
                    $returnstock_id = $rowtabledata['col_10'];
                    $detailID = $rowtabledata['col_12'];

                    $stock_id='';
                    if($grnstock_id!=='' && $returnstock_id==''){
                        $stock_id=$grnstock_id;
        
                    }
                    else if($returnstock_id!=='' && $grnstock_id==''){
                        $stock_id=$returnstock_id;
        
                    }
        
                    $issuedetail = Issuedetail::where('id', $detailID)->first();
                    $issuedetail->item_id = $item;
                    $issuedetail->rate = $rate;
                    $issuedetail->qty = $qty;
                    $issuedetail->total = $total;
                    $issuedetail->storelist_id = $storelist_id;
                    $issuedetail->asset_value = $asset_value;
                    $issuedetail->stock_id = $stock_id;
                    $issuedetail->update_by = Auth::id();
                    $issuedetail->save();

                    
                }else if($rowtabledata['col_11'] == "NewData") {
                    $item = $rowtabledata['col_6'];
                    $rate = $rowtabledata['col_3'];
                    $qty = $rowtabledata['col_4'];
                    $total = $rowtabledata['col_5'];
                    $storelist_id = $rowtabledata['col_8'];
                    $asset_value = $rowtabledata['col_7'];
                    $grnstock_id = $rowtabledata['col_9'];
                    $returnstock_id = $rowtabledata['col_10'];
        
                    $stock_id='';
                    if($grnstock_id!=='' && $returnstock_id==''){
                        $stock_id=$grnstock_id;
        
                    }
                    else if($returnstock_id!=='' && $grnstock_id==''){
                        $stock_id=$returnstock_id;
        
                    }
                        if($item != 0){
                            $issuedetail = new Issuedetail();
                            $issuedetail->issue_id = $hidden_id;
                            $issuedetail->item_id = $item;
                            $issuedetail->rate = $rate;
                            $issuedetail->qty = $qty;
                            $issuedetail->total = $total;
                            $issuedetail->storelist_id = $storelist_id;
                            $issuedetail->asset_value = $asset_value;
                            $issuedetail->stock_id = $stock_id;
                            $issuedetail->status = '1';
                            $issuedetail->create_by = Auth::id();
                            $issuedetail->update_by = '0';
                            $issuedetail->save();
                        }
                  }
            }
        
        
        return response()->json(['success' => 'Issue is Successfully Updated']);
    }

    public function approvel_details(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('issues')
        ->leftjoin('customerbranches', 'issues.location_id', '=', 'customerbranches.id')
        ->select('issues.*','customerbranches.branch_name AS customerbranch')
        ->where('issues.id', $id)
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
      $data = DB::table('issuedetails')
       ->leftjoin('inventorylists', 'issuedetails.item_id', '=', 'inventorylists.id')
       ->select('issuedetails.*', 'inventorylists.inventorylist_id', 'inventorylists.name AS inventorylistname','inventorylists.uniform_size', DB::raw('(issuedetails.id) AS issuedetailsID'))
       ->where('issuedetails.issue_id', $recordID)
       ->where('issuedetails.status', 1)
       ->get(); 


       $htmlTable = '';
       foreach ($data as $row) {
          
        $htmlTable .= '<tr>';
        $htmlTable .= '<td>' . $row->inventorylist_id . '-' . $row->inventorylistname . ' '.($row->uniform_size==null?'':$row->uniform_size.'"').'</td>'; 
        $htmlTable .= '<td>' . $row->asset_value . '</td>'; 
        $htmlTable .= '<td>' . $row->rate . '</td>'; 
        $htmlTable .= '<td>' . $row->qty . '</td>'; 
        $htmlTable .= '<td>' . $row->total . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">' . $row->stock_id . '</td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
        $htmlTable .= '</tr>';
       }

       return $htmlTable;

   }



    public function delete(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Issue-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Issue::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Issue is successfully Deleted']);

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
            Issue::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Issue is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Issue::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Issue is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Issue::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Issue is successfully Approved']);
          }
    }




    public function status($id,$statusid){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Issue-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Issue::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('issue');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Issue::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('issue');
        }

    }


    public function deletelist(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Issue-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Issuedetail::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Item is successfully Deleted']);

    }

    public function getsearchempinfo(Request $request) {
        $searchTerm = $request->input('search');
    
        $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('emp_national_id', 'like', '%' . $searchTerm . '%')
                ->orWhere('service_no', 'like', '%' . $searchTerm . '%')
                ->orWhere('emp_name_with_initial', 'like', '%' . $searchTerm . '%');
        })
        ->limit(1)
        ->get();
    
        if ($matchingData->count() > 0) {
            return response()->json($matchingData);
        } else {
            $first5Items = DB::table('employees')
            ->limit(5)->get();
            return response()->json($first5Items);
        }
    }

    public function getitem($store_id)
{
    $items = DB::table('stocks')
    ->leftjoin('inventorylists', 'stocks.item_id', '=', 'inventorylists.id')
    ->select('stocks.item_id','inventorylists.inventorylist_id','inventorylists.name','inventorylists.uniform_size')
    ->where('stocks.store_id', '=', $store_id)
    ->groupBy('stocks.item_id')
    // ->where('stocks.qty', '>', 0)
    ->get();

    return response()->json($items);
}


    // data bachno get & filtering item part
    public function getBachno($itemId,$store_id)
{
    $stocks = DB::table('stocks')->select('stocks.*')
    ->where('item_id', '=', $itemId)
    ->where('store_id', '=', $store_id)
     ->where('stocks.qty', '>', 0)
    ->get();

    return response()->json($stocks);
}

public function getQtyPriceList(Request $request){

    $id = Request('id');
    if (request()->ajax()){
        $data = DB::table('stocks')
        ->leftJoin('issuedetails', 'stocks.id', '=', 'issuedetails.stock_id')
        ->leftJoin('issues', 'issues.id', '=', 'issuedetails.issue_id')
        ->select('stocks.*', DB::raw('SUM(CASE WHEN issues.approve_status = 0 AND issues.status IN (1, 2) AND issuedetails.status IN (1, 2) THEN issuedetails.qty ELSE 0 END) AS issueQty'))
        ->where('stocks.id', $id)
        ->whereIn('stocks.status', [1, 2])
        ->groupBy('stocks.id')
        ->get();
    return response() ->json(['result'=> $data[0]]);
}
}

// Return item get part
public function getreturnitem($store_id)
{
    $returnitems = DB::table('return_stocks')
    ->leftjoin('inventorylists', 'return_stocks.item_id', '=', 'inventorylists.id')
    ->select('return_stocks.item_id','inventorylists.inventorylist_id','inventorylists.name','inventorylists.uniform_size')
    ->where('return_stocks.store_id', '=', $store_id)
    ->groupBy('return_stocks.item_id')
    // ->where('return_stocks.qty', '>', 0)
    ->get();

    return response()->json($returnitems);
}

// return itemm quality get part
public function getReturnItemQuality($itemId,$store_id)
{
    $returnstocks = DB::table('return_stocks')->select('return_stocks.*')
    ->where('item_id', '=', $itemId)
    ->where('store_id', '=', $store_id)
    ->where('return_stocks.qty', '>', 0)
    ->get();

    return response()->json($returnstocks);
}

// return Item Qty and Price Get
public function getReturnItemQtyPriceList(Request $request){

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('return_stocks')
    ->leftJoin('issuedetails', 'return_stocks.id', '=', 'issuedetails.stock_id')
    ->leftJoin('issues', 'issues.id', '=', 'issuedetails.issue_id')
    ->select('return_stocks.*', DB::raw('SUM(CASE WHEN issues.approve_status = 0 AND issues.status IN (1, 2) AND issuedetails.status IN (1, 2) THEN issuedetails.qty ELSE 0 END) AS issueQty'))
    ->where('return_stocks.id', $id)
    ->whereIn('return_stocks.status', [1, 2])
    ->groupBy('return_stocks.id')
    ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}


public function stockupdate(Request $request){
    $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Approve-Level-03', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    $current_date_time = Carbon::now()->toDateTimeString();
    $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        $item = $rowtabledata['col_6'];
        $qty = $rowtabledata['col_4'];
        $assestvalue = $rowtabledata['col_2'];
        $stockid = $rowtabledata['col_7'];

        if($assestvalue=='brandnew'){

            $currentQuantity = Stock::where('id', $stockid)->value('qty');
            $newQuantity = $currentQuantity - $qty;
            $form_data = array(
                'qty' =>  $newQuantity,
                'updated_at' => $current_date_time,
                'update_by' => Auth::id(),
            );
            Stock::findOrFail($stockid)
            ->update($form_data);
        }
        else if($assestvalue=='used'){

            $currentQuantity = Return_stock::where('id', $stockid)->value('qty');
            $newQuantity = $currentQuantity - $qty;
            $form_data = array(
                'qty' =>  $newQuantity,
                'updated_at' => $current_date_time,
                'update_by' => Auth::id(),
            );
            Return_stock::findOrFail($stockid)
            ->update($form_data);
        }
    }
    return response()->json(['success' => 'Stock Updated']);

}


public function updateprice(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Issue-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    
        $assetvalue = Request('assetvalue');
        $itemid = Request('itemid');
        $itemname = Request('itemname');
        $batchno_id = Request('batchno_id');
        $returnItemQuality_id = Request('returnItemQuality_id');
        $unite_price = Request('unite_price');

    $current_date_time = Carbon::now()->toDateTimeString();

  if($assetvalue=="brandnew" && $batchno_id!=""){
    $form_data = array(
        'unit_price' =>  $unite_price,
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Stock::findOrFail($batchno_id)
    ->update($form_data);
  }
  else if($assetvalue=="used" && $returnItemQuality_id!=""){
    $form_data = array(
        'unit_price' =>  $unite_price,
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Return_stock::findOrFail($returnItemQuality_id)
    ->update($form_data);
  }
   

  $pricesummary = new inventory_list_price_summary();
  $pricesummary->asset_value = $assetvalue;
  $pricesummary->item_id = $itemid;
  $pricesummary->item_name = $itemname;
  $pricesummary->unit_price = $unite_price;
  $pricesummary->status = '1';
  $pricesummary->create_by = Auth::id();
  $pricesummary->save();
    return response()->json(['success' => 'Unite Price successfully Updated']);

}

public function getlocationinselect2(Request $request) {
    $searchTerm = $request->input('search');

    $matchingData = DB::table('customerbranches')
        ->where(function ($query) use ($searchTerm) {
            $query->where('branch_name', 'like', '%' . $searchTerm . '%')
                   ->where('approve_status', 1)
                   ->where('status', 1);
        })
        ->limit(100)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    } else {
        $first5Items = DB::table('customerbranches')
            ->limit(5)->get();
        return response()->json($first5Items);
    }
}

}
