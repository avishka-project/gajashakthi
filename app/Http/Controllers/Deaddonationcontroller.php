<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Deaddonation;
use App\Deaddonationallocation;
use App\Deaddonationincomplete;
use App\Deaddonationlastallocation;
use App\Employee_dependent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Deaddonationcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $employees = DB::table('employees')->select('employees.*')->get();
        return view('Deaddonation.deaddonation', compact('employees','userPermissions'));
    }
    public function insert(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonation-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }


        $deaddonation = new Deaddonation();
        $deaddonation->employee_id = $request->input('serviceno');
        $deaddonation->relative_id = $request->input('relatives');
        $deaddonation->dateofdead = $request->input('date');
        $deaddonation->causesofdead = $request->input('reason');
        $deaddonation->funeral_pace = $request->input('funeral_place');
        $deaddonation->funeral_date = $request->input('funeral_date');
        $deaddonation->subregion_id = $request->input('voregion_id');
        $deaddonation->status = '1';
        $deaddonation->approve_status = '0';
        $deaddonation->approve_01 = '0';
        $deaddonation->approve_02 = '0';
        $deaddonation->approve_03 = '0';
        $deaddonation->create_by = Auth::id();
        $deaddonation->update_by = '0';
        $deaddonation->save();

        $id=$request->input('relatives');
        $requestID = $deaddonation->id;

        $deaddonationallocation = new Deaddonationallocation();
        $deaddonationallocation->deaddonation_id = $requestID;
        $deaddonationallocation->funeral_pace = $request->input('funeral_place');
        $deaddonationallocation->funeral_date = $request->input('funeral_date');
        $deaddonationallocation->subregion_id = $request->input('voregion_id');
        $deaddonationallocation->status = '1';
        $deaddonationallocation->approve_status = '0';
        $deaddonationallocation->approve_01 = '0';
        $deaddonationallocation->approve_02 = '0';
        $deaddonationallocation->approve_03 = '0';
        $deaddonationallocation->create_by = Auth::id();
        $deaddonationallocation->update_by = '0';
        $deaddonationallocation->save();

        
        $deaddonationincomplete = new Deaddonationincomplete();
        $deaddonationincomplete->deaddonation_id = $requestID;
        $deaddonationincomplete->funeral_pace = $request->input('funeral_place');
        $deaddonationincomplete->funeral_date = $request->input('funeral_date');
        $deaddonationincomplete->subregion_id = $request->input('voregion_id');
        $deaddonationincomplete->status = '1';
        $deaddonationincomplete->approve_status = '0';
        $deaddonationincomplete->approve_01 = '0';
        $deaddonationincomplete->approve_02 = '0';
        $deaddonationincomplete->approve_03 = '0';
        $deaddonationincomplete->create_by = Auth::id();
        $deaddonationincomplete->update_by = '0';
        $deaddonationincomplete->save();

        
        $deaddonationlastallocation = new Deaddonationlastallocation();
        $deaddonationlastallocation->deaddonation_id = $requestID;
        $deaddonationlastallocation->funeral_pace = $request->input('funeral_place');
        $deaddonationlastallocation->funeral_date = $request->input('funeral_date');
        $deaddonationlastallocation->subregion_id = $request->input('voregion_id');
        $deaddonationlastallocation->status = '1';
        $deaddonationlastallocation->approve_status = '0';
        $deaddonationlastallocation->approve_01 = '0';
        $deaddonationlastallocation->approve_02 = '0';
        $deaddonationlastallocation->approve_03 = '0';
        $deaddonationlastallocation->create_by = Auth::id();
        $deaddonationlastallocation->update_by = '0';
        $deaddonationlastallocation->save();



        $form_data = array(
            'life_status' => 'dead',
        );

        Employee_dependent::findOrFail($id)
    ->update($form_data);


        return response()->json(['success' => 'Dead Donation is successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('deaddonations')
            ->leftjoin('employees', 'deaddonations.employee_id', '=', 'employees.id')
            ->leftjoin('employee_dependents', 'deaddonations.relative_id', '=', 'employee_dependents.id')
            ->leftjoin('subregions', 'employees.subregion_id', '=', 'subregions.id')
            ->select('deaddonations.*','employees.emp_name_with_initial AS emp_name_with_initial','employee_dependents.emp_dep_relation AS emp_dep_relation','subregions.subregion')
            ->whereIn('deaddonations.status', [1, 2])
            ->where('deaddonations.approve_status', 0)
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $commen= new Commen();
                $userPermissions = $commen->Allpermission();

                        if(in_array('Approve-Level-01',$userPermissions)){
                            if($row->approve_01 == 0){
                                $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if(in_array('Approve-Level-02',$userPermissions)){
                            if($row->approve_01 == 1 && $row->approve_02 == 0){
                                $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if(in_array('Approve-Level-03',$userPermissions)){
                            if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                                $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }

                        if(in_array('Deaddonation-edit',$userPermissions)){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        if(in_array('Deaddonation-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('deaddonationstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('deaddonationstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Deaddonation-delete',$userPermissions)){
                            $btn .= ' <button name="delete" id="'.$row->id.'" relative_id="'.$row->relative_id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonation-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('deaddonations')
        ->select('deaddonations.*')
        ->where('deaddonations.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Deaddonation-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'employee_id' => $request->editempid,
                'relative_id' => $request->relatives,
                'dateofdead' => $request->date,
                'causesofdead' => $request->reason,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Deaddonation::findOrFail($id)
        ->update($form_data);

 if($request->old_relative!==$request->relatives){
    $id = $request->old_relative;
    $form_data2 = array(
        'life_status' => null
    );
    Employee_dependent::findOrFail($id)
    ->update($form_data2);

    
    $id = $request->relatives;
    $form_data3 = array(
        'life_status' => 'dead'
    );
    Employee_dependent::findOrFail($id)
    ->update($form_data3);
 }      

       
        
        return response()->json(['success' => 'Dead Donation is Successfully Updated']);
    }




    public function delete(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonation-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
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
        
        Deaddonationallocation::where('deaddonation_id', $id)
            ->update($form_data4);

        $form_data5 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationincomplete::where('deaddonation_id', $id)
        ->update($form_data5);

        $form_data6 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationlastallocation::where('deaddonation_id', $id)
        ->update($form_data6);


        $relative_id = Request('relative_id');
        $form_data2 = array(
            'life_status' => null
        );

        Employee_dependent::findOrFail($relative_id)
    ->update($form_data2);


        return response()->json(['success' => 'Dead Donation is successfully Deleted']);

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
            Deaddonation::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Dead Donation is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Deaddonation::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Dead Donation is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Deaddonation::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Dead Donation is successfully Approved']);
          }
    }




    public function status($id,$statusid){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonation-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Deaddonation::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('deaddonation');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Deaddonation::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('deaddonation');
        }

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
    
    public function getempname(Request $request){

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('employees')
        ->leftjoin('subregions', 'employees.subregion_id', '=', 'subregions.id')
        ->select('employees.*','subregions.subregion','subregions.id AS subregion_id')
        ->where('employees.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }
    public function getrelatives($empId)
    {
        $relative = DB::table('employee_dependents')->select('employee_dependents.*')->where('emp_id', '=', $empId)->get();
    
        return response()->json($relative);
    }
    
}
