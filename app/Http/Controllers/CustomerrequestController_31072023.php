<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Customerrequest;
use App\customerrequestdetail;
use App\Customerbranch;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;


class CustomerrequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $customers = Customer::orderBy('id', 'asc')->whereIn('customers.status', [1])->get();
        $areas = Customerbranch::orderBy('id', 'asc')->whereIn('customerbranches.status', [1])->get();
        $titles = DB::table('job_titles')->select('job_titles.*')->get();
        $holidays = DB::table('holidays')->select('holidays.*')->get();
        $shifttypes = DB::table('shift_types')->select('shift_types.*')->get();

        return view('Customerrequest.customerrequest', compact('customers', 'areas', 'titles', 'holidays', 'shifttypes'));
    }

    public function insert(Request $request)
    {
        $user = Auth::user();
        $permission =$user->can('CustomerRequest-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $this->validate($request, [
            'customer' => 'required',
            'date' => 'required',
            'area' => 'required',
            'year' => 'required',
            'shift' => 'required',  
            'tableData' => 'required',
        ]);

        $customerrequest = new Customerrequest();
        $customerrequest->customer_id = $request->input('customer');
        $customerrequest->customerbranch_id = $request->input('area');
        $customerrequest->year = $request->input('year');
        $customerrequest->date = $request->input('date');
        $customerrequest->holiday_id = $request->input('holiday');
        $customerrequest->shift_id = $request->input('shift');
        $customerrequest->status = '1';
        $customerrequest->approve_status = '0';
        $customerrequest->approve_01 = '0';
        $customerrequest->approve_02 = '0';
        $customerrequest->approve_03 = '0';
        $customerrequest->status = '1';
        $customerrequest->create_by = Auth::id();
        $customerrequest->update_by = '0';
        $customerrequest->save();

        $requestID = $customerrequest->id;

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            $count = $rowtabledata['col_4'];
            $title = $rowtabledata['col_8'];

            $customerrequestdetail = new Customerrequestdetail();
            $customerrequestdetail->customerrequest_id = $requestID;
            $customerrequestdetail->job_title_id = $title;
            $customerrequestdetail->count = $count;
            $customerrequestdetail->status = '1';
            $customerrequestdetail->create_by = Auth::id();
            $customerrequestdetail->update_by = '0';
            $customerrequestdetail->save();
        }

        return response()->json(['status' => 1, 'message' => 'Customer Request is Successfully Created']);
    }

    public function displaycustomerrequest(Request $request)
    {

        $requests = DB::table('customerrequests')
                    ->join('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                    ->join('customers', 'customerrequests.customer_id', '=', 'customers.id')
                    ->join('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                    ->join('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                    ->select('customerrequests.*','customers.name','customerbranches.branch_name')
                    ->whereIn('customerrequests.status', [1, 2])
                    ->get();

        return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = Auth::user();

                $btn='';

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

                        $permission = $user->can('CustomerRequest-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    
                        $permission = $user->can('CustomerRequest-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('customerrequeststatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('customerrequeststatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                $permission = $user->can('CustomerRequest-delete');
                if($permission){
                    $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';  
                }
                 return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function delete(Request $request){
        $user = Auth::user();
      
        $permission =$user->can('CustomerRequest-delete');
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
        Customerrequest::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Customer Request is successfully Deleted']);

    }

    public function edit(Request $request){
            $user = Auth::user();
            $permission =$user->can('CustomerRequest-edit');
            if(!$permission) {
                    return response()->json(['error' => 'UnAuthorized'], 401);
                }

            $id = Request('id');
            if (request()->ajax()){
            $data = DB::table('customerrequests')
                        ->join('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
                        ->join('customers', 'customerrequests.customer_id', '=', 'customers.id')
                        ->join('customerbranches', 'customerrequests.customerbranch_id', '=', 'customerbranches.id')
                        ->join('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
                        ->select('customerrequests.*', 'customers.name', 'customerbranches.branch_name', 'customerrequestdetails.job_title_id', 'customerrequestdetails.count')
                        ->where('customerrequests.id', $id)
                        ->get(); 
            return response() ->json(['result'=> $data[0]]);
        }
    }

    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('CustomerRequest-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

        $this->validate($request, [
            'customer' => 'required',
            'date' => 'required',
            'area' => 'required',
            'year' => 'required',
            'shift' => 'required',
            'tableData' => 'required',
        ]);

        $hidden_id = $request->input('hidden_id');

        $customerrequest = Customerrequest::where('id', $hidden_id)->first();
        $customerrequest->customer_id = $request->input('customer');
        $customerrequest->customerbranch_id = $request->input('area');
        $customerrequest->year = $request->input('year');
        $customerrequest->date = $request->input('date');
        $customerrequest->holiday_id = $request->input('holiday');
        $customerrequest->shift_id = $request->input('shift');
        $customerrequest->status = '1';
        $customerrequest->allocationstatus = '0';
        $customerrequest->approve_status = '0';
        $customerrequest->approve_01 = '0';
        $customerrequest->approve_02 = '0';
        $customerrequest->approve_03 = '0';
        $customerrequest->status = '1';
        $customerrequest->create_by = Auth::id();
        $customerrequest->update_by = '0';
        $customerrequest->save();

        DB::table('customerrequestdetails')
        ->where('customerrequest_id', $hidden_id)
        ->delete();

        $tableData = $request->input('tableData');

        foreach ($tableData as $rowtabledata) {
            $count = $rowtabledata['col_4'];
            $title = $rowtabledata['col_8'];

            $customerrequestdetail = new Customerrequestdetail();
            $customerrequestdetail->customerrequest_id = $hidden_id;
            $customerrequestdetail->job_title_id = $title;
            $customerrequestdetail->count = $count;
            $customerrequestdetail->status = '1';
            $customerrequestdetail->create_by = Auth::id();
            $customerrequestdetail->update_by = '0';
            $customerrequestdetail->save();
        }

        return response()->json(['status' => 1, 'message' => 'Customer Request is Successfully Updated']);

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
            Customerrequest::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Customer Request is successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
           ->update($form_data);
  
            return response()->json(['success' => 'Customer Request is successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
            ->update($form_data);
   
           return response()->json(['success' => 'Customer Request is successfully Approved']);
          }
    }

    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('CustomerRequest-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('customerrequest');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Customerrequest::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('customerrequest');
        }

    }
    
}
