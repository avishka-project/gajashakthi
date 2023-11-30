<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Company;
use App\Employee;
use App\EmpType;
use App\Region;
use App\Subregion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($company_id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('company-list', $userPermissions)) {
            abort(403);
        } 
        $department = Department::orderBy('id', 'asc')->where('company_id', $company_id)->get();
        $company = Company::where('id', $company_id)->first();
        return view('Organization.department', compact('department', 'company','userPermissions'));
    }

    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('department-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 

        $rules = array(
            'name' => 'required',
            'company_id' => 'required',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $department = new Department();
        $department->name = $request->input('name');
        $department->company_id = $request->input('company_id');
        $department->create_by = Auth::id();
        $department->save();

        return response()->json(['success' => 'Department Added successfully.']);
    }

    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('department-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 

        if (request()->ajax()) {
            $data = Department::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, Department $department)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('department-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 

        $rules = array(
            'name' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $current_date_time = Carbon::now()->toDateTimeString();

        $form_data = array(
            'name' => $request->name,
            'update_by' => Auth::id(),
            'updated_at' => $current_date_time,
        );

        Department::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Department is successfully updated']);
    }

    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('department-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $data = Department::findOrFail($id);
        $data->delete();
    }

    public function department_list_sel2(Request $request){
        if ($request->ajax())
        {
            $page = Input::get('page');
            $company = Input::get('company');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $breeds = Department::where('name', 'LIKE',  '%' . Input::get("term"). '%')
                ->where('company_id', $company)
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('id as id'),DB::raw('name as text')]);

            $count = Department::where('company_id', $company)->count();
            $endCount = $offset + $resultCount;
            $morePages = $endCount < $count;

            $results = array(
                "results" => $breeds,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function region_list_sel2(Request $request)
    {
        if ($request->ajax())
        {
            $page = Input::get('page');
            //$company = Input::get('company');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $query = Region::where('region', 'LIKE',  '%' . Input::get("term"). '%');

//            if ($company != '') {
//                $query->where('emp_company', $company);
//            }

            $query1 = $query->orderByDesc('region')
                ->skip($offset)
                ->take($resultCount);

            $breeds = $query1->get([DB::raw('id'),DB::raw('region as text')]);

            $count = $query->count();

            $endCount = $offset + $resultCount;
            $morePages = $endCount < $count;

            $results = array(
                "results" => $breeds,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function subregion_list_sel2(Request $request)
    {
        if ($request->ajax())
        {
            $page = Input::get('page');
            $region_id = Input::get('region_id');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            if($region_id == ''){
                $results = array(
                    "results" => array(),
                    "pagination" => array(
                        "more" => 0
                    )
                );

                return response()->json($results);
            }

            $query = SubRegion::where('subregion', 'LIKE',  '%' . Input::get("term"). '%');

            if ($region_id != '') {
                $query->where('region_id', $region_id);
            }

            $query1 = $query->orderByDesc('subregion')
                ->skip($offset)
                ->take($resultCount);

            $breeds = $query1->get([DB::raw('id'),DB::raw('subregion as text')]);

            $count = $query->count();

            $endCount = $offset + $resultCount;
            $morePages = $endCount < $count;

            $results = array(
                "results" => $breeds,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

    public function emptype_list_sel2(Request $request)
    {
        if ($request->ajax())
        {
            $page = Input::get('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $query = EmpType::where('emptype', 'LIKE',  '%' . Input::get("term"). '%');

            $query1 = $query->orderByDesc('emptype')
                ->skip($offset)
                ->take($resultCount);

            $breeds = $query1->get([DB::raw('id'),DB::raw('emptype as text')]);

            $count = $query->count();

            $endCount = $offset + $resultCount;
            $morePages = $endCount < $count;

            $results = array(
                "results" => $breeds,
                "pagination" => array(
                    "more" => $morePages
                )
            );

            return response()->json($results);
        }
    }

}
