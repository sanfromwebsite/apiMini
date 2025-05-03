<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $req){
        $req->validate([
            'name' => ['required','string','max:250'],
            'email' => ['required','email','max:250','unique:users,email'],
            'password' => ['required','string','min:8','confirmed'],
            'contact' => ['required','string','max:15','unique:customers,contact']
        ]);

        $user = new User();
        $user->name = $req->input('name');
        $user->email = $req->input('email');
        $user->password = Hash::make($req->input('password'));
        $user->role_id = 1;
        $user->save();
        $user->customer()->create([
            'contact' => $req->input('contact')
        ]);
        $token = $user->createToken($user->id)->plainTextToken;
        return response()->json([
            'result' => true,
            'message' => 'registered successfully',
            'data' =>[
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->customer->contact,
                'token' => $token,
                'role' =>[
                    'id' => $user->role->id,
                    'name' => $user->role->name
                ]
             ] 
        ]);
    }

    public function login(Request $req){
        $req->validate([
            'email' => ['required','email','max:250'],
            'password' => ['required','string','min:8']
        ]);
        $user = User::where('email',$req->input('email'))->first(['id','name','email','password']);
        if(!$user){
            return response()->json([
                'result' => false,
                'message' => 'user not found'
            ]);
        }

        if(!Hash::check($req->input('password'),$user->password)){
            return response()->json([
                'result' => false,
                'message' => 'password not match'
            ]);
        }

        $token = $user->createToken($user->id)->plainTextToken;
        return response()->json([
            'result' => true,
            'message' => 'login successfully',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'contact' => $user->customer->contact,
                'token' => $token
            ]
        ]);
    }

    public function logout(Request $req){
        $req->user('sanctum')->currentAccessToken()->delete();
        return response()->json([
            'result' => true,
            'message' => 'logout successfully',
            'data' => []
        ]);
    }
}
