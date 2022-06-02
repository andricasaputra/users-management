<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $token = User::all()->map(function($user){
            	return $token = [
                    'username' => $user->username,
                    'api_token' => $user->api_token
                ];
            });
        } else {
        	$token = [
                'username' => $request->user()->username,
                'api_token' => $request->user()->api_token
            ];
        }

        return response()->json($token);
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
