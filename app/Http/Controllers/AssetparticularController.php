<?php

namespace App\Http\Controllers;

use App\Assetparticular;
use App\Commen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;

class AssetparticularController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $assetcategories = DB::table('assetcategories')->select('assetcategories.*')
        ->whereIn('assetcategories.status', [1, 2])
        ->where('assetcategories.approve_status', 1)
        ->get();

        return view('Assetmanagement.assetparticular', compact('assetcategories','userPermissions'));
    }
    public function insert(Request $request){

        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetparticular-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 

        $tableData = $request->input('tableData');

    foreach ($tableData as $rowtabledata) {
        $name = $rowtabledata['col_1'];

        $storelist = new Assetparticular();
        $storelist->name = $name;
        $storelist->asset_category_id = $request->input('asset_category');
        $storelist->status = '1';
        $storelist->approve_status = '0';
        $storelist->approve_01 = '0';
        $storelist->approve_02 = '0';
        $storelist->approve_03 = '0';
        $storelist->create_by = Auth::id();
        $storelist->update_by = '0';
        $storelist->save();

    }
        return response()->json(['success' => 'Asset Particular is Successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('assetparticulars')
            ->leftjoin('assetcategories', 'assetparticulars.asset_category_id', '=', 'assetcategories.id')
            ->select('assetparticulars.*','assetcategories.asset_category AS asset_category')
            ->whereIn('assetparticulars.status', [1, 2])
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

                        if(in_array('Assetparticular-edit',$userPermissions)){
                            if($row->approve_status == 0 ){
                                $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>'; 
                            }
                        }

                        if(in_array('Assetparticular-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('assetparticularstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('assetparticularstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Assetparticular-delete',$userPermissions)){
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
            if (!in_array('Assetparticular-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('assetparticulars')
        ->select('assetparticulars.*')
        ->where('assetparticulars.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetparticular-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'name' => $request->name,
                'asset_category_id' => $request->asset_category,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Assetparticular::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Asset Particular is Successfully Updated']);
    }




    public function delete(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetparticular-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Assetparticular::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Asset Particular is Successfully Deleted']);

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
            Assetparticular::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Asset Particular is Successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Assetparticular::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Asset Particular is Successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Assetparticular::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Asset Particular is Successfully Approved']);
          }
    }




    public function status($id,$statusid){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetparticular-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Assetparticular::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('assetparticular');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Assetparticular::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('assetparticular');
        }

    }
}
