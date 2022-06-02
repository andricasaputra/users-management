<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiLoginController extends LoginController
{
    public function loginApiUsingUsername(Request $request)
    {

        $credentials = $this->validateLogin($request);

        if(! Auth::attempt($credentials)){

            return response()->json([
                'error' => true,
                'message' => 'Unauthorized',
                'status' => 'Unauthenticated',
            ], 401);
        }

    	return auth()->user();
    }

    public function loginUsingToken(Request $request)
    {
        $token = $request->all();

        return User::whereApiToken($token['api_token'])->first();
    }
}
