<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Emp_expense;
use App\Travelrequest;
use App\Travelrequestdetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Yajra\Datatables\Datatables;

class Travelrequestcontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $branches = DB::table('customerbranches')->select('customerbranches.*')->get();
        $employees = DB::table('employees')->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])->get();
        return view('Travelrequest.travelrequest', compact('branches', 'employees','userPermissions'));
    } 

    public function insert(Request $request)
    {
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Travelrequest-create', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        $this->validate($request, [
        //     'customer' => 'required',
        //     'subcustomer' => 'required',
        //  //   'date' => 'required',
        //     'area' => 'required',
        //     'shift' => 'required',
        //     'holiday' => 'required',
        //     'tableData' => 'required',
        ]);

        $travelrequest = new Travelrequest();
        $travelrequest->request_type = $request->input('requesttype');
        $travelrequest->location_id = $request->input('location');
        $travelrequest->month = $request->input('month');
        $travelrequest->remark = $request->input('remark');
        $travelrequest->status = '1';
        $travelrequest->approve_status = '0';
        $travelrequest->approve_01 = '0';
        $travelrequest->approve_02 = '0';
        $travelrequest->approve_03 = '0';
        $travelrequest->status = '1';
        $travelrequest->create_by = Auth::id();
        $travelrequest->update_by = '0';
        $travelrequest->save();

        $requestID = $travelrequest->id;

        $requesttype = $request->input('requesttype');

        $tableDataArray = $request->input('tableDataArray');

            foreach ($tableDataArray as $rowtableDataArray) {
                $employee = $rowtableDataArray['empid'];
                $cost = $rowtableDataArray['amount'];
               
                $travelrequestdetail = new Travelrequestdetail();
                $travelrequestdetail->travelrequests_id = $requestID;
                $travelrequestdetail->emp_id = $employee;
                $travelrequestdetail->cost = $cost;
                $travelrequestdetail->status = '1';
                $travelrequestdetail->create_by = Auth::id();
                $travelrequestdetail->update_by = '0';
                $travelrequestdetail->save();
            }

        
        return response()->json(['success' => 'Travel Request is successfully Inserted']);
        // return response()->json(['status' => 1, 'message' => 'Employee Payment is Successfully Created']);
    }

    public function requestlist()
    {

        $requests = DB::table('travelrequests')
                    ->leftjoin('customerbranches', 'travelrequests.location_id', '=', 'customerbranches.id')
                     ->select('travelrequests.*', 'customerbranches.branch_name AS branch_name')
                    ->whereIn('travelrequests.status', [1, 2])
                    ->get();

        return Datatables::of($requests)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $commen= new Commen();
                $userPermissions = $commen->Allpermission();      

                $btn='';

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

                if(in_array('Travelrequest-edit',$userPermissions)){
                            if($row->approve_status == 0){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                        else{
                            $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fas fa-eye"></i></button>';
                        }
                    }
                    
                    if(in_array('Travelrequest-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('travelrequeststatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('travelrequeststatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                        if(in_array('Travelrequest-delete',$userPermissions)){
                    $btn .= ' <button name="delete" id="'.$row->id.'" class="delete btn btn-outline-danger btn-sm"><i class="far fa-trash-alt"></i></button>';  
                }
                 return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }


    public function delete(Request $request){
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Travelrequest-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
        $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Travel Request is successfully Deleted']);

    }


    public function approvel_details(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

            $id = Request('id');
            if (request()->ajax()){
            $data = DB::table('travelrequests')
            ->leftjoin('customerbranches', 'travelrequests.location_id', '=', 'customerbranches.id')
            ->leftjoin('subregions', 'customerbranches.subregion_id', '=', 'subregions.id')
            ->select('travelrequests.*','subregions.subregion')
            ->where('travelrequests.id', $id)
            ->get(); 

        $requestlist = $this->app_reqestcountlist($id); 

                    $responseData = array(
                        'mainData' => $data[0],
                        'requestdata' => $requestlist,
                    );

        return response() ->json(['result'=>  $responseData]);
    }
}



private function app_reqestcountlist($id){

    $recordID =$id ;
   $data = DB::table('travelrequestdetails')
   ->leftjoin('employees', 'travelrequestdetails.emp_id', '=', 'employees.id')
   ->select('travelrequestdetails.*', 'employees.emp_fullname', DB::raw('(travelrequestdetails.id) AS travelrequestdetailsID'))
   ->where('travelrequestdetails.travelrequests_id', $recordID)
   ->where('travelrequestdetails.status', 1)
   ->get(); 


   $htmlTable = '';
   foreach ($data as $row) {
      
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td id="empname">' . $row->emp_fullname . '</td>'; 
    $htmlTable .= '<td id="cost">' . $row->cost . '</td>'; 
    $htmlTable .= '<td class="d-none" id="empid">' . $row->emp_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function edit(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Travelrequest-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('travelrequests')
    ->leftjoin('customerbranches', 'travelrequests.location_id', '=', 'customerbranches.id')
    ->leftjoin('subregions', 'customerbranches.subregion_id', '=', 'subregions.id')
    ->select('travelrequests.*','subregions.subregion')
    ->where('travelrequests.id', $id)
    ->get(); 

    $requestlist = $this->reqestcountlist($id); 

    $responseData = array(
        'mainData' => $data[0],
        'requestdata' => $requestlist,
    );

return response() ->json(['result'=>  $responseData]);
}
}



private function reqestcountlist($id){

    $recordID =$id ;
    $data = DB::table('travelrequestdetails')
    ->leftjoin('employees', 'travelrequestdetails.emp_id', '=', 'employees.id')
    ->select('travelrequestdetails.*','employees.service_no', 'employees.emp_fullname', DB::raw('(travelrequestdetails.id) AS travelrequestdetailsID'))
    ->where('travelrequestdetails.travelrequests_id', $recordID)
    ->where('travelrequestdetails.status', 1)
    ->get(); 


   $htmlTable = '';
   $count = 1;
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .='<td>';
    $htmlTable .='<select name="employee' . $count . '" id="employee' . $count .'" class="form-control form-control-sm" onclick="getEmp('. $count .');" required>';
    $htmlTable .='<option value="'. $row->emp_id . '">'. $row->service_no . '-'. $row->emp_fullname . '</option>';
    $htmlTable .='</select>';
    $htmlTable .='</td>';
    $htmlTable .= '<td>';
    $htmlTable .= '<input type="number" id="amount' . $count . '" name="amount' . $count . '" value="' . $row->cost . '">';
    $htmlTable .= '</td>';        
    $htmlTable .= '<td class="d-none" id="empid">' . $row->emp_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '<td><button type="button" onclick= "productDelete(this);" id="btnDeleterow" class=" btn btn-danger btn-sm "><i class="fas fa-trash-alt"></i></button></td>'; 
    $htmlTable .= '</tr>';

    $count++;
   }

   return $htmlTable;

}



public function editlist(Request $request){
    $id = Request('id');
    if (request()->ajax()){
    $data = DB::table('travelrequestdetails')
                ->select('travelrequestdetails.*')
                ->where('travelrequestdetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}


public function update(Request $request){
    $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Travelrequest-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
   
        $current_date_time = Carbon::now()->toDateTimeString();



    $hidden_id = $request->input('hidden_id');

    
    $id =  $request->hidden_id ;
    $form_data = array(
        'request_type' =>  $request->input('requesttype'),
        'location_id' =>  $request->input('location'),
        'month' =>  $request->input('month'),
        'remark' =>  $request->input('remark'),
            'approve_status' =>  '0',
            'approve_01' =>  '0',
            'approve_02' =>  '0',
            'approve_03' =>  '0',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time, 
        );

        Travelrequest::findOrFail($id)
    ->update($form_data);
    
    DB::table('travelrequestdetails')
    ->where('travelrequests_id', $hidden_id)
    ->delete();

    $tableDataArray = $request->input('tableDataArray');

            foreach ($tableDataArray as $rowtableDataArray) {
                $employee = $rowtableDataArray['empid'];
                $cost = $rowtableDataArray['amount'];
               
                $travelrequestdetail = new Travelrequestdetail();
                $travelrequestdetail->travelrequests_id = $hidden_id;
                $travelrequestdetail->emp_id = $employee;
                $travelrequestdetail->cost = $cost;
                $travelrequestdetail->status = '1';
                $travelrequestdetail->create_by = Auth::id();
                $travelrequestdetail->update_by = '0';
                $travelrequestdetail->save();
            }


    return response()->json(['success' => 'Travel Request is successfully Updated']);

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
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Travel Request is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Travel Request is successfully Approved']);

     }else{
        $form_data = array(
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

           //expenses add to table

           $tableDataArray = $request->input('tableDataArray');

            foreach ($tableDataArray as $rowtableDataArray) {
                $employee = $rowtableDataArray['empid'];
                $cost = $rowtableDataArray['cost'];

               $emp_expense = new Emp_expense();
               $emp_expense->employee_id =  $employee;
               $emp_expense->cost = $cost;
               $emp_expense->expenses_type = 'Travel_Request';
               $emp_expense->month = $request->input('month');
               $emp_expense->status = '1';
               $emp_expense->create_by = Auth::id();
               $emp_expense->save();
           }

       return response()->json(['success' => 'Travel Request is successfully Approved']);
      }
}


public function status($id,$statusid){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Travelrequest-status', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return redirect()->route('travelrequest');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Travelrequest::findOrFail($id)
        ->update($form_data);

        return redirect()->route('travelrequest');
    }

}

public function deletelist(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Travelrequest-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
        $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Travelrequestdetail::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'Employee is successfully Deleted']);

}

public function GetAllEmployee(Request $request){

    $location_id = Request('location_id');
    if (request()->ajax()){
    $data = DB::table('customerbranches')
    ->leftjoin('subregions', 'customerbranches.subregion_id', '=', 'subregions.id')
    ->select('customerbranches.*','subregions.subregion','subregions.id AS subregion_id')
    ->where('customerbranches.id', $location_id)
    ->get(); 

    
    $subregion_id = $data[0]->subregion_id; 
   
    $requestlist = $this->VoEmpreqestcountlist($subregion_id); 
    
        $responseData = array(
            'mainData' => $data[0],
            'requestdata' => $requestlist,
        );

return response() ->json(['result'=>  $responseData]);
}

}

private function VoEmpreqestcountlist($id){

    $recordID =$id ;
   $data = DB::table('employees')
   ->select('employees.*')
   ->where('employees.subregion_id', $recordID)
   ->where('employees.emp_status', 1)
   ->get(); 

   $htmlTable = '';
   $count = 1;
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td id="empname">' .  $row->service_no.'-'.$row->emp_fullname .'</td>'; 
    $htmlTable .= '<td>';
    $htmlTable .= '<input type="number" id="amount' . $count . '" name="amount' . $count . '" >';
    $htmlTable .= '</td>';        
    $htmlTable .= '<td class="d-none" id="empid">' . $row->id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
    $count++;
   }

   return $htmlTable;

}
}