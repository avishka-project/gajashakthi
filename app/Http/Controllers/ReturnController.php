<?php

namespace App\Http\Controllers;

use App\Returnlist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
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
            $deletepermission = 1;
        }
        if (Auth::user()->can('Return-delete')) {
            $statuspermission = 1;
        }
        $locations = DB::table('branches')->select('branches.*')
        // ->whereIn('branches.status', [1, 2])
        // ->where('branches.approve_status', 1)
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
       ->leftjoin('items', 'issuedetails.item_id', '=', 'items.id')
       ->select('issuedetails.*', 'items.item_name', DB::raw('(issuedetails.id) AS issuedetailsID'))
       ->where('issuedetails.issue_id', $recordID)
       ->where('issuedetails.status', 1)
       ->get(); 


       $htmlTable = '';
       foreach ($data as $row) {
          
        $htmlTable .= '<tr>';
        $htmlTable .= '<td>' . $row->item_name . '</td>'; 
        $htmlTable .= '<td>' . $row->rate . '</td>'; 
        $htmlTable .= '<td>' . $row->qty . '</td>'; 
        $htmlTable .= '<td>' . $row->total . '</td>'; 
        $htmlTable .= '<td>';
        $htmlTable .= '<input type="text" id="assetvalue_' . $row->item_id . '" name="assetvalue_' . $row->item_id . '" >';
        $htmlTable .= '</td>';        
        $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
        $htmlTable .= '</tr>';
       }

       return $htmlTable;

   }

}
