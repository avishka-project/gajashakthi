<?php

namespace App\Http\Controllers;

use App\Customerbranch;
use App\Customerrequest;
use App\empallocation;
use App\empallocationdetail;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Gate;

class EmpallocationController extends Controller
{
    public function index()
    {

        $user = Auth::user();
               
        $permission = $user->can('allocation-admin-create');
        if($permission){
            $customers = Customerbranch::orderBy('id', 'asc')
            ->whereIn('customerbranches.status', [1, 2])
            ->where('customerbranches.approve_status', 1)
            ->get();
        }else{
     $customers = Customerbranch::orderBy('id', 'asc')
            ->leftjoin('subregions','customerbranches.subregion_id','=','subregions.id')
            ->select('customerbranches.*' )
            ->whereIn('customerbranches.status', [1, 2])
            ->where('customerbranches.approve_status', 1)
            ->where('subregions.emp_id', $user)
            ->get();
        }

      

        $employees = DB::table('employees')
        ->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])
        ->get();

        $jobtitles = DB::table('job_titles')
        ->select('job_titles.*')
        ->get();
       
        return view('EmployeeAllocation.employeeallocation'  ,compact('customers','employees','jobtitles'));
    }
    public function requestlist(Request $request){

        $id = Request('cusid');
        
        $requests = DB::table('customerrequests')
                    ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
                    ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                    ->leftjoin('shift_types', 'customerrequests.shift_id', '=', 'shift_types.id')
                    ->select('customerrequests.*','customers.name','customerbranches.branch_name','shift_types.shift_name')
                    ->where('customerrequests.customerbranch_id', $id)
                    ->where('customerrequests.allocationstatus', 0)
                    ->get();

            return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                        
                  $btn .= ' <button name="allocate" id="'.$row->id.'" class="allocate btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-plus"></i></button>';
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function list(Request $request){

        $id = Request('cusid');
        
        $requests = DB::table('empallocations')
                    ->leftjoin('customers', 'empallocations.customer_id', '=', 'customers.id')
                    ->leftjoin('customerbranches', 'empallocations.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('shift_types', 'empallocations.shift_id', '=', 'shift_types.id')
                    ->select('empallocations.*','customers.name AS customername','customerbranches.branch_name AS branchname','shift_types.shift_name AS shiftname')
                    ->where('empallocations.customerbranch_id', $id)
                    ->whereIn('empallocations.status', [1, 2])
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

                        $permission = $user->can('allocation-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    
                        $permission = $user->can('allocation-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('alloctionstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('alloctionstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                $permission = $user->can('allocation-delete');
                if($permission){
                    $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';  
                }
                 return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }




    public function requestdetails(Request $request){
    
                $id = Request('id');
                if (request()->ajax()){
                $data = DB::table('customerrequests')
                            ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                            ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
                            ->leftjoin('subcustomers', 'customerrequests.subcustomer_id', '=', 'subcustomers.id')
                            ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                            ->leftjoin('shift_types', 'customerrequests.shift_id', '=', 'shift_types.id')
                            ->leftjoin('holidays', 'customerrequests.holiday_id', '=', 'holidays.id')
                            ->select('customerrequests.*', 'customers.name', 'customerbranches.branch_name', 'shift_types.shift_name','subcustomers.sub_name')
                            ->where('customerrequests.id', $id)
                            ->get(); 
                return response() ->json(['result'=> $data[0]]);
            }

    }




    public function requestdetailslist(){
        $id = Request('id');
    
            if (request()->ajax()){
            $data = DB::table('customerrequests')
                        ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                        ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                        ->select('customerrequests.*', 'job_titles.title','customerrequestdetails.count')
                        ->where('customerrequests.id', $id)
                        ->get(); 
    
    
                        $htmlTable = '';
                        foreach ($data as $row) {
                           
                            $htmlTable .= '<tr>';
                            $htmlTable .= '<td>' . $row->title . '</td>'; 
                            $htmlTable .= '<td>' . $row->count . '</td>'; 
                            $htmlTable .= '</tr>';
                        }
                      
                        return response() ->json(['result2'=>$htmlTable ]);
                   
           }
        
    }


    public function insert(Request $request){


        $user = Auth::user();
        $permission =$user->can('allocation-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $customerrequestid = $request->input('recordID');
            $allocation = new empallocation();
            $allocation->request_id =$customerrequestid ;
            $allocation->customer_id = $request->input('customer');
            $allocation->subcustomer_id = $request->input('subcustomer');
            $allocation->customerbranch_id = $request->input('branch');
            $allocation->date = $request->input('date');
            $allocation->holiday_id = $request->input('hollyday');
            $allocation->shift_id = $request->input('shift');
            $allocation->manager_id = Auth::id();
            $allocation->status = '1';
            $allocation->allocatedstatus = '1';
            $allocation->approve_status = '0';
            $allocation->approve_01 = '0';
            $allocation->approve_02 = '0';
            $allocation->approve_03 = '0';
            $allocation->delete_status = '0';
            $allocation->create_by = Auth::id();
            $allocation->update_by = '0';
            $allocation->save();
    
            $requestID = $allocation->id;
    
            $tableData = $request->input('tableData');
    
            foreach ($tableData as $rowtabledata) {
                $empID = $rowtabledata['col_3'];
                $title = $rowtabledata['col_4'];
    
                $allocationdetail = new empallocationdetail();
                $allocationdetail->allocation_id = $requestID;
                $allocationdetail->emp_id = $empID;
                $allocationdetail->assigndesignation_id = $title;
                $allocationdetail->status = '1';
                $allocationdetail->delete_status = '0';
                $allocationdetail->create_by = Auth::id();
                $allocationdetail->update_by = '0';
                $allocationdetail->save();
            }

            $form_data = array(
                'allocationstatus' =>  '1',
            );
            Customerrequest::findOrFail($customerrequestid)
           ->update($form_data);
    
            return response()->json(['status' => 1, 'message' => 'Employee Allocation is Successfully Created']);
    }



    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('allocation-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $id = Request('id');
                $data = DB::table('empallocations')
                    ->leftjoin('customers', 'empallocations.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'empallocations.subcustomer_id', '=', 'subcustomers.id')
                    ->leftjoin('customerbranches', 'empallocations.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('shift_types', 'empallocations.shift_id', '=', 'shift_types.id')
                    ->leftjoin('holidays', 'empallocations.holiday_id', '=', 'holidays.id')
                    ->select('empallocations.*', 'customers.name', 'customerbranches.branch_name','subcustomers.sub_name','shift_types.shift_name')
                    ->where('empallocations.id', $id)
                    ->get(); 

                    $requestlist = $this->reqestcountlist($id); 
                    $detaillist = $this->allocationdetailelist($id); 

                    $responseData = array(
                        'mainData' => $data[0],
                        'requestdata' => $requestlist,
                        'detaildata' => $detaillist
                    );

        return response() ->json(['result'=>  $responseData]);
      

    }

    private function reqestcountlist($id){

         $recordID =$id ;
        $data = DB::table('customerrequests')
        ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
        ->leftjoin('empallocations', 'customerrequests.id', '=', 'empallocations.request_id')
        ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
        ->select('customerrequests.*', 'job_titles.title','customerrequestdetails.count')
        ->where('empallocations.id', $recordID)
        ->where('customerrequestdetails.status', 1)
        ->get(); 


        $htmlTable = '';
        foreach ($data as $row) {
           
            $htmlTable .= '<tr>';
            $htmlTable .= '<td>' . $row->title . '</td>'; 
            $htmlTable .= '<td>' . $row->count . '</td>'; 
            $htmlTable .= '</tr>';
        }

        return $htmlTable;

    }

    private function allocationdetailelist($id){

        $recordID =$id ;
        $data = DB::table('empallocationdetails')
        ->leftjoin('empallocations', 'empallocationdetails.allocation_id', '=', 'empallocations.id')
        ->leftjoin('job_titles', 'empallocationdetails.assigndesignation_id', '=', 'job_titles.id')
        ->leftjoin('employees','empallocationdetails.emp_id','=','employees.id')
        ->select('empallocationdetails.*', 'job_titles.title','employees.emp_fullname')
        ->where('empallocationdetails.allocation_id', $recordID)
        ->where('empallocationdetails.status', 1)
        ->get(); 


        $html = '';
        foreach ($data as $row) {
           
            $html .= '<tr>';
            $html .= '<td>' . $row->emp_fullname . '</td>'; 
            $html .= '<td>' . $row->title . '</td>'; 
            $html .= '<td id ="actionrow"><button type="button" id="'.$row->id.'" class="btnEditlist btn btn-primary btn-sm ">
            <i class="fas fa-pen"></i>
          </button>&nbsp;
          <button type="button" id="'.$row->id.'" class="btnDeletelist btn btn-danger btn-sm " >
            <i class="fas fa-trash-alt"></i>
          </button></td>'; 
            $html .= '<td class="d-none">' . $row->allocation_id. '</td>'; 
            $html .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->id.'"></td>'; 
            
            $html .= '</tr>';
        }

        return $html;

    }


    public function editlist(Request $request){
                    $id = Request('id');
                    if (request()->ajax()){
                    $data = DB::table('empallocationdetails')
                                ->select('empallocationdetails.*')
                                ->where('empallocationdetails.id', $id)
                                ->get(); 
                    return response() ->json(['result'=> $data[0]]);
                }
    }
    public function deletelist(Request $request){
        $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'delete_status' =>  '1',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        empallocationdetail::findOrFail($id)
        ->update($form_data);
    
        return response()->json(['success' => 'Employee Allocation list is successfully Deleted']);
    }



public function update(Request $request){
    $user = Auth::user();
   
    $permission =$user->can('allocation-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   
        $current_date_time = Carbon::now()->toDateTimeString();

   

    $hidden_id = $request->hidden_id ;

    $allocation = empallocation::where('id', $hidden_id)->first();
    $allocation->approve_status = '0';
    $allocation->approve_01 = '0';
    $allocation->approve_02 = '0';
    $allocation->approve_03 = '0';
    $allocation->status = '1';
    $allocation->update_by =Auth::id();
    $allocation->save();

    $id =  $request->hidden_id ;
    $form_data = array(
            'approve_status' =>  '0',
            'approve_01' =>  '0',
            'approve_02' =>  '0',
            'approve_03' =>  '0',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time, 
        );

        empallocation::findOrFail($id)
    ->update($form_data);


    $tableData = $request->input('tableData');

    
    foreach ($tableData as $rowtabledata) {
        if(isset($rowtabledata['col_5'])){

            $empID = $rowtabledata['col_3'];
            $title = $rowtabledata['col_4'];
            $detailID = $rowtabledata['col_5'];

            $allocationdetail = empallocationdetail::where('id', $detailID)->first();
            $allocationdetail->emp_id = $empID;
            $allocationdetail->assigndesignation_id = $title;
            $allocationdetail->update_by = Auth::id();
            $allocationdetail->save();
            
        }  else {
            $empID = $rowtabledata['col_3'];
            $title = $rowtabledata['col_4'];
                if($empID != 0){
                    $allocationdetail = new empallocationdetail();
                    $allocationdetail->allocation_id = $hidden_id;
                    $allocationdetail->emp_id = $empID;
                    $allocationdetail->assigndesignation_id = $title;
                    $allocationdetail->status = '1';
                    $allocationdetail->delete_status = '0';
                    $allocationdetail->create_by = Auth::id();
                    $allocationdetail->update_by = '0';
                    $allocationdetail->save();
                }
          }
    }

    return response()->json(['status' => 1, 'message' => 'Employee Allocation is Successfully Updated']);

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
        empallocation::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employee Allocation is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        empallocation::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Employee Allocation is successfully Approved']);

     }else{
        $form_data = array(
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        empallocation::findOrFail($id)
        ->update($form_data);

       return response()->json(['success' => 'Employee Allocation  is successfully Approved']);
      }
}


public function delete(Request $request){
    $user = Auth::user();
  
    $permission =$user->can('allocation-delete');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
    
    $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'delete_status' =>  '1',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    empallocation::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'Employee Allocation is successfully Deleted']);

}



public function status($id,$statusid){
    $user = Auth::user();
   
   
    $permission =$user->can('allocation-status');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }


    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        empallocation::findOrFail($id)
        ->update($form_data);

        return redirect()->route('allocation');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        empallocation::findOrFail($id)
        ->update($form_data);

        return redirect()->route('allocation');
    }

}

public function perviousdatelist(Request $request){

    $user = Auth::user();
    $permission =$user->can('allocation-create');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $customerid = $request->input('customer');
        $subcustomerid = $request->input('subcustomer');
        $branch = $request->input('branch');
        $shift = $request->input('shift');
        $date = $request->input('date');
       
        $carbonDate = new Carbon($date);
        $oneDayBefore = $carbonDate->subDay();


    $data = DB::table('empallocationdetails')
    ->leftjoin('empallocations', 'empallocationdetails.allocation_id', '=', 'empallocations.id')
    ->leftjoin('job_titles', 'empallocationdetails.assigndesignation_id', '=', 'job_titles.id')
    ->leftjoin('employees','empallocationdetails.emp_id','=','employees.id')
    ->select('empallocationdetails.*', 'job_titles.title','employees.emp_fullname')
    ->where('empallocations.customer_id', $customerid)
    ->where('empallocations.subcustomer_id', $subcustomerid)
    ->where('empallocations.customerbranch_id', $branch)
    ->where('empallocations.shift_id', $shift)
    ->where('empallocations.date', $oneDayBefore)
    ->where('empallocations.approve_status', 1)
    ->where('empallocations.allocatedstatus', 1)
    ->where('empallocationdetails.status', 1)
    ->get(); 


    $html = '';
    foreach ($data as $row) {
       
        $html .= '<tr>';
        $html .= '<td>' . $row->emp_fullname . '</td>'; 
        $html .= '<td>' . $row->title . '</td>'; 
        $html .= '<td class="d-none">' . $row->emp_id . '</td>'; 
        $html .= '<td class="d-none">' . $row->assigndesignation_id . '</td>'; 
        $html .= '<td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td>'; 
        
        $html .= '</tr>';
    }
    return response() ->json(['result'=>  $html]);

  

}



}
