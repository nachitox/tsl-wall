<?php
namespace Tests;

use Illuminate\Contracts\Auth\Authenticatable;
use Tymon\JWTAuth\Facades\JWTAuth;

trait JWT
{
	public function actingAs(Authenticatable $user, $driver = null)
	{
	    $token = JWTAuth::fromUser($user);
	    $this
		->withHeader('Accept', 'application/json')
		->withHeader('Authorization', 'Bearer ' . $token);

	    return $this;
	}
}
