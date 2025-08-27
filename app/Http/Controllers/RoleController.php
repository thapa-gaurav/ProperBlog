<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    public function createRole(Request $request): JsonResponse
    {
        if(is_string($request->permission)){
            $request->merge([
                'permission'=>json_decode($request->permission)
            ]);
        }
        $request->validate([
            'name' => ['required'],
            'permission'=>['required','array'],
            'permission.*'=>['integer']
        ]);
        $role = Role::create(['name'=>$request->name]);
//        $permissionArray = [];
        foreach ($request->permission as $permissionId){
//            array_push($permissionArray,Permission::findById($permissionId));
            $role->givePermissionTo(Permission::findById($permissionId));
        }
        activity()->by(auth()->user())->log("New role was created");
        return response()->json(['message'=>'New Role created along with permissions']);
    }

    public function assignRole(Request $request): JsonResponse
    {
        //needs to validate
        $request->validate([
            'userid' => 'required',
            'roleid'=>'required'
        ]);
        $user = User::findOrFail($request->userid);
        $roleID = $request->roleid;
        $role = $user->assignRole(Role::findById($roleID));
        activity()->on($user)->by(auth()->user())->log("Role was assigned to user");
        return response()->json(['Message'=>"Role was assigned  successfully.",
            'user'=>$role]);


    }

    public function unassignRole(Request $request)
    {
        $request->validate([
            'userid' => 'required',
            'roleid'=>'required',
        ]);
        $roleId = $request->roleid;
        $user = User::findOrFail($request->userid);
        $user->removeRole(Role::findById($roleId));
        activity()->on($user)->by(auth()->user())->log("Role was unassigned from user");
        return response() ->json(['message'=>'Unassigned role']);
    }

    public function delete($id)
    {
        $role = Role::findById($id);
        $role->delete();
        return response() ->json(['message'=>'Deleted deleted successfully.']);
    }

    public function edit($id,Request $request)
    {
        if(is_string($request->permission)){
            $request->merge([
                'permission'=>json_decode($request->permission)
            ]);
        }
        $request->validate([
            'name' => ['required'],
            'permission'=>['required','array'],
            'permission.*'=>['integer']
        ]);
        $role = Role::findById($id);
        $role->update(['name'=>$request->name]);
        $newPermissions = [];
        foreach ($request->permission as $permissionId){
            array_push($newPermissions,Permission::findById($permissionId));
        }
        $role->syncPermissions($newPermissions);
        return response()->json(['message'=>'Role update successfully']);
    }

    public function getRolePermission($id)
    {

        $role = Role::findById($id);
        return response()->json(['permissions'=>$role->permissions]);
    }

    public function getRoles()
    {
        $roles = Role::get();
        return $roles;

    }

}
