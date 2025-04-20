<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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

    public function login_api(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $response = Http::asForm()->post(config('services.passport.login_endpoint', url('/oauth/token')), [
            'grant_type' => 'password',
            'client_id' => config('services.passport.client_id'),
            'client_secret' => config('services.passport.client_secret'),
            'username' => $request->email,
            'password' => $request->password,
            'scope' => '',
        ]);

        if ($response->failed()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid credentials!',
                'details' => $response->json()
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Login Successfully',
            'token' => $response->json(),
        ]);
    }

    public function logout_api(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'status' => 'success',
            'message' => 'Logout Successfully'
        ]);
    }

}
