<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class ApiLoginController extends Controller
{
    public function login(Request $request)
    {//$request = $request;
        $token = $request->all();
        //return json_encode($token['api_token']);
    	return User::whereApiToken($token['api_token'])->first();
    }
}
