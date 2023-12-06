<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Emp_expense;
use App\Employeeloan;
use App\Employeeloandetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;

class EmployeeloansController extends Controller
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
        if (in_array('Employeeloans-list', $userPermissions)) {
            $listpermission = 1;
        } 
        if (in_array('Employeeloans-edit', $userPermissions)) {
            $editpermission = 1;
        } 
        if (in_array('Employeeloans-status', $userPermissions)) {
            $statuspermission = 1;
        } 
        if (in_array('Employeeloans-delete', $userPermissions)) {
            $deletepermission = 1;
        } 

        $suppliers = DB::table('suppliers')->select('suppliers.*')
        ->whereIn('suppliers.status', [1, 2])
        ->get();
        $employees = DB::table('employees')->select('employees.*')
        ->whereIn('employees.emp_status', [1, 2])->get();
        $branches = DB::table('customerbranches')->select('customerbranches.*')->get();

        $voregions = DB::table('subregions')->select('subregions.*')
        ->whereIn('subregions.status', [1, 2])
        ->where('subregions.approve_status', 1)
        ->get();

        return view('Employeeloans.employeeloan', compact('suppliers', 'employees','branches','voregions','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission','userPermissions'));
    } 

    public function insert(Request $request)
    {
            $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Employeeloans-create', $userPermissions)) {
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

        $accommodationfee = new Employeeloan();
        $accommodationfee->request_type = $request->input('requesttype');
        $accommodationfee->location_id = $request->input('location');
        $accommodationfee->month = $request->input('month');
        $accommodationfee->remark = $request->input('remark');
        $accommodationfee->status = '1';
        $accommodationfee->approve_status = '0';
        $accommodationfee->approve_01 = '0';
        $accommodationfee->approve_02 = '0';
        $accommodationfee->approve_03 = '0';
        $accommodationfee->status = '1';
        $accommodationfee->create_by = Auth::id();
        $accommodationfee->update_by = '0';
        $accommodationfee->save();

        $requestID = $accommodationfee->id;

        $tableDataArray = $request->input('tableDataArray');

        foreach ($tableDataArray as $rowtableDataArray) {
            $employee = $rowtableDataArray['empid'];
            $loan = $rowtableDataArray['loan'];
           
            $accommodationfeesdetail = new Employeeloandetail();
            $accommodationfeesdetail->employeeloan_id = $requestID;
            $accommodationfeesdetail->emp_id = $employee;
            $accommodationfeesdetail->loan = $loan;
            $accommodationfeesdetail->status = '1';
            $accommodationfeesdetail->create_by = Auth::id();
            $accommodationfeesdetail->update_by = '0';
            $accommodationfeesdetail->save();
        }

        return response()->json(['success' => 'Employeeloans is successfully Inserted']);
        // return response()->json(['status' => 1, 'message' => 'Employee Payment is Successfully Created']);
    }

    public function requestlist()
    {

        $requests = DB::table('employeeloans')
                     ->select('employeeloans.*')
                    ->whereIn('employeeloans.status', [1, 2])
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

                if(in_array('Employeeloans-edit',$userPermissions)){
                            $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>';
                        }
                    
                        if(in_array('Employeeloans-status',$userPermissions)){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('employeeloanstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('employeeloanstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }

                        if(in_array('Employeeloans-delete',$userPermissions)){
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
            if (!in_array('Employeeloans-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        
        $id = Request('id');
        $current_date_time = Carbon::now()->toDateTimeString();
        $form_data = array(
            'status' =>  '3',
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );
        Employeeloan::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employeeloans is successfully Deleted']);

    }


    public function approvel_details(Request $request){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Approve-Level-01', $userPermissions) || !in_array('Approve-Level-02', $userPermissions) || !in_array('Approve-Level-03', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

            $id = Request('id');
            if (request()->ajax()){
            $data = DB::table('employeeloans')
            ->leftjoin('customerbranches', 'employeeloans.location_id', '=', 'customerbranches.id')
            ->leftjoin('subregions', 'customerbranches.subregion_id', '=', 'subregions.id')
            ->select('employeeloans.*','subregions.subregion')
            ->where('employeeloans.id', $id)
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
   $data = DB::table('employeeloandetails')
   ->leftjoin('employees', 'employeeloandetails.emp_id', '=', 'employees.id')
   ->select('employeeloandetails.*','employees.service_no', 'employees.emp_fullname', DB::raw('(employeeloandetails.id) AS boardingfeesdetailsID'))
   ->where('employeeloandetails.employeeloan_id', $recordID)
   ->where('employeeloandetails.status', 1)
   ->get(); 


   $htmlTable = '';
   foreach ($data as $row) {
      
      
    $htmlTable .= '<tr>';
    $htmlTable .= '<td>' . $row->service_no . '-'. $row->emp_fullname . '</td>'; 
    $htmlTable .= '<td id="boardingfee">' . $row->loan . '</td>'; 
    $htmlTable .= '<td class="d-none" id="empid">' . $row->emp_id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
   }

   return $htmlTable;

}



public function edit(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Employeeloans-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

    $id = Request('id');

    if (request()->ajax()){
        $data = DB::table('employeeloans')
        ->leftjoin('customerbranches', 'employeeloans.location_id', '=', 'customerbranches.id')
        ->leftjoin('subregions', 'customerbranches.subregion_id', '=', 'subregions.id')
        ->select('employeeloans.*','subregions.subregion')
        ->where('employeeloans.id', $id)
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

    $data = DB::table('employeeloandetails')
    ->leftjoin('employeeloans', 'employeeloandetails.employeeloan_id', '=', 'employeeloans.id')
    ->leftjoin('employees', 'employeeloandetails.emp_id', '=', 'employees.id')
    ->select('employeeloandetails.*','employees.service_no', 'employees.emp_fullname','employeeloans.request_type', DB::raw('(employeeloandetails.id) AS boardingfeesdetailsID'))
    ->where('employeeloandetails.employeeloan_id', $recordID)
    ->where('employeeloandetails.status', 1)
    ->get(); 

    $count = 1;
   $htmlTable = '';
   foreach ($data as $row) {
      
    $htmlTable .= '<tr>';
    $htmlTable .='<td>';
    $htmlTable .='<select name="employee' . $count . '" id="employee' . $count .'" class="form-control form-control-sm" onclick="getEmp('. $count .');" required>';
    $htmlTable .='<option value="'. $row->emp_id . '">'. $row->service_no . '-'. $row->emp_fullname . '</option>';
    $htmlTable .='</select>';
    $htmlTable .='</td>';
    $htmlTable .= '<td>';
    $htmlTable .= '<input type="number" id="amount' . $count . '" name="amount' . $count . '" value="' . $row->loan . '" >';
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
    $data = DB::table('employeeloandetails')
                ->select('employeeloandetails.*')
                ->where('employeeloandetails.id', $id)
                ->get(); 
    return response() ->json(['result'=> $data[0]]);
}
}


public function update(Request $request){
        $current_date_time = Carbon::now()->toDateTimeString();
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Employeeloans-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }



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

        Employeeloan::findOrFail($id)
    ->update($form_data);
    
    DB::table('employeeloandetails')
    ->where('employeeloan_id', $hidden_id)
    ->delete();

    $tableDataArray = $request->input('tableDataArray');

    foreach ($tableDataArray as $rowtableDataArray) {
        $employee = $rowtableDataArray['empid'];
        $loan = $rowtableDataArray['loan'];
       
        $accommodationfeesdetail = new Employeeloandetail();
        $accommodationfeesdetail->employeeloan_id = $hidden_id;
        $accommodationfeesdetail->emp_id = $employee;
        $accommodationfeesdetail->loan = $loan;
        $accommodationfeesdetail->status = '1';
        $accommodationfeesdetail->create_by = Auth::id();
        $accommodationfeesdetail->update_by = '0';
        $accommodationfeesdetail->save();
    }
 

    return response()->json(['success' => 'Employeeloans is successfully Updated']);

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
        Employeeloan::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Employeeloans is successfully Approved']);

     }elseif($applevel == 2){
        $form_data = array(
            'approve_02' =>  '1',
            'approve_02_time' => $current_date_time,
            'approve_02_by' => Auth::id(),
        );
        Employeeloan::findOrFail($id)
       ->update($form_data);

        return response()->json(['success' => 'Employeeloans is successfully Approved']);

     }else{
        $form_data = array(
            'approve_status' =>  '1',
            'approve_03' =>  '1',
            'approve_03_time' => $current_date_time,
            'approve_03_by' => Auth::id(),
        );
        Employeeloan::findOrFail($id)
        ->update($form_data);

        //expenses add to table

        $tableDataArray = $request->input('tableDataArray');

        foreach ($tableDataArray as $rowtableDataArray) {
            $employee = $rowtableDataArray['empid'];
            $loan = $rowtableDataArray['loan'];

            $emp_expense = new Emp_expense();
            $emp_expense->employee_id =  $employee;
            $emp_expense->cost = $loan;
            $emp_expense->expenses_type = 'Employee_Loan';
            $emp_expense->month = $request->input('month');
            $emp_expense->status = '1';
            $emp_expense->create_by = Auth::id();
            $emp_expense->save();
        }

       return response()->json(['success' => 'Employeeloans is successfully Approved']);
      }
}


public function status($id,$statusid){
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('Employeeloans-status', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

    if($statusid == 1){
        $form_data = array(
            'status' =>  '1',
            'update_by' => Auth::id(),
        );
        Employeeloan::findOrFail($id)
        ->update($form_data);

        return redirect()->route('employeeloan');
    } else{
        $form_data = array(
            'status' =>  '2',
            'update_by' => Auth::id(),
        );
        Employeeloan::findOrFail($id)
        ->update($form_data);

        return redirect()->route('employeeloan');
    }

}

public function deletelist(Request $request){
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('Employeeloans-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
        $id = Request('id');
    $current_date_time = Carbon::now()->toDateTimeString();
    $form_data = array(
        'status' =>  '3',
        'update_by' => Auth::id(),
        'updated_at' => $current_date_time,
    );
    Employeeloandetail::findOrFail($id)
    ->update($form_data);

    return response()->json(['success' => 'Employee is successfully Deleted']);

}

public function GetAllEmployee(Request $request){

    $subregion_id = Request('subregion_id');
    if (request()->ajax()){
    $data = DB::table('subregions')
    ->select('subregions.*')
    ->where('subregions.id', $subregion_id)
    ->get(); 

    
    $subregion_id = $data[0]->id; 

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
    $htmlTable .= '<input type="number" id="amount' . $count . '" name="amount' . $count . '">';
    $htmlTable .= '</td>';   
    $htmlTable .= '<td class="d-none" id="empid">' . $row->id . '</td>'; 
    $htmlTable .= '<td class="d-none">ExistingData</td>'; 
    $htmlTable .= '</tr>';
    $count++;
   }

   return $htmlTable;

}
}
