<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Inventorylist;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;

class InventorylistController extends Controller
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
        if (in_array('InventoryList-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('InventoryList-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('InventoryList-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('InventoryList-delete', $userPermissions)) {
            $deletepermission = 1;
        } 
        

        $inventorytypes = DB::table('inventorytypes')->select('inventorytypes.*')
        ->whereIn('inventorytypes.status', [1, 2])
        ->where('inventorytypes.approve_status', 1)
        ->get();

        return view('Inventory.inventorylist',compact('inventorytypes','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    }
    public function insert(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('InventoryList-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            $itemcode = $rowtabledata['col_1'];
            $name = $rowtabledata['col_2'];
            $inventorytype = $rowtabledata['col_9'];
            $uom = $rowtabledata['col_4'];
            $spec = $rowtabledata['col_5'];
            $reorderlevel = $rowtabledata['col_6'];
            $reorderqty = $rowtabledata['col_7'];
            $remark = $rowtabledata['col_8'];
            $uniformsize = $rowtabledata['col_10'];
    

        $inventorylist = new Inventorylist();
        $inventorylist->inventorylist_id = $itemcode;
        $inventorylist->name = $name;
        $inventorylist->inventory_type_id = $inventorytype;
        $inventorylist->uom = $uom;
        $inventorylist->specification = $spec;
        $inventorylist->re_order_level = $reorderlevel;
        $inventorylist->re_order_quantity = $reorderqty;
        $inventorylist->remarks = $remark;
        $inventorylist->uniform_size = $uniformsize;
        $inventorylist->status = '1';
        $inventorylist->approve_status = '0';
        $inventorylist->approve_01 = '0';
        $inventorylist->approve_02 = '0';
        $inventorylist->approve_03 = '0';
        $inventorylist->create_by = Auth::id();
        $inventorylist->update_by = '0';
        $inventorylist->save();
        }
        return response()->json(['success' => 'Inventory List is Successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('inventorytypes')
            ->select('inventorytypes.*')
            ->whereIn('inventorytypes.status', [1, 2])
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

                        if(in_array('InventoryList-edit',$userPermissions)){
                            if($row->approve_status == 0 ){
                                $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>'; 
                            }
                        }

                        if(in_array('InventoryList-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('inventoryliststatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('inventoryliststatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('InventoryList-delete',$userPermissions)){
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
            if (!in_array('InventoryList-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('inventorylists')
        ->select('inventorylists.*')
        ->where('inventorylists.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('InventoryList-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'inventory_type_id' => $request->inventorytype,
                'uom' => $request->uom,
                'uniform_size' => $request->uniformsize,
                'inventorylist_id' => $request->itemcode,
                'name' => $request->name,
                'specification' => $request->spec,
                're_order_level' => $request->recorderlevel,
                're_order_quantity' => $request->recoderqty,
                'remarks' => $request->remark,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Inventorylist::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Inventory List is Successfully Updated']);
    }




    public function delete(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('InventoryList-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Inventorylist::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Inventory List is Successfully Deleted']);

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
            Inventorylist::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Inventory List is Successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Inventorylist::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Inventory List is Successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Inventorylist::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Inventory List is Successfully Approved']);
          }
    }




    public function status($id,$statusid){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('InventoryList-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Inventorylist::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('inventorylist');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Inventorylist::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('inventorylist');
        }

    }

    public function getitemcode(Request $request){
        $id = Request('id');
        
        if (request()->ajax()){
        $data = DB::table('inventorylists')
        ->select('inventorylists.*')
        ->where('inventorylists.inventory_type_id', $id)
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

        $data1 = DB::table('inventorytypes')
        ->select('inventorytypes.*')
        ->where('inventorytypes.id', $id)
        ->get(); 
        $type = $data1[0]->inventory_type;
        $fl='';
        if($type=="Uniforms"){
            $fl="UN";
        }else if($type=="Used Uniforms"){
            $fl="UU";
        }
        else if($type=="Barrack Items"){
            $fl="BI";
        }
        else if($type=="Stationary"){
            $fl="ST";
        }
        else if($type=="IT Accessories"){
            $fl="IT";
        }
        else if($type=="Staff welfare"){
            $fl="SW";
        }
        else if($type=="Office Supplies"){
            $fl="OS";
        }
        else if($type=="Meals"){
            $fl="ML";
        }
        else if($type=="Vehicle Spare parts"){
            $fl="VS";
        }

        $data2= $fl.$itemcode;
    
        return response() ->json(['result'=> $data2]);
    }
    }
}
