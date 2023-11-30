<?php

namespace App\Http\Controllers;

use App\Commen;
use App\EmergencyContact;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Session;
use Validator;

class EmployeeEmergencyContacts extends Controller
{
    public function show($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-list', $userPermissions)) {
            abort(403);
        } 

        $emergency_contact = EmergencyContact::where('emp_id',$id)->get();
        return view('Employee.viewEmergencyContacts',compact('emergency_contact','id','userPermissions'));
    }

    public function create(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $this->validate($request, array(
            'emp_id' => 'required',
            'name' => 'required|string|max:150',
            'relationship' => 'required|string|max:150',
            'address' => 'required|string|max:500',
            'contact_no' => 'required|numeric|digits:10',
        ));

        $ec=new EmergencyContact;
        $id=$request->input('emp_id'); ;
        $ec->emp_id=$request->input('emp_id');
        $ec->name=$request->input('name');
        $ec->relationship=$request->input('relationship');
        $ec->address=$request->input('address');
        $ec->contact_no=$request->input('contact_no');
        $ec->save();

        Session::flash('success','The Emergency Contact Details Successfully Saved');
        return redirect('viewEmergencyContacts/'.$id);
    }

    public function edit_json($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        if (request()->ajax()) {
            $data = EmergencyContact::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    public function update(Request $request, EmergencyContact $emergencyContact)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rules = array(
            'ec_id' => 'required',
            'name' => 'required|string|max:150',
            'relationship' => 'required|string|max:150',
            'address' => 'required|string|max:500',
            'contact_no' => 'required|numeric|digits:10',
        );

        $error = Validator::make($request->all(), $rules);

        if ($error->fails()) {
            return response()->json(['errors' => $error->errors()->all()]);
        }

        $form_data = array(
            'name' => $request->name,
            'relationship' => $request->relationship,
            'address' => $request->address,
            'contact_no' => $request->contact_no
        );

        EmergencyContact::whereId($request->ec_id)->update($form_data);

        return response()->json(['success' => 'Emergency Contact updated successfully']);
    }

    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('employee-edit', $userPermissions)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $data = EmergencyContact::findOrFail($id);
        $data->delete();
    }
}
