<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public function createPermission(Request $request)
    {
        $request->validate([
            'permission'=>['required','string']
        ]);

        $permission = Permission::create(['name'=>$request->permission]);
        return response()->json(['message'=>'Permission was created.','permission'=>$permission]);

    }

    public function assignToRole(Request $request)
    {
        $request->validate([
            'permissionid'=>'required',
            'roleid'=>'required'
        ]);
        $permission = Permission::findById($request->permissionid);
        $role = Role::findById($request->roleid);

        $assignment = $role->givePermissionTo($permission);

        return response()->json(['message'=> 'Permission to give to role','Role'=>$assignment]);
    }

    public function revokePermission(Request $request)
    {
        $request->validate([
            'permissionid'=>'required',
            'roleid'=>'required'
        ]);

        $permission = Permission::findById($request->permissionid);
        $role = Role::findById($request->roleid);

        $unassignment = $role->revokePermissionTo($permission);

        return response() ->json(['message'=>'Permission revoked successfully.','unassignment'=>$unassignment]);
    }
}
