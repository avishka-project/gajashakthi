<?php

namespace App\Http\Controllers;

use App\Emp_expense;
use App\Vehicleservice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Vehicleserviceandrepaircontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $employees = DB::table('employees')->select('employees.*')->get();
        return view('Vehicleserviceandrepair.vehicleserviceandrepair',compact('employees'));
    }

    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('Vehicleserviceandrepair-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
    
            $user = Auth::user();

        $mobilebillpayment = new Vehicleservice();
        $mobilebillpayment->month = $request->input('month');
        $mobilebillpayment->vehicle_number = $request->input('vehicle_number');
        $mobilebillpayment->cost = $request->input('cost');
        $mobilebillpayment->remark = $request->input('remark');
        $mobilebillpayment->emp_id = $request->input('employee');
        $mobilebillpayment->status = '1';
        $mobilebillpayment->approve_status = '0';
        $mobilebillpayment->approve_01 = '0';
        $mobilebillpayment->approve_02 = '0';
        $mobilebillpayment->approve_03 = '0';
        $mobilebillpayment->create_by = Auth::id();
        $mobilebillpayment->update_by = '0';
        $mobilebillpayment->save();
        return response()->json(['success' => 'Vehicle Service is successfully Inserted']);
    }


    public function requestlist()
    {
        $types = DB::table('vehicleservices')
            ->join('employees', 'vehicleservices.emp_id', '=', 'employees.id')
            ->select('vehicleservices.*','employees.emp_first_name AS emp_first_name')
            ->whereIn('vehicleservices.status', [1, 2])
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $user = Auth::user();

                        $permission = $user->can('Approve-Level-01');
                        if($permission){
                            if($row->approve_01 == 0 && $row->approve_status != 2){
                                $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        $permission = $user->can('Approve-Level-02');
                        if($permission){
                            if($row->approve_01 == 1 && $row->approve_02 == 0 && $row->approve_status != 2){
                                $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        $permission = $user->can('Approve-Level-03');
                        if($permission){
                            if($row->approve_02 == 1 && $row->approve_03 == 0 && $row->approve_status != 2){
                                $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }

                        $permission = $user->can('Vehicleserviceandrepair-edit');
                        if($permission && $row->approve_status != 2){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                    $permission = $user->can('Vehicleserviceandrepair-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('vehicleserviceandrepairstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('vehicleserviceandrepairstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('Mobilebillpayment-delete');
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
        $permission =$user->can('Vehicleserviceandrepair-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('vehicleservices')
        ->select('vehicleservices.*')
        ->where('vehicleservices.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }


    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('Vehicleserviceandrepair-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'emp_id' => $request->employee,
                'month' => $request->month,
                'vehicle_number' => $request->vehicle_number,
                'remark' => $request->remark,
                'cost' => $request->cost,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Vehicleservice::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Vehicle Service is Successfully Updated']);
    }

    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('Vehicleserviceandrepair-delete');
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
        Vehicleservice::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Vehicle Service is successfully Deleted']);

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
            
            $form_data = array(
                'approve_01' =>  '1',
                'approve_01_time' => $current_date_time,
                'approve_01_by' => Auth::id(),
            );
            Vehicleservice::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Vehicle Service is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Vehicleservice::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Vehicle Service is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Vehicleservice::findOrFail($id)
            ->update($form_data);


            //expenses add to table

            $emp_expense = new Emp_expense();
            $emp_expense->employee_id = $request->input('empid');
            $emp_expense->cost = $request->input('cost');
            $emp_expense->expenses_type = 'Vehicle_Service&Repair';
            $emp_expense->month = $request->input('month');
            $emp_expense->status = '1';
            $emp_expense->create_by = Auth::id();
            $emp_expense->save();


           return response()->json(['success' => 'Vehicle Service is successfully Approved']);
          }  
    }

    public function reject(Request $request){

        $user = Auth::user();
       
       
        $permission =$user->can('Approve-Level-01');
        $permission =$user->can('Approve-Level-02');
        $permission =$user->can('Approve-Level-03');
    
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
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
            Vehicleservice::findOrFail($id)
            ->update($form_data);
    
            return response()->json(['success' => 'Vehicle Service is successfully Reject']);
    
    }

    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('Vehicleserviceandrepair-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Vehicleservice::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vehicleserviceandrepair');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Vehicleservice::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vehicleserviceandrepair');
        }

    }
}
