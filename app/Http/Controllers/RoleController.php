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
        $request->validate([
            'name' => ['required']
        ]);
        activity()->by(auth()->user())->log("New role was created");
        Role::create(['name'=>$request->name]);
        return response()->json(['message'=>'New Role created']);
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

    public function getPermissions()
    {
        return Permission::get();
    }

    public function getRoles()
    {
        $roles = Role::get();
        return $roles;

    }

}
