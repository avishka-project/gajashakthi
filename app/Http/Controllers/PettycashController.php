<?php

namespace App\Http\Controllers;

use App;
use App\Pettycash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Pettycashdetail;
use Auth;
use Carbon\Carbon;
use Datatables;
use DB;
use PDF;

class PettycashController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $approvel01permission = 0;
        $approvel02permission = 0;
        $approvel03permission = 0;

        $listpermission = 0;
        $editpermission = 0;
        $deletepermission = 0;
        $statuspermission = 0;
        
        if (Auth::user()->can('Approve-Level-01')) {
            $approvel01permission = 1;
        } 
        if (Auth::user()->can('Approve-Level-02')) {
            $approvel02permission = 1;
        } 
        if (Auth::user()->can('Approve-Level-03')) {
            $approvel03permission = 1;
        } 
        if (Auth::user()->can('Pettycash-list')) {
            $listpermission = 1;
        } 
        if (Auth::user()->can('Pettycash-edit')) {
            $editpermission = 1;
        }
        if (Auth::user()->can('Pettycash-status')) {
            $deletepermission = 1;
        }
        if (Auth::user()->can('Pettycash-delete')) {
            $statuspermission = 1;
        }

        $categories = DB::table('pettycashcategories')->select('pettycashcategories.*')
        ->whereIn('pettycashcategories.status', [1, 2])
        ->where('pettycashcategories.approve_status', 1)
        ->get();
        return view('Pettycash.pettycash', compact('categories','approvel01permission','approvel02permission','approvel03permission','listpermission','editpermission','deletepermission','statuspermission'));
    }
    public function insert(Request $request){
        $user = Auth::user();
        $permission =$user->can('Pettycash-create');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
        $user = Auth::user();

        $this->validate($request, [
            'documentno' => 'required',
            'employee' => 'required',
            'totalcost' => 'required',
        ]);

        $pettycash = new Pettycash();
        $pettycash->document_no =$request->input('documentno');
        $pettycash->employee_id = $request->input('employee');
        $pettycash->cost = $request->input('totalcost');
        $pettycash->date = $request->input('pettydate');
        $pettycash->pettycashfloat = $request->input('pettycashfloat');
        $pettycash->status = '1';
        $pettycash->approve_status = '0';
        $pettycash->approve_01 = '0';
        $pettycash->approve_02 = '0';
        $pettycash->approve_03 = '0';
        $pettycash->create_by = Auth::id();
        $pettycash->update_by = '0';
        $pettycash->save();

        $requestID = $pettycash->id;

        $filteredArray = $request->input('filteredArray');

        foreach ($filteredArray as $rowfilteredArray) {
            $bill_date = $rowfilteredArray['bill_date'];
            $bill_no = $rowfilteredArray['bill_no'];
            $description = $rowfilteredArray['description'];
            $vat_precentage = $rowfilteredArray['vat_precentage'];
            $cost = $rowfilteredArray['rs'];
            $after_vat = $rowfilteredArray['after_vat'];
            $category = $rowfilteredArray['category'];
            $float_balance = $rowfilteredArray['floatbalance'];
            $emp_type = $rowfilteredArray['emp_type'];
            $paid_to = $rowfilteredArray['paid_to'];
            $bill_no = $rowfilteredArray['bill_no'];

            $reg_emp=null;
            $non_reg_emp=null;
            if($emp_type=="Reg_Emp"){
                $reg_emp= $paid_to;
            }else if($emp_type=="Non_reg_Emp"){
                $non_reg_emp= $paid_to;
            }
        
            $pettycashdetail = new Pettycashdetail();
            $pettycashdetail->bill_date = $bill_date;
            $pettycashdetail->emp_type = $emp_type;
            $pettycashdetail->emp_id = $reg_emp;
            $pettycashdetail->non_reg_emp = $non_reg_emp;
            $pettycashdetail->bill_no = $bill_no;
            $pettycashdetail->description = $description;
            $pettycashdetail->vat_precentage = $vat_precentage;
            $pettycashdetail->cost = $cost;
            $pettycashdetail->after_vat = $after_vat;
            $pettycashdetail->float_balance = $float_balance;
            $pettycashdetail->category = $category;
            $pettycashdetail->pettycash_id = $requestID;
            $pettycashdetail->status = '1';
            $pettycashdetail->create_by = Auth::id();
            $pettycashdetail->update_by = '0';
            $pettycashdetail->save();
        }
        
        return response()->json(['success' => 'Petty Cash is Successfully Inserted']);
    }

    public function requestlist()
    {
        $types = DB::table('pettycashes')
            ->leftjoin('employees', 'pettycashes.employee_id', '=', 'employees.id')
            ->select('pettycashes.*','employees.service_no AS service_no','employees.emp_name_with_initial AS emp_name_with_initial')
            ->whereIn('pettycashes.status', [1, 2])
            ->get();

            return Datatables::of($types)
            ->addIndexColumn()
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

                        $permission = $user->can('Pettycash-edit');
                        if($permission){
                            if($row->approve_status == 0 ){
                                $btn .= ' <button name="edit" id="'.$row->id.'" class="edit btn btn-outline-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i></button>'; 
                            }else{
                                $btn .= ' <button name="view" id="'.$row->id.'" class="view btn btn-outline-secondary btn-sm" type="submit"><i class="fa fa-eye"></i></button>'; 
                            }
                        }

                    $permission = $user->can('Pettycash-status');
                        if($permission){
                            if($row->status == 1){
                                $btn .= ' <a href="'.route('pettycashstatus', ['id' => $row->id, 'stasus' => 2]) .'" onclick="return deactive_confirm()" target="_self" class="btn btn-outline-success btn-sm mr-1 "><i class="fas fa-check"></i></a>';
                            }else{
                                $btn .= '&nbsp;<a href="'.route('pettycashstatus', ['id' => $row->id, 'stasus' => 1]) .'" onclick="return active_confirm()" target="_self" class="btn btn-outline-warning btn-sm mr-1 "><i class="fas fa-times"></i></a>';
                            }
                        }
                        $permission = $user->can('Pettycash-delete');
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
        $permission =$user->can('Pettycash-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }

        $id = Request('id');
        if (request()->ajax()){
        $data = DB::table('pettycashes')
        ->leftjoin('employees', 'pettycashes.employee_id', '=', 'employees.id')
        ->select('pettycashes.*','employees.service_no AS service_no','employees.emp_name_with_initial AS emp_name_with_initial')
        ->where('pettycashes.id', $id)
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
    
        $datadetails = DB::table('pettycashdetails')
        ->select('pettycashdetails.*','employees.service_no AS service_no','employees.emp_name_with_initial AS emp_name_with_initial')
        ->leftjoin('employees', 'pettycashdetails.emp_id', '=', 'employees.id')
        ->where('pettycashdetails.pettycash_id', $recordID)
        ->whereIn('pettycashdetails.status', [1, 2])
        ->get(); 
        
        $dataArray = [];

        foreach ($datadetails as $detail) {
            $subarray = [
                'id' => $detail->id,
                'bill_date' => $detail->bill_date,
                'emp_type' => $detail->emp_type,
                'non_reg_emp' => $detail->non_reg_emp,
                'emp_id' => $detail->emp_id,
                'emp_name' => $detail->emp_name_with_initial,
                'emp_serviceno' => $detail->service_no,
                'bill_no' => $detail->bill_no,
                'description' => $detail->description,
                'vat_precentage' => $detail->vat_precentage,
                'rs' => $detail->cost,
                'after_vat' => $detail->after_vat,
                'float_balance' => $detail->float_balance,
                'category' => $detail->category
            ];
            $dataArray[] = $subarray;
        }
    
        return $dataArray;
    
    }
    




    public function update(Request $request){
        $user = Auth::user();
       
        $permission =$user->can('Pettycash-edit');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }
       
            $current_date_time = Carbon::now()->toDateTimeString();

            $this->validate($request, [
                'documentno' => 'required',
                'employee' => 'required',
                'totalcost' => 'required',
            ]);
    

        $id =  $request->hidden_id ;
        $form_data = array(
                'document_no' =>$request->input('documentno'),
                'employee_id' => $request->input('employee'),
                'cost' => $request->input('totalcost'),
                'date' => $request->input('pettydate'),
                'pettycashfloat' => $request->input('pettycashfloat'),
                'approve_status' =>  '0',
                'approve_01' =>  '0',
                'approve_02' =>  '0',
                'approve_03' =>  '0',
                'update_by' => Auth::id(),
                'updated_at' => $current_date_time,
            );

            Pettycash::findOrFail($id)
        ->update($form_data);

        DB::table('pettycashdetails')
        ->where('pettycash_id', $id)
        ->delete();

        $filteredArray = $request->input('filteredArray');

        foreach ($filteredArray as $rowfilteredArray) {
            $bill_date = $rowfilteredArray['bill_date'];
            $bill_no = $rowfilteredArray['bill_no'];
            $description = $rowfilteredArray['description'];
            $vat_precentage = $rowfilteredArray['vat_precentage'];
            $cost = $rowfilteredArray['rs'];
            $after_vat = $rowfilteredArray['after_vat'];
            $category = $rowfilteredArray['category'];
            $float_balance = $rowfilteredArray['floatbalance'];
            $emp_type = $rowfilteredArray['emp_type'];
            $paid_to = $rowfilteredArray['paid_to'];
            $bill_no = $rowfilteredArray['bill_no'];

            $reg_emp=null;
            $non_reg_emp=null;
            if($emp_type=="Reg_Emp"){
                $reg_emp= $paid_to;
            }else if($emp_type=="Non_reg_Emp"){
                $non_reg_emp= $paid_to;
            }
        
            $pettycashdetail = new Pettycashdetail();
            $pettycashdetail->bill_date = $bill_date;
            $pettycashdetail->emp_type = $emp_type;
            $pettycashdetail->emp_id = $reg_emp;
            $pettycashdetail->non_reg_emp = $non_reg_emp;
            $pettycashdetail->bill_no = $bill_no;
            $pettycashdetail->description = $description;
            $pettycashdetail->vat_precentage = $vat_precentage;
            $pettycashdetail->cost = $cost;
            $pettycashdetail->after_vat = $after_vat;
            $pettycashdetail->float_balance = $float_balance;
            $pettycashdetail->category = $category;
            $pettycashdetail->pettycash_id = $id;
            $pettycashdetail->status = '1';
            $pettycashdetail->create_by = Auth::id();
            $pettycashdetail->update_by = '0';
            $pettycashdetail->save();

            
        }

        
        return response()->json(['success' => 'Petty Cash is Successfully Updated']);
    }




    public function delete(Request $request){

        $user = Auth::user();
      
        $permission =$user->can('Pettycash-delete');
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
        Pettycash::findOrFail($id)
        ->update($form_data);

        return response()->json(['success' => 'Petty Cash is Successfully Deleted']);

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
            Pettycash::findOrFail($id)
            ->update($form_data);

            return response()->json(['success' => 'Petty Cash is Successfully Approved']);

         }elseif($applevel == 2){
            $form_data = array(
                'approve_02' =>  '1',
                'approve_02_time' => $current_date_time,
                'approve_02_by' => Auth::id(),
            );
            Pettycash::findOrFail($id)
           ->update($form_data);

            return response()->json(['success' => 'Petty Cash is Successfully Approved']);

         }else{
            $form_data = array(
                'approve_status' =>  '1',
                'approve_03' =>  '1',
                'approve_03_time' => $current_date_time,
                'approve_03_by' => Auth::id(),
            );
            Pettycash::findOrFail($id)
            ->update($form_data);

            $filteredArray = $request->input('filteredArray');

            foreach ($filteredArray as $rowfilteredArray) {
                $detailsid = $rowfilteredArray['id'];
                $bill_date = $rowfilteredArray['bill_date'];
                $bill_no = $rowfilteredArray['bill_no'];
                $description = $rowfilteredArray['description'];
                $cost = $rowfilteredArray['rs'];
                $category = $rowfilteredArray['category'];
            
                $pettycashdetail = Pettycashdetail::find($detailsid);

            if ($pettycashdetail) {
                $pettycashdetail->bill_date = $bill_date;
                $pettycashdetail->bill_no = $bill_no;
                $pettycashdetail->description = $description;
                $pettycashdetail->cost = $cost;
                $pettycashdetail->category = $category;
                $pettycashdetail->pettycash_id = $id;
                $pettycashdetail->status = '1';
                $pettycashdetail->update_by = Auth::id();
                $pettycashdetail->save();
            }
            }


           return response()->json(['success' => 'Petty Cash is Successfully Approved']);
          }
    }




    public function status($id,$statusid){
        $user = Auth::user();
       
       
        $permission =$user->can('Pettycash-status');
        if(!$permission) {
                return response()->json(['error' => 'UnAuthorized'], 401);
            }


        if($statusid == 1){
            $form_data = array(
                'status' =>  '1',
                'update_by' => Auth::id(),
            );
            Pettycash::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('pettycash');
        } else{
            $form_data = array(
                'status' =>  '2',
                'update_by' => Auth::id(),
            );
            Pettycash::findOrFail($id)
            ->update($form_data);
    
            return redirect()->route('pettycash');
        }

    }

    public function getdocno(Request $request){
    
        $data1 = DB::table('pettycashes')
        ->select('pettycashes.*')
        ->get(); 
        $rowCount = count($data1);
    
        if ($rowCount === 0) {
            $batchno=date('dmY').'001';
        }
        else{
            $count='000'.($rowCount+1);
            $count=substr($count, -3);
            $batchno=date('dmY').$count;
        }
        $data2= $batchno;
    
        return response() ->json(['result'=> $data2]);

    }

    public function pettycashserviceno(Request $request) {
        $searchTerm = $request->input('search');
    
        $matchingData = DB::table('employees')
            ->where(function ($query) use ($searchTerm) {
                $query->where('service_no', '=', $searchTerm);
            })
            ->limit(1)
            ->get();
    
        if ($matchingData->count() > 0) {
            return response()->json($matchingData);
        } 
    }
    public function pettycashgetempname(Request $request) {
        $searchTerm = $request->input('search');
    
        $matchingData = DB::table('employees')
            ->where(function ($query) use ($searchTerm) {
                $query->where('emp_fullname', '=', $searchTerm)
                       ->orWhere('emp_fullname', 'like', '%' . $searchTerm . '%')
                       ->orWhere('emp_name_with_initial', 'like', '%' . $searchTerm . '%');
            })
            ->limit(1)
            ->get();
    
        if ($matchingData->count() > 0) {
            return response()->json($matchingData);
        }
    }
    public function pettycashgetempnic(Request $request) {
        $searchTerm = $request->input('search');
    
        $matchingData = DB::table('employees')
            ->where(function ($query) use ($searchTerm) {
                $query->where('emp_national_id', '=', $searchTerm);
            })
            ->limit(1)
            ->get();
    
        if ($matchingData->count() > 0) {
            return response()->json($matchingData);
        }
    }

    public function pettycashgetVat(Request $request){
        $user = Auth::user();

        $date = Request('date');
        if (request()->ajax()){
            $data = DB::table('vats')
            ->select('vats.*')
            ->where('vats.approve_status', 1)
            ->whereIn('vats.status', [1, 2])
            ->whereDate('fromdate', '<=', $date)
            ->whereDate('todate', '>=', $date)
            ->get();
    
        if ($data->count() > 0) {
            // Return the vat value of the first matching row
            return response()->json(['result' => $data[0]->vat]);
        } else {
            // No matching row found
            return response()->json(['result' => 0]);
        }
    }
    }

    public function pettycashprint(Request $request){
        $id = $request->input('id');
    
        $types = DB::table('pettycashdetails')
        ->leftJoin('pettycashcategories', 'pettycashcategories.id', '=', 'pettycashdetails.category')
        ->leftjoin('employees', 'pettycashdetails.emp_id', '=', 'employees.id')
        ->select('pettycashdetails.*','pettycashcategories.pettycash_category AS pettycash_category','employees.service_no AS service_no','employees.emp_name_with_initial AS emp_name_with_initial')
        ->whereIn('pettycashdetails.status', [1, 2])
        ->where('pettycashdetails.pettycash_id', $id)
        ->get();
    
    
        $data = DB::table('pettycashes')
            ->leftJoin('employees', 'pettycashes.employee_id', '=', 'employees.id')
            ->leftJoin('departments', 'employees.emp_department', '=', 'departments.id')
            ->leftJoin('employees as emp2', 'pettycashes.approve_01_by', '=', 'emp2.id')
            ->leftJoin('employees as emp3', 'pettycashes.approve_02_by', '=', 'emp3.id')
            ->leftJoin('employees as emp4', 'pettycashes.approve_03_by', '=', 'emp4.id')
            ->select('pettycashes.*','pettycashes.id as porderid', 'employees.*','employees.emp_name_with_initial as emp_name_with_initial','employees.service_no as service_no','departments.name as departmentname','emp2.emp_name_with_initial AS first_app_emp','emp3.emp_name_with_initial AS second_app_emp','emp4.emp_name_with_initial AS third_app_emp')
            ->where('pettycashes.id', $id)
            ->get();
    
        $tblinvoice='';
        $categorySummary='';
      
        $totalcost='';
        $empno='';
        $empname='';
        $department='';
        $pettycashfloat='';
        $date='';
        $firstapproval='';
        $secondapproval='';
        $thirdapproval='';
        foreach ($data as $datalist) {

            $totalcost=$datalist->cost;
            $empno=$datalist->service_no;
            $empname=$datalist->emp_name_with_initial;
            $department=$datalist->departmentname;
            $date=$datalist->date;
            $pettycashfloat = number_format($datalist->pettycashfloat, 2);
            $firstapproval=$datalist->first_app_emp;
            $secondapproval=$datalist->second_app_emp;
            $thirdapproval=$datalist->third_app_emp;
        }
       
        $count = 1;

        $afrtervattotal = DB::table('pettycashdetails')
    ->select('pettycashdetails.*')
    ->whereIn('pettycashdetails.status', [1, 2])
    ->where('pettycashdetails.pettycash_id', $id)
    ->get();

// Calculate the sum of the 'after_vat' column
    $afterVatTotalSum = $afrtervattotal->sum('after_vat');
    

        $categoryData = DB::table('pettycashdetails')
    ->leftJoin('pettycashcategories', 'pettycashcategories.id', '=', 'pettycashdetails.category')
    ->select('pettycashdetails.*', 'pettycashcategories.pettycash_category AS pettycash_category')
    ->whereIn('pettycashdetails.status', [1, 2])
    ->where('pettycashdetails.pettycash_id', $id)
    ->groupBy('pettycashdetails.category')
    ->select('pettycash_category', DB::raw('SUM(after_vat) as after_vat_sum'))
    ->get();

    $categoryArray = $categoryData->toArray();

    
    foreach ($categoryArray as $rowlist) {
        $categorySummary .= '
        <tr>
            <td style="font-size: 14px;" class="text-left;text-align:left;"><b>' . $rowlist->pettycash_category . '</b></td>
            <td style="font-size: 14px;text-align:center;"><b>' . number_format(($rowlist->after_vat_sum),2) . '</b></td>
        </tr>
        
        ';
    }
    
        
    foreach ($types as $rowlist) {
        $tblinvoice .= '
            <tr>
                <td style="font-size:11px; border:2px solid black; text-align:center;height: 25px;" class="text-center">' . $count . '</td>
                <td colspan="3" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . $rowlist->bill_date . '</td>
                <td colspan="2" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . $rowlist->emp_type . '</td>
                <td colspan="6" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . ($rowlist->emp_type == "Reg_Emp" ? ($rowlist->emp_id == null ? '' : $rowlist->service_no . '-' . $rowlist->emp_name_with_initial) : ($rowlist->non_reg_emp == null ? '' : $rowlist->non_reg_emp)) . '</td>
                <td colspan="2" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . $rowlist->bill_no . '</td>
                <td colspan="5" style="font-size:11px; border:2px solid black; black; text-align:left;padding-left:4px;padding-bottom:4px;height: 25px;" class="text-justify">' . $rowlist->description . '</td>
                <td colspan="2" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . number_format(($rowlist->vat_precentage)) . '</td>
                <td colspan="2" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . number_format(($rowlist->cost), 2) . '</td>
                <td colspan="2" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . number_format(($rowlist->after_vat), 2) . '</td>
                <td colspan="2" style="font-size:11px; border:2px solid black; black; text-align:center;height: 25px;" class="text-center">' . number_format(($rowlist->float_balance), 2) . '</td>
                <td colspan="4" style="font-size:11px; border:2px solid black; black; text-align:left;padding-left:4px;padding-bottom:4px;height: 25px;" class="text-left">' . $rowlist->pettycash_category . '</td>   
            </tr>
        ';
    
        $count++;
    
        // Add a page break after every 15 rows
        if ($count % 12 == 0) {
            $tblinvoice .= '</table>'; // Close the current table
            $tblinvoice .= '<div style="page-break-before: always;"></div>'; // Add a page break
            $tblinvoice .= '<table class="tg" style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">'; // Start a new table
        }
    }
    
    
        $html='';
    
    $html ='
    
    <!doctype html>
    <html lang="en">
    
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      
      
        <title>Petty Cash</title>
        <style media="print">
            * {
                font-family: "Fira Mono", monospace;
            }
    
            table,
            tr,
            th,
            td {
                font-family: "Fira Mono", monospace;
            }
    
            img {
                width: 200px;
                height: 100px;
            }
    
            @page {
                size: 80mm 100mm;
                /* Set the print size width to 80mm and height to 100mm */
            }
    
            body {
                width: 80mm;
                /* Set the body width to 80mm */
            }
    
            #DivIdToPrint {
                border: 1px solid black;
                padding: 10px;
                width: 100%;
                /* Set the div width to 100% */
            }
        </style>
        <style>
            * {
                font-family: "Fira Mono", monospace;
            }
    
            table,
            tr,
            th,
            td {
                font-family: "Fira Mono", monospace;
            }
    
            img {
                width: 100px;
                height: 100px;
            }
    
            #DivIdToPrint {
                width: 100%;
                /* Set the div width to 100% */
            }
        </style>
        <style>
            * {
                font-family: "Cutive Mono", monospace;
            }
    
            table,
            tr,
            th,
            td {
                font-family: "Cutive Mono", monospace;
            }
    
            img {
                width: 100px;
                height: 100px;
            }
        </style>
    </head>
    
    <body>
        <div id="DivIdToPrint">
            <table style="width:100%;">
                <tr>
                    <td style="text-align: left;width:20px;"><img id="logo" src="./images/logogaja.png" width="50" alt="Logo"></td>
                    <td style="text-align: center; padding-right: 140px;">
                        <h3 style="margin-left:0px;">Gajashakthi Security Service (Pvt) Ltd</h3>
                        <h5 style="margin-left:0px;">PETTY CASH REIMBURSEMENT AS AT '.$date.'</h5>
                        <p>PETTY CASH FLOAT Rs&nbsp;'.$pettycashfloat.'</p>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; margin-bottom:50px; padding-right: 0px;" colspan="2">
                        <table class="tg" style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
                            <tr style="text-align:right; font-weight:bold; font-size:5px;">
                                <td style="text-align: center; font-size:12px;border:2px solid black;">Seq</td>
                                <td colspan="3" style="text-align: center; font-size:12px;border:2px solid black;">Bill Date</td>
                                <td colspan="2" style="text-align: center; font-size:12px;border:2px solid black;">Emp Type</td>
                                <td colspan="6" style="text-align: center; font-size:12px;border:2px solid black;">Employee</td>
                                <td colspan="2" style="text-align: center; font-size:12px;border:2px solid black;">Bill No</td>
                                <td colspan="5" style="text-align: center; font-size:12px;border:2px solid black;">Description</td>
                                <td colspan="2" style="text-align: center; font-size:12px;border:2px solid black;">Vat(%)</td>
                                <td colspan="2" style="text-align: center; font-size:12px;border:2px solid black;">Rs.</td>
                                <td colspan="2" style="text-align: center; font-size:12px;border:2px solid black;">After Vat</td>
                                <td colspan="2" style="text-align: center; font-size:12px;border:2px solid black;">Float Balance</td>
                                <td colspan="4" style="text-align: center; font-size:12px;border:2px solid black;">Petty Cash Category</td>
                            </tr>
                            <tbody>
                                '.$tblinvoice.'
                            </tbody>
                            <tfoot>
                            <tr style="font-weight:bold;">
                            <td colspan="21" style="border:2px solid black;text-align:center;font-size:10px;height: 25px;" class="text-center">TOTAL</td>
                            <td colspan="2" style="border:2px solid black;text-align:center;font-size:10px;height: 25px;">'.number_format(($totalcost),2).'</td>
                            <td colspan="2" style="border:2px solid black;text-align:center;font-size:10px;height: 25px;">'.number_format(($afterVatTotalSum),2).'</td>
                            <td colspan="6" style="border:2px solid black;text-align:center;height: 25px;"></td>
                            </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            <tr></tr>
      
                
            </table>
            <br><br>
            <table style="width:100%;">
            '.$categorySummary.'
            <tr>
            <td style="font-size: 14px;border-top:1px solid black;" class="text-left"><b>Total</b></td>
            <td style="font-size: 14px;border-top:1px solid black;border-bottom:1px dashed black;text-align: center;"><b>' . number_format(($afterVatTotalSum),2) . '</b></td>
            </tr>
            <tr>
            <td></td>
            <td style="font-size: 14px;border-bottom:1px dashed black;text-align: center;"></td>
            </tr>
            <tr>
             <td style="text-align: center; font-size:14px;padding-bottom: 25px;"></td>
             </tr>
            </table>
            <table style="width:100%;">
             <tr >
             <th style="text-align: center; font-size:14px;">Prepaired By :-</th>
             <th style="text-align: center; font-size:14px;">1st Approval</th>
             <th style="text-align: center; font-size:14px;">2nd Approval</th>
             <th style="text-align: center; font-size:14px;">3rd Approval</th>
             </tr>
             <tr>
             <td style="text-align: center; font-size:14px;"></td>
             <td style="text-align: center; font-size:14px;padding-top: 5px;">'.$firstapproval.'</td>
             <td style="text-align: center; font-size:14px;padding-top: 5px;">'.$secondapproval.'</td>
             <td style="text-align: center; font-size:14px;padding-top: 5px;">'.$thirdapproval.'</td>
             </tr>
             <tr>
             <td style="text-align: center; font-size:14px;padding-top: 15px;"></td>
             </tr>
             <tr>
             <td style="text-align: left; font-size:14px;"><b>EMP NO. :-</b> '.$empno.'</td>
             </tr>
             <tr>
             <td style="text-align: left; font-size:14px;padding-top: 5px;"><b>Name  :-</b> '.$empname.'</td>
             </tr>
             <tr>
             <td style="text-align: left; font-size:14px;padding-top: 5px;"><b>Dept. :-</b> '.$department.'</td>
             </tr>
            </table>
        </div>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    </body>
    
    </html>';
    
    // echo $html;
    
    // $pdf = PDF::loadHTML($html);
    
    // // Generate a unique filename
    // $filename = 'Purchase_Order_' . uniqid() . '.pdf';
    
    // // Save the PDF with the custom filename to the default storage path
    // $pdf->save(public_path('storage/purchase_order_pdf/' . $filename));
    
    // // Generate the URL to the PDF file
    // $pdfUrl = asset('storage/purchase_order_pdf/' . $filename);
    
    // // Open a new tab with the PDF URL using JavaScript
    // echo '<script>window.open("' . $pdfUrl . '", "_blank");</script>';
    // // return response()->json(['success' => true, 'url' => $pdfUrl]);
    
    // $pdf = PDF::loadHTML($html)->setPaper('legal', 'portrait');
    // $pdfContent = $pdf->output();
    
    // $pdfBase64 = base64_encode($pdfContent);
    
    // $responseData = [
    //     'pdf' => $pdfBase64,
    //     'message' => 'PDF generated successfully',
    // ];
    
    // // Return the JSON response
    // return response()->json($responseData);
 
    $pdf = PDF::loadHTML($html);

    // Set page orientation to landscape
    $pdf->setPaper('A4', 'landscape');
    
    // Return the PDF as base64-encoded data
    $pdfContent = $pdf->output();
    return response()->json(['pdf' => base64_encode($pdfContent)]);
    }
    
}