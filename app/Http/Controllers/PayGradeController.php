<?php

namespace App\Http\Controllers;

use App\Commen;
use App\PayGrade;
use http\Env\Response;
use Illuminate\Http\Request;
use Validator;

class PayGradeController extends Controller
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
        if (!in_array('pay-grade-list', $userPermissions)) {
            abort(403);
        } 

        $paygrade= PayGrade::orderBy('id', 'asc')->get();
        return view('Job.payGrade',compact('paygrade','userPermissions'));
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
        if (!in_array('pay-grade-create', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'paygrade'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'paygrade'        =>  $request->paygrade
            
        );

        $paygrade=new PayGrade;
       $paygrade->pay_grade=$request->input('paygrade');       
       $paygrade->save();

       

        return response()->json(['success' => 'Data Added successfully.']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PayGrade  $payGrade
     * @return \Illuminate\Http\Response
     */
    public function show(PayGrade $payGrade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PayGrade  $payGrade
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('pay-grade-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if(request()->ajax())
        {
            $data = PayGrade::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PayGrade  $payGrade
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PayGrade $payGrade)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('pay-grade-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'paygrade'    =>  'required'
        );

        $error = Validator::make($request->all(), $rules);

        if($error->fails())
        {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'pay_grade'    =>  $request->paygrade
            
        );

        PayGrade::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'Pay Grade is Successfully Updated']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PayGrade  $payGrade
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('pay-grade-delete', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = PayGrade::findOrFail($id);
        $data->delete();
    }
}
