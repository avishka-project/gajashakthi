<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;

class Suppliercontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        return view('Supplier.supplier',compact('userPermissions'));
    }

    public function insert(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Supplier-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

        $supplier = new Supplier();
        $supplier->supplier_name = $request->input('supname');
        $supplier->address1 = $request->input('line_1');
        $supplier->address2 = $request->input('line_2');
        $supplier->city = $request->input('city');
        $supplier->payment_terms = $request->input('paymentterms');
        // $supplier->contact_no = $request->input('contact');
        // $supplier->email = $request->input('email');
        $supplier->status = '1';
        $supplier->approve_status = '0';
        $supplier->approve_01 = '0';
        $supplier->approve_02 = '0';
        $supplier->approve_03 = '0';
        $supplier->create_by = Auth::id();
        $supplier->update_by = '0';
        $supplier->save();
        return response()->json(['success' => 'Supplier is successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('suppliers')
            ->select('suppliers.*')
            ->whereIn('suppliers.status', [1, 2])
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

                        if(in_array('Supplier-edit',$userPermissions)){
                            if($row->approve_status == 0 ){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    }

                        $btn .= ' <a href="'.route('supcontact',['id' => $row->id]) .'" target="_self" class="btn btn-outline-info btn-sm mr-1 "><i class="fas fa-phone"></i></a>';

                        if(in_array('Supplier-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('supplierstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('supplierstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        if(in_array('Supplier-delete',$userPermissions)){
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
            if (!in_array('Supplier-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('suppliers')
        ->select('suppliers.*')
        ->where('suppliers.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Supplier-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'supplier_name' => $request->supname,
                'address1' => $request->line_1,
                'address2' => $request->line_2,
                'city' => $request->city,
                'payment_terms' => $request->paymentterms,
                // 'contact_no' => $request->contact,
                // 'email' => $request->email,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Supplier::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Supplier is Successfully Updated']);
    }




    public function delete(Request $request){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Supplier-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
            $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Supplier::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Supplier is successfully Deleted']);

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
            Supplier::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Supplier is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Supplier::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Supplier is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Supplier::findOrFail($id)
            ->update($form_data);

           return response()->json(['success' => 'Supplier is successfully Approved']);
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
            Supplier::findOrFail($id)
            ->update($form_data);
    
            return response()->json(['success' => 'Vehicle Type is successfully Reject']);
    
    }

    public function status($id,$statusid){

            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Supplier-status', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Supplier::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('supplier');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Supplier::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('supplier');
        }

    }
}
