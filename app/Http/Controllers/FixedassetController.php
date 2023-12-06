<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Fixed_asset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;
use PDF;

class FixedassetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();

        $approvel01permission = 0;
        $approvel02permission = 0;
        $approvel03permission = 0;

        $listpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        $statuspermission = 0;
        

        if (in_array('Approve-Level-01', $userPermissions)) {
            $approvel01permission = 1;
        } 
        if (in_array('Approve-Level-02', $userPermissions)) {
            $approvel02permission = 1;
        } 
        if (in_array('Approve-Level-03', $userPermissions)) {
            $approvel03permission = 1;
        } 
        if (in_array('Fixedasset-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('Fixedasset-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('Fixedasset-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('Fixedasset-delete', $userPermissions)) {
            $deletepermission = 1;
        } 
        
        $assetcategories = DB::table('assetcategories')->select('assetcategories.*')
        ->whereIn('assetcategories.status', [1, 2])
        ->where('assetcategories.approve_status', 1)
        ->get();

        return view('Assetmanagement.fixedasset', compact('assetcategories','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    }
    public function insert(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Assetcategory-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $fixed_asset = new Fixed_asset();
        $fixed_asset->code = $request->input('code');
        $fixed_asset->asset_category_id = $request->input('asset_category');
        $fixed_asset->particular_id = $request->input('particular');
        $fixed_asset->employee_id = $request->input('employee');
        $fixed_asset->region = $request->input('region');
        $fixed_asset->department = $request->input('department');
        $fixed_asset->clientbranch = $request->input('clientbranch');
        $fixed_asset->opening_value = $request->input('opening_value');
        $fixed_asset->dateofpurchase = $request->input('dateofpurchase');
        $fixed_asset->rate = $request->input('rate');
        $fixed_asset->addition_deletion = $request->input('addition_deletion');
        $fixed_asset->closing_value = $request->input('closing_value');
        $fixed_asset->acc_dep_2022 = $request->input('acc_dep_2022');
        $fixed_asset->dep_2023 = $request->input('dep_2023');
        $fixed_asset->acc_dep_2023 = $request->input('acc_dep_2023');
        $fixed_asset->writtendown_2023 = $request->input('writtendown_2023');
        $fixed_asset->status = '1';
        $fixed_asset->approve_status = '0';
        $fixed_asset->approve_01 = '0';
        $fixed_asset->approve_02 = '0';
        $fixed_asset->approve_03 = '0';
        $fixed_asset->create_by = Auth::id();
        $fixed_asset->update_by = '0';
        $fixed_asset->save();
        return response()->json(['success' => 'Fixed Asset is Successfully Inserted']);
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

                        if(in_array('Fixedasset-edit',$userPermissions)){
                            if($row->approve_status == 0 ){
                                $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>'; 
                            }
                        }

                        if(in_array('Fixedasset-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('fixedassetstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('fixedassetstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Fixedasset-delete',$userPermissions)){
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
            if (!in_array('Fixedasset-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('fixed_assets')
        ->leftjoin('employees', 'fixed_assets.employee_id', '=', 'employees.id')
        ->select('fixed_assets.*','employees.id AS empid','employees.service_no','employees.emp_name_with_initial')
        ->where('fixed_assets.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }


    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Fixedasset-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'code' => $request->code,
                'asset_category_id' => $request->asset_category,
                'particular_id' => $request->particular,
                'employee_id' => $request->employee,
                'region' => $request->region,
                'department' => $request->department,
                'clientbranch' => $request->clientbranch,
                'opening_value' => $request->opening_value,
                'dateofpurchase' => $request->dateofpurchase,
                'rate' => $request->rate,
                'addition_deletion' => $request->addition_deletion,
                'closing_value' => $request->closing_value,
                'acc_dep_2022' => $request->acc_dep_2022,
                'dep_2023' => $request->dep_2023,
                'acc_dep_2023' => $request->acc_dep_2023,
                'writtendown_2023' => $request->writtendown_2023,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Fixed_asset::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Fixed Asset is Successfully Updated']);
    }




    public function delete(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Fixedasset-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Fixed_asset::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Fixed Asset is Successfully Deleted']);

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
            Fixed_asset::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Fixed Asset is Successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Fixed_asset::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Fixed Asset is Successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Fixed_asset::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Fixed Asset is Successfully Approved']);
          }
    }




    public function status($id,$statusid){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Fixedasset-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Fixed_asset::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('fixedasset');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Fixed_asset::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('fixedasset');
        }

    }

    public function getitemcode(Request $request){
        $id = Request('id');
        
        if (request()->ajax()){
        $data = DB::table('fixed_assets')
        ->select('fixed_assets.*')
        ->where('fixed_assets.asset_category_id', $id)
        ->get(); 

        $rowCount = count($data);
    
        if ($rowCount === 0) {
            $itemcode='0001';
        }
        else if($rowCount<10){
            $count='000'.($rowCount+1);
            $itemcode= $count;
        }
        else if($rowCount>9){
            $count='00'.($rowCount+1);
            $itemcode= $count;
        }
        else if($rowCount>99){
            $count='0'.($rowCount+1);
            $itemcode= $count;
        }
        else{
            $count=($rowCount+1);
            $itemcode= $count;
        }

        $data1 = DB::table('assetcategories')
        ->select('assetcategories.*')
        ->where('assetcategories.id', $id)
        ->get(); 
        $type = $data1[0]->asset_category;
        $fl='';
        if($type=="Office Equipments"){
            $fl="OFF_EQ";
        }else if($type=="Office Furniture"){
            $fl="OFF_FU";
        }
        else if($type=="Security Equipments"){
            $fl="SEC_EQ";
        }
        else if($type=="Computers"){
            $fl="COM";
        }
        else if($type=="Motor Vehicles"){
            $fl="MOT";
        }
        else if($type=="Land"){
            $fl="LAND";
        }

        $data2= $fl.$itemcode;
    
        return response() ->json(['result'=> $data2]);
    }
    }


public function getparticularfilter($categoryid)
{
    $particular = DB::table('assetparticulars')->select('assetparticulars.*')
    ->where('asset_category_id', '=', $categoryid)
    ->get();

    return response()->json($particular);
}

public function empdetails(Request $request){

$id = Request('id');
if (request()->ajax()){
$data = DB::table('employees')
->leftjoin('departments', 'employees.emp_department', '=', 'departments.id')
->leftjoin('subregions', 'employees.subregion_id', '=', 'subregions.id')
->leftjoin('customerbranches', 'subregions.id', '=', 'customerbranches.subregion_id')
->select('employees.*','departments.name AS departmentname','subregions.subregion AS subregionname','customerbranches.branch_name AS branchname')
->where('employees.id', $id)
->get(); 
return response() ->json(['result'=> $data[0]]);
}
}
}
