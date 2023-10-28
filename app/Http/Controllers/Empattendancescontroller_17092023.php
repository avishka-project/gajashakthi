<?php

namespace App\Http\Controllers;

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
               
        $permission = $user->can('empattendence-admin-create');
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
       
        return view('EmployeeAttendance.attendance'  ,compact('customers'));
    }

    public function allocationlist(Request $request){

        $id = Request('cusid');
        $currentDate = Carbon::today();

       
        $sevenDaysAgo = $currentDate->copy()->subDays(7);

        $requests = DB::table('empallocations')
                ->leftjoin('customers', 'empallocations.customer_id', '=', 'customers.id')
                ->leftjoin('customerbranches', 'empallocations.customerbranch_id', '=', 'customerbranches.id')
                ->leftjoin('subcustomers', 'empallocations.subcustomer_id', '=', 'subcustomers.id')
                ->leftjoin('shift_types', 'empallocations.shift_id', '=', 'shift_types.id')
                ->select('empallocations.*','customers.name AS customername','customerbranches.branch_name AS branchname','shift_types.shift_name AS shiftname','subcustomers.sub_name AS subcustomer')
                ->where('empallocations.customerbranch_id', $id)
                ->whereIn('empallocations.status', [1, 2])
                ->where('empallocations.approve_status', 1)
                ->get();

        $attendanceIds = DB::table('empattendances')
                ->whereIn('allocation_id', $requests->pluck('id'))
                ->pluck('allocation_id')
                ->toArray();
                

            return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row)  use ($attendanceIds){
                $btn = '';
                $user = Auth::user();
                $permission = $user->can('empattendance-create');
                if($permission){
                    if (in_array($row->id, $attendanceIds)) {
                    }else{
                        $btn .= ' <button name="btnAdd" id="'.$row->id.'" class="btnAdd btn btn-outline-primary btn-sm " type="submit"><i class="fas fa-plus"></i></button>';
                    }
                   
                }
               
            
                
                
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function list(Request $request){

        $id = Request('cusid');
        $currentDate = Carbon::today();
        $sevenDaysAgo = $currentDate->copy()->subDays(7);

        $requests = DB::table('empattendances')
                ->leftjoin('customerbranches', 'empattendances.customerbranch_id', '=', 'customerbranches.id')
                ->leftjoin('subcustomers', 'empattendances.subcustomer_id', '=', 'subcustomers.id')
                ->leftjoin('shift_types', 'empattendances.shift_id', '=', 'shift_types.id')
                ->select('empattendances.*','customerbranches.branch_name AS branch','shift_types.shift_name AS shift','subcustomers.sub_name AS cussub')
                ->where('empattendances.customerbranch_id', $id)
                 ->whereBetween('empattendances.date', [$sevenDaysAgo, $currentDate])
                ->whereIn('empattendances.status', [1, 2])
                ->where('empattendances.approve_status', 0)
                ->groupBy('empattendances.allocation_id')
                ->get();

    
                

            return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $user = Auth::user();
                $permission = $user->can('empattendance-edit');
                if($permission){
                    $btn .= ' <button name="edit" id="'.$row->allocation_id.'" class="edit btn btn-outline-primary btn-sm float-right" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                }
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }







    public function allocationdetails(Request $request){
    
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


        $detaillist = $this->allocationdetailslist($id); 

        $responseData = array(
            'mainData' => $data[0],
            'detaildata' => $detaillist
        );

return response() ->json(['result'=>  $responseData]);

}


private function allocationdetailslist($id){

    $recordID =$id ;
    $data = DB::table('empallocationdetails')
    ->leftjoin('empallocations', 'empallocationdetails.allocation_id', '=', 'empallocations.id')
    ->leftjoin('job_titles', 'empallocationdetails.assigndesignation_id', '=', 'job_titles.id')
    ->leftjoin('employees','empallocationdetails.emp_id','=','employees.id')
    ->leftjoin('shift_types', 'empallocations.shift_id', '=', 'shift_types.id')
    ->select('empallocationdetails.*','empallocations.*', 'job_titles.title','employees.emp_fullname','shift_types.onduty_time AS ontime','shift_types.offduty_time AS offtime',
    'shift_types.saturday_onduty_time AS saturdayonduty','shift_types.saturday_offduty_time AS saturdayoffduty','empallocations.date AS allocation_date')
    ->where('empallocationdetails.allocation_id', $recordID)
    ->where('empallocationdetails.status', 1)
    ->get(); 

    $allocationDate = $data[0]->allocation_date;
    $holiday = $data[0]->holiday_id;


  

    $html = '';
    foreach ($data as $row) {
 
        if($holiday == 5){
            $shifyontime = $row->saturdayonduty; 
            $shifyofftime = $row->saturdayoffduty; 

        }else{
            $shifyontime = $row->ontime; 
            $shifyofftime = $row->offtime; 
        }
        
        $combinedontime = Carbon::parse("$allocationDate $shifyontime");
        $ontime = $combinedontime->format('Y-m-d H:i:s');

     
        $combinedofftime = Carbon::parse("$allocationDate $shifyofftime");
        $offtime = $combinedofftime->format('Y-m-d H:i:s');

        $html .= '<tr>';
        $html .= '<td><input type="checkbox" id="rowselect" name="rowselect" value="1"></td>';  
        $html .= '<td>' . $row->emp_fullname . '</td>'; 
        $html .= '<td>' . $row->title . '</td>'; 
        $html .= '<td><input type="datetime-local" id="empontime" name="empontime" value ="'. $ontime.'"></td>';
        $html .= '<td><input type="datetime-local" id="empofftime" name="empofftime" value ="'. $offtime.'"></td>';
        $html .= '<td class="d-none">' . $row->emp_id . '</td>'; 
        $html .= '<td class="d-none">' . $row->assigndesignation_id . '</td>'; 
        $html .= '<td class="d-none"><input type="hidden" id="hiddenid" name="hiddenid" value="' . $row->id . '"></td>'; 
        
        $html .= '</tr>';
    }
    
    return $html;
    

}

public function insert(Request $request){

    $user = Auth::user();
    $permission =$user->can('allocation-create');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        $allocationid = $request->input('recordID');
   
        $customer_id = $request->input('customer');
        $subcustomer_id = $request->input('subcustomer');
        $customerbranch_id = $request->input('branch');
        $date = $request->input('date');
        $holiday_id = $request->input('hollyday');
        $shift_id = $request->input('shift');

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {

            $attstatus = $rowtabledata['col_1'];
            $empontime = $rowtabledata['col_4'];
            $empofftime = $rowtabledata['col_5'];
            $empID = $rowtabledata['col_6'];
            $title = $rowtabledata['col_7'];


            if($attstatus == 1){
                $attendancese_status= 1;
                $ontime =  $empontime;
                $offtime = $empofftime;

            }else{
                $attendancese_status= 0;
                $ontime = '0';
                $offtime = '0';
            }

            
            


            $attendances = new Empattendances();
            $attendances->date = $date;
            $attendances->allocation_id = $allocationid;
            $attendances->subcustomer_id = $subcustomer_id;
            $attendances->customerbranch_id = $customerbranch_id;
            $attendances->holiday_id = '4';
            $attendances->shift_id = $shift_id;
            $attendances->emp_id = $empID;
            $attendances->jobtitle_id = $title;
            $attendances->ontime = $ontime;
            $attendances->outtime = $offtime;
            $attendances->attendance_status = $attendancese_status;
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

        return response()->json(['status' => 1, 'message' => 'Employee Attendance is Successfully Created']);

}

    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('empattendance-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

            $id = Request('id');
  
            $data = DB::table('empattendances')
            ->leftjoin('job_titles', 'empattendances.jobtitle_id', '=', 'job_titles.id')
            ->leftjoin('employees','empattendances.emp_id','=','employees.id')
            ->leftjoin('shift_types', 'empattendances.shift_id', '=', 'shift_types.id')
            ->select('empattendances.*', 'job_titles.title','employees.emp_fullname','shift_types.offduty_time AS off_time')
            ->where('empattendances.allocation_id', $id)
            ->where('empattendances.status', 1)
            ->get(); 

            $html = '';
            foreach ($data as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row->id. '</td>';  
                $html .= '<td>' . $row->emp_fullname . '</td>'; 
                $html .= '<td>' . $row->title . '</td>'; 
                $html .= '<td>' . $row->ontime . '</td>';
                $html .= '<td><input type="datetime-local" id="empontime" name="empontime" ></td>'; 
                $html .= '<td>' . $row->outtime . '</td>';
                $html .= '<td><input type="datetime-local" id="empofftime" name="empofftime" ></td>';
                $html .= '<td class="d-none">' . $row->emp_id . '</td>'; 
                $html .= '<td class="d-none">' . $row->jobtitle_id . '</td>'; 
                $html .= '<td class="d-none">' . $row->date . '</td>'; 
                $html .= '<td class="d-none">' . $row->id . '</td>'; 
                
                $html .= '</tr>';
            }
            
           
        
            return response() ->json(['result'=>  $html]);

    }
    public function update(Request $request){
        $user = Auth::user();
        $permission =$user->can('empattendance-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $tableData = $request->input('tableData');

            foreach ($tableData as $rowtabledata) {

                $recordID = $rowtabledata['col_1'];
                $empontime = $rowtabledata['col_5'];
                $empofftime = $rowtabledata['col_7'];
                $empID = $rowtabledata['col_8'];
                $title = $rowtabledata['col_9'];
                $date = $rowtabledata['col_10'];
    
                $current_date_time = Carbon::now()->toDateTimeString();
    
              
                    if(!empty($empontime) || !empty($empofftime)){

                        $originalAttendance = Empattendances::find($recordID);
    
                        $attendances = Empattendances::where('id', $recordID)->first();
                        $attendances->update_by = Auth::id();
                        $attendances->updated_at = $current_date_time;
                        $attendances->approve_status = '0';
                        $attendances->approve_01 = '0';
                        $attendances->approve_02 = '0';
                        $attendances->approve_03 = '0';


                    
                        if (!empty($empontime) && !empty($empofftime)) {
                            $attendances->ontime = $empontime;
                            $attendances->outtime = $empofftime;
                            $attendances->attendance_status = '1';
                        } elseif (!empty($empontime)) {
                            $attendances->ontime = $empontime;
                            $attendances->attendance_status = '1';
                        } elseif (!empty($empofftime)) {
                            $attendances->outtime = $empofftime;
                        }
                    
                        $attendances->save();

                        // add old record to the duplicate table
                        $originalAttributes = $originalAttendance->toArray();
                        $originalAttributes['attendance_id'] = $recordID;
                        empattendances_duplicate::create($originalAttributes);
        
                    }else{
                      
                    }
    
            }
           return response()->json(['status' => 1, 'message' => 'Employee Attendance is Successfully Updated']);
    
    } 

}
