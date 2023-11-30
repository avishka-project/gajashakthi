<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Vehiclecontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $vehicletypes = DB::table('vehicletypes')->select('vehicletypes.*')
        ->whereIn('vehicletypes.status', [1, 2])
        ->where('vehicletypes.approve_status', 1)
        ->get();
        return view('Vehicle.vehicle',compact('vehicletypes','userPermissions'));
    }

    public function insert(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vehicle-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        $vehicle = new Vehicle();
        $vehicle->vehicle_name = $request->input('vehicleregno');
        $vehicle->vehicletype_id = $request->input('vehicletype');
        // $vehicle->vehicle_brand = $request->input('vehiclebrand');
        $vehicle->vehicle_model = $request->input('vehiclemodel');
        $vehicle->engine_no = $request->input('engineno');
        $vehicle->chassis_no = $request->input('chassisno');
        $vehicle->start_mileage = $request->input('startmileage');
        $vehicle->description = $request->input('description');
        $vehicle->status = '1';
        $vehicle->approve_status = '0';
        $vehicle->approve_01 = '0';
        $vehicle->approve_02 = '0';
        $vehicle->approve_03 = '0';
        $vehicle->create_by = Auth::id();
        $vehicle->update_by = '0';
        $vehicle->save();
        return response()->json(['success' => 'Vehicle is successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('vehicles')
            ->join('vehicletypes', 'vehicles.vehicletype_id', '=', 'vehicletypes.id')
            ->select('vehicles.*','vehicletypes.vehicle_type AS vehicle_type')
            ->whereIn('vehicles.status', [1, 2])
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

                        if(in_array('Vehicle-edit',$userPermissions) && $row->approve_status != 2){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        if(in_array('Vehicle-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('vehiclestatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('vehiclestatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Vehicle-delete',$userPermissions)){
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
            if (!in_array('Vehicle-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('vehicles')
        ->select('vehicles.*')
        ->where('vehicles.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vehicle-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'vehicle_name' => $request->vehicleregno,
                'vehicletype_id' => $request->vehicletype,
                'vehicle_model' => $request->vehiclemodel,
                'engine_no' => $request->engineno,
                'chassis_no' => $request->chassisno,
                'start_mileage' => $request->startmileage,
                'description' => $request->description,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Vehicle::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Vehicle is Successfully Updated']);
    }




    public function delete(Request $request){

            $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Vehicle-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Vehicle::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Vehicle is successfully Deleted']);

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
            Vehicle::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Vehicle is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Vehicle::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Vehicle is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Vehicle::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Vehicle is successfully Approved']);
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
            Vehicle::findOrFail($id)
            ->update($form_data);
    
            return response()->json(['success' => 'Vehicle is successfully Reject']);
    
    }

    public function status($id,$statusid){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vehicle-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Vehicle::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vehicle');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Vehicle::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vehicle');
        }

    }
}