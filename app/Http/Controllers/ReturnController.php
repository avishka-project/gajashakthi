<?php

namespace App\Http\Controllers;

use App\Returnlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Issue;
use App\Returndetail;
use Auth;
use Carbon\Carbon;
use DB;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $approvel01permission = 0;
        $approvel02permission = 0;
        $approvel03permission = 0;

        $listpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        $statuspermission = 0;
        
        if (Auth::user()->can('Approve-Level-01')) {
            $approvel01permission = 1;
        } 
        if (Auth::user()->can('Approve-Level-02')) {
            $approvel02permission = 1;
        } 
        if (Auth::user()->can('Approve-Level-03')) {
            $approvel03permission = 1;
        } 
        if (Auth::user()->can('Return-list')) {
            $listpermission = 1;
        } 
        if (Auth::user()->can('Return-edit')) {
            $editpermission = 1;
        }
        if (Auth::user()->can('Return-status')) {
            $statuspermission = 1;
        }
        if (Auth::user()->can('Return-delete')) {
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

        return view('Returnlist.return',compact('locations','departments','listpermission','editpermission','deletepermission','statuspermission','employees'));
    }
 

    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('Return-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('issues')
        ->select('issues.*')
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
        $htmlTable .= '<input type="text" id="assetvalue' . $count . '" name="assetvalue' . $count . '" > %';
        $htmlTable .= '</td>';   
        $htmlTable .= '<td>';
        $htmlTable .= '<select id="stock' . $count . '" name="stock' . $count . '">
                        <option value="">Select Location</option>';
                        foreach ($storelists as $storelist) {
        $htmlTable .= '<option value="' . $storelist->id . '">' . $storelist->name . '</option>';}
        $htmlTable .= '</select>';
        $htmlTable .= '</td>';        
        $htmlTable .= '<td class="d-none" id="itemid">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
        $htmlTable .= '</tr>';

        $count++;
       }

       return $htmlTable;

   }

   public function add(Request $request){
    $user = Auth::user();
    $permission =$user->can('Return-create');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        $current_date_time = Carbon::now()->toDateTimeString();

    $user = Auth::user();

    $returnlist = new Returnlist();
    $returnlist->issuing = $request->input('issuing');
    $returnlist->location_id = $request->input('location');
    $returnlist->department_id = $request->input('department');
    $returnlist->employee_id = $request->input('employee');
    $returnlist->month = $request->input('month');
    $returnlist->issue_type = $request->input('issuetype');
    $returnlist->payment_type = $request->input('paymenttype');
    $returnlist->remark = $request->input('remark');
    $returnlist->issue_id = $request->input('hidden_id');
    $returnlist->status = '1';
    $returnlist->approve_status = '0';
    $returnlist->approve_01 = '0';
    $returnlist->approve_02 = '0';
    $returnlist->approve_03 = '0';
    $returnlist->create_by = Auth::id();
    $returnlist->update_by = '0';
    $returnlist->save();

    $requestID = $returnlist->id;

    $tableDataArray = $request->input('tableDataArray');

    foreach ($tableDataArray as $rowtableDataArray) {
        $item = $rowtableDataArray['item'];
        $rate = $rowtableDataArray['rate'];
        $qty = $rowtableDataArray['qty'];
        $total = $rowtableDataArray['total'];
        $storelist_id = $rowtableDataArray['stockId'];
        $asset_value = $rowtableDataArray['assetvalue'];

        $returndetail = new Returndetail();
        $returndetail->return_id = $requestID;
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

    $id = $request->input('hidden_id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'add_to_return' =>  'Yes',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Issue::findOrFail($id)
    ->update($form_data);
    return response()->json(['success' => 'Return is successfully Add']);
    // return response()->json(['status' => 1, 'message' => 'Issue is successfully Inserted']);
}


public function getserviceno(Request $request) {
    $searchTerm = $request->input('search');

    $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('service_no', '=', $searchTerm);
        })
        ->limit(10)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    } 
}
public function getempname(Request $request) {
    $searchTerm = $request->input('search');

    $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('emp_fullname', '=', $searchTerm)
                   ->orWhere('emp_fullname', 'like', '%' . $searchTerm . '%')
                   ->orWhere('emp_name_with_initial', 'like', '%' . $searchTerm . '%');
        })
        ->limit(10)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    }
}
public function getempnic(Request $request) {
    $searchTerm = $request->input('search');

    $matchingData = DB::table('employees')
        ->where(function ($query) use ($searchTerm) {
            $query->where('emp_national_id', '=', $searchTerm);
        })
        ->limit(10)
        ->get();

    if ($matchingData->count() > 0) {
        return response()->json($matchingData);
    }
}

}
