<?php

namespace App\Http\Controllers;

use App\Commen;
use App\EmploymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class EmploymentStatusController extends Controller
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
        if (!in_array('job-employment-status-list', $userPermissions)) {
            abort(403);
        } 
        $employmentstatus= EmploymentStatus::orderBy('id', 'asc')->get();
        return view('Job.employmentstatus',compact('employmentstatus','userPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-employment-status-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $rules = array(
            'emp_status'    =>  'required|unique:employment_statuses'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'emp_status'        =>  $request->title
            
        );

        $EmploymentStatus=new EmploymentStatus;
       $EmploymentStatus->emp_status=$request->input('emp_status');       
       $EmploymentStatus->save();

       

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function show(EmploymentStatus $employmentStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-employment-status-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if(request()->ajax())
        {
            $data = EmploymentStatus::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EmploymentStatus $employmentStatus)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-employment-status-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'emp_status'    =>  'required|unique:employment_statuses'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'emp_status'    =>  $request->emp_status
            
        );
        

        EmploymentStatus::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmploymentStatus  $employmentStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-employment-status-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $data = EmploymentStatus::findOrFail($id);
        $data->delete();
    }
}
