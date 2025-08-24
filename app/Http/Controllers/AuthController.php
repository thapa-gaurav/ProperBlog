<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(AuthRequest $request): JsonResponse
    {
        //request and resource file should be separate.
        $request->validated();
         $newUser = User::create([
            'name' => $request->name,
            'email'=>$request -> email,
            'password'=>$request->password,
            'isPassChangeReq' => false,
            'role_id'=> $request->role_id,
        ]);

         return response() -> json([
             'message'=>'New user registered.',
             'data'=> $newUser
         ]);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $request->validated();
        $user = User::where('email',$request->email)->first();

        if(!$user || !Hash::check($request->password,$user->password)){
            return response()->json(['message'=>"invalid credentials."]);
        }
        $token = $user->createToken('token-name')->plainTextToken;
        return  response()->json([
            'user'=>$user,
            'token'=>$token
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->tokens()->delete();
        return response()->json('Logged out successfully.');
    }

    public function get(): ?Authenticatable
    {
        return  auth()->user();
    }
}
