<?php

namespace App\Http\Controllers;

use App\Customer;
use App\customercontact;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Facades\Datatables;
use Illuminate\Support\Facades\Gate;

class Customercontactcontroller extends Controller
{
    public function index($id)
    {

        return view('Customers.customercontact' ,compact('id'));
    }

    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('customer-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
    
        $user = Auth::user();

        $contact = new customercontact();
        $contact->name = $request->input('conname');
        $contact->contact = $request->input('contact');
        $contact->designation = $request->input('designation');
        $contact->email = $request->input('email');
        $contact->customer_id = $request->input('cusid');
        $contact->status = '1';
        $contact->approve_01 = '0';
        $contact->approve_02 = '0';
        $contact->approve_03 = '0';
        $contact->create_by = Auth::id();
        $contact->update_by = '0';
        $contact->save();
        return response()->json(['success' => 'Customer Contact is successfully Inserted']);
    }


    public function list(Request $request)
    {

        $cus_id = $request->cus_id;
        $types = DB::table('customercontacts')
            ->select('customercontacts.*')
            ->where('customercontacts.status',1)
            ->where('customercontacts.customer_id', $cus_id)
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '';
                $user = Auth::user();
                       
                        $permission = $user->can('customer-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
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
        $data = DB::table('customercontacts')
        ->select('customercontacts.*')
        ->where('customercontacts.id', $id)
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
                'name' => $request->conname,
                'contact' => $request->contact,
                'designation' => $request->designation,
                'email' => $request->email,
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            customercontact::findOrFail($id)
        ->update($form_data);
        
        return response()->json(['success' => 'Customer Contact is Successfully Updated']);
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
        customercontact::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Customer Contact is successfully Deleted']);

    }

}
