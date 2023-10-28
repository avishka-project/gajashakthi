<?php

namespace App\Http\Controllers;

use App\Customerbranch;
use App\Customerrequest;
use App\empallocation;
use App\empallocationdetail;
use App\Subcustomer;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SpecialrequestapproveController extends Controller
{
    public function index()
    {
        return view('EmployeeAllocation.specialrequestapprove');
    }


    public function list(){

        $requests = DB::table('empallocations')
                    ->leftjoin('empallocationdetails', 'empallocations.id', '=', 'empallocationdetails.allocation_id')
                    ->leftjoin('customers', 'empallocations.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'empallocations.subcustomer_id', '=', 'subcustomers.id')
                    ->leftjoin('customerbranches', 'empallocations.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('shift_types', 'empallocations.shift_id', '=', 'shift_types.id')
                    ->select('empallocations.*','customers.name AS customername','subcustomers.sub_name AS subcustomer','customerbranches.branch_name AS branchname','shift_types.shift_name AS shiftname')
                    ->where('empallocations.specialrequest_approvestatus', 0)
                    ->where('empallocationdetails.specialrequest_approve_need', 1)
                    ->whereIn('empallocations.status', [1, 2])
                    ->groupBy('empallocations.id')
                    ->get();

            return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = Auth::user();

                $btn='';

                $permission = $user->can('Approve-Level-01');
                if($permission){
                    if($row->specialrequest_approve01 == 0){
                        $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-outline-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                    }
                }
                $permission = $user->can('Approve-Level-02');
                if($permission){
                    if($row->specialrequest_approve01 == 1 && $row->specialrequest_approve02 == 0){
                        $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-outline-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                    }
                }
                $permission = $user->can('Approve-Level-03');
                if($permission){
                    if($row->specialrequest_approve02 == 1 && $row->specialrequest_approve03 == 0 ){
                        $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-outline-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                    }
                }

                 return $btn;
            })
           
            ->rawColumns(['action'])
            ->make(true);
    }


    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('allocation-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $id = Request('id');
                $data = DB::table('empallocations')
                    ->leftjoin('customers', 'empallocations.customer_id', '=', 'customers.id')
                    ->leftjoin('subcustomers', 'empallocations.subcustomer_id', '=', 'subcustomers.id')
                    ->leftjoin('customerbranches', 'empallocations.customerbranch_id', '=', 'customerbranches.id')
                    ->leftjoin('shift_types', 'empallocations.shift_id', '=', 'shift_types.id')
                    ->leftjoin('holiday_types', 'empallocations.holiday_id', '=', 'holiday_types.id')
                    ->leftjoin('subregions','empallocations.subregion_id','=','subregions.id')
                    ->select('empallocations.*', 'customers.name', 'customerbranches.branch_name','subcustomers.sub_name','shift_types.shift_name','holiday_types.name AS holiday','subregions.subregion AS subregion')
                    ->where('empallocations.id', $id)
                    ->get(); 

                    $requestlist = $this->reqestcountlist($id); 
                    $detaillist = $this->allocationdetailelist($id); 

                    $responseData = array(
                        'mainData' => $data[0],
                        'requestdata' => $requestlist,
                        'detaildata' => $detaillist
                    );

        return response() ->json(['result'=>  $responseData]);
      

    }

    private function reqestcountlist($id){

         $recordID =$id ;
        $data = DB::table('customerrequests')
        ->leftjoin('customerrequestdetails', 'customerrequestdetails.customerrequest_id', '=', 'customerrequests.id')
        ->leftjoin('empallocations', 'customerrequests.id', '=', 'empallocations.request_id')
        ->leftjoin('job_titles', 'customerrequestdetails.job_title_id', '=', 'job_titles.id')
        ->select('customerrequests.*', 'job_titles.title','customerrequestdetails.count')
        ->where('empallocations.id', $recordID)
        ->where('customerrequestdetails.status', 1)
        ->get(); 


        $htmlTable = '';
        foreach ($data as $row) {
           
            $htmlTable .= '<tr>';
            $htmlTable .= '<td>' . $row->title . '</td>'; 
            $htmlTable .= '<td>' . $row->count . '</td>'; 
            $htmlTable .= '</tr>';
        }

        return $htmlTable;

    }

    private function allocationdetailelist($id){

        $recordID =$id ;
        $data = DB::table('empallocationdetails')
        ->leftjoin('empallocations', 'empallocationdetails.allocation_id', '=', 'empallocations.id')
        ->leftjoin('job_titles', 'empallocationdetails.assigndesignation_id', '=', 'job_titles.id')
        ->leftjoin('employees','empallocationdetails.emp_id','=','employees.id')
        ->leftjoin('subregions','employees.subregion_id','=','subregions.id')
        ->select('empallocationdetails.*', 'job_titles.title','employees.emp_fullname','subregions.subregion')
        ->where('empallocationdetails.allocation_id', $recordID)
        ->where('empallocationdetails.status', 1)
        ->get(); 


        $html = '';
        foreach ($data as $row) {
           
            $html .= '<tr>';
            $html .= '<td>' . $row->emp_fullname . '</td>'; 
            $html .= '<td>' . $row->title . '</td>'; 
            $html .= '<td>' . $row->subregion. '</td>'; 
            $html .= '</tr>';
        }

        return $html;

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
                'specialrequest_approve01' =>  '1',
                'specialrequest_approve01_time' => $current_date_time,
                'specialrequest_approve01_by' => Auth::id(),
            );
            empallocation::findOrFail($id)
            ->update($form_data);
    
            return response()->json(['success' => 'Special Request is successfully Approved']);
    
         }elseif($applevel == 2){
            $form_data = array(
                'specialrequest_approve02' =>  '1',
                'specialrequest_approve02_time' => $current_date_time,
                'specialrequest_approve02_by' => Auth::id(),
            );
            empallocation::findOrFail($id)
           ->update($form_data);
    
            return response()->json(['success' => 'Special Request is successfully Approved']);
    
         }else{
            $form_data = array(
                'specialrequest_approvestatus' =>  '1',
                'specialrequest_approve03' =>  '1',
                'specialrequest_approve03_time' => $current_date_time,
                'specialrequest_approve03_by' => Auth::id(),
            );
            empallocation::findOrFail($id)
            ->update($form_data);
    
           return response()->json(['success' => 'Special Request is successfully Approved']);
          }
    }

}
