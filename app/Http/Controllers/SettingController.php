<?php

namespace App\Http\Controllers;

use \AppModels\\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
	{
		$token = auth()->user()->api_token;

		return view('setting.index')->withToken($token);
	}

	public function showToken(Request $request)
	{
		$admin = $request->user()->hasRole('administrator');

        if ($admin) {
            $token = User::all()->map(function($users){
            	return $users->api_token;
            });
        } else {
        	$token = $request->user()->map(function($user){
            	return $user->api_token;
            });
        }

        return $token;
	}

    /**
     * Update the authenticated user's API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function generateToken(Request $request)
    {	
        $token = Str::random(60);

        $request->user()->forceFill([
            'api_token' => $token
        ])->save();

        return [
        	'token' => $token,
        	'alert' => 'alert-success',
        	'message' =>'API Token baru berhasil digenerate'
        ];
    }
}
