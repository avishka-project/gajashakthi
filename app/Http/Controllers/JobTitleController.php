<?php

namespace App\Http\Controllers;

use App\Commen;
use App\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class JobTitleController extends Controller
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
        if (!in_array('job-title-list', $userPermissions)) {
            abort(403);
        } 
        
        $title = JobTitle::orderBy('id', 'asc')->get();
        return view('Job.jobtitle', compact('title','userPermissions'));
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-title-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'title' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'title' => $request->title

        );

        $jobtitle = new JobTitle;
        $jobtitle->title = $request->input('title');
        $jobtitle->occupation_group_id = $request->input('occupation_group_id');
        $jobtitle->save();

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\JobTitle $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function show(JobTitle $jobTitle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\JobTitle $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-title-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (request()->ajax()) {
            $data = JobTitle::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\JobTitle $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobTitle $jobTitle)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-title-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'title' => 'required'
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'title' => $request->title,
            'occupation_group_id' => $request->occupation_group_id

        );

        JobTitle::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\JobTitle $jobTitle
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-title-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }


        $data = JobTitle::findOrFail($id);
        $data->delete();
    }
}
