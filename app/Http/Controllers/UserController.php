<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        if(auth()->user()->hasPermissionTo('show users')){
            return User::all();
        }
        else{
            return response('Access denied.',Response::HTTP_FORBIDDEN);
        }
    }

    public function flagPasswordChange($id)
    {
        if(auth()->user()->hasPermissionTo('flag pass change') && auth()->user()->getAuthIdentifier() != $id){
            $user = User::findOrFail($id);
            $user->update([
                'isPassChangeReq'=>!($user->isPassChangeReq),
            ]);
            activity()->on($user)->by(auth()->user())->log('Flagged Password change.');
            return \response()->json(['message'=>'change user password flag successfully.']);
        }else{
            return response('Access denied.',Response::HTTP_FORBIDDEN);

        }
    }
}
