<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        //Check email
        $email = User::where("email", $request->email)->exists();

        if (User::where('email', $email)->exists()) {
            return response()->json(['message' => 'Invalid login credentials.'], 401);
        }

        //check user status 
        $status = User::where('email', $request->email)->first();

        if ($status->status == 0) {
            return response()->json(['message' => 'Profile already blocked. Contact admin.'], 345);
        }

        //check user type
        if ($status->role == 1 || $status->role == 2) {
            return response()->json(['message' => 'Invalid login credentialsee.'], 401);
        }

        $credentials = $request->only('email', 'password');

        $imei = $request->imei ?? null;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Delete old tokens
            $user->tokens()->delete();

            // Create new token
            $token = $user->createToken('Insurance API Auth')->accessToken;

            // Update the login_request column to 1 on every login attempt
            if ($user) {
                $user->login_request = 1;
                $user->save();
            }

            DB::table('users')->where('id', $user->id)->update(['imei' => $imei]);


            $response = [
                'token' => $token,
                'name' => $user->name,
                'place' => $user->place,
                'phone' => $user->phone,
                'email' => $user->email,
                'user_id' => $user->id,
                'imei' => $imei,
            ];

            return response()->json($response, 200);
        }

        return response()->json(['message' => 'Invalid login credentials.'], 401);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();

            return response()->json(['message' => 'Successfully logged out.'], 200);
        }

        return response()->json(['message' => 'User not authenticated.'], 401);
    }

}

