<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Mail\UserWelcomeMail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use JWTAuth;
use Validator;

class UserController extends Controller
{
    public function register(UserRequest $request)
    {
		$user = User::create([
			'email'			=> $request->email,
			'password'		=> bcrypt($request->password),
			'first_name'	=> $request->first_name,
			'last_name'		=> $request->last_name,
		]);
		
		$token = auth()->login($user);
		
		Mail::to($user->email)->send(new UserWelcomeMail($user));
		
		return $this->respondWithToken($token);
	}
	
	public function login(Request $request)
    {
		$validator = Validator::make($request->all(), [
            'email'		=> 'max:255|required|string',
            'password'	=> 'required|string',
        ]);
		if ($validator->fails())
		{
			$response = [
				'errors' => $validator->errors()
			];
			
			return response()->json($response, 422);
		}
		
		$credentials = $request->only(['email', 'password']);
		
		if (!$token = auth()->attempt($credentials))
			return response()->json(['error' => 'Credentials are not valid'], 401);
		
		return $this->respondWithToken($token);
    }
	
	public function keepAlive(Request $request)
    {
		$token = JWTAuth::getToken();

    	try 
		{
        	$token = JWTAuth::refresh($token);
    	}
		catch (JWTException $e)
		{
        	return response()->json(['error' => 'Token could not be refresh'], 500);
    	}
		
		return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
		$response = [
			'data' => [
				'access_token'	=> $token,
				'expires_in'	=> auth()->factory()->getTTL(),
				'token_type'	=> 'bearer',
				'user'			=> Auth::user(),
			],
		];
		
		return response()->json($response);
    }
}
