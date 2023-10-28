<?php

namespace App\Http\Controllers;

use App\Deaddonation;
use App\Deaddonationallocation;
use App\Deaddonationdetail;
use App\Deaddonationincomplete;
use App\Deaddonationlastallocation;
use App\Employee_dependent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\Datatables\Facades\Datatables;

class Deaddonationdetailcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $employees = DB::table('employees')->select('employees.*')->get();
        return view('Deaddonation.deaddonationdetail', compact('employees'));
    }


    public function requestlist(Request $request)
    {

        $emp_id = $request->input('emp_id');
        $types = DB::table('deaddonationlastallocations')
            ->leftjoin('deaddonations', 'deaddonationlastallocations.deaddonation_id', '=', 'deaddonations.id')
            ->leftjoin('employees', 'deaddonations.employee_id', '=', 'employees.id')
            ->leftjoin('deaddonationincompletes', 'deaddonations.id', '=', 'deaddonationincompletes.deaddonation_id')
            ->leftjoin('employee_dependents', 'deaddonations.relative_id', '=', 'employee_dependents.id')
            ->select('deaddonationlastallocations.*','deaddonations.relative_id AS relative_id','deaddonations.dateofdead AS dateofdead','employees.emp_name_with_initial AS emp_name_with_initial','employee_dependents.emp_dep_relation AS emp_dep_relation','employee_dependents.emp_dep_name AS emp_dep_name')
            // ->whereIn('deaddonationlastallocations.status', [1, 2])
            // ->where('deaddonationlastallocations.approve_status', 1)
            ->where('employee_dependents.life_status', 'dead')
            ->where('employee_dependents.emp_id', $emp_id)
            ->get();
        

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $user = Auth::user();

                       
                     
                        $permission = $user->can('Deaddonationdetail-view');
                        if($permission){
                            $btn .= ' <button name="details" id="'.$row->id.'" class="details btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';
                        }

                    $permission = $user->can('Deaddonationdetail-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('deaddonationdetailstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('deaddonationdetailstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('Deaddonationdetail-delete');
                        if($permission){
                            $btn .= ' <button name="delete" id="'.$row->id.'" relative_id="'.$row->relative_id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function requestlist1(Request $request)
    {

        $emp_id = $request->input('emp_id');

            $types = DB::table('employee_dependents')
            ->leftjoin('employees', 'employee_dependents.emp_id', '=', 'employees.id')
            ->select('employee_dependents.*','employee_dependents.emp_dep_relation AS emp_dep_relation','employees.emp_name_with_initial AS emp_name_with_initial')
            ->where('employee_dependents.life_status', null)
            ->where('employee_dependents.emp_id', $emp_id)
            ->get();
        

            return Datatables::of($types)
            ->addIndexColumn()
           
           
          
            ->make(true);
    }

    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('Deaddonationdetail-delete');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();

        $form_data3 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonation::findOrFail($id)
        ->update($form_data3);

        $form_data4 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationallocation::findOrFail($id)
        ->update($form_data4);

        $form_data5 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationincomplete::findOrFail($id)
        ->update($form_data5);

        $form_data6 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationlastallocation::findOrFail($id)
        ->update($form_data6);

        $id = Request('relative_id');
        $form_data2 = array(
            'life_status' => null
        );

        Employee_dependent::findOrFail($id)
    ->update($form_data2);

        return response()->json(['success' => 'Dead Donation Details is successfully Deleted']);

    }





    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('Deaddonationdetail-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Deaddonationlastallocation::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('deaddonationdetail');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Deaddonationlastallocation::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('deaddonationdetail');
        }

       
    

    }

    public function getdeaddonationdetails(Request $request){

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('deaddonationlastallocations')
            ->leftjoin('deaddonations', 'deaddonationlastallocations.deaddonation_id', '=', 'deaddonations.id')
            ->leftjoin('deaddonationallocations', 'deaddonationallocations.deaddonation_id', '=', 'deaddonations.id')
            ->leftjoin('deaddonationincompletes', 'deaddonationincompletes.deaddonation_id', '=', 'deaddonations.id')
            ->leftjoin('employees', 'deaddonations.employee_id', '=', 'employees.id') 
            ->leftjoin('employee_dependents', 'deaddonations.relative_id', '=', 'employee_dependents.id')
            ->select('employees.service_no AS service_no','deaddonations.causesofdead AS causesofdead','deaddonationlastallocations.amount AS lastallocation','deaddonationallocations.amount AS firstallocation','deaddonations.dateofdead AS dateofdead','employees.emp_name_with_initial AS emp_name_with_initial','employee_dependents.emp_dep_relation AS emp_dep_relation','deaddonationincompletes.filename AS filename')
            // ->whereIn('deaddonationlastallocations.status', [1, 2])
            // ->where('deaddonationlastallocations.approve_status', 1)
            ->where('employee_dependents.life_status', 'dead')
            ->where('deaddonationlastallocations.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }
   
}
