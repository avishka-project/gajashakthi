<?php

namespace App\Http\Controllers;

use App\Commen;
use App\JobCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Validator;

class JobCategoryController extends Controller
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
        if (!in_array('job-category-list', $userPermissions)) {
            abort(403);
        } 

        $jobcategory= JobCategory::orderBy('id', 'asc')->get();
        return view('Job.jobcategory',compact('jobcategory','userPermissions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
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
        if (!in_array('job-category-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $rules = array(
            'jobcategory'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'category'        =>  $request->jobcategory
            
        );

        $JobCategory=new JobCategory;
       $JobCategory->category=$request->input('jobcategory');       
       $JobCategory->save();

       

        return response()->json(['success' => 'Data Added successfully.']);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function show(JobCategory $jobCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-category-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        if(request()->ajax())
        {
            $data = JobCategory::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, JobCategory $jobCategory)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-category-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $rules = array(
            'jobcategory'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'category'    =>  $request->jobcategory
            
        );

        JobCategory::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\JobCategory  $jobCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('job-category-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $data = JobCategory::findOrFail($id);
        $data->delete();
    }
}
