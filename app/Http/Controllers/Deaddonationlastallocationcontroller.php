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

class Deaddonationlastallocationcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $banks = DB::table('banks')->select('banks.*')
        ->whereIn('banks.status', [1, 2])
        ->get();

        return view('Deaddonation.lastallocation',compact('banks','userPermissions'));
    }

    public function insert(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonationlastallocation-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

        $id=$request->input('hidden_id');
        $chequeno = $request->input('chequno');
        $bank = $request->input('bank');
        $amount = $request->input('amount');

        $form_data = array(
            'chequeno' =>  $chequeno,
            'bank' =>  $bank,
            'amount' =>  $amount,
        );

        Deaddonationlastallocation::findOrFail($id)
    ->update($form_data);

        return response()->json(['success' => 'Dead Donation Last Allocation is successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('deaddonationlastallocations')
            ->leftjoin('deaddonations', 'deaddonationlastallocations.deaddonation_id', '=', 'deaddonations.id')
            ->leftjoin('employees', 'deaddonations.employee_id', '=', 'employees.id')
            ->leftjoin('deaddonationincompletes', 'deaddonations.id', '=', 'deaddonationincompletes.deaddonation_id')
            ->leftjoin('employee_dependents', 'deaddonations.relative_id', '=', 'employee_dependents.id')
            ->leftjoin('subregions', 'employees.subregion_id', '=', 'subregions.id')
            ->select('deaddonationlastallocations.*','deaddonations.relative_id AS relative_id','deaddonations.dateofdead AS dateofdead','employees.emp_name_with_initial AS emp_name_with_initial','employee_dependents.emp_dep_relation AS emp_dep_relation','subregions.subregion')
            ->whereIn('deaddonationlastallocations.status', [1, 2])
            ->where('deaddonationlastallocations.approve_status', 0)
            ->where('deaddonationincompletes.approve_status', 1)
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $commen= new Commen();
                $userPermissions = $commen->Allpermission();
                $amountEmpty = empty($row->amount);

                if(in_array('Approve-Level-01',$userPermissions)){
                    if(!$amountEmpty){
                            if($row->approve_01 == 0){
                                $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
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
                        if(in_array('Deaddonationlastallocation-edit',$userPermissions)){
                            if($amountEmpty){
                            $btn .= ' <button name="allocate" id="'.$row->id.'" class="allocate btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-plus"></i></button>';
                        }
                    }

                    if(in_array('Deaddonationlastallocation-edit',$userPermissions)){
                        if(!$amountEmpty){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    }

                    if(in_array('Deaddonationlastallocation-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('lastallocationstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('lastallocationstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Deaddonationlastallocation-delete',$userPermissions)){
                            $btn .= ' <button name="delete" id="'.$row->id.'" relative_id="'.$row->relative_id.'" deaddonation_id="'.$row->deaddonation_id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonationlastallocation-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('deaddonationlastallocations')
        ->select('deaddonationlastallocations.*')
        ->where('deaddonationlastallocations.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonationlastallocation-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'chequeno' => $request->chequno,
                'bank' => $request->bank,
                'amount' => $request->amount, 
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Deaddonationlastallocation::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Dead Donation Last Allocation is Successfully Updated']);
    }




    public function delete(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonationlastallocation-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
            $deaddonation_id= Request('deaddonation_id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data3 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonation::findOrFail($deaddonation_id)
        ->update($form_data3);

        $form_data4 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationallocation::where('deaddonation_id', $deaddonation_id)
        ->update($form_data4);

        $form_data5 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationincomplete::where('deaddonation_id', $deaddonation_id)
        ->update($form_data5);

        $form_data6 = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Deaddonationlastallocation::findOrFail($id)
        ->update($form_data6);

        $relative_id = Request('relative_id');
        $form_data2 = array(
            'life_status' => null
        );

        Employee_dependent::findOrFail($relative_id)
    ->update($form_data2);

    
        return response()->json(['success' => 'Dead Donation Last Allocation is successfully Deleted']);

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
            Deaddonationlastallocation::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Dead Donation Last Allocation is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Deaddonationlastallocation::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Dead Donation Last Allocation is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Deaddonationlastallocation::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Dead Donation Last Allocation is successfully Approved']);
          }
    }




    public function status($id,$statusid){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Deaddonationlastallocation-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Deaddonationlastallocation::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('lastallocation');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Deaddonationlastallocation::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('lastallocation');
        }

    }

    
    public function getempname(Request $request){

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('employees')
        ->select('employees.*')
        ->where('employees.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }

}
