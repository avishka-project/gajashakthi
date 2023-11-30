<?php

namespace App\Http\Controllers;

use App\Commen;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{

    function __construct()
    {
    }


    public function index(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-list', $userPermissions)) {
            abort(403);
        } 

        $roles = Role::orderBy('id','DESC')->get();
        return view('roles.index',compact('roles','userPermissions'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-create', $userPermissions)) {
            abort(403);
        } 

        $permission = Permission::get();
        return view('roles.create',compact('permission','userPermissions'));
    }


    public function store(Request $request)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-create', $userPermissions)) {
            abort(403);
        } 

        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        $role = Role::create(['name' => $request->input('name')]);
        $role->permissions()->sync($request->input('permission'));

        return redirect()->route('roles.index')
                        ->with('success','Role created successfully');
    }

    public function show($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-list', $userPermissions)) {
            abort(403);
        } 

        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$id)
            ->get();

        return view('roles.show',compact('role','rolePermissions','userPermissions'));
    }


    public function edit($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-edit', $userPermissions)) {
            abort(403);
        } 

        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)
            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
            ->all();

        $perms_with_modules = DB::table("permissions")
            ->select('module')
            ->groupBy("module")
            ->get()
            ->toArray();

        return view('roles.edit',compact('role','permission','rolePermissions', 'perms_with_modules','userPermissions'));
    }


    public function update(Request $request, $id)
    {
        $user = Auth::user();
        
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-edit', $userPermissions)) {
            abort(403);
        } 

        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->permissions()->sync($request->input('permission'));

        $userRole = $user->roles->pluck('name','name')->all();

        $user->roles()->detach();
        $user->assignRole($userRole);

        return redirect()->route('roles.index')
                        ->with('success','Role updated successfully');
    }

    public function destroy($id)
    {
        $commen= new Commen();
        $userPermissions = $commen->Allpermission();
        if (!in_array('role-delete', $userPermissions)) {
            abort(403);
        } 

        DB::table("roles")->where('id',$id)->delete();
        return redirect()->route('roles.index')
                        ->with('success','Role deleted successfully');
    }
}
