<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Newbusinessproposal;
use App\Newbusinessproposal_ratedetail;
use App\Newbusinessproposaldetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Yajra\Datatables\Facades\Datatables;

class Newbusinessproposalcontroller extends Controller
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
        if (in_array('Newbusinessproposal-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('Newbusinessproposal-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('Newbusinessproposal-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('Newbusinessproposal-delete', $userPermissions)) {
            $deletepermission = 1;
        } 
        
        $employees = DB::table('employees')->select('employees.*')->get();

        $shifttypes = DB::table('shift_types')->select('shift_types.*')->get();
        $subcustomer = DB::table('subcustomers')->select('subcustomers.*')->get();

        $titles = DB::table('job_titles')
        ->select('job_titles.*')
        ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
        ->get();

        $holidays = DB::table('holiday_types')
        ->select('holiday_types.*')
        ->where('id', '!=', 6)
        ->get();

         $specialholidays = DB::table('holiday_types')
         ->select('holiday_types.*')
         ->where('holiday_types.id', 6)
         ->get();

        return view('Newbusinessproposal.newbusinessproposal',compact('employees','titles', 'holidays', 'shifttypes','subcustomer','specialholidays','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    }

    
    public function insert(Request $request){
        
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Newbusinessproposal-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 

        $mobilebillpayment = new Newbusinessproposal();
        $mobilebillpayment->document_no = $request->input('documentno');
        $mobilebillpayment->employee_id = $request->input('employee'); 
        $mobilebillpayment->date = $request->input('date'); 
        $mobilebillpayment->client_name = $request->input('clientname');
        $mobilebillpayment->client_address = $request->input('address');
        $mobilebillpayment->fromdate = $request->input('fromdate');
        $mobilebillpayment->todate = $request->input('todate');
        $mobilebillpayment->holidays = $request->input('holidays');
        $mobilebillpayment->special_holidays = $request->input('specialholidays');
        $mobilebillpayment->status = '1';
        $mobilebillpayment->approve_status = '0';
        $mobilebillpayment->approve_01 = '0';
        $mobilebillpayment->approve_02 = '0';
        $mobilebillpayment->approve_03 = '0';
        $mobilebillpayment->create_by = Auth::id();
        $mobilebillpayment->update_by = '0';
        $mobilebillpayment->save();

        $requestID = $mobilebillpayment->id;

        $DetailsArrays = $request->input('DetailsArrays');

        foreach ($DetailsArrays as $title => $dataArray) {
            foreach ($dataArray as $dataObject) {
                $jobtitle = $dataObject['jobtitle'];
                $shift = $dataObject['shift'];
                $holiday = $dataObject['holiday'];
                $value = $dataObject['value'];
                
                $newbusinessproposaldetail = new Newbusinessproposaldetail();
                $newbusinessproposaldetail->newbusinessproposals_id = $requestID;
                $newbusinessproposaldetail->job_title_id = $jobtitle;
                $newbusinessproposaldetail->count = $value;
                $newbusinessproposaldetail->shift_id = $shift;
                $newbusinessproposaldetail->holiday_id = $holiday;
                $newbusinessproposaldetail->status = '1';
                $newbusinessproposaldetail->create_by = Auth::id();
                $newbusinessproposaldetail->update_by = '0';
                $newbusinessproposaldetail->save();
                }
        }
        $RateArrays = $request->input('RateArrays');
        foreach ($RateArrays as $dataArray) {
            foreach ($dataArray as $dataObject) {
                $jobtitle = $dataObject['jobtitle'];
                $rateType = $dataObject['rateType'];
                $value = $dataObject['value'];

                $newbusinessproposal_ratedetail = new Newbusinessproposal_ratedetail();
                $newbusinessproposal_ratedetail->newbusinessproposals_id = $requestID;
                $newbusinessproposal_ratedetail->job_title_id = $jobtitle;
                $newbusinessproposal_ratedetail->rate_type = $rateType;
                $newbusinessproposal_ratedetail->value = $value;
                $newbusinessproposal_ratedetail->status = '1';
                $newbusinessproposal_ratedetail->create_by = Auth::id();
                $newbusinessproposal_ratedetail->update_by = '0';
                $newbusinessproposal_ratedetail->save();
            }
        }
      
        return response()->json(['status' => 1, 'message' => 'Business Proposal is successfully Inserted']);

    }

    public function requestlist()
    {
        $types = DB::table('newbusinessproposals')
            ->join('employees', 'newbusinessproposals.employee_id', '=', 'employees.id')
            ->select('newbusinessproposals.*','employees.emp_first_name AS emp_first_name')
            ->whereIn('newbusinessproposals.status', [1, 2])
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

                        $permission = $user->can('Newbusinessproposal-edit');
                        if($permission){
                            if($row->approve_status == 0){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    }
                    if($row->approve_status == 1){
                        $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';
                    }

                    $permission = $user->can('Newbusinessproposal-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('newbusinessproposalstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('newbusinessproposalstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('Newbusinessproposal-delete');
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
        $permission =$user->can('Newbusinessproposal-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('newbusinessproposals')
        ->leftjoin('employees', 'newbusinessproposals.employee_id', '=', 'employees.id')
        ->select('newbusinessproposals.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('newbusinessproposals.id', $id)
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

       $datadetails = DB::table('newbusinessproposals')
       ->leftjoin('newbusinessproposaldetails', 'newbusinessproposaldetails.newbusinessproposals_id', '=', 'newbusinessproposals.id')
       ->leftjoin('job_titles', 'newbusinessproposaldetails.job_title_id', '=', 'job_titles.id')
       ->leftjoin('holiday_types', 'newbusinessproposaldetails.holiday_id', '=', 'holiday_types.id')
       ->leftjoin('shift_types', 'newbusinessproposaldetails.shift_id', '=', 'shift_types.id')
       ->select('newbusinessproposals.*', 'job_titles.title AS title','job_titles.id AS title_id', 'holiday_types.name', 'shift_types.shift_name AS shift_name','shift_types.id AS shift_id','newbusinessproposaldetails.count AS count','newbusinessproposaldetails.holiday_id AS holiday_id','newbusinessproposaldetails.shift_id AS shift_id','newbusinessproposaldetails.job_title_id', DB::raw('(newbusinessproposaldetails.id) AS newbusinessproposaldetailsID'))
       ->where('newbusinessproposaldetails.newbusinessproposals_id', $recordID)
       ->whereIn('newbusinessproposaldetails.status', [1, 2])
       ->get(); 
       

       $holidays = DB::table('holiday_types')
    ->select('holiday_types.*')
    ->where('id', '!=', 6)
    ->get();

$titles = DB::table('job_titles')
    ->select('job_titles.*')
    ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
    ->get();

$rates = DB::table('newbusinessproposal_ratedetails')
    ->select('newbusinessproposal_ratedetails.*')
    ->where('newbusinessproposal_ratedetails.newbusinessproposals_id', $recordID)
    ->get();

$htmlTable = '';

foreach ($titles as $title) {
    $htmlTable .= '<tr>';
    $htmlTable .= '<th id="' . $title->id . '">' . $title->title . '</th>';
    foreach ($rates as $rate) {
        if($rate->job_title_id==$title->id){
            if($rate->rate_type=='shiftrate'){
            $htmlTable .= '<td class="text-center"><input type="number" class="text-center rate-input2" onkeyup="calculateTotal2()" rate_type2="shiftrate" jobtitle_id2="' . $title->id . '" style="width: 70px;border: none; background-color: transparent" id="' . $title->id .'_shiftrate2" name="' . $title->id .'_shiftrate2" value="'.$rate->value.'"></td>';
        }
        else{
            $htmlTable .= '<td class="text-center"><input type="number" class="text-center rate-input2" onkeyup="calculateTotal2()" rate_type2="salaryrate" jobtitle_id2="' . $title->id . '" style="width: 70px;border: none; background-color: transparent" id="' . $title->id . '_salaryrate2" name="' . $title->id. '_salaryrate2" value="'.$rate->value.'"></td>';
        }
    }
    }
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
       
        $permission =$user->can('Newbusinessproposal-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'document_no' => $request->documentno,
                'employee_id' => $request->employee,
                'date' => $request->date,
                'client_name' => $request->clientname,
                'client_address' => $request->address,
                'fromdate' => $request->fromdate,
                'todate' => $request->todate,
                'holidays' => $request->holidays,
                'special_holidays' => $request->specialholidays,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Newbusinessproposal::findOrFail($id)
        ->update($form_data);

        $hidden_id = $request->input('hidden_id');

        DB::table('newbusinessproposaldetails')
        ->where('newbusinessproposals_id', $hidden_id)
        ->delete();
        DB::table('newbusinessproposal_ratedetails')
        ->where('newbusinessproposals_id', $hidden_id)
        ->delete();

        $DetailsArrays = $request->input('DetailsArrays');

        foreach ($DetailsArrays as $title => $dataArray) {
            foreach ($dataArray as $dataObject) {
                $jobtitle = $dataObject['jobtitle'];
                $shift = $dataObject['shift'];
                $holiday = $dataObject['holiday'];
                $value = $dataObject['value'];
                
                $newbusinessproposaldetail = new Newbusinessproposaldetail();
                $newbusinessproposaldetail->newbusinessproposals_id = $hidden_id;
                $newbusinessproposaldetail->job_title_id = $jobtitle;
                $newbusinessproposaldetail->count = $value;
                $newbusinessproposaldetail->shift_id = $shift;
                $newbusinessproposaldetail->holiday_id = $holiday;
                $newbusinessproposaldetail->status = '1';
                $newbusinessproposaldetail->create_by = Auth::id();
                $newbusinessproposaldetail->update_by = '0';
                $newbusinessproposaldetail->save();
                }
        }
        $RateArrays = $request->input('RateArrays');
        foreach ($RateArrays as $dataArray) {
            foreach ($dataArray as $dataObject) {
                $jobtitle = $dataObject['jobtitle'];
                $rateType = $dataObject['rateType'];
                $value = $dataObject['value'];

                $newbusinessproposal_ratedetail = new Newbusinessproposal_ratedetail();
                $newbusinessproposal_ratedetail->newbusinessproposals_id = $hidden_id;
                $newbusinessproposal_ratedetail->job_title_id = $jobtitle;
                $newbusinessproposal_ratedetail->rate_type = $rateType;
                $newbusinessproposal_ratedetail->value = $value;
                $newbusinessproposal_ratedetail->status = '1';
                $newbusinessproposal_ratedetail->create_by = Auth::id();
                $newbusinessproposal_ratedetail->update_by = '0';
                $newbusinessproposal_ratedetail->save();
            }
        }
      
        
        return response()->json(['status' => 1, 'message' => 'Business Proposal is Successfully Updated']);
    }

    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('Newbusinessproposal-delete');
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
        Newbusinessproposal::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Business Proposal is successfully Deleted']);

    }

    public function approvel_details(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }  

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('newbusinessproposals')
        ->leftjoin('employees', 'newbusinessproposals.employee_id', '=', 'employees.id')
        ->select('newbusinessproposals.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('newbusinessproposals.id', $id)
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

       $datadetails = DB::table('newbusinessproposals')
       ->leftjoin('newbusinessproposaldetails', 'newbusinessproposaldetails.newbusinessproposals_id', '=', 'newbusinessproposals.id')
       ->leftjoin('job_titles', 'newbusinessproposaldetails.job_title_id', '=', 'job_titles.id')
       ->leftjoin('holiday_types', 'newbusinessproposaldetails.holiday_id', '=', 'holiday_types.id')
       ->leftjoin('shift_types', 'newbusinessproposaldetails.shift_id', '=', 'shift_types.id')
       ->select('newbusinessproposals.*', 'job_titles.title AS title','job_titles.id AS title_id', 'holiday_types.name', 'shift_types.shift_name AS shift_name','shift_types.id AS shift_id','newbusinessproposaldetails.count AS count','newbusinessproposaldetails.holiday_id AS holiday_id','newbusinessproposaldetails.shift_id AS shift_id','newbusinessproposaldetails.job_title_id', DB::raw('(newbusinessproposaldetails.id) AS newbusinessproposaldetailsID'))
       ->where('newbusinessproposaldetails.newbusinessproposals_id', $recordID)
       ->whereIn('newbusinessproposaldetails.status', [1, 2])
       ->get(); 
       

       $holidays = DB::table('holiday_types')
    ->select('holiday_types.*')
    ->where('id', '!=', 6)
    ->get();

$titles = DB::table('job_titles')
    ->select('job_titles.*')
    ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
    ->get();

$rates = DB::table('newbusinessproposal_ratedetails')
    ->select('newbusinessproposal_ratedetails.*')
    ->where('newbusinessproposal_ratedetails.newbusinessproposals_id', $recordID)
    ->get();

$htmlTable = '';

foreach ($titles as $title) {
    $htmlTable .= '<tr>';
    $htmlTable .= '<th id="' . $title->id . '">' . $title->title . '</th>';
    foreach ($rates as $rate) {
        if($rate->job_title_id==$title->id){
            if($rate->rate_type=='shiftrate'){
            $htmlTable .= '<td class="text-center"><input type="number" class="text-center app_rate-input"  app_rate_type="shiftrate" app_jobtitle_id="' . $title->id . '" style="width: 70px;border: none; background-color: transparent" id="app_' . $title->id .'_shiftrate" name="app_' . $title->id .'_shiftrate" value="'.$rate->value.'" readonly></td>';
        }
        else{
            $htmlTable .= '<td class="text-center"><input type="number" class="text-center app_rate-input" app_rate_type="salaryrate" app_jobtitle_id="' . $title->id . '" style="width: 70px;border: none; background-color: transparent" id="app_' . $title->id . '_salaryrate" name="app_' . $title->id. '_salaryrate" value="'.$rate->value.'" readonly></td>';
        }
    }
    }
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
        $htmlTable .= '<td class="text-center"><input type="number" class="text-center app_holiday-input" app_jobtitle_id="' . $title->id . '" app_shift_id="2" app_holiday_id="' . $holiday->id . '" app_data-holiday="' . $holiday->name . '" app_data-time="day" style="width: 55px; border: none; background-color: transparent" id="app_' . $title->title . $holiday->name . '_day" name="app_' . $title->title . $holiday->name . '_day" value="' . $dayInputValue . '" readonly></td>';
        $htmlTable .= '<td class="text-center"><input type="number" class="text-center app_holiday-input" app_jobtitle_id="' . $title->id . '" app_shift_id="3" app_holiday_id="' . $holiday->id . '" app_data-holiday="' . $holiday->name . '" app_data-time="night" style="width: 55px; border: none; background-color: transparent" id="app_' . $title->title . $holiday->name . '_night" name="app_' . $title->title . $holiday->name . '_night" value="' . $nightInputValue . '" readonly></td>';
    }


    $htmlTable .= '</tr>';
}

return $htmlTable;


   }

   public function view_details(Request $request){
    $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Newbusinessproposal-list', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('newbusinessproposals')
    ->leftjoin('employees', 'newbusinessproposals.employee_id', '=', 'employees.id')
    ->select('newbusinessproposals.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
    ->where('newbusinessproposals.id', $id)
    ->get(); 


   $requestlist = $this->view_reqestcountlist($id); 

                    $responseData = array(
                        'mainData' => $data[0],
                        'requestdata' => $requestlist,
                    );

        return response() ->json(['result'=>  $responseData]);
}
}
private function view_reqestcountlist($id){

    $recordID =$id ;

   $datadetails = DB::table('newbusinessproposals')
   ->leftjoin('newbusinessproposaldetails', 'newbusinessproposaldetails.newbusinessproposals_id', '=', 'newbusinessproposals.id')
   ->leftjoin('job_titles', 'newbusinessproposaldetails.job_title_id', '=', 'job_titles.id')
   ->leftjoin('holiday_types', 'newbusinessproposaldetails.holiday_id', '=', 'holiday_types.id')
   ->leftjoin('shift_types', 'newbusinessproposaldetails.shift_id', '=', 'shift_types.id')
   ->select('newbusinessproposals.*', 'job_titles.title AS title','job_titles.id AS title_id', 'holiday_types.name', 'shift_types.shift_name AS shift_name','shift_types.id AS shift_id','newbusinessproposaldetails.count AS count','newbusinessproposaldetails.holiday_id AS holiday_id','newbusinessproposaldetails.shift_id AS shift_id','newbusinessproposaldetails.job_title_id', DB::raw('(newbusinessproposaldetails.id) AS newbusinessproposaldetailsID'))
   ->where('newbusinessproposaldetails.newbusinessproposals_id', $recordID)
   ->whereIn('newbusinessproposaldetails.status', [1, 2])
   ->get(); 
   

   $holidays = DB::table('holiday_types')
->select('holiday_types.*')
->where('id', '!=', 6)
->get();

$titles = DB::table('job_titles')
->select('job_titles.*')
->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
->get();

$rates = DB::table('newbusinessproposal_ratedetails')
->select('newbusinessproposal_ratedetails.*')
->where('newbusinessproposal_ratedetails.newbusinessproposals_id', $recordID)
->get();

$htmlTable = '';

foreach ($titles as $title) {
$htmlTable .= '<tr>';
$htmlTable .= '<th id="' . $title->id . '">' . $title->title . '</th>';
foreach ($rates as $rate) {
    if($rate->job_title_id==$title->id){
        if($rate->rate_type=='shiftrate'){
        $htmlTable .= '<td class="text-center"><input type="number" class="text-center view_rate-input"  view_rate_type="shiftrate" view_jobtitle_id="' . $title->id . '" style="width: 70px;border: none; background-color: transparent" id="view_' . $title->id .'_shiftrate" name="view_' . $title->id .'_shiftrate" value="'.$rate->value.'" readonly></td>';
    }
    else{
        $htmlTable .= '<td class="text-center"><input type="number" class="text-center view_rate-input" view_rate_type="salaryrate" view_jobtitle_id="' . $title->id . '" style="width: 70px;border: none; background-color: transparent" id="view_' . $title->id . '_salaryrate" name="view_' . $title->id. '_salaryrate" value="'.$rate->value.'" readonly></td>';
    }
}
}
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
    $htmlTable .= '<td class="text-center"><input type="number" class="text-center view_holiday-input" view_jobtitle_id="' . $title->id . '" view_shift_id="2" view_holiday_id="' . $holiday->id . '" view_data-holiday="' . $holiday->name . '" view_data-time="day" style="width: 55px; border: none; background-color: transparent" id="view_' . $title->title . $holiday->name . '_day" name="view_' . $title->title . $holiday->name . '_day" value="' . $dayInputValue . '" readonly></td>';
    $htmlTable .= '<td class="text-center"><input type="number" class="text-center view_holiday-input" view_jobtitle_id="' . $title->id . '" view_shift_id="3" view_holiday_id="' . $holiday->id . '" view_data-holiday="' . $holiday->name . '" view_data-time="night" style="width: 55px; border: none; background-color: transparent" id="view_' . $title->title . $holiday->name . '_night" name="view_' . $title->title . $holiday->name . '_night" value="' . $nightInputValue . '" readonly></td>';
}


$htmlTable .= '</tr>';
}

return $htmlTable;


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
            Newbusinessproposal::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Region is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Newbusinessproposal::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Region is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Newbusinessproposal::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Region is successfully Approved']);
          }
    }


    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('Newbusinessproposal-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Newbusinessproposal::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('newbusinessproposal');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Newbusinessproposal::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('newbusinessproposal');
        }

    }

    public function newbusinessgetvat(Request $request){

        $date = Request('date');
    
            $data = DB::table('vats')
            ->select('vats.*')
            ->where('vats.approve_status', 1)
            ->whereIn('vats.status', [1, 2])
            ->whereDate('fromdate', '<=', $date)
            ->where(function ($query) use ($date) {
                $query->whereDate('todate', '>=', $date)
                    ->orWhereNull('todate');
            })
            ->get();
    
        if ($data->count() > 0) {
            // Return the vat value of the first matching row
            return response()->json(['vat_result' => $data[0]->vat,'sscl_result' => $data[0]->sscl]);
        } else {
            // No matching row found
            return response()->json(['vat_result' => 0,'sscl_result' => 0]);
        }
    
    }
    public function getdocno(Request $request){
    
        $data1 = DB::table('newbusinessproposals')
        ->select('newbusinessproposals.*')
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
        $data2= $batchno;
    
        return response() ->json(['result'=> $data2]);

    }

    public function print(Request $request)
    {
        $content = $request->input('content');

        $pdf = PDF::loadHTML($content);
        
        $pdfContent = $pdf->output();

        $pdfBase64 = base64_encode($pdfContent);

        $responseData = [
            'pdf' => $pdfBase64,
            'message' => 'PDF generated successfully',
        ];

        // Return the JSON response
        return response()->json($responseData);

    }
}
