<?php

namespace App\Http\Controllers;

use App\Commen;
use App\Company;
use App\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('company-list', $userPermissions)) {
            abort(403);
        } 

        $company = Company::orderBy('id', 'asc')->paginate(10);
        return view('Organization.company', compact('company','userPermissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('company-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 

        $rules = array(
            'name' => 'required',
            'code' => 'required',
            'mobile' => 'required|Numeric'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $company = new Company;
        $company->name = $request->input('name');
        $company->code = $request->input('code');
        $company->address = $request->input('address');
        $company->mobile = $request->input('mobile');
        $company->land = $request->input('land');
        $company->email = $request->input('email');
        $company->epf = $request->input('epf');
        $company->etf = $request->input('etf');
        $company->save();

        return response()->json(['success' => 'Company Added successfully.']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Company $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('company-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 

        if (request()->ajax()) {
            $data = Company::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Company $company)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('company-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 

        $rules = array(
            'name' => 'required',
            'code' => 'required',
            'mobile' => 'required|Numeric'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name,
            'code' => $request->code,
            'address' => $request->address,
            'mobile' => $request->mobile,
            'land' => $request->land,
            'etf' => $request->etf,
            'epf' => $request->epf
        );

        Company::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Company is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Company $company
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('company-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        } 


        $data = Company::findOrFail($id);
        $data->delete();
    }

    public function company_list_sel2(Request $request){
        if ($request->ajax())
        {
            $page = Input::get('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $breeds = Company::where('name', 'LIKE',  '%' . Input::get("term"). '%')
                ->orderBy('name')
                ->skip($offset)
                ->take($resultCount)
                ->get([DB::raw('id as id'),DB::raw('name as text')]);

            $count = Company::count();
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
