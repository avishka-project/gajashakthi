<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customerbranch;
use App\Customerrequest;
use App\empallocation;
use App\empallocationdetail;
use App\Empattendances;
use App\empattendances_duplicate;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Gate;

class Empattendancescontroller extends Controller
{
    public function index()
    {

        $user = Auth::user();
        $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1])->get();
        $titles = DB::table('job_titles')
        ->select('job_titles.*')
        ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
        ->get();
       
        return view('EmployeeAttendance.attendance' ,compact('titles','customers'));
    }

    public function addattendace(){

        $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1])->get();
        $shifttypes = DB::table('shift_types')->select('shift_types.*')->get();
        $titles = DB::table('job_titles')
        ->select('job_titles.*')
        ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
        ->get();

        return view('EmployeeAttendance.addattendance'  ,compact('customers','titles', 'shifttypes'));
    }


    public function allocationdetails(Request $request){


        $customerbranch_id = $request->input('branch');
        $date = $request->input('todate');
        $shift_id = $request->input('shift');

// checking there is a record in customer request to given date shift and customer branch

        $matchingRecord = Customerrequest::where('customerbranch_id', $customerbranch_id)
        ->where('fromdate', '<=', $date) 
        ->where('todate', '>=', $date)   
        ->where('status', 1)
        ->where('approve_status', 1)
        ->first();

           $holidaytype ='';
           $recordtype='';

        if ($matchingRecord) {


            $data = DB::table('customerrequests')
            ->leftjoin('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
            ->select('customerrequests.*', 
               'customerbranches.subregion_id AS subregion')
               ->where('customerbranch_id', $customerbranch_id)
               ->where('fromdate', '<=', $date) 
               ->where('todate', '>=', $date)   
               ->where('customerrequests.status', 1)
               ->where('customerrequests.approve_status', 1)
               ->get(); 
               $subregionid = $data[0]->subregion;
               $recordtype = $data[0]->requeststatus;

        // checking in the holidays table that there is a holiday of given date
        $requestid = $matchingRecord->id;
          $Holidaylist = DB::table('holidays')
            ->select('holidays.*')
            ->where('date', $date)
            ->first();


            if ($Holidaylist) {
         // get holiday type and holiday id
                  $holidaytype = $Holidaylist[0]->holiday_type;
                  $holidaytypelist = DB::table('holiday_types')
                    ->where('id', $holidaytype)
                    ->get();
                    $holidayname = $holidaytypelist[0]->name;
        
            } else {


                $carbonDate = Carbon::createFromFormat('Y-m-d', $date);

                    if ($carbonDate->isWeekend()) {
                    // $currentDate is a weekend
                        $weekendType = $carbonDate->format('N');

                            if ($weekendType == 6) {
                                // Current date is Saturday
                                $holidaytype = 4;
                            } elseif ($weekendType == 7) {
                            // Current date is Sunday
                                $holidaytype = 5;
                            }
                        
                    } else {
                        $holidaytype = 3;
                    }
                   
            }

        } else {
            
            return response()->json(['error' => 'No matching record found']);
        }


     
        $detaillist = $this->allocationdetailslist($requestid, $shift_id,$holidaytype, $date); 

        $responseData = array(
            'maindata' => $requestid,
            'subregion' => $subregionid,
            'holidaytype' => $holidaytype,
            'detaildata' => $detaillist
        );

return response() ->json(['result'=>  $responseData]);

}


private function allocationdetailslist($requestid, $shiftid, $holidattype,$reqdate){

    $recordID =$requestid ;
    $shift =$shiftid ;
    $datetype =$holidattype ;
    $allocationDate = $reqdate;

    $data = DB::table('customerrequestdetails')
    ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
    ->leftjoin('shift_types', 'customerrequestdetails.shift_id', '=', 'shift_types.id')
    ->select('customerrequestdetails.*', 
       'job_titles.title AS jobtitle',
       'shift_types.onduty_time AS ontime',
       'shift_types.offduty_time AS offtime',
       'shift_types.saturday_onduty_time AS saturdayonduty',
       'shift_types.saturday_offduty_time AS saturdayoffduty',
       DB::raw('(SELECT SUM(count) FROM customerrequestdetails 
       WHERE customerrequestdetails.customerrequest_id = ' . $recordID . ' 
       AND customerrequestdetails.shift_id = ' . $shift . ' 
       AND customerrequestdetails.holiday_id = ' . $datetype . ' 
       AND customerrequestdetails.status = 1) AS totalreqcount')
       )
    ->where('customerrequestdetails.customerrequest_id', $recordID)
    ->where('customerrequestdetails.shift_id', $shift)
    ->where('customerrequestdetails.holiday_id', $datetype)
    ->where('customerrequestdetails.status', 1)
    ->get(); 


    $totalCount = $data[0]->totalreqcount;

  

    if($datetype == 4 || $datetype == 5){
        $shifyontime =$data[0]->saturdayonduty; 
        $shifyofftime = $data[0]->saturdayoffduty; 

    }else{
        $shifyontime = $data[0]->ontime; 
        $shifyofftime = $data[0]->offtime; 
    }

    $combinedontime = Carbon::parse("$allocationDate $shifyontime");
    $ontime = $combinedontime->format('Y-m-d H:i:s');

 
    $combinedofftime = Carbon::parse("$allocationDate $shifyofftime");
    $offtime = $combinedofftime->format('Y-m-d H:i:s');


    $html = '';
    foreach ($data as $row) {
        $html .= '<tr>';
        $html .= '<td>' . $row->jobtitle . '</td>'; 
        $html .= '<td>' . $row->count . '</td>'; 
        $html .= '</tr>';
    }
    
    return [
        'html' => $html,
        'ontime' => $ontime,
        'offtime' => $offtime,
        'totalCount' => $totalCount,
    ];
    

}





public function insert(Request $request){

    $user = Auth::user();
    $permission =$user->can('empattendance-create');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }

        $requestid = $request->input('requestid');
   
        $customer_id = $request->input('customer');
        $subcustomer_id = $request->input('subcustomer');
        $customerbranch_id = $request->input('branch');
        $date = $request->input('date');
        $holiday_id = $request->input('holidaytype');
        $shift_id = $request->input('shiftid');

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {

            $empID = $rowtabledata['col_1'];
            $empservice = $rowtabledata['col_2'];
            $title = $rowtabledata['col_3'];
            $empage= $rowtabledata['col_4'];
            $empontime = $rowtabledata['col_5'];
            $empofftime = $rowtabledata['col_6'];
           
          
            $attendances = new Empattendances();
            $attendances->request_id = $requestid;
            $attendances->date = $date;
            $attendances->customer_id = $customer_id;
            $attendances->subcustomer_id = $subcustomer_id;
            $attendances->customerbranch_id = $customerbranch_id;
            $attendances->holiday_id =  $holiday_id;
            $attendances->shift_id = $shift_id;
            $attendances->emp_id = $empID;
            $attendances->jobtitle_id = $title;
            $attendances->emp_serviceno = $empservice;
            $attendances->emp_age = $empage;
            $attendances->ontime = $empontime;
            $attendances->outtime = $empofftime;
            $attendances->attendance_status = '1';
            $attendances->status = '1';
            $attendances->approve_status = '0';
            $attendances->approve_01 = '0';
            $attendances->approve_02 = '0';
            $attendances->approve_03 = '0';
            $attendances->delete_status = '0';
            $attendances->create_by = Auth::id();
            $attendances->update_by = '0';

            $attendances->save();
        }

            $redirectUrl = route('empattendance');
           return response()->json(['redirectUrl' => $redirectUrl]);
   

}

    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('empattendance-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

            $id = Request('id');

            $data = DB::table('empattendances')
            ->leftjoin('customerbranches','empattendances.customerbranch_id','=','customerbranches.id')
            ->leftjoin('shift_types', 'empattendances.shift_id', '=', 'shift_types.id')
            ->select('empattendances.*', 'shift_types.shift_name AS Shift','customerbranches.branch_name AS branchname','customerbranches.subregion_id AS subregion')
            ->where('empattendances.id', $id)
            ->where('empattendances.status', 1)
            ->get(); 

            return response() ->json(['result'=> $data[0]]);

    }

    
    public function update(Request $request){
        $user = Auth::user();
        $permission =$user->can('empattendance-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $tableData = $request->input('tableData');
            $recordID = $request->input('recordID');
            foreach ($tableData as $rowtabledata) {

                $empID = $rowtabledata['col_1'];
                $empservice = $rowtabledata['col_2'];
                $title = $rowtabledata['col_3'];
                $empage= $rowtabledata['col_4'];
                $empontime = $rowtabledata['col_5'];
                $empofftime = $rowtabledata['col_6'];
    
                $current_date_time = Carbon::now()->toDateTimeString();
    

                        $originalAttendance = Empattendances::find($recordID);
    
                        $attendances = Empattendances::where('id', $recordID)->first();
                        $attendances->emp_id = $empID;
                        $attendances->jobtitle_id = $title;
                        $attendances->emp_serviceno = $empservice;
                        $attendances->emp_age = $empage;
                        $attendances->ontime = $empontime;
                        $attendances->outtime = $empofftime;
                        $attendances->update_by = Auth::id();
                        $attendances->updated_at = $current_date_time;
                        $attendances->approve_status = '0';
                        $attendances->approve_01 = '0';
                        $attendances->approve_02 = '0';
                        $attendances->approve_03 = '0';
                        $attendances->save();

                        // add old record to the duplicate table
                        $originalAttributes = $originalAttendance->toArray();
                        $originalAttributes['attendance_id'] = $recordID;
                        empattendances_duplicate::create($originalAttributes);

            }
           return response()->json(['status' => 1, 'message' => 'Employee Attendance is Successfully Updated']);
    
    } 

    public function employeeselect(Request $request){

        $user = Auth::user();
     
            $employee = $request->input('employeeID');
           
            $data = DB::table('employees')
            ->select('employees.*')
            ->where('employees.id', '=', $employee)
            ->where('employees.deleted', '=', '0')
            ->get();


            $serviceno =$data[0]->service_no; 
            $designation = $data[0]->emp_job_code;
            $DOB = $data[0]->emp_birthday;

            $age = Carbon::parse($DOB)->age;

            $responseData = array(
                'serviceno' => $serviceno,
                'designation' => $designation,
                'age' => $age
            );
    return response() ->json(['result'=>  $responseData]);
    }


    public function getstafflistall($areaId, $shiftId, $today)
    {
        $branch = DB::table('employees')
            ->select('employees.id','employees.emp_fullname')
            ->where('subregion_id', '=', $areaId)
            ->where('deleted', '=', '0')
            ->leftJoin('empattendances', function ($join) use ($today, $shiftId) {
                $join->on('employees.id', '=', 'empattendances.emp_id')
                     ->where('empattendances.date', '=', $today)
                     ->where('empattendances.shift_id', '=', $shiftId);
            })
            ->whereNull('empattendances.id')
            ->get();
    
        return response()->json($branch);
    }


        public function getstafflisttransfer($areaId,$shiftId,$today)

        {
                $branch = DB::table('employeetransfers')
                ->leftjoin('employeetransfer_details', 'employeetransfers.id', '=', 'employeetransfer_details.transfer_id')
                ->leftjoin('employees', 'employees.emp_job_code', '=', 'employeetransfer_details.emp_id')
                ->select('employees.*')
                ->where('subregion_id', '=', $areaId)
                ->where('employeetransfers.delete_status', '=', '0')
                ->where('employees.deleted', '=', '0')
                ->where('employeetransfers.from_date', '<=', $today) 
                ->where('employeetransfers.to_date', '>=', $today)
                ->where('employeetransfers.approve_status', 1)
                ->leftJoin('empattendances', function ($join) use ($today, $shiftId) {
                    $join->on('employees.id', '=', 'empattendances.emp_id')
                        ->where('empattendances.date', '=', $today)
                        ->where('empattendances.shift_id', '=', $shiftId);
                })
                ->whereNull('empattendances.id')   
                ->get();

            
                return response()->json($branch);
        }

        public function getlastshift(Request $request){
            $customerbranch_id = $request->input('branch');
            $date = $request->input('todate');
            $shift_id = $request->input('shift');
            

                $yesterdate = Carbon::parse($date);
                $yesterdate->subDay();

                $data = DB::table('empattendances')
                ->leftjoin('job_titles', 'empattendances.jobtitle_id', '=', 'job_titles.id')
                ->leftjoin('employees','empattendances.emp_id','=','employees.id')
                ->select('empattendances.*','job_titles.title','employees.emp_fullname')
                ->where('empattendances.date', '=', $yesterdate)
                ->where('empattendances.customerbranch_id', '=', $customerbranch_id)
                ->where('empattendances.shift_id', '=', $shift_id)
                ->where('employees.deleted', '=', '0')
                ->get();

                $titles = DB::table('job_titles')
                ->select('job_titles.*')
                ->whereIn('job_titles.id', [3,7,9,23,26, 27, 28, 29, 30, 31])
                ->get();
    
      $html = '';
            foreach ($data as $row) {
                $html .= '<tr>';
                $html .= '<td><select name="employee" id="employee" class="employee form-control form-control-sm"><option value="' . $row->emp_id . '">'. $row->emp_fullname.'</option></select></td>';  
                $html .= '<td> <input type="text" id="serviceno" name="serviceno" class="form-control form-control-sm" readonly value="' . $row->emp_serviceno . '"></td>'; 
                $html .= '<td><select name="title" id="title" class="form-control form-control-sm" required><option value="">Select Job Title</option>';

                foreach ($titles as $title) {
                    $selected = ($title->id == $row->jobtitle_id) ? 'selected' : '';
                    $html .= '<option value="' . $title->id . '" ' . $selected . '>' . $title->title . '</option>';
                }
        
                $html .= '</select></td>';
                $html .= '<td> <input type="text" id="empage" name="empage" class="form-control form-control-sm" value="' . $row->emp_age . '" readonly></td>';
                $html .= '<td> <input type="datetime-local" id="empontime" name="empontime" class="form-control form-control-sm"  value="' . $row->ontime . '" required></td>'; 
                $html .= '<td><input type="datetime-local" id="empofftime" name="empofftime" class="form-control form-control-sm" value="' . $row->outtime . '" required></td>';
                $html .= '<td> <button type="button" onclick="productDelete(this);" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                               <button class="addRowBtn btn btn-success btn-sm "><i class="fas fa-plus"></i></button></td>';
                $html .= '</tr>';
            }
            
           
        
            return response() ->json(['result'=>  $html]);


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
}
