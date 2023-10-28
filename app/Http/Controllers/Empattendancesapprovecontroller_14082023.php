<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customerbranch;
use App\Empattendances;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Gate;


class Empattendancesapprovecontroller extends Controller
{
    public function index()
    {

        $user = Auth::user();
               
        
            $customersbranch = Customerbranch::orderBy('id', 'asc')
            ->whereIn('customerbranches.status', [1, 2]) 
            ->where('customerbranches.approve_status', 1)
            ->get();
            $subcustomer = DB::table('subcustomers')->select('subcustomers.*')->whereIn('subcustomers.status', [1, 2])->where('subcustomers.approve_status', 1)->get();
            $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1,2])->where('customers.approve_status', 1)->get();
       
        return view('EmployeeAttendance.attendanceapprove'  ,compact('customers', 'subcustomer', 'customersbranch'));
    }



    

            public function getSubCustomers($customerId)
        {
            $subCustomers = DB::table('subcustomers')->select('subcustomers.*')->where('subcustomers.approve_status', 1)->whereIn('subcustomers.status', [1, 2])
            ->where('customer_id', '=', $customerId)->get();

            return response()->json($subCustomers);
        }

        public function getbranch($subcustomerId)
        {
            $branch = DB::table('customerbranches')->select('customerbranches.*')->where('customerbranches.approve_status', 1)->whereIn('customerbranches.status', [1, 2])
            ->where('subcustomer_id', '=', $subcustomerId)->get();

            return response()->json($branch);
        }


        public function list(Request $request){

            $id = Request('cusid');
            $currentDate = Carbon::today();

            $requests = DB::table('empattendances')
                    ->leftjoin('customerbranches', 'empattendances.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('subcustomers', 'empattendances.subcustomer_id', '=', 'subcustomers.id')
                    ->leftjoin('customers', 'subcustomers.customer_id', '=', 'customers.id')
                    ->leftjoin('shift_types', 'empattendances.shift_id', '=', 'shift_types.id')
                    ->select('empattendances.*','customerbranches.branch_name AS branchname','shift_types.shift_name AS shiftname','subcustomers.sub_name AS subcustomer','customers.name AS customername')
                    ->where('empattendances.customerbranch_id', $id)
                    ->where('empattendances.status', 1)
                    ->where('empattendances.approve_status', 0)
                    ->groupBy('empattendances.allocation_id')
                    ->get();


                    

                return Datatables::of($requests)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '';
                    $user = Auth::user();
                    $permission = $user->can('Approve-Level-01');
                    if($permission){
                        if($row->approve_01 == 0){
                            $btn .= ' <button name="appL1" id="'.$row->allocation_id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('Approve-Level-02');
                    if($permission){
                        if($row->approve_01 == 1 && $row->approve_02 == 0){
                            $btn .= ' <button name="appL2" id="'.$row->allocation_id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('Approve-Level-03');
                    if($permission){
                        if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                            $btn .= ' <button name="appL3" id="'.$row->allocation_id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                        }
                    }
                    $permission = $user->can('empattendance-delete');
                    if($permission){
                        $btn .= ' <button name="delete" id="'.$row->allocation_id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';  
                    }
                    return $btn;
                })
            
                ->rawColumns(['action'])
                ->make(true);
        }

        public function edit(Request $request){
            $user = Auth::user();
    
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
                    $html .= '<td>' . $row->outtime . '</td>';  
                    $html .= '</tr>';
                }
                
               
            
                return response() ->json(['result'=>  $html]);
    
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
        
                        Empattendances::where('allocation_id', $id)
                    ->update([
                        'approve_01' =>  '1',
                        'approve_01_time' => $current_date_time,
                        'approve_01_by' => Auth::id(),
                    ]);

                return response()->json(['success' => 'Employee Attendance is successfully Approved']);
        
             }elseif($applevel == 2){

                Empattendances::where('allocation_id', $id)
                ->update([
                    'approve_02' =>  '1',
                    'approve_02_time' => $current_date_time,
                    'approve_02_by' => Auth::id(),
                ]);
        
                return response()->json(['success' => 'Employee Attendance is successfully Approved']);
        
             }else{

                Empattendances::where('allocation_id', $id)
                ->update([
                    'approve_status' =>  '1',
                    'approve_03' =>  '1',
                    'approve_03_time' => $current_date_time,
                    'approve_03_by' => Auth::id(),
                ]);

               return response()->json(['success' => 'Employee Attendance  is successfully Approved']);
              }
        }
        
        
        public function delete(Request $request){
            $user = Auth::user();
          
            $permission =$user->can('empattendance-delete');
            if(!$permission) {
                    return response()->json(['error' => 'UnAuthorized'], 401);
                }
            
            $id = Request('id');

            $current_date_time = Carbon::now()->toDateTimeString();

            Empattendances::where('allocation_id', $id)
            ->update([
                'status' =>  '3',
                'delete_status' =>  '1',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            ]);
        
            return response()->json(['success' => 'Employee Attendance is successfully Deleted']);
        
        }

}
