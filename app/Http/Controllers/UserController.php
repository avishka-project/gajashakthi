<?php

namespace App\Http\Controllers;

use App\Commen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $data = User::orderBy('id','DESC')->get();
        return view('users.index',compact('data','userPermissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }


    public function create()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        
        $roles = Role::pluck('name','name')->all();
        return view('users.create',compact('roles','userPermissions'));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User created successfully');
    }


    public function show($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();

        $user = User::find($id);
        return view('users.show',compact('user','userPermissions'));
    }


    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();

        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();

        return view('users.edit',compact('user','roles','userRole','userPermissions'));
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        //remove roles from user
        $user->roles()->detach();
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')
                        ->with('success','User updated successfully');
    }

    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}
