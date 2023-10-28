<?php

namespace App\Http\Controllers;

use App\Regionalmanager;
use App\SubRegionalmanager;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class SubRegionalmanagerController extends Controller
{
    public function index($id)
    {
        $employees = DB::table('employees')
        ->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])
        ->get();
        return view('masterfiles.subregionalmanagers', compact('id','employees') );
    }

    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('Sub-Region-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

            $region = $request->input('region');
                $managersids = DB::table('sub_regionalmanagers')
                    ->where('subregion_id', $region)
                    ->pluck('subregion_id') 
                    ->toArray();
            
            if (!empty($managersids)) {
                SubRegionalmanager::where('subregion_id', $region)
                    ->update(['assign_status' => '2']);
            }else{}

            
            
            if(!empty($request->input('todate'))){
                $todate = $request->input('todate');
            }else{
                $todate = '0';
            }
        
       $manager = new SubRegionalmanager();
       $manager->subregion_id = $region;
       $manager->emp_id = $request->input('manager');
       $manager->fromdate = $request->input('fromdate');
       $manager->todate = $todate;
       $manager->assign_status = '1';
       $manager->status = '1';
       $manager->approve_01 = '0';
       $manager->approve_02 = '0';
       $manager->approve_03 = '0';
       $manager->create_by = Auth::id();
       $manager->update_by = '0';
       $manager->save();

        return response()->json(['success' => 'Sub Regional Manager is successfully Inserted']);
    }

    
    public function list(Request $request)
    {

        $cus_id = $request->region_id;
        $types = DB::table('sub_regionalmanagers')
            ->leftjoin('employees','sub_regionalmanagers.emp_id','=','employees.id')
            ->select('sub_regionalmanagers.*' , 'employees.emp_fullname AS employeename')
            ->where('sub_regionalmanagers.status',1)
            ->where('sub_regionalmanagers.subregion_id', $cus_id)
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
                        $permission = $user->can('Sub-Region-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        $permission = $user->can('Sub-Region-create');
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
        $permission =$user->can('Sub-Region-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('sub_regionalmanagers')
        ->select('sub_regionalmanagers.*')
        ->where('sub_regionalmanagers.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }

    }


    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('Sub-Region-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'emp_id' => $request->manager,
                'fromdate' => $request->fromdate,
                'todate' => $request->todate,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            SubRegionalmanager::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Sub Regional Manager is Successfully Updated']);
    }




    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('Sub-Region-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'delete_status' =>  '1',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        SubRegionalmanager::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Sub Regional Manager is successfully Deleted']);

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
            SubRegionalmanager::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Sub Region Manager is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            SubRegionalmanager::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Sub Region Manager is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            SubRegionalmanager::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Sub Region Manager is successfully Approved']);
          }
    }
}
