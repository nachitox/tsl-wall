<?php
namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
	use RefreshDatabase;
	
    /**
     * Test login
     *
     * @return void
     */
    public function testLogin()
    {
		$password = $this->faker->password;
		$user = factory(User::class)->create([
            'password' => bcrypt($password),
        ]);
		
		$response = $this->json('POST', route('users.login'), [
            'email'		=> $user->email,
            'password'	=> $password,
        ]);

        $response->assertStatus(200)
		->assertJson([
			'access_token'	=> true,
			'token_type'	=> true,
			'expires_in'	=> true,
		]);
    }
	
    /**
     * Register login
     *
     * @return void
     */
    public function testRegister()
    {
		$response = $this->json('POST', route('users.register'), [
		   'email'		=> $this->faker->email,
		   'password'	=> $this->faker->password,
		   'first_name'	=> $this->faker->name,
		   'last_name'	=> $this->faker->name,
	   ]);

        $response->assertStatus(200)
		->assertJson([
			'access_token'	=> true,
			'token_type'	=> true,
			'expires_in'	=> true,
        ]);
    }
}
