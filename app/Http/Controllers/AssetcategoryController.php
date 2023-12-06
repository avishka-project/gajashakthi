<?php

namespace App\Http\Controllers;

use App\Assetcategory;
use App\Commen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;

class AssetcategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        return view('Assetmanagement.assetcategory',compact('userPermissions'));
    }
    public function insert(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetcategory-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $pettycashcategory = new Assetcategory();
        $pettycashcategory->asset_category = $request->input('category');
        $pettycashcategory->status = '1';
        $pettycashcategory->approve_status = '0';
        $pettycashcategory->approve_01 = '0';
        $pettycashcategory->approve_02 = '0';
        $pettycashcategory->approve_03 = '0';
        $pettycashcategory->create_by = Auth::id();
        $pettycashcategory->update_by = '0';
        $pettycashcategory->save();
        return response()->json(['success' => 'Asset Category is Successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('assetcategories')
            ->select('assetcategories.*')
            ->whereIn('assetcategories.status', [1, 2])
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

                        if(in_array('Assetcategory-edit',$userPermissions)){
                            if($row->approve_status == 0 ){
                                $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>'; 
                            }
                        }

                        if(in_array('Assetcategory-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('assetcategorystatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('assetcategorystatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Assetcategory-delete',$userPermissions)){
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
            if (!in_array('Assetcategory-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('assetcategories')
        ->select('assetcategories.*')
        ->where('assetcategories.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetcategory-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'asset_category' => $request->category,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Assetcategory::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Asset Category is Successfully Updated']);
    }




    public function delete(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetcategory-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Assetcategory::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Asset Category is Successfully Deleted']);

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
            Assetcategory::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Asset Category is Successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Assetcategory::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Asset Category is Successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Assetcategory::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Asset Category is Successfully Approved']);
          }
    }




    public function status($id,$statusid){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetcategory-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Assetcategory::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('assetcategory');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Assetcategory::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('assetcategory');
        }

    }
}
