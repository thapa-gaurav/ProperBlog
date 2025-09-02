<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Rules\MatchOldPassword;
use App\Rules\StrongPassword;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $roleString = $request->role;
        if (is_string($request->role)) {
            $request->merge([
                'role' => json_decode($request->role)
            ]);
        }
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'array'],
            'role.*' => ['integer']
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'isPassChangeReq' => false,
            'role_id'=>json_encode($roleString)

        ]);
        foreach ($request->role as $roleId) {
            $user->assignRole(Role::findById($roleId));
        }
        activity()->by(auth()->user())->log("New user was registered.");
        return response()->json(['message' => 'New user was registered along with roles.']);

    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->delete();
        return response()->json(['message' => 'Deleted deleted successfully.']);

    }

    public function index()
    {
        $user = User::all();
        return \response()->json(['user' => $user]);
    }

    public  function editRole($id,Request $request)
    {
        if (is_string($request->role)) {
            $request->merge([
                'role' => json_decode($request->role)
            ]);
        }
        $request->validate([
            'role' => ['required', 'array'],
            'role.*' => ['integer']
        ]);

        $user = User::findOrFail($id);
        $newRoles = [];
        foreach ($request->role as $role){
            array_push($newRoles,Role::findById($role));
        }
        $user->syncRoles($newRoles);
        return response()->json(['message'=>'User role was updated successfully']);
    }
    public  function getUserRoles($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['roles'=>$user->roles]);
    }
    public function flagPasswordChange($id)
    {
        if (auth()->user()->hasPermissionTo('flag pass change') && auth()->user()->getAuthIdentifier() != $id) {
            $user = User::findOrFail($id);
            $user->update([
                'isPassChangeReq' => !($user->isPassChangeReq),
            ]);
            activity()->on($user)->by(auth()->user())->log('Flagged Password change.');
            return \response()->json(['message' => 'change user password flag successfully.']);
        } else {
            return response('Access denied.', Response::HTTP_FORBIDDEN);

        }
    }

    public function changePassword($id,Request $request){
        $request->validate([
            'current_password'=>['required',new MatchOldPassword],
            'password'=>['required','confirmed',new StrongPassword],
            'password_confirmation'=>['required']
        ]);

        $user = User::findOrFail($id);
        if($user){
            $user->update([
                'password'=> $request->password,
            ]);
            return \response()->json(['message'=>'Password was changed successFully.']);
        }else{
            return \response()->json(['message'=>'User not found.']);
        }
    }
}
