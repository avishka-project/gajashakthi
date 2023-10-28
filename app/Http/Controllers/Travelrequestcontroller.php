<?php

namespace App\Http\Controllers;

use App\Emp_expense;
use App\Travelrequest;
use App\Travelrequestdetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class Travelrequestcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $branches = DB::table('branches')->select('branches.*')->get();
        $employees = DB::table('employees')->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])->get();
        return view('Travelrequest.travelrequest', compact('branches', 'employees'));
    } 

    public function insert(Request $request)
    {
        $user = Auth::user();
        $permission =$user->can('Travelrequest-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $this->validate($request, [
        //     'customer' => 'required',
        //     'subcustomer' => 'required',
        //  //   'date' => 'required',
        //     'area' => 'required',
        //     'shift' => 'required',
        //     'holiday' => 'required',
        //     'tableData' => 'required',
        ]);

        $travelrequest = new Travelrequest();
        $travelrequest->location_id = $request->input('location');
        $travelrequest->month = $request->input('month');
        $travelrequest->amount = $request->input('amount');
        $travelrequest->remark = $request->input('remark');
        $travelrequest->status = '1';
        $travelrequest->approve_status = '0';
        $travelrequest->approve_01 = '0';
        $travelrequest->approve_02 = '0';
        $travelrequest->approve_03 = '0';
        $travelrequest->status = '1';
        $travelrequest->create_by = Auth::id();
        $travelrequest->update_by = '0';
        $travelrequest->save();

        $requestID = $travelrequest->id;

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            $employee = $rowtabledata['col_2'];
           


            $travelrequestdetail = new Travelrequestdetail();
            $travelrequestdetail->travelrequests_id = $requestID;
            $travelrequestdetail->emp_id = $employee;
            $travelrequestdetail->status = '1';
            $travelrequestdetail->create_by = Auth::id();
            $travelrequestdetail->update_by = '0';
            $travelrequestdetail->save();
        }
        return response()->json(['success' => 'Travel Request is successfully Inserted']);
        // return response()->json(['status' => 1, 'message' => 'Employee Payment is Successfully Created']);
    }

    public function requestlist()
    {

        $requests = DB::table('travelrequests')
                    ->leftjoin('branches', 'travelrequests.location_id', '=', 'branches.id')
                     ->select('travelrequests.*', 'branches.location AS location')
                    ->whereIn('travelrequests.status', [1, 2])
                    ->get();

        return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = Auth::user();

                $btn='';

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

                        $permission = $user->can('Travelrequest-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    
                        $permission = $user->can('Travelrequest-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('travelrequeststatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('travelrequeststatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                $permission = $user->can('Travelrequest-delete');
                if($permission){
                    $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';  
                }
                 return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function delete(Request $request){
        $user = Auth::user();
      
        $permission =$user->can('Travelrequest-delete');
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
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Travel Request is successfully Deleted']);

    }


    public function approvel_details(Request $request){
        $user = Auth::user();
        $permission =$user->can('Travelrequest-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

            $id = Request('id');
            if (request()->ajax()){
            $data = DB::table('travelrequests')
            ->select('travelrequests.*')
            ->where('travelrequests.id', $id)
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
   $data = DB::table('travelrequestdetails')
   ->leftjoin('employees', 'travelrequestdetails.emp_id', '=', 'employees.id')
   ->select('travelrequestdetails.*', 'employees.emp_fullname', DB::raw('(travelrequestdetails.id) AS travelrequestdetailsID'))
   ->where('travelrequestdetails.travelrequests_id', $recordID)
   ->where('travelrequestdetails.status', 1)
   ->get(); 


   $htmlTable = '';
   foreach ($data as $row) {
      
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->emp_fullname . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->emp_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function edit(Request $request){
    $user = Auth::user();
    $permission =$user->can('Travelrequest-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('travelrequests')
    ->select('travelrequests.*')
    ->where('travelrequests.id', $id)
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
    $data = DB::table('travelrequestdetails')
    ->leftjoin('employees', 'travelrequestdetails.emp_id', '=', 'employees.id')
    ->select('travelrequestdetails.*', 'employees.emp_fullname', DB::raw('(travelrequestdetails.id) AS travelrequestdetailsID'))
    ->where('travelrequestdetails.travelrequests_id', $recordID)
    ->where('travelrequestdetails.status', 1)
    ->get(); 


   $htmlTable = '';
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->emp_fullname . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->emp_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '<td id ="actionrow"><button type="button" id="'.$row->travelrequestdetailsID.'" class="btnEditlist btn btn-primary btn-sm ">
        <i class="fas fa-pen"></i>
        </button>&nbsp;
        <button type="button" id="'.$row->travelrequestdetailsID.'" class="btnDeletelist btn btn-danger btn-sm " >
        <i class="fas fa-trash-alt"></i>
        </button></td>'; 
    $htmlTable .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->travelrequestdetailsID.'"></td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function editlist(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('travelrequestdetails')
                ->select('travelrequestdetails.*')
                ->where('travelrequestdetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}


public function update(Request $request){
    $user = Auth::user();
   
    $permission =$user->can('Travelrequest-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   
        $current_date_time = Carbon::now()->toDateTimeString();



    $hidden_id = $request->input('hidden_id');

    
    $id =  $request->hidden_id ;
    $form_data = array(
        'location_id' =>  $request->input('location'),
        'month' =>  $request->input('month'),
        'amount' =>  $request->input('amount'),
        'remark' =>  $request->input('remark'),
            'approve_status' =>  '0',
            'approve_01' =>  '0',
            'approve_02' =>  '0',
            'approve_03' =>  '0',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time, 
        );

        Travelrequest::findOrFail($id)
    ->update($form_data);
    
    // DB::table('employee_payment_details')
    // ->where('employeepayments_id', $hidden_id)
    // ->delete();

    $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        if($rowtabledata['col_3'] == "Updated"){
   
            $employee = $rowtabledata['col_2'];  
            $detailID = $rowtabledata['col_4'];
          

            $travelrequestdetail = Travelrequestdetail::where('id', $detailID)->first();
            $travelrequestdetail->travelrequests_id = $hidden_id;
            $travelrequestdetail->emp_id = $employee;
            $travelrequestdetail->update_by = Auth::id();
            $travelrequestdetail->save();
            
        }else if($rowtabledata['col_3'] == "NewData") {
            $employee = $rowtabledata['col_2'];
                if($employee != 0){
                    $travelrequestdetail = new Travelrequestdetail();
                    $travelrequestdetail->travelrequests_id = $hidden_id;
                    $travelrequestdetail->emp_id = $employee;
                    $travelrequestdetail->status = '1';
                    $travelrequestdetail->create_by = Auth::id();
                    $travelrequestdetail->update_by = '0';
                    $travelrequestdetail->save();
                }
          }
    }

    return response()->json(['success' => 'Travel Request is successfully Updated']);

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
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Travel Request is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Travel Request is successfully Approved']);

     }else{
        $form_data = array(
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

           //expenses add to table

           $tableData = $request->input('tableData');

           foreach ($tableData as $rowtabledata) {
               $employee = $rowtabledata['col_2'];

               $emp_expense = new Emp_expense();
               $emp_expense->employee_id =  $employee;
               $emp_expense->cost = $request->input('cost');
               $emp_expense->expenses_type = 'Travel_Request';
               $emp_expense->month = $request->input('month');
               $emp_expense->status = '1';
               $emp_expense->create_by = Auth::id();
               $emp_expense->save();
           }

       return response()->json(['success' => 'Travel Request is successfully Approved']);
      }
}


public function status($id,$statusid){
    $user = Auth::user();
   
   
    $permission =$user->can('Travelrequest-status');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }


    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return redirect()->route('travelrequest');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return redirect()->route('travelrequest');
    }

}

public function deletelist(Request $request){

    $user = Auth::user();
  
    $permission =$user->can('Travelrequest-delete');
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
    Travelrequestdetail::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'Employee is successfully Deleted']);

}
}