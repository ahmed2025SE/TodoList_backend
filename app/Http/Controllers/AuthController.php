<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

public function signup(Request $request)
{
    $inputs = $request->validate([
        'name'     => ['required', 'string', 'max:255'],
        'username' => ['required', 'string', 'min:4', 'max:20', 'unique:users'],
        'email'    => ['required', 'email', 'unique:users'],
        'password' => ['required', 'min:8'],// سوف يتم تشفيرها تلقائيًا في كلاس المستخدم
    ]);

    User::create($inputs);

    return response()->json([
        'message' => 'User registered successfully'
    ], 201);
}


    public function login(Request $request)
    {
         $inputs = $request->validate([
        'username' => ['required', 'string', 'max:255'],
        'password' => ['required', 'min:8'],
        ]);


         $user = User::where('username', $inputs['username'])->first();

        if (!$user || !Hash::check($inputs['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ]);
    }

      public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'data' => null,
            'message' => 'Logged out successfully',
            'errors' => null
        ]);
    }

}
