<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
    {
        $decode = json_decode($request->getContent(), true);

        $request->merge($decode);

        $credentials = $this->validateLogin($request);

        if(! Auth::attempt($credentials)){

            return response()->json([
                'error' => true,
                'message' => 'Unauthorized',
                'status' => 'Unauthenticated',
            ], 401);
        }
            
        $user = $request->user();

        if (is_null($user->api_token)) {
            $user->api_token = Str::random(80);
            $user->save();
        }

        return response()->json([
            'error' => false,
            'message' => 'Successfully Login',
            'redirect' => route('home'),
            'access_token' => auth()->user()->api_token,
            'token_type' => 'Bearer',
            'status' => 'Authenticated'
        ], 200);
    }

    protected function validateLogin(Request $request)
    {
        $credentials = $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
        
        if (str_contains($credentials['username'], '@pertanian.go.id')) {
            $credentials['username'] = str_replace('@pertanian.go.id', '', $credentials['username']);
        }

        if (str_contains($credentials['username'], '-')) {
            $credentials['username'] = str_replace('-', '_', $credentials['username']);
        }

        return $credentials;
    }

    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {

        $this->guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if (request()->ajax()) {
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        }

        return $this->loggedOut($request) ?: redirect('/');
    }

    public function eOfficeLogin(Request $request)
    {
        $this->user = User::whereApiToken($request->getContent())->first();

        if(!$this->user){
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized',
                'status' => 'Unauthenticated',
            ], 401);
        }

        if (! Auth::loginUsingId($this->user->id)) {
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized',
                'status' => 'Unauthenticated',
            ], 401);
        }

        return response()->json([
            'error' => false,
            'message' => 'Successfully Login',
            'redirect' => route('login') .'?_sk='. auth()->user()->api_token,
            'access_token' => auth()->user()->api_token,
            'token_type' => 'Bearer',
            'status' => 'Authenticated'
        ], 200);
    }

    public function autoLogin(Request $request)
    {
        $this->apiToken = $request->secret_key;

        $this->user = User::whereApiToken($this->apiToken)->first();

        if(!$this->user){
            return response()->json([
                'error' => true,
                'message' => 'Unauthorized',
                'status' => 'Unauthenticated',
            ], 401);
        }

        auth()->login($this->user);

        return response()->json([
            'error' => false,
            'message' => 'Successfully Login',
            'redirect' => route('home'),
            'api_token' => $this->apiToken,
            'token_type' => 'Bearer',
            'status' => 'Authenticated'
        ], 200);

    }

}
