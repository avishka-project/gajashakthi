<?php

namespace App\Http\Controllers;

use App\Issue;
use App\Issuedetail;
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
        $items = DB::table('items')->select('items.*')
        ->whereIn('items.status', [1, 2])
        ->where('items.approve_status', 1)
        ->get();

        $employees = DB::table('employees')->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])
        // ->where('employees.approve_status', 1)
        ->get();

        $locations = DB::table('branches')->select('branches.*')
        // ->whereIn('branches.status', [1, 2])
        // ->where('branches.approve_status', 1)
        ->get();

        $departments = DB::table('departments')->select('departments.*')
        // ->whereIn('branches.status', [1, 2])
        // ->where('branches.approve_status', 1)
        ->get();

        return view('Issues.issue', compact('items','employees','locations','departments'));
    }

    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('Issue-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
    
        $user = Auth::user();

        $issue = new Issue();
        $issue->issuing = $request->input('issuing');
        $issue->location_id = $request->input('location');
        $issue->employee_id = $request->input('employee');
        $issue->month = $request->input('month');
        $issue->issue_type = $request->input('issuetype');
        $issue->payment_type = $request->input('paymenttype');
        $issue->remark = $request->input('remark');
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
            $item = $rowtabledata['col_5'];
            $rate = $rowtabledata['col_2'];
            $qty = $rowtabledata['col_3'];
            $total = $rowtabledata['col_4'];

            $issuedetail = new Issuedetail();
            $issuedetail->issue_id = $requestID;
            $issuedetail->item_id = $item;
            $issuedetail->rate = $rate;
            $issuedetail->qty = $qty;
            $issuedetail->total = $total;
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
                $user = Auth::user();

                        $permission = $user->can('Approve-Level-01');
                        if($permission){
                            if($row->approve_01 == 0){
                                $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        $permission = $user->can('Approve-Level-02');
                        if($permission){
                            if($row->approve_01 == 1 && $row->approve_02 == 0){
                                $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        $permission = $user->can('Approve-Level-03');
                        if($permission){
                            if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                                $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }

                        $permission = $user->can('Issue-edit');
                        if ($permission) {
                            if($row->approve_03 == 1 ){
                           $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm"
                           role="button"><i class="fa fa-eye"></i></button>';
                           }
                        }
                        

                        $permission = $user->can('Issue-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                    $permission = $user->can('Issue-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('issuestatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('issuestatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('Issue-delete');
                        if($permission){
                            $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('Issue-edit');
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
        $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
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
        $user = Auth::user();
       
        $permission =$user->can('Issue-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

            $hidden_id = $request->input('hidden_id');

            // $issue = Issue::where('id', $hidden_id)->first();
            // $issue->issuing = $request->input('issuing');
            // $issue->location_id = $request->input('location');
            // $issue->employee_id = $request->input('employee');
            // $issue->month = $request->input('month');
            // $issue->issue_type = $request->input('issuetype');
            // $issue->payment_type = $request->input('paymenttype');
            // $issue->remark = $request->input('remark');
            // $issue->status = '1';
            // $issue->approve_status = '0';
            // $issue->approve_01 = '0';
            // $issue->approve_02 = '0';
            // $issue->approve_03 = '0';
            // $issue->update_by = Auth::id();
            // $issue->save();
    
            $id =  $request->hidden_id ;
            $form_data = array(
                'issuing' =>  $request->input('issuing'),
                'location_id' =>  $request->input('location'),
                'employee_id' =>  $request->input('editempid'),
                'month' =>  $request->input('month'),
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
                if($rowtabledata['col_6'] == "Updated"){
           
                    $item = $rowtabledata['col_5'];
                    $rate = $rowtabledata['col_2'];
                    $qty = $rowtabledata['col_3'];
                    $total = $rowtabledata['col_4'];
                    $detailID = $rowtabledata['col_7'];
        
                    $issuedetail = Issuedetail::where('id', $detailID)->first();
                    $issuedetail->issue_id = $hidden_id;
                    $issuedetail->item_id = $item;
                    $issuedetail->rate = $rate;
                    $issuedetail->qty = $qty;
                    $issuedetail->total = $total;
                    $issuedetail->update_by = Auth::id();
                    $issuedetail->save();

                    
                }else if($rowtabledata['col_6'] == "NewData") {
                    $item = $rowtabledata['col_5'];
                    $rate = $rowtabledata['col_2'];
                    $qty = $rowtabledata['col_3'];
                    $total = $rowtabledata['col_4'];
                        if($item != 0){
                            $issuedetail = new Issuedetail();
                            $issuedetail->issue_id = $hidden_id;
                            $issuedetail->item_id = $item;
                            $issuedetail->rate = $rate;
                            $issuedetail->qty = $qty;
                            $issuedetail->total = $total;
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
        $user = Auth::user();
        $permission =$user->can('Issue-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('issues')
        ->select('issues.*')
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
        $htmlTable .= '<td class="d-none">' . $row->item_id . '</td>'; 
        $htmlTable .= '<td class="d-none">ExistingData</td>'; 
        $htmlTable .= '</tr>';
       }

       return $htmlTable;

   }



    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('Issue-delete');
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
        Issue::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Issue is successfully Deleted']);

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
        $user = Auth::user();
       
       
        $permission =$user->can('Item-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
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

        $user = Auth::user();
      
        $permission =$user->can('Issue-delete');
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

    public function getsaleprice(Request $request){

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('items')
        ->select('items.*')
        ->where('items.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
}
}
