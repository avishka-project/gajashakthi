<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Customerbranch;
use App\Customerrequest;
use App\empallocation;
use App\empallocationdetail;
use App\empallocationdetails_duplicate;
use App\Employeetransfer;
use App\Employeetransfer_detail;
use App\Subcustomer;
use App\Subregion;
use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Gate;

class EmpallocationController extends Controller
{
    public function index()
    {

      

        $subregion = Subregion::orderBy('id', 'asc')
        ->whereIn('subregions.status', [1, 2])
        ->where('subregions.approve_status', 1)
        ->get();
       
        return view('EmployeeAllocation.employeetransfer'  ,compact('subregion'));
    }


    public function insert(Request $request){
 

        $user = Auth::user();
        $permission =$user->can('Employee-Transfer-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $from_date = $request->input('from_date');
            $to_date = $request->input('to_date');
            $subregionto = $request->input('subregionto');
            

            $emptransfer = new Employeetransfer();
            $emptransfer->subregion_id_to =$subregionto ;
            $emptransfer->from_date = $from_date;
            $emptransfer->to_date = $to_date;
            $emptransfer->status = '1';
            $emptransfer->approve_status = '0';
            $emptransfer->approve_01 = '0';
            $emptransfer->approve_02 = '0';
            $emptransfer->approve_03 = '0';
            $emptransfer->delete_status = '0';
            $emptransfer->create_by = Auth::id();
            $emptransfer->update_by = '0';
            $emptransfer->save();
    
            $requestID = $emptransfer->id;
    
            $tableData = $request->input('tableData');
            foreach ($tableData as $rowtabledata) {
                $subregion_from = $rowtabledata['col_3'];
                $empID = $rowtabledata['col_4'];
                $service_no = $rowtabledata['col_5'];
                $empsubregion = $rowtabledata['col_6'];
    
                $transferdetail = new Employeetransfer_detail();
                $transferdetail->transfer_id = $requestID;
                $transferdetail->emp_id = $empID;
                $transferdetail->service_no = $service_no;
                $transferdetail->emp_subregion_from = $subregion_from;
                $transferdetail->status = '1';
                $transferdetail->delete_status = '0';
                $transferdetail->create_by = Auth::id();
                $transferdetail->update_by = '0';
                $transferdetail->save();
            }

            return response()->json(['status' => 1, 'message' => 'Employee Transfer is Successfully Created']);
    }



    public function displaytransferlist()
    {

        $requests = DB::table('employeetransfers')               
        ->leftjoin('subregions', 'employeetransfers.subregion_id_to', '=', 'subregions.id')
        ->leftjoin('employeetransfer_details', 'employeetransfers.id', '=', 'employeetransfer_details.transfer_id')
        ->select(
            'employeetransfers.*',
            'subregions.subregion AS transferregion',
            DB::raw('COUNT(employeetransfer_details.id) AS details_count')
        )
        ->whereIn('employeetransfers.status', [1, 2])
        ->where('employeetransfer_details.status', 1)
        ->groupBy('employeetransfers.id')
        ->get();

        return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $user = Auth::user();

                $btn='';

                $permission = $user->can('Approve-Level-01');
                if($permission){
                    if($row->approve_01 == 0){
                        $btn .= ' <button name="appL1" id="'.$row->id.'" class="appL1 btn btn-danger btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                    }
                }
                $permission = $user->can('Approve-Level-02');
                if($permission){
                    if($row->approve_01 == 1 && $row->approve_02 == 0){
                        $btn .= ' <button name="appL2" id="'.$row->id.'" class="appL2 btn btn-warning btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                    }
                }
                $permission = $user->can('Approve-Level-03');
                if($permission){
                    if($row->approve_02 == 1 && $row->approve_03 == 0 ){
                        $btn .= ' <button name="appL3" id="'.$row->id.'" class="appL3 btn btn-info btn-sm" type="submit"><i class="fas fa-level-up-alt"></i></button>';
                    }
                }

                        $permission = $user->can('Employee-Transfer-edit');
                        if($permission){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    
                        $permission = $user->can('Employee-Transfer-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('emptransferstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('emptransferstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                $permission = $user->can('Employee-Transfer-delete');
                if($permission){
                    $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-danger btn-sm"><i class="far fa-trash-alt"></i></button>';  
                }
                 return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }



    public function edit(Request $request){
        $user = Auth::user();
        $permission =$user->can('Employee-Transfer-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
            $id = Request('id');
                $data = DB::table('employeetransfers')
                    ->select('employeetransfers.*')
                    ->where('employeetransfers.id', $id)
                    ->get(); 

                    $detaillist = $this->allocationdetailelist($id); 

                    $responseData = array(
                        'mainData' => $data[0],
                        'detaildata' => $detaillist
                    );

        return response() ->json(['result'=>  $responseData]);
      

    }

    private function allocationdetailelist($id){

        $recordID =$id ;
        $data = DB::table('employeetransfer_details')
        ->leftjoin('employees', 'employeetransfer_details.emp_id', '=', 'employees.id')
        ->leftjoin('subregions', 'employeetransfer_details.emp_subregion_from', '=', 'subregions.id')
        ->select('employeetransfer_details.*', 'subregions.subregion AS empsubregion','employees.emp_fullname AS empname')
        ->where('employeetransfer_details.transfer_id', $recordID)
        ->where('employeetransfer_details.status', 1)
        ->get(); 


        $html = '';
        foreach ($data as $row) {
           
            $html .= '<tr>';
            $html .= '<td>' . $row->empname . '</td>'; 
            $html .= '<td>' . $row->empsubregion . '</td>'; 
            $html .= '<td class="d-none">' . $row->emp_subregion_from. '</td>'; 
            $html .= '<td class="d-none">' . $row->emp_id. '</td>'; 
            $html .= '<td class="d-none">' . $row->service_no. '</td>'; 
            $html .= '<td class="d-none">' . $row->emp_subregion_from. '</td>'; 
            $html .= '<td class="d-none">' . $row->transfer_id. '</td>'; 
            $html .= '<td class="d-none">' . $row->id. '</td>'; 
            $html .= '<td class="d-none"><input type ="hidden" id ="hiddenid" name="hiddenid" value="'.$row->id.'"></td>'; 
            $html .= '<td id ="actionrow">
          <button type="button" id="'.$row->id.'" class="btnDeletelist btn btn-danger btn-sm " >
            <i class="fas fa-trash-alt"></i>
          </button></td>'; 
        
            $html .= '</tr>';
        }

        return $html;

    }


    public function deletelist(Request $request){
        $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'delete_status' =>  '1',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Employeetransfer_detail::findOrFail($id)
        ->update($form_data);
    
        return response()->json(['success' => 'Employee Transfer list is successfully Deleted']);
    }



public function update(Request $request){
    $user = Auth::user();
   
    $permission =$user->can('Employee-Transfer-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
   
        $current_date_time = Carbon::now()->toDateTimeString();

        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $subregionto = $request->input('subregionto');
        $id = $request->input('hidden_id');
        $tableData = $request->input('tableData');

        $emptransfer = Employeetransfer::where('id', $id)->first();
        $emptransfer->subregion_id_to =$subregionto ;
        $emptransfer->from_date = $from_date;
        $emptransfer->to_date = $to_date;
        $emptransfer->approve_status = '0';
        $emptransfer->approve_01 = '0';
        $emptransfer->approve_02 = '0';
        $emptransfer->approve_03 = '0';
        $emptransfer->update_by = Auth::id();
        $emptransfer->updated_at = $current_date_time;
        $emptransfer->save();
     

    foreach ($tableData as $rowtabledata) {
        if(isset($rowtabledata['col_7'])){


            $subregion_from = $rowtabledata['col_3'];
            $empID = $rowtabledata['col_4'];
            $service_no = $rowtabledata['col_5'];
            $empsubregion = $rowtabledata['col_6'];
            $detailID = $rowtabledata['col_7'];
            $rowid = $rowtabledata['col_8'];
          

                $transferdetail = Employeetransfer_detail::where('id', $rowid)->first();
                $transferdetail->emp_id = $empID;
                $transferdetail->service_no = $service_no;
                $transferdetail->emp_subregion_from = $subregion_from;
                $transferdetail->update_by = Auth::id();
                $transferdetail->save();
            
        }  else {
            
            $subregion_from = $rowtabledata['col_3'];
                $empID = $rowtabledata['col_4'];
                $service_no = $rowtabledata['col_5'];
                $empsubregion = $rowtabledata['col_6'];
                    
                $transferdetail = new Employeetransfer_detail();
                $transferdetail->transfer_id = $id;
                $transferdetail->emp_id = $empID;
                $transferdetail->service_no = $service_no;
                $transferdetail->emp_subregion_from = $subregion_from;
                $transferdetail->status = '1';
                $transferdetail->delete_status = '0';
                $transferdetail->create_by = Auth::id();
                $transferdetail->update_by = '0';
                $transferdetail->save();
          }
    }


    return response()->json(['status' => 1, 'message' => 'Employee Transfer is Successfully Updated']);

}


public function delete(Request $request){
    $user = Auth::user();
  
    $permission =$user->can('Employee-Transfer-delete');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
    
    $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'delete_status' =>  '1',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Employeetransfer::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'Employee Transfer is successfully Deleted']);

}



public function status($id,$statusid){
    $user = Auth::user();
   
   
    $permission =$user->can('Employee-Transfer-status');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }


    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Employeetransfer::findOrFail($id)
        ->update($form_data);

        return redirect()->route('allocation');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Employeetransfer::findOrFail($id)
        ->update($form_data);

        return redirect()->route('allocation');
    }

}



public function approvedetails(Request $request){
    $user = Auth::user();
    $permission =$user->can('Employee-Transfer-edit');
    if(!$permission) {
            return response()->json(['error' => 'UnAuthorized'], 401);
        }
        $id = Request('id');
            $data = DB::table('employeetransfers')
                ->select('employeetransfers.*')
                ->where('employeetransfers.id', $id)
                ->get(); 

                $detaillist = $this->approvedetailslist($id); 

                $responseData = array(
                    'mainData' => $data[0],
                    'detaildata' => $detaillist
                );

    return response() ->json(['result'=>  $responseData]);
  

}

private function approvedetailslist($id){

    $recordID =$id ;
    $data = DB::table('employeetransfer_details')
    ->leftjoin('employees', 'employeetransfer_details.emp_id', '=', 'employees.id')
    ->leftjoin('subregions', 'employeetransfer_details.emp_subregion_from', '=', 'subregions.id')
    ->select('employeetransfer_details.*', 'subregions.subregion AS empsubregion','employees.emp_fullname AS empname')
    ->where('employeetransfer_details.transfer_id', $recordID)
    ->where('employeetransfer_details.status', 1)
    ->get(); 


    $html = '';
    foreach ($data as $row) {
       
        $html .= '<tr>';
        $html .= '<td>' . $row->empname . '</td>'; 
        $html .= '<td>' . $row->empsubregion . '</td>'; 
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
            'approve_01' =>  '1',
            'approve_01_time' => $current_date_time,
            'approve_01_by' => Auth::id(),
        );
        Employeetransfer::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employee Transfer is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Employeetransfer::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Employee Transfer is successfully Approved']);

     }else{
        $form_data = array(
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Employeetransfer::findOrFail($id)
        ->update($form_data);

       return response()->json(['success' => 'Employee Transfer  is successfully Approved']);
      }
}




public function employeeselect(Request $request){

    $user = Auth::user();
 
        $employee = $request->input('employeeID');
       
        $data = DB::table('employees')->select('employees.*')
        ->where('id', '=', $employee)
        ->where('deleted', '=', '0')
        ->get();
        return response() ->json(['result'=> $data[0]]);

}







public function getstafflist($areaId)
{
        $branch = DB::table('employees')->select('employees.*')
        ->where('subregion_id', '=', $areaId)
        ->where('deleted', '=', '0')
        ->get();
    
        return response()->json($branch);
}



// public function getsearchempinfo(Request $request) {
//     $searchTerm = $request->input('search');
//     $areaId = $request->input('subregion_id'); // Corrected the key to 'subregion_id'

//     $matchingData = DB::table('employees')
//         ->where('subregion_id', '=', $areaId)
//         ->where(function ($query) use ($searchTerm) {
//             $query->where('emp_national_id', 'like', '%' . $searchTerm . '%')
//                 ->orWhere('service_no', 'like', '%' . $searchTerm . '%')
//                 ->orWhere('emp_name_with_initial', 'like', '%' . $searchTerm . '%');
//         })
//         ->limit(1)
//         ->get();

//     if ($matchingData->count() > 0) {
//         return response()->json($matchingData);
//     } else {
//         $first5Items = DB::table('employees')
//             ->limit(5)->get();
//         return response()->json($first5Items);
//     }
// }






}
