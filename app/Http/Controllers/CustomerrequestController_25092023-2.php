<?php

namespace App\Http\Controllers;

use App\allocation_security_staff;
use Illuminate\Http\Request;
use App\Customer;
use App\Customerrequest;
use App\Customerbranch;
use App\Customerrequestdetail;
use App\empallocation;
use App\empallocationdetail;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;
use \PDF;


class CustomerrequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1])->get();
        $areas = Customerbranch::orderBy('id', 'asc')->whereIn('customerbranches.status', [1])->get();

        $holidays = DB::table('holiday_types')
        ->select('holiday_types.*')
        ->where('id', '!=', 6)
        ->get();
        
        $shifttypes = DB::table('shift_types')->select('shift_types.*')->get();
        $subcustomer = DB::table('subcustomers')->select('subcustomers.*')->get();

        $titles = DB::table('job_titles')
        ->select('job_titles.*')
        ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
        ->get();

         $specialholidays = DB::table('holiday_types')
         ->select('holiday_types.*')
         ->where('holiday_types.id', 6)
         ->get();

        return view('Customerrequest.customerrequest', compact('customers', 'areas', 'titles', 'holidays', 'shifttypes','subcustomer','specialholidays'));
    }

    public function insert(Request $request)
    {
        $user = Auth::user();
        $permission =$user->can('CustomerRequest-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $this->validate($request, [
            'customer' => 'required',
            'area' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
            'DetailsArrays' => 'required',
        ]);

        $customerrequest = new Customerrequest();
        $customerrequest->customer_id = $request->input('customer');
        $customerrequest->subcustomer_id = $request->input('subcustomer');
        $customerrequest->customerbranch_id = $request->input('area');
        $customerrequest->fromdate = $request->input('fromdate');
        $customerrequest->todate = $request->input('todate');
        // $customerrequest->holiday_id = $request->input('holiday');
        $customerrequest->subregion_id = $request->input('subregion_id');
        // $customerrequest->shift_id = $request->input('shift');
        $customerrequest->requeststatus = 'Normal';
        $customerrequest->status = '1';
        $customerrequest->approve_status = '0';
        $customerrequest->approve_01 = '0';
        $customerrequest->approve_02 = '0';
        $customerrequest->approve_03 = '0';
        $customerrequest->status = '1';
        $customerrequest->create_by = Auth::id();
        $customerrequest->update_by = '0';
        $customerrequest->save();

        $requestID = $customerrequest->id;

        $DetailsArrays = $request->input('DetailsArrays');

        foreach ($DetailsArrays as $title => $dataArray) {
            foreach ($dataArray as $dataObject) {
                $jobtitle = $dataObject['jobtitle'];
                $shift = $dataObject['shift'];
                $holiday = $dataObject['holiday'];
                $value = $dataObject['value'];
                
            $customerrequestdetail = new Customerrequestdetail();
            $customerrequestdetail->customerrequest_id = $requestID;
            $customerrequestdetail->job_title_id = $jobtitle;
            $customerrequestdetail->count = $value;
            $customerrequestdetail->shift_id = $shift;
            $customerrequestdetail->holiday_id = $holiday;
            $customerrequestdetail->status = '1';
            $customerrequestdetail->create_by = Auth::id();
            $customerrequestdetail->update_by = '0';
            $customerrequestdetail->save();
            }
        }



        return response()->json(['status' => 1, 'message' => 'Customer Request is Successfully Created']);
    }
 

    public function displaycustomerrequest(Request $request)
    {

        $requests = DB::table('customerrequests')               
                    ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'customerrequests.subcustomer_id', '=', 'subcustomers.id')
                    ->leftjoin('shift_types', 'customerrequests.shift_id', '=', 'shift_types.id')
                    ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                    ->select('customerrequests.*','customers.name AS customername','customerbranches.branch_name AS branch_name','subcustomers.sub_name AS sub_name','shift_types.shift_name AS shift_name')
                    ->whereIn('customerrequests.status', [1, 2])
                    ->get();

        return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = Auth::user();

                $btn='';

                $requestype=$row->requeststatus;

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

                $permission = $user->can('CustomerRequest-edit');
                if ($permission) {
                    if($row->approve_03 == 1 ){
                   $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm"
                   role="button"><i class="fa fa-eye"></i></button>';
                   }
                }

                $btn .= ' <button name="viewpdf" id="'.$row->id.'" class="viewDocument btn btn-outline-primary btn-sm"
                   role="button"><i class="fa fa-download"></i></button>';

                        $permission = $user->can('CustomerRequest-edit');
                        if($permission){
                            if($requestype=="Normal"){
                                $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                            }else{
                                $btn .= ' <button name="specialedit" id="'.$row->id.'" class="specialedit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>'; 
                            }
                          
                        }
                    
                        $permission = $user->can('CustomerRequest-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('customerrequeststatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('customerrequeststatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                $permission = $user->can('CustomerRequest-delete');
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
      
        $permission =$user->can('CustomerRequest-delete');
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
        Customerrequest::findOrFail($id)
        ->update($form_data);

        empallocation::where('request_id', $id)
        ->update([
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        ]);

        return response()->json(['success' => 'Customer Request is successfully Deleted']);

    }

    public function approvel_details(Request $request){
        $user = Auth::user();
        $permission =$user->can('CustomerRequest-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('customerrequests')
                    ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                    ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'customerrequests.subcustomer_id', '=', 'subcustomers.customer_id')
                    ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                    ->select('customerrequests.*', 'customers.name', 'customerbranches.branch_name', 'customerrequestdetails.job_title_id', 'customerrequestdetails.count','subcustomers.sub_name')
                    ->where('customerrequests.id', $id)
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

    $datadetails = DB::table('customerrequests')
    ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
    ->leftjoin('holiday_types', 'customerrequestdetails.holiday_id', '=', 'holiday_types.id')
    ->leftjoin('shift_types', 'customerrequestdetails.shift_id', '=', 'shift_types.id')
    ->select('customerrequests.*', 'job_titles.title AS title','job_titles.id AS title_id', 'holiday_types.name', 'shift_types.shift_name AS shift_name','shift_types.id AS shift_id','customerrequestdetails.count AS count','customerrequestdetails.holiday_id AS holiday_id','customerrequestdetails.shift_id AS shift_id','customerrequestdetails.job_title_id', DB::raw('(customerrequestdetails.id) AS customerrequestdetailsID'))
    ->where('customerrequestdetails.customerrequest_id', $recordID)
    ->whereIn('customerrequestdetails.status', [1, 2])
    ->get(); 
    

    $holidays = DB::table('holiday_types')
 ->select('holiday_types.*')
 ->where('id', '!=', 6)
 ->get();

$titles = DB::table('job_titles')
 ->select('job_titles.*')
 ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
 ->get();

$htmlTable = '';

foreach ($titles as $title) {
 $htmlTable .= '<tr>';
 $htmlTable .= '<th id="' . $title->id . '">' . $title->title . '</th>';

 foreach ($holidays as $holiday) {
     $dayInputValue = '';
     $nightInputValue = '';

     foreach ($datadetails as $rowlist) {
         if ($rowlist->title_id == $title->id && $rowlist->holiday_id == $holiday->id) {
             if ($rowlist->shift_id == 2) {
                 $dayInputValue = $rowlist->count;
             } elseif ($rowlist->shift_id == 3) {
                 $nightInputValue = $rowlist->count;
             }
         }
     }

     $htmlTable .= '<td class="text-center"><input type="number" class="text-center holiday-input3" onkeyup="calculateTotal3()" jobtitle_id3="' . $title->id . '" shift_id3="2" holiday_id2="' . $holiday->id . '" data-holiday3="' . $holiday->name . '" data-time3="day" style="width: 55px; border: none; background-color: transparent" id="' . $title->title . $holiday->name . '_day2" name="' . $title->title . $holiday->name . '_day2" value="' . $dayInputValue . '" readonly></td>';
     $htmlTable .= '<td class="text-center"><input type="number" class="text-center holiday-input3" onkeyup="calculateTotal3()" jobtitle_id3="' . $title->id . '" shift_id3="3" holiday_id2="' . $holiday->id . '" data-holiday3="' . $holiday->name . '" data-time3="night" style="width: 55px; border: none; background-color: transparent" id="' . $title->title . $holiday->name . '_night2" name="' . $title->title . $holiday->name . '_night2" value="' . $nightInputValue . '" readonly></td>';
 }

 $htmlTable .= '</tr>';
}

return $htmlTable;

}


    public function edit(Request $request){
            $user = Auth::user();
            $permission =$user->can('CustomerRequest-edit');
            if(!$permission) {
                    return response()->json(['error' => 'UnAuthorized'], 401);
                }

            $id = Request('id');
            if (request()->ajax()){
            $data = DB::table('customerrequests')
                        ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                        ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
                        ->leftjoin('subcustomers', 'customerrequests.subcustomer_id', '=', 'subcustomers.customer_id')
                        ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                        ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                        ->select('customerrequests.*', 'customers.name', 'customerbranches.branch_name', 'customerrequestdetails.job_title_id', 'customerrequestdetails.count','subcustomers.sub_name')
                        ->where('customerrequests.id', $id)
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

       $datadetails = DB::table('customerrequests')
       ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
       ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
       ->leftjoin('holiday_types', 'customerrequestdetails.holiday_id', '=', 'holiday_types.id')
       ->leftjoin('shift_types', 'customerrequestdetails.shift_id', '=', 'shift_types.id')
       ->select('customerrequests.*', 'job_titles.title AS title','job_titles.id AS title_id', 'holiday_types.name', 'shift_types.shift_name AS shift_name','shift_types.id AS shift_id','customerrequestdetails.count AS count','customerrequestdetails.holiday_id AS holiday_id','customerrequestdetails.shift_id AS shift_id','customerrequestdetails.job_title_id', DB::raw('(customerrequestdetails.id) AS customerrequestdetailsID'))
       ->where('customerrequestdetails.customerrequest_id', $recordID)
       ->whereIn('customerrequestdetails.status', [1, 2])
       ->get(); 
       

       $holidays = DB::table('holiday_types')
    ->select('holiday_types.*')
    ->where('id', '!=', 6)
    ->get();

$titles = DB::table('job_titles')
    ->select('job_titles.*')
    ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
    ->get();

$htmlTable = '';

foreach ($titles as $title) {
    $htmlTable .= '<tr>';
    $htmlTable .= '<th id="' . $title->id . '">' . $title->title . '</th>';

    foreach ($holidays as $holiday) {
        $dayInputValue = '';
        $nightInputValue = '';

        foreach ($datadetails as $rowlist) {
            if ($rowlist->title_id == $title->id && $rowlist->holiday_id == $holiday->id) {
                if ($rowlist->shift_id == 2) {
                    $dayInputValue = $rowlist->count;
                } elseif ($rowlist->shift_id == 3) {
                    $nightInputValue = $rowlist->count;
                }
            }
        }

        $htmlTable .= '<td class="text-center"><input type="number" class="text-center holiday-input2" onkeyup="calculateTotal2()" jobtitle_id2="' . $title->id . '" shift_id2="2" holiday_id2="' . $holiday->id . '" data-holiday2="' . $holiday->name . '" data-time2="day" style="width: 55px; border: none; background-color: transparent" id="' . $title->title . $holiday->name . '_day2" name="' . $title->title . $holiday->name . '_day2" value="' . $dayInputValue . '"></td>';
        $htmlTable .= '<td class="text-center"><input type="number" class="text-center holiday-input2" onkeyup="calculateTotal2()" jobtitle_id2="' . $title->id . '" shift_id2="3" holiday_id2="' . $holiday->id . '" data-holiday2="' . $holiday->name . '" data-time2="night" style="width: 55px; border: none; background-color: transparent" id="' . $title->title . $holiday->name . '_night2" name="' . $title->title . $holiday->name . '_night2" value="' . $nightInputValue . '"></td>';
    }

    $htmlTable .= '</tr>';
}

return $htmlTable;


   }
  

    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('CustomerRequest-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $this->validate($request, [
            'customer' => 'required',
            'area' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
            'DetailsArrays' => 'required',
        ]);

        $hidden_id = $request->input('hidden_id');

        $customerrequest = Customerrequest::where('id', $hidden_id)->first();
        $customerrequest->customer_id = $request->input('customer');
        $customerrequest->subcustomer_id = $request->input('subcustomer');
        $customerrequest->customerbranch_id = $request->input('area');
        $customerrequest->fromdate = $request->input('fromdate');
        $customerrequest->todate = $request->input('todate');
        $customerrequest->subregion_id = $request->input('subregion_id');
        $customerrequest->allocationstatus = '0';
        $customerrequest->approve_status = '0';
        $customerrequest->approve_01 = '0';
        $customerrequest->approve_02 = '0';
        $customerrequest->approve_03 = '0';
        $customerrequest->update_by = Auth::id();
        $customerrequest->save();

        DB::table('customerrequestdetails')
        ->where('customerrequest_id', $hidden_id)
        ->delete();
    
        $DetailsArrays = $request->input('DetailsArrays');

        foreach ($DetailsArrays as $title => $dataArray) {
            foreach ($dataArray as $dataObject) {
                $jobtitle = $dataObject['jobtitle'];
                $shift = $dataObject['shift'];
                $holiday = $dataObject['holiday'];
                $value = $dataObject['value'];
                
            $customerrequestdetail = new Customerrequestdetail();
            $customerrequestdetail->customerrequest_id = $hidden_id;
            $customerrequestdetail->job_title_id = $jobtitle;
            $customerrequestdetail->count = $value;
            $customerrequestdetail->shift_id = $shift;
            $customerrequestdetail->holiday_id = $holiday;
            $customerrequestdetail->status = '1';
            $customerrequestdetail->create_by = Auth::id();
            $customerrequestdetail->update_by = '0';
            $customerrequestdetail->save();
            }
        }

        return response()->json(['status' => 1, 'message' => 'Customer Request is Successfully Updated']);

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
            Customerrequest::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Customer Request is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
           ->update($form_data);
  
            return response()->json(['success' => 'Customer Request is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
            ->update($form_data);
   
           return response()->json(['success' => 'Customer Request is successfully Approved']);
          }
    }

    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('CustomerRequest-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('customerrequest');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('customerrequest');
        }

    }



    // spectial request part
    public function specialrequestinsert(Request $request)
    {
        $user = Auth::user();
        $permission =$user->can('CustomerRequest-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $this->validate($request, [
            'customer' => 'required',
            'area' => 'required',
            'fromdate' => 'required',
            'todate' => 'required',
            'tableData' => 'required',
        ]);

        $customerrequest = new Customerrequest();
        $customerrequest->customer_id = $request->input('customer');
        $customerrequest->subcustomer_id = $request->input('subcustomer');
        $customerrequest->customerbranch_id = $request->input('area');
        $customerrequest->fromdate = $request->input('fromdate');
        $customerrequest->todate = $request->input('todate');
        // $customerrequest->holiday_id = $request->input('holiday');
        $customerrequest->subregion_id = $request->input('subregion_id');
        // $customerrequest->shift_id = $request->input('shift');
        $customerrequest->requeststatus = 'Special';
        $customerrequest->status = '1';
        $customerrequest->approve_status = '0';
        $customerrequest->approve_01 = '0';
        $customerrequest->approve_02 = '0';
        $customerrequest->approve_03 = '0';
        $customerrequest->status = '1';
        $customerrequest->create_by = Auth::id();
        $customerrequest->update_by = '0';
        $customerrequest->save();

        $requestID = $customerrequest->id;

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            $count = $rowtabledata['col_4'];
            $title = $rowtabledata['col_7'];
            $holiday = $rowtabledata['col_5'];
            $shift = $rowtabledata['col_6'];

            $customerrequestdetail = new Customerrequestdetail();
            $customerrequestdetail->customerrequest_id = $requestID;
            $customerrequestdetail->job_title_id = $title;
            $customerrequestdetail->count = $count;
            $customerrequestdetail->shift_id = $shift;
            $customerrequestdetail->holiday_id = $holiday;
            $customerrequestdetail->status = '1';
            $customerrequestdetail->create_by = Auth::id();
            $customerrequestdetail->update_by = '0';
            $customerrequestdetail->save();
        }


        return response()->json(['status' => 1, 'message' => 'Cadre Special Request is Successfully Created']);
    }

    public function specialcustomerrequestedit(Request $request){
        $user = Auth::user();
        $permission =$user->can('CustomerRequest-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('customerrequests')
                    ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                    ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'customerrequests.subcustomer_id', '=', 'subcustomers.customer_id')
                    ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                    ->select('customerrequests.*', 'customers.name', 'customerbranches.branch_name', 'customerrequestdetails.job_title_id', 'customerrequestdetails.count','subcustomers.sub_name')
                    ->where('customerrequests.id', $id)
                    ->get(); 

                    $requestlist = $this->specialreqestcountlist($id); 
                   
                    $responseData = array(
                        'mainData' => $data[0],
                        'requestdata' => $requestlist,
                    );

        return response() ->json(['result'=>  $responseData]);
    }
}

private function specialreqestcountlist($id){

    $recordID =$id ;
   $data = DB::table('customerrequests')
   ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
   ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
   ->leftjoin('holiday_types', 'customerrequestdetails.holiday_id', '=', 'holiday_types.id')
   ->leftjoin('shift_types', 'customerrequestdetails.shift_id', '=', 'shift_types.id')
   ->select('customerrequests.*', 'job_titles.title', 'holiday_types.name', 'shift_types.shift_name','customerrequestdetails.count','customerrequestdetails.holiday_id','customerrequestdetails.shift_id','customerrequestdetails.job_title_id', DB::raw('(customerrequestdetails.id) AS customerrequestdetailsID'))
   ->where('customerrequestdetails.customerrequest_id', $recordID)
   ->whereIn('customerrequestdetails.status', [1, 2])
   ->get(); 


   $htmlTable = '';
   
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->name . '</td>'; 
    $htmlTable .= '<td>' . $row->shift_name . '</td>'; 
    $htmlTable .= '<td>' . $row->title . '</td>'; 
    $htmlTable .= '<td>' . $row->count . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->holiday_id . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->shift_id . '</td>'; 
    $htmlTable .= '<td class="d-none">' . $row->job_title_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '<td id ="actionrow"><button type="button" id="'.$row->customerrequestdetailsID.'" class="btnSpecialEditlist btn btn-primary btn-sm ">
        <i class="fas fa-pen"></i>
        </button>&nbsp;
        <button type="button" id="'.$row->customerrequestdetailsID.'" class="btnSpecialDeletelist btn btn-danger btn-sm " >
        <i class="fas fa-trash-alt"></i>
        </button></td>'; 
    $htmlTable .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->customerrequestdetailsID.'"></td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}

public function specialupdate(Request $request){
    $user = Auth::user();
   
    $permission =$user->can('CustomerRequest-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   
        $current_date_time = Carbon::now()->toDateTimeString();

    $this->validate($request, [
        'customer' => 'required',
        'area' => 'required',
        'fromdate' => 'required',
        'todate' => 'required',
        'tableData' => 'required',
    ]);

    $hidden_id = $request->input('hidden_id');

    $customerrequest = Customerrequest::where('id', $hidden_id)->first();
    $customerrequest->customer_id = $request->input('customer');
    $customerrequest->subcustomer_id = $request->input('subcustomer');
    $customerrequest->customerbranch_id = $request->input('area');
    $customerrequest->fromdate = $request->input('fromdate');
    $customerrequest->todate = $request->input('todate');
    // $customerrequest->holiday_id = $request->input('holiday');
    $customerrequest->subregion_id = $request->input('subregion_id');
    // $customerrequest->shift_id = $request->input('shift');
    $customerrequest->allocationstatus = '0';
    $customerrequest->approve_status = '0';
    $customerrequest->approve_01 = '0';
    $customerrequest->approve_02 = '0';
    $customerrequest->approve_03 = '0';
    $customerrequest->update_by = Auth::id();
    $customerrequest->save();


    $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        if($rowtabledata['col_8'] == "Updated"){
   
            $count = $rowtabledata['col_4'];
            $title = $rowtabledata['col_7'];
            $holiday = $rowtabledata['col_5'];
            $shift = $rowtabledata['col_6']; 
            $detailID = $rowtabledata['col_9'];

            $customerrequestdetail = Customerrequestdetail::where('id', $detailID)->first();
            $customerrequestdetail->job_title_id = $title;
            $customerrequestdetail->count = $count;
            $customerrequestdetail->shift_id = $shift;
            $customerrequestdetail->holiday_id = $holiday;
            $customerrequestdetail->update_by = Auth::id();
            $customerrequestdetail->save();
            
        }else if($rowtabledata['col_8'] == "NewData") {
            $count = $rowtabledata['col_4'];
            $title = $rowtabledata['col_7'];
            $holiday = $rowtabledata['col_5'];
            $shift = $rowtabledata['col_6'];

                if($title != 0){
                    $customerrequestdetail = new Customerrequestdetail();
                    $customerrequestdetail->customerrequest_id = $hidden_id;
                    $customerrequestdetail->job_title_id = $title;
                    $customerrequestdetail->count = $count;
                    $customerrequestdetail->shift_id = $shift;
                    $customerrequestdetail->holiday_id = $holiday;
                    $customerrequestdetail->status = '1';
                    $customerrequestdetail->create_by = Auth::id();
                    $customerrequestdetail->update_by = '0';
                    $customerrequestdetail->save();
                }
          }

         
    }

    return response()->json(['status' => 1, 'message' => 'Special Customer Request is Successfully Updated']);

}

    // data get & filtering part
    public function getSubCustomers($customerId)
{
    $subCustomers = DB::table('subcustomers')->select('subcustomers.*')->where('customer_id', '=', $customerId)->get();

    return response()->json($subCustomers);
}

public function getbranch($customerId)
{
    $branch = DB::table('customerbranches')->select('customerbranches.*')->where('customer_id', '=', $customerId)->get();

    return response()->json($branch);
}

public function getbranchsubcustomerfilter($subcustomerId,$customerId)
{
    $branch = DB::table('customerbranches')->select('customerbranches.*')
    ->where('subcustomer_id', '=', $subcustomerId)
    ->where('customer_id', '=', $customerId)
    ->get();

    return response()->json($branch);
}

public function getSubregionId($areaId)
{
    $subregionId = DB::table('customerbranches')
        ->where('id', '=', $areaId)
        ->value('subregion_id');

    return response()->json(['subregion_id' => $subregionId]);
}

public function getsubcustomerbranchfilter($areaId)
{
    $subcustomer_id = DB::table('customerbranches')
        ->where('id', '=', $areaId)
        ->value('subcustomer_id');

    return response()->json(['subcustomer_id' => $subcustomer_id]);
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



public function customerrequestdocument(Request $request){
    $id = $request->input('id');

    $headerdata = DB::table('customerrequests')
    ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
    ->leftjoin('customers', 'customerrequests.customer_id', '=', 'customers.id')
    ->leftjoin('subcustomers', 'customerrequests.subcustomer_id', '=', 'subcustomers.customer_id')
    ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
    ->select('customerrequests.fromdate AS fromdate','customerrequests.todate AS todate', 'customers.name AS clientname', 'customerbranches.branch_name AS branchname')
    ->where('customerrequests.id', $id)
    ->get(); 

    $fromdate='none';
    $todate='none';
    $clientname='';
    $branchname='';

    foreach ($headerdata as $datalist) {
        $fromdate = $datalist->fromdate;
        $todate = $datalist->todate;
        $clientname = $datalist->clientname;
        $branchname = $datalist->branchname;
    }

    $datadetails = DB::table('customerrequests')
    ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
    ->leftjoin('holiday_types', 'customerrequestdetails.holiday_id', '=', 'holiday_types.id')
    ->leftjoin('shift_types', 'customerrequestdetails.shift_id', '=', 'shift_types.id')
    ->select('customerrequests.*', 'job_titles.title AS title', 'holiday_types.name', 'shift_types.shift_name','customerrequestdetails.count AS count','customerrequestdetails.holiday_id AS holiday_id','customerrequestdetails.shift_id AS shift_id','customerrequestdetails.job_title_id', DB::raw('(customerrequestdetails.id) AS customerrequestdetailsID'))
    ->where('customerrequestdetails.customerrequest_id', $id)
    ->whereIn('customerrequestdetails.status', [1, 2])
    ->get(); 

    $tbldocument='';


    $count = 1;
    
    foreach ($datadetails as $rowlist) {
        $weekday_day='';
        $weekday_night='';
        $saturday_day='';
        $saturday_night='';
        $sunday_day='';
        $sunday_night='';
        $holiday_day='';
        $holiday_night='';
        $specialday_day='';
        $specialday_night='';
        $specialassesmentday_day='';
        $specialassesmentday_night='';

        // normalweekdays
        if($rowlist->holiday_id==3){
            if($rowlist->shift_id==2){
                $weekday_day=$rowlist->count;
            }
            else if($rowlist->shift_id==3){
                $weekday_night=$rowlist->count;
            }
        }
        // saturday
        if($rowlist->holiday_id==4){
            if($rowlist->shift_id==2){
                $saturday_day=$rowlist->count;
            }
            else if($rowlist->shift_id==3){
                $saturday_night=$rowlist->count;
            }
        }
        // sunday
        if($rowlist->holiday_id==5){
            if($rowlist->shift_id==2){
                $sunday_day=$rowlist->count;
            }
            else if($rowlist->shift_id==3){
                $sunday_night=$rowlist->count;
            }
        }
        // holiday
        if($rowlist->holiday_id==1){
            if($rowlist->shift_id==2){
                $holiday_day=$rowlist->count;
            }
            else if($rowlist->shift_id==3){
                $holiday_night=$rowlist->count;
            }
        }
        // special holiday
        if($rowlist->holiday_id==2){
            if($rowlist->shift_id==2){
                $specialday_day=$rowlist->count;
            }
            else if($rowlist->shift_id==3){
                $specialday_night=$rowlist->count;
            }
        }
        // special assement day
        if($rowlist->holiday_id==6){
            if($rowlist->shift_id==2){
                $specialassesmentday_day=$rowlist->count;
            }
            else if($rowlist->shift_id==3){
                $specialassesmentday_night=$rowlist->count;
            }
        }

        $tbldocument.='
        <tr>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'. $count.'</td>
            <td colspan="3" style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$clientname.'</td>
            <td colspan="4" style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$branchname.'</td>
            <td colspan="2" style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$rowlist->title.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$weekday_day.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$weekday_night.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$saturday_day.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$saturday_night.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$sunday_day.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$sunday_night.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$holiday_day.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$holiday_night.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$specialday_day.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$specialday_night.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$specialassesmentday_day.'</td>
            <td style="font-size:9px; border:1px solid black; text-align:center;" class="text-center">'.$specialassesmentday_night.'</td>
        </tr>
        ';
        $count++;
    }

    $html='';

    $html ='
    <!doctype html>
    <html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    
        <title>Authorized Cadre</title>
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
                size: 210mm 297mm;
                /* Set the print size width to 80mm and height to 100mm */
            }

            body {
                width: 210mm;
                /* Set the body width to 80mm */
            }

            #DivIdToPrint {
                border: 1px solid black;
                
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
            <table style="width:100%;" cellspacing="0" cellpadding="0">
                <tr >
                    <td style="padding-left: 35%; font-size:6px;" colspan="2">
                        <h6 class="font-weight-light" style="margin-top:0;margin-bottom:0;">
                            
                        </h6>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; font-size:7px; padding-top: 5px; padding-right: 30px" colspan="2">
                    <h2 class="font-weight-light">Gajashakthi Security Service (PVT) LTD</h2>
                    <h1 class="font-weight-light">Authorized Cadre</h1>
                    <h3 class="font-weight-light">Fom:'.$fromdate.'  To:'.$todate.'</h3></td>
                </tr>        
                <tr>
                    <td style="text-align: center; margin-bottom:50px" colspan="2">
                        <table class="tg" style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
                            <thead style="margin: 50px;">
                                <tr style="text-align:center; font-weight:bold; font-size:5px;">
                                    <th rowspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black;border-collapse: collapse;">
                                        #</td>
                                    <th rowspan="2" colspan="3"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Client Name</th>
                                    <th rowspan="2" colspan="4"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Branch Name</th>
                                    <th rowspan="2" colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Rank </th>
                                    <th colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Normal Week Days</th>
                                    <th colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Saturdays</th>
                                    <th colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Sundays</th>
                                    <th colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Holidays</th>
                                    <th colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Special Days</th>
                                    <th colspan="2"
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">Special Assignment Days *</th>
                                </tr>
                                <tr style="text-align:center; font-weight:bold; font-size:5px;">
                                    <th
                                        style="text-align: center; font-size:10px; border: 1px solid black;border-collapse: collapse;">D</th>
                                    <th
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">N</th>
                                    <th
                                        style="text-align: center; font-size:10px; border: 1px solid black;border-collapse: collapse;">D</th>
                                    <th
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">N</th>
                                    <th
                                        style="text-align: center; font-size:10px; border: 1px solid black;border-collapse: collapse;">D</th>
                                    <th
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">N</th>
                                    <th
                                        style="text-align: center; font-size:10px; border: 1px solid black;border-collapse: collapse;">D</td>
                                    <th
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">N</th>
                                    <th
                                        style="text-align: center; font-size:10px; border: 1px solid black;border-collapse: collapse;">D</th>
                                    <th
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">N</th>
                                    <th
                                        style="text-align: center; font-size:10px; border: 1px solid black;border-collapse: collapse;">D</th>
                                    <th
                                        style="text-align: center; font-size:10px;border: 1px solid black; border-collapse: collapse;">N</th>
                                </tr>
                            </thead>
                            <br>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: right;text-align:right;" class="text-right"></td>
                                </tr>
                                '.$tbldocument.'
                            </tbody>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style=font-size: 12px; padding-top: 5px; padding-right: 10px" colspan="2">
                    </td>
                </tr>
                <tr>
                    <td style=font-size: 12px; padding-top: 5px; padding-right: 10px" colspan="2"></td>
                </tr>
            </table>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </body>
    </html>';

    // echo $html;

    $pdf = PDF::loadHTML($html);

    $directory = public_path('storage/customer_request');

    // Check if the directory exists, if not, create it
    if (!file_exists($directory)) {
        mkdir($directory, 0755, true); // You can adjust the permission (0755) as needed
    }

    // Generate a unique filename
    $filename = 'AuthorizedCadre_' . uniqid() . '.pdf';

    // Save the PDF with the custom filename to the default storage path
    $pdf->save(public_path('storage/customer_request/' . $filename));

    // // Generate the URL to the PDF file
    // $pdfUrl = asset('storage/customer_request/' . $filename);

    // // Open a new tab with the PDF URL using JavaScript
    // echo '<script>window.open("' . $pdfUrl . '", "_blank");</script>';
    // // return response()->json(['success' => true, 'url' => $pdfUrl]);


    $filePath = public_path('storage/customer_request/' . $filename);

    if (file_exists($filePath)) {
        // Create the full URL for the PDF file
        $pdfUrl = asset('storage/customer_request/' . $filename);

        // Return the URL as a JSON response
        return response()->json(['success' => true, 'url' => $pdfUrl]);
    } else {
        return response()->json(['error' => 'File not found'], 404);
    }
}


}