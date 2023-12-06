<?php

namespace App\Http\Controllers;

use App\Commen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vat;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;
use Log;

class VatController extends Controller
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
        if (in_array('Vat-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('Vat-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('Vat-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('Vat-delete', $userPermissions)) {
            $deletepermission = 1;
        } 
        

        return view('vat.vat',compact('approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    }
    public function insert(Request $request){
        
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vat-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            } 

        // If no existing data, proceed with inserting new data
        $vat = new Vat();
        $vat->fromdate = $request->input('fromdate');
        $vat->vat = $request->input('vat');
        $vat->tax = $request->input('tax');
        $vat->nbt = $request->input('nbt');
        $vat->sscl = $request->input('sscl');
        $vat->status = '1';
        $vat->approve_status = '0';
        $vat->approve_01 = '0';
        $vat->approve_02 = '0';
        $vat->approve_03 = '0';
        $vat->create_by = Auth::id();
        $vat->update_by = '0';
        $vat->save();

        return response()->json(['success' => 'Vat is Successfully Inserted']);

    }

    public function requestlist()
    {
        $types = DB::table('vehicletypes')
            ->select('vehicletypes.*')
            ->whereIn('vehicletypes.status', [1, 2])
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

                        if(in_array('Vat-edit',$userPermissions) && $row->approve_status != 2){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        if(in_array('Vat-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('vehicletypestatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('vehicletypestatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Vat-delete',$userPermissions)){
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
        if (!in_array('Vat-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('vats')
        ->select('vats.*')
        ->where('vats.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Vat-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;


        $form_data = array(
                'fromdate' => $request->fromdate,
                'vat' => $request->vat,
                'tax' => $request->tax,
                'nbt' => $request->nbt,
                'sscl' => $request->sscl,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Vat::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Vat is Successfully Updated']);
    }




    public function delete(Request $request){

        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Vat-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } 
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Vat::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Vat is Successfully Deleted']);

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
            Vat::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Vat is Successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Vat::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Vat is Successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Vat::findOrFail($id)
            ->update($form_data);


            $existingData = Vat::count();

            if ($existingData > 0) {
                $fromDate = $request->input('fromdate');
                $carbonDate = Carbon::parse($fromDate);
                $previousDay = $carbonDate->subDay();
            
                $previousRecord = Vat::where('id', '<', $id)->whereIn('status', [1, 2])->orderBy('id', 'desc')->first();
            
                if ($previousRecord) {
                    $previousRecord->todate = $previousDay->toDateString();
                    $previousRecord->save();
                }
            }

           return response()->json(['success' => 'Vat is Successfully Approved']);
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
            Vat::findOrFail($id)
            ->update($form_data);
    
            return response()->json(['success' => 'Vat is successfully Reject']);
    
    }


    public function status($id,$statusid){
       
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Vat-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            } 


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Vat::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vat');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Vat::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('vat');
        }

    }
}
