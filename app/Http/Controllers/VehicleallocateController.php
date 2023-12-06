<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Vehicleallocate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class VehicleallocateController extends Controller
{
   
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();

        $employees = DB::table('employees')
        ->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])
        ->get();

        $vehicles = DB::table('vehicles')
        ->select('vehicles.*')
        ->whereIn('vehicles.status', [1, 2])
        ->where('vehicles.approve_status', 1 )
        ->get();

        return view('Vehicle.vehicleallocate'  ,compact('employees','vehicles','userPermissions'));
    }
    public function insert(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vehicle-Allocate-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        $employee =$request->input('employee');
            $lastrecord = Vehicleallocate::where('emp_id', $employee)
            ->orderBy('id', 'desc') 
            ->first();
    
        if ($lastrecord && empty($lastrecord->to_date)) {
            $lastrecord->to_date = $request->input('fromdate');
            $lastrecord->save();
        }


        $vehicle = new Vehicleallocate();
        $vehicle->emp_id =  $employee;
        $vehicle->vehicle_id = $request->input('vehiclename');
        $vehicle->from_date = $request->input('fromdate');
        $vehicle->to_date = $request->input('todate');
        $vehicle->status = '1';
        $vehicle->approve_status = '0';
        $vehicle->approve_01 = '0';
        $vehicle->approve_02 = '0';
        $vehicle->approve_03 = '0';
        $vehicle->create_by = Auth::id();
        $vehicle->update_by = '0';
        $vehicle->save();
        return response()->json(['success' => 'Vehicle Allocatin is successfully Inserted']);
    }

    public function list()
    {
        $types = DB::table('vehicleallocates')
            ->leftjoin('employees','vehicleallocates.emp_id','=','employees.id')
            ->leftjoin('vehicles','vehicleallocates.vehicle_id','=','vehicles.id')
            ->select('vehicleallocates.*' , 'employees.emp_fullname AS employeename','vehicles.vehicle_name AS vehiclename')
            ->whereIn('vehicleallocates.status', [1, 2])
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $commen= new Commen();
                $userPermissions = $commen->Allpermission();

                        if(in_array('Approve-Level-01',$userPermissions)){
                            if($row->approve_01 == 0 && $row->approve_status != 2){
                                $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if(in_array('Approve-Level-02',$userPermissions)){
                            if($row->approve_01 == 1 && $row->approve_02 == 0 && $row->approve_status != 2){
                                $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        if(in_array('Approve-Level-03',$userPermissions)){
                            if($row->approve_02 == 1 && $row->approve_03 == 0 && $row->approve_status != 2){
                                $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }

                        if(in_array('Vehicle-Allocate-edit',$userPermissions) && $row->approve_status != 2){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        if(in_array('Vehicle-Allocate-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('vehicleallocatestatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('vehicleallocatestatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Vehicle-Allocate-delete',$userPermissions)){
                            $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';
                        }
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vehicle-Allocate-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('vehicleallocates')
        ->select('vehicleallocates.*')
        ->where('vehicleallocates.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }

    }
    public function update(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Vehicle-Allocate-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'emp_id' => $request->employee,
                'vehicle_id' => $request->vehiclename,
                'from_date' => $request->fromdate,
                'to_date' => $request->todate,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Vehicleallocate::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Vehicle Allocatin is Successfully Updated']);
    }

    public function delete(Request $request){

            $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Vehicle-Allocate-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Vehicleallocate::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Vehicle Allocatin is successfully Deleted']);

    }

    public function status($id,$statusid){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vehicle-Allocate-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Vehicleallocate::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vehicleallocate');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Vehicleallocate::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vehicleallocate');
        }

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
            Vehicleallocate::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Vehicle Allocation is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Vehicleallocate::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Vehicle Allocation is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Vehicleallocate::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Vehicle Allocation is successfully Approved']);
          }
    }

    public function reject(Request $request){

        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 
          
        $id = Request('id');
         $current_date_time = Carbon::now()->toDateTimeString();
            
            $form_data = array(
                'approve_status' =>  '2',
                'reject' =>  '1',
                'reject_comment' => $request->comment,
                'reject_time' => $current_date_time,
                'reject_by' => Auth::id(),
                
            );
            Vehicleallocate::findOrFail($id)
            ->update($form_data);
    
            return response()->json(['success' => 'Vehicle Allocation is successfully Reject']);
    
    }

    public function getsearchempinfo(Request $request) {
        $searchTerm = $request->input('search');
    
        $matchingData = DB::table('employees')
            ->where(function ($query) use ($searchTerm) {
                $query->where('service_no', '=', $searchTerm)
                       ->orWhere('emp_name_with_initial', 'like', '%' . $searchTerm . '%')
                       ->orWhere('emp_fullname', 'like', '%' . $searchTerm . '%')
                       ->orWhere('emp_national_id', '=', $searchTerm)
                       ->where('deleted', 0);
            })
            ->limit(20)
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
