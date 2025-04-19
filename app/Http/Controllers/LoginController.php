<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    //
    public function login(Request $request)
    {
        // return response()->json(['status'=>"failed", 'request'=>$request->all()], 500);

        $email = $request->email;
        $password = $request->password;

        $credentials = $request->validate([
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->where('status', 'ACTIVE')->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Email or Password!'
            ], 401);
        }

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json([
                "status" => "success",
                "message" => "Login Successfully"
            ], 200);

        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials!'
        ], 401);

    }

    public function logout()
    {
        session()->flush();
        return redirect('/');
    }
}
