<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customerbranch;
use App\Employeepayment;
use App\Employeepaymentdetail;
use App\Subcustomer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class Employeepaymentcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1])->get();
        $subcustomers = Subcustomer::orderBy('id', 'asc')->whereIn('subcustomers.status', [1])->get();
        $areas = Customerbranch::orderBy('id', 'asc')->whereIn('customerbranches.status', [1])->get();
        $titles = DB::table('job_titles')->select('job_titles.*')->get();
        $holidays = DB::table('holiday_types')->select('holiday_types.*')->get();
        $shifttypes = DB::table('shift_types')->select('shift_types.*')->get();

        return view('Employeepayment.employeepayment', compact('customers', 'subcustomers','areas', 'titles', 'holidays', 'shifttypes'));
    } 

    public function insert(Request $request)
    {
        $user = Auth::user();
        $permission =$user->can('EmployeePayment-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $this->validate($request, [
            'customer' => 'required',
            'subcustomer' => 'required',
         //   'date' => 'required',
            'area' => 'required',
            'shift' => 'required',
            'holiday' => 'required',
            'tableData' => 'required',
        ]);

        $employeepayment = new Employeepayment();
        $employeepayment->customer_id = $request->input('customer');
        $employeepayment->customerbranch_id = $request->input('area');
        $employeepayment->subcustomer_id = $request->input('subcustomer');
        $employeepayment->holiday_type_id = $request->input('holiday');
        $employeepayment->shift_id = $request->input('shift');
        $employeepayment->status = '1';
        $employeepayment->allocationstatus = '0';
        $employeepayment->approve_status = '0';
        $employeepayment->approve_01 = '0';
        $employeepayment->approve_02 = '0';
        $employeepayment->approve_03 = '0';
        $employeepayment->status = '1';
        $employeepayment->create_by = Auth::id();
        $employeepayment->update_by = '0';
        $employeepayment->save();

        $requestID = $employeepayment->id;

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            // $count = $rowtabledata['col_4'];
            $title = $rowtabledata['col_10'];
            // $holidaytype = $rowtabledata['col_8'];
            // $shift = $rowtabledata['col_9'];
            $companyrate = $rowtabledata['col_5'];
            $guardrate = $rowtabledata['col_6'];
            $extra_otrate = $rowtabledata['col_7'];

            $employeepaymentdetail = new Employeepaymentdetail();
            $employeepaymentdetail->employeepayments_id = $requestID;
            $employeepaymentdetail->job_title_id = $title;
            // $employeepaymentdetail->holiday_type_id = $holidaytype;
            // $employeepaymentdetail->shift_id = $shift;
            $employeepaymentdetail->companyrate = $companyrate;
            $employeepaymentdetail->guardrate = $guardrate;
            $employeepaymentdetail->extra_otrate = $extra_otrate;
            $employeepaymentdetail->status = '1';
            $employeepaymentdetail->create_by = Auth::id();
            $employeepaymentdetail->update_by = '0';
            $employeepaymentdetail->save();
        }

        return response()->json(['status' => 1, 'message' => 'Employee Payment is Successfully Created']);
    }

    public function displayemployeepayment(Request $request)
    {

        $requests = DB::table('employeepayments')
                    ->join('customers', 'employeepayments.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'employeepayments.subcustomer_id', '=', 'subcustomers.id')
                    ->join('customerbranches', 'employeepayments.customerbranch_id', '=', 'customerbranches.id')
                    //   ->join('job_titles', 'employeepaymentdetails.job_title_id', '=', 'job_titles.id')
                    ->join('shift_types', 'employeepayments.shift_id', '=', 'shift_types.id')
                     ->join('holiday_types', 'employeepayments.holiday_type_id', '=', 'holiday_types.id')
                     ->select('employeepayments.*', 'customers.name AS customer_name', 'customerbranches.branch_name AS branch', 'subcustomers.sub_name AS subname', 'shift_types.shift_name AS shifts', 'holiday_types.name as holiday_type_name')
                    ->whereIn('employeepayments.status', [1, 2])
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

                        $permission = $user->can('EmployeePayment-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    
                        $permission = $user->can('EmployeePayment-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('employeepaymentstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('employeepaymentstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                $permission = $user->can('EmployeePayment-delete');
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
      
        $permission =$user->can('EmployeePayment-delete');
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
        Employeepayment::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employee Payment is successfully Deleted']);

    }


    public function approvel_details(Request $request){
        $user = Auth::user();
        $permission =$user->can('EmployeePayment-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('employeepayments')
        ->join('employeepaymentdetails', 'employeepaymentdetails.employeepayments_id', '=', 'employeepayments.id')
        ->join('customers', 'employeepayments.customer_id', '=', 'customers.id')
        ->leftjoin('subcustomers', 'employeepayments.subcustomer_id', '=', 'subcustomers.id')
        ->join('customerbranches', 'employeepayments.customerbranch_id', '=', 'customerbranches.id')

        ->join('shift_types', 'employeepayments.shift_id', '=', 'shift_types.id')

        ->join('job_titles', 'employeepaymentdetails.job_title_id', '=', 'job_titles.id')
        ->select('employeepayments.*','customers.name','customerbranches.branch_name','subcustomers.sub_name')
        ->whereIn('employeepayments.status', [1, 2])
        ->groupBy('employeepayments.customer_id', 'employeepayments.subcustomer_id', 'employeepayments.customerbranch_id')
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
   $data = DB::table('employeepayments')
   ->leftjoin('employeepaymentdetails', 'employeepaymentdetails.employeepayments_id', '=', 'employeepayments.id')
   ->leftjoin('job_titles', 'employeepaymentdetails.job_title_id', '=', 'job_titles.id')
   ->select('employeepayments.*', 'job_titles.title','employeepaymentdetails.companyrate' ,'employeepaymentdetails.guardrate','employeepaymentdetails.job_title_id','employeepaymentdetails.extra_otrate', DB::raw('(employeepaymentdetails.id) AS employeepaymentdetailsID'))
   ->where('employeepaymentdetails.employeepayments_id', $recordID)
   ->where('employeepaymentdetails.status', 1)
   ->get(); 


   $htmlTable = '';
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->title . '</td>'; 
    $htmlTable .= '<td>' . $row->companyrate . '</td>'; 
    $htmlTable .= '<td>' . $row->guardrate . '</td>';
    $htmlTable .= '<td>' . $row->extra_otrate . '</td>';  
    $htmlTable .= '<td class="d-none">' . $row->job_title_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->employeepaymentdetailsID.'"></td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function edit(Request $request){
    $user = Auth::user();
    $permission =$user->can('EmployeePayment-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('employeepayments')
    ->join('employeepaymentdetails', 'employeepaymentdetails.employeepayments_id', '=', 'employeepayments.id')
    ->join('customers', 'employeepayments.customer_id', '=', 'customers.id')
    ->leftjoin('subcustomers', 'employeepayments.subcustomer_id', '=', 'subcustomers.id')
    ->join('customerbranches', 'employeepayments.customerbranch_id', '=', 'customerbranches.id')

    ->join('shift_types', 'employeepayments.shift_id', '=', 'shift_types.id')

    ->join('job_titles', 'employeepaymentdetails.job_title_id', '=', 'job_titles.id')
    ->select('employeepayments.*','customers.name','customerbranches.branch_name','subcustomers.sub_name')
    ->whereIn('employeepayments.status', [1, 2])
    ->groupBy('employeepayments.customer_id', 'employeepayments.subcustomer_id', 'employeepayments.customerbranch_id')
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
   $data = DB::table('employeepayments')
   ->leftjoin('employeepaymentdetails', 'employeepaymentdetails.employeepayments_id', '=', 'employeepayments.id')
   ->leftjoin('job_titles', 'employeepaymentdetails.job_title_id', '=', 'job_titles.id')
   ->select('employeepayments.*', 'job_titles.title','employeepaymentdetails.companyrate' ,'employeepaymentdetails.guardrate','employeepaymentdetails.job_title_id','employeepaymentdetails.extra_otrate', DB::raw('(employeepaymentdetails.id) AS employeepaymentdetailsID'))
   ->where('employeepaymentdetails.employeepayments_id', $recordID)
   ->where('employeepaymentdetails.status', 1)
   ->get(); 


   $htmlTable = '';
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->title . '</td>'; 
    $htmlTable .= '<td>' . $row->companyrate . '</td>'; 
    $htmlTable .= '<td>' . $row->guardrate . '</td>'; 
    $htmlTable .= '<td>' . $row->extra_otrate . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->job_title_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '<td id ="actionrow"><button type="button" id="'.$row->employeepaymentdetailsID.'" class="btnEditlist btn btn-primary btn-sm ">
        <i class="fas fa-pen"></i>
        </button>&nbsp;
        <button type="button" id="'.$row->employeepaymentdetailsID.'" class="btnDeletelist btn btn-danger btn-sm " >
        <i class="fas fa-trash-alt"></i>
        </button></td>'; 
    $htmlTable .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->employeepaymentdetailsID.'"></td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function editlist(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('employeepaymentdetails')
                ->select('employeepaymentdetails.*')
                ->where('employeepaymentdetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}


public function update(Request $request){
    $user = Auth::user();
   
    $permission =$user->can('EmployeePayment-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   
        $current_date_time = Carbon::now()->toDateTimeString();

    $this->validate($request, [
        'customer' => 'required',
        'subcustomer' => 'required',
        'area' => 'required',
        'holiday' => 'required',
        'shift' => 'required',
        'tableData' => 'required',
    ]);

    $hidden_id = $request->input('hidden_id');

    $employeepayment = Employeepayment::where('id', $hidden_id)->first();
    $employeepayment->customer_id = $request->input('customer');
    $employeepayment->subcustomer_id = $request->input('subcustomer');
    $employeepayment->customerbranch_id = $request->input('area');
    $employeepayment->holiday_type_id = $request->input('holiday');
    $employeepayment->shift_id = $request->input('shift');
    $employeepayment->status = '1';
    $employeepayment->allocationstatus = '0';
    $employeepayment->approve_status = '0';
    $employeepayment->approve_01 = '0';
    $employeepayment->approve_02 = '0';
    $employeepayment->approve_03 = '0';
    $employeepayment->update_by = Auth::id();
    $employeepayment->save();

    
    $id =  $request->hidden_id ;
    $form_data = array(
        'customer_id' =>  $request->input('customer'),
        'customerbranch_id' =>  $request->input('area'),
        'holiday_type_id' =>  $request->input('holiday'),
        'shift_id' =>  $request->input('shift'),
        'allocationstatus' =>  '0',
            'approve_status' =>  '0',
            'approve_01' =>  '0',
            'approve_02' =>  '0',
            'approve_03' =>  '0',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time, 
        );

        Employeepayment::findOrFail($id)
    ->update($form_data);
    
    // DB::table('employee_payment_details')
    // ->where('employeepayments_id', $hidden_id)
    // ->delete();

    $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        if($rowtabledata['col_6'] == "Updated"){
   
            $companyrate = $rowtabledata['col_2'];  
            $guardrate = $rowtabledata['col_3'];   
            $extra_otrate = $rowtabledata['col_4'];
            $titleID = $rowtabledata['col_5'];  
            $detailID = $rowtabledata['col_7'];

            $employeepaymentdetail = Employeepaymentdetail::where('id', $detailID)->first();
            $employeepaymentdetail->job_title_id = $titleID;
            $employeepaymentdetail->companyrate = $companyrate;
            $employeepaymentdetail->guardrate = $guardrate;
            $employeepaymentdetail->extra_otrate = $extra_otrate;
            $employeepaymentdetail->update_by = Auth::id();
            $employeepaymentdetail->save();
            
        }else if($rowtabledata['col_6'] == "NewData") {
            $companyrate = $rowtabledata['col_2'];
            $guardrate = $rowtabledata['col_3'];
            $extra_otrate = $rowtabledata['col_4'];
            $titleID = $rowtabledata['col_5'];
                if($titleID != 0){
                    $employeepaymentdetail = new Employeepaymentdetail();
                    $employeepaymentdetail->employeepayments_id = $hidden_id;
                    $employeepaymentdetail->job_title_id = $titleID;
                    $employeepaymentdetail->companyrate = $companyrate;
                    $employeepaymentdetail->guardrate = $guardrate;
                    $employeepaymentdetail->extra_otrate = $extra_otrate;
                    $employeepaymentdetail->status = '1';
                    $employeepaymentdetail->create_by = Auth::id();
                    $employeepaymentdetail->update_by = '0';
                    $employeepaymentdetail->save();
                }
          }
    }

    return response()->json(['status' => 1, 'message' => 'Employee Payment is Successfully Updated']);

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
        Employeepayment::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employee Payment is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Employeepayment::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Employee Payment is successfully Approved']);

     }else{
        $form_data = array(
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Employeepayment::findOrFail($id)
        ->update($form_data);

       return response()->json(['success' => 'Employee Payment is successfully Approved']);
      }
}


public function status($id,$statusid){
    $user = Auth::user();
   
   
    $permission =$user->can('EmployeePayment-status');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }


    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Employeepayment::findOrFail($id)
        ->update($form_data);

        return redirect()->route('employeepayment');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Employeepayment::findOrFail($id)
        ->update($form_data);

        return redirect()->route('employeepayment');
    }

}

public function getSubCustomers($customerId)
{
    $subCustomers = DB::table('subcustomers')->select('subcustomers.*')->where('customer_id', '=', $customerId)->get();

    return response()->json($subCustomers);
}

public function getbranch($subcustomerId)
{
    $branch = DB::table('customerbranches')->select('customerbranches.*')->where('subcustomer_id', '=', $subcustomerId)->get();

    return response()->json($branch);
}
}