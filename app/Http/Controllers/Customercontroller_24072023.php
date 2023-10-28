<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customercategory;
use App\customercontact;
use App\Subregion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Gate;

class Customercontroller extends Controller
{
    public function index()
    {
        $subregions = Subregion::orderBy('id', 'asc')
        ->whereIn('subregions.status', [1, 2])
        ->where('subregions.approve_status', 1)
        ->get();

        $category = Customercategory::orderBy('id', 'asc')
        ->whereIn('customercategories.status', [1, 2])
        ->where('customercategories.approve_status', 1)
        ->get();

        return view('Customers.customer' , compact('subregions' ,'category'));
    }

    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('customer-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
    
        $user = Auth::user();

        $customer = new Customer();
        $customer->name = $request->input('cusname');
        $customer->address1 = $request->input('line_1');
        $customer->address2 = $request->input('line_2');
        $customer->city = $request->input('line_3');
        $customer->subregion_id = $request->input('subregion_id');
        $customer->category_id = $request->input('category_id');
        $customer->pay_by = $request->input('payby');
        $customer->approve_01 = '0';
        $customer->approve_02 = '0';
        $customer->approve_03 = '0';
        $customer->status = '1';
        $customer->create_by = Auth::id();
        $customer->update_by = '0';
        $customer->save();
        return response()->json(['success' => 'Customer is successfully Inserted']);
    }


    public function list()
    {
        $types = DB::table('customers')
            ->join('subregions','customers.subregion_id','=','subregions.id')
            ->join('customercategories','customers.category_id','=','customercategories.id')
            ->select('customers.*' ,'subregions.subregion' ,'customercategories.category')
            ->whereIn('customers.status', [1, 2])
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('payby', function ($row) {
                $label = '';
               if($row->pay_by == 1 ){
                $label .= 'Company';

               }else{
                $label .= 'Branch';
               }

              
                return $label;
            })
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

                        $permission = $user->can('customer-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }

                        $btn .= ' <a href="'.route('cuscontact',['id' => $row->id]) .'" target="_self" class="btn btn-outline-info btn-sm mr-1 "><i class="fas fa-phone"></i></a>';
                    
                        $permission = $user->can('customer-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('customerstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('customerstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('customer-delete');
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
        $permission =$user->can('customer-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('customers')
        ->select('customers.*')
        ->where('customers.id', $id)
        ->get(); 
        return response() ->json(['result'=> $data[0]]);
    }
    }




    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('customer-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $id =  $request->hidden_id ;
        $form_data = array(
                'name' => $request->cusname,
                'address1' => $request->line_1,
                'address2' => $request->line_2,
                'city' => $request->line_3,
                'subregion_id' => $request->subregion_id,
                'category_id' => $request->category_id,
                'pay_by' => $request->payby,
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Customer::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Customer is Successfully Updated']);
    }




    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('customer-delete');
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
        Customer::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Customer is successfully Deleted']);

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
            Customer::findOrFail($id)
            ->update($form_data);

            // approve contact details
            customercontact::where('customer_id', $id)
            ->update([
                'approve_01' =>  '1',
                'approve_01_time' => $current_date_time,
                'approve_01_by' => Auth::id(),
            ]);

            return response()->json(['success' => 'Customer is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Customer::findOrFail($id)
           ->update($form_data);

              // approve contact details
              customercontact::where('customer_id', $id)
              ->update([
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
              ]);
  
            return response()->json(['success' => 'Customer is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Customer::findOrFail($id)
            ->update($form_data);

               // approve contact details
               customercontact::where('customer_id', $id)
               ->update([
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
               ]);
   
           return response()->json(['success' => 'Customer is successfully Approved']);
          }


    }




    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('customer-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Customer::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('customers');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Customer::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('customers');
        }

    }
}
