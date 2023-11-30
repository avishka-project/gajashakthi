<?php

namespace App\Http\Controllers;

use App\Commen;
use App\EmpType;
use App\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Validator;

class SkillController extends Controller
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
        if (!in_array('skill-list', $userPermissions)) {
            abort(403);
        } 

        $skill= Skill::orderBy('id', 'asc')->get();
        return view('Qulification.skill',compact('skill','userPermissions'));
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
        if (!in_array('skill-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'skill'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'skill'        =>  $request->skill
            
        );

        $skill=new Skill;
       $skill->skill=$request->input('skill');       
       $skill->save();

       

        return response()->json(['success' => 'Skill Added Successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function show(Skill $skill)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('skill-edit', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        if(request()->ajax())
        {
            $data = Skill::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Skill $skill)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('skill-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'skill'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'skill'    =>  $request->skill
            
        );

        Skill::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Data is successfully updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Skill  $skill
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
            $userPermissions = $commen->Allpermission();
            if (!in_array('skill-delete', $userPermissions)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

        $data = Skill::findOrFail($id);
        $data->delete();
    }

    public function skill_list_sel2(Request $request)
    {
        if ($request->ajax())
        {
            $page = Input::get('page');
            $resultCount = 25;

            $offset = ($page - 1) * $resultCount;

            $query = Skill::where('skill', 'LIKE',  '%' . Input::get("term"). '%');

            $query1 = $query->orderByDesc('skill')
                ->skip($offset)
                ->take($resultCount);

            $breeds = $query1->get([DB::raw('id'),DB::raw('skill as text')]);

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
