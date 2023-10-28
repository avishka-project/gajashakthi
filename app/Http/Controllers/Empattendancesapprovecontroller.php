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
        $permission_level1 =0;
        $permission_level2 =0;
        $permission_level3 =0;
        $user = Auth::user();
      
        $permission = $user->can('Approve-Level-01');
        if($permission){
            $permission_level1 =1;
        }
        $permission = $user->can('Approve-Level-02');
        if($permission){
            $permission_level2 =1;
        }
        $permission = $user->can('Approve-Level-03');
        if($permission){
            $permission_level3 =1;
        }
               
    
            $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1,2])->where('customers.approve_status', 1)->get();
       
        return view('EmployeeAttendance.attendanceapprove'  ,compact('customers','permission_level1','permission_level2','permission_level3'));
    }



        // public function edit(Request $request){
        //     $user = Auth::user();
    
        //         $id = Request('id');
      
        //         $data = DB::table('empattendances')
        //         ->leftjoin('job_titles', 'empattendances.jobtitle_id', '=', 'job_titles.id')
        //         ->leftjoin('employees','empattendances.emp_id','=','employees.id')
        //         ->leftjoin('shift_types', 'empattendances.shift_id', '=', 'shift_types.id')
        //         ->select('empattendances.*', 'job_titles.title','employees.emp_fullname','shift_types.offduty_time AS off_time')
        //         ->where('empattendances.allocation_id', $id)
        //         ->where('empattendances.status', 1)
        //         ->get(); 
    
        //         $html = '';
        //         foreach ($data as $row) {
        //             $html .= '<tr>';
        //             $html .= '<td>' . $row->id. '</td>';  
        //             $html .= '<td>' . $row->emp_fullname . '</td>'; 
        //             $html .= '<td>' . $row->title . '</td>'; 
        //             $html .= '<td>' . $row->ontime . '</td>';
        //             $html .= '<td>' . $row->outtime . '</td>';  
        //             $html .= '</tr>';
        //         }
                
               
            
        //         return response() ->json(['result'=>  $html]);
    
        // }

        public function approve(Request $request){

            $user = Auth::user();
           
           
            $permission =$user->can('Approve-Level-01');
            $permission =$user->can('Approve-Level-02');
            $permission =$user->can('Approve-Level-03');

            if(!$permission) {
                    return response()->json(['error' => 'UnAuthorized'], 401);
            }
           
            
            $selectedRowIds = $request->input('recordID');
            $current_date_time = Carbon::now()->toDateTimeString();

            $apppermission =0;
            if($user->can('Approve-Level-01')&& $user->can('Approve-Level-02') && $user->can('Approve-Level-03')){
                $apppermission = 4;
            }
            elseif($user->can('Approve-Level-01')){
                $apppermission = 1;
            }elseif($user->can('Approve-Level-02')){
                $apppermission = 2;
            }elseif($user->can('Approve-Level-03')){
                $apppermission = 3;
            }else{
                $apppermission =0;
            }

            foreach ($selectedRowIds as $row) {
                $id = $row['id'];
                $applevel = $row['applevel'];
                
                if( $apppermission == 1){
                    if($applevel == 1){
                        Empattendances::where('id', $id)
                        ->update([
                            'approve_01' =>  '1',
                            'approve_01_time' => $current_date_time,
                            'approve_01_by' => Auth::id(),
                        ]);
                    }

                } elseif($apppermission == 2){

                    if($applevel == 2){

                        Empattendances::where('id', $id)
                        ->update([
                            'approve_02' =>  '1',
                            'approve_02_time' => $current_date_time,
                            'approve_02_by' => Auth::id(),
                        ]);
                    }

                }elseif ($apppermission == 3){
                    if($applevel == 3){
                        Empattendances::where('id', $id)
                        ->update([
                            'approve_status' =>  '1',
                            'approve_03' =>  '1',
                            'approve_03_time' => $current_date_time,
                            'approve_03_by' => Auth::id(),
                        ]);
                    }

                } elseif($apppermission == 4){
                    if($applevel == 1){
                        Empattendances::where('id', $id)
                        ->update([
                            'approve_01' =>  '1',
                            'approve_01_time' => $current_date_time,
                            'approve_01_by' => Auth::id(),
                        ]);
                    }else if($applevel == 2){

                        Empattendances::where('id', $id)
                        ->update([
                            'approve_02' =>  '1',
                            'approve_02_time' => $current_date_time,
                            'approve_02_by' => Auth::id(),
                        ]);
                    }else{
                        Empattendances::where('id', $id)
                        ->update([
                            'approve_status' =>  '1',
                            'approve_03' =>  '1',
                            'approve_03_time' => $current_date_time,
                            'approve_03_by' => Auth::id(),
                        ]);
                    }

                }

            
            }
            return response()->json(['success' => 'Security Attendance is successfully Approved']);
        }
        
        
        public function delete(Request $request){
            $user = Auth::user();
          
            $permission =$user->can('empattendance-delete');
            if(!$permission) {
                    return response()->json(['error' => 'UnAuthorized'], 401);
                }
            
                $selectedRowIds = $request->input('recordID');

            $current_date_time = Carbon::now()->toDateTimeString();

            foreach ($selectedRowIds as $id) {
                Empattendances::where('id', $id)
                ->update([
                    'status' =>  '3',
                    'delete_status' =>  '1',
                    'update_by' => Auth::id(),
                    'updated_at' => $current_date_time,
                ]);
                
            }
            return response()->json(['success' => 'Security Attendance is successfully Deleted']);
        
        }

        public function singledelete(Request $request){
            $user = Auth::user();

            $permission =$user->can('empattendance-delete');
            if(!$permission) {
                    return response()->json(['error' => 'UnAuthorized'], 401);
                }
            
                $id = Request('id');
                $current_date_time = Carbon::now()->toDateTimeString();

                Empattendances::where('id', $id)
                ->update([
                    'status' =>  '3',
                    'delete_status' =>  '1',
                    'update_by' => Auth::id(),
                    'updated_at' => $current_date_time,
                ]);

                return response()->json(['success' => 'Security Attendance is successfully Deleted']);
        }

}
