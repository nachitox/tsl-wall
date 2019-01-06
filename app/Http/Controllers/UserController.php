<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;

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
		
		return $this->respondWithToken($token);
	}
	
	public function login(UserRequest $request)
    {
		$credentials = $request->only(['email', 'password']);
		
		if (!$token = auth()->attempt($credentials))
			return response()->json(['error' => 'Credentials do not exist'], 200);
		
		return $this->respondWithToken($token);
    }

    protected function respondWithToken($token)
    {
		return response()->json([
			'access_token'	=> $token,
			'token_type'	=> 'bearer',
			'expires_in'	=> auth()->factory()->getTTL() * 60
		]);
    }
}
