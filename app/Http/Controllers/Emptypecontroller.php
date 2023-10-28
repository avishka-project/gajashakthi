<?php

namespace App\Http\Controllers;

use App\EmpType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Gate;

class Emptypecontroller extends Controller
{
    public function index()
    {
        return view('masterfiles.emptype');

    }

    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('emptype-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
    
        $user = Auth::user();

        $emptype = new Emptype();
        $emptype->emptype = $request->input('emptype');
        $emptype->status = '1';
        $emptype->approve_status = '0';
        $emptype->approve_01 = '0';
        $emptype->approve_02 = '0';
        $emptype->approve_03 = '0';
        $emptype->create_by = Auth::id();
        $emptype->update_by = '0';
        $emptype->save();
        return response()->json(['success' => 'Employee Type is successfully Inserted']);
    }


    public function typelist()
    {
        $types = DB::table('emptypes')
            ->select('emptypes.*')
            ->whereIn('emptypes.status', [1, 2])
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $user = Auth::user();
                        $permission = $user->can('Approve-Level-01');
                        if($permission){
                            if($row->approve_01 == 0){
                                $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        $permission = $user->can('Approve-Level-02');
                        if($permission){
                            if($row->approve_01 == 1 && $row->approve_02 == 0){
                                $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }
                        $permission = $user->can('Approve-Level-03');
                        if($permission){
                            if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                                $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                            }
                        }

                        $permission = $user->can('emptype-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                    $permission = $user->can('emptype-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('typestatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('typestatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('emptype-delete');
                        if($permission){
                            $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm "><i class="far fa-trash-alt"></i></button>';
                        }
              
                return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }

    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('emptype-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('emptypes')
        ->select('emptypes.*')
        ->where('emptypes.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }
    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('emptype-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'emptype' => $request->emptype,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Emptype::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Employee Type is Successfully Updated']);
    }

    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('emptype-delete');
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
        Emptype::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employee Type is successfully Deleted']);

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
            Emptype::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Employee Type is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Emptype::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Employee Type is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Emptype::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Employee Type is successfully Approved']);
          }
        

    }

    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('emptype-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Emptype::findOrFail($id)
            ->update($form_data);
    
            return view('masterfiles.emptype');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Emptype::findOrFail($id)
            ->update($form_data);
    
            return view('masterfiles.emptype');
        }

    }

}
