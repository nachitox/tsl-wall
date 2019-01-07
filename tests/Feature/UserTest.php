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
    public function testUserLoginCorrectly()
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
			'data' => [
				'access_token'	=> true,
				'expires_in'	=> true,
				'token_type'	=> true,
				'user'			=> true
			]
		]);
    }
	
    /**
     * Test login
     *
     * @return void
     */
    public function testUserLoginIncorrectly()
    {
		$user = factory(User::class)->create([
            'password' => bcrypt($this->faker->password),
        ]);
		
		$response = $this->json('POST', route('users.login'), [
            'email'		=> $user->email,
            'password'	=> 'someother password',
        ]);

        $response->assertStatus(401);
    }
	
    /**
     * Test login
     *
     * @return void
     */
    public function testUserLoginMissingFields()
    {
		$user = factory(User::class)->create([
            'password' => bcrypt($this->faker->password),
        ]);
		
		$response = $this->json('POST', route('users.login'), [
            'email' => $user->email,
        ]);

        $response->assertStatus(422);
		
		$response = $this->json('POST', route('users.login'), [
            'password' => $this->faker->password,
        ]);

        $response->assertStatus(422);
    }
	
    /**
     * Test login
     *
     * @return void
     */
    public function testUserLoginDoesNotExist()
    {
		$response = $this->json('POST', route('users.login'), [
            'email'		=> $this->faker->email,
			'password'	=> $this->faker->password,
        ]);

        $response->assertStatus(401);
    }
	
    /**
     * Test register
     *
     * @return void
     */
    public function testUserRegisterCorrectly()
    {
		$response = $this->json('POST', route('users.register'), [
		   'email'		=> $this->faker->email,
		   'password'	=> $this->faker->password,
		   'first_name'	=> $this->faker->name,
		   'last_name'	=> $this->faker->name,
	   ]);

        $response->assertStatus(200)
		->assertJson([
			'data' => [
				'access_token'	=> true,
				'expires_in'	=> true,
				'token_type'	=> true,
				'user'			=> true
			]
        ]);
    }
	
    /**
     * Test register
     *
     * @return void
     */
    public function testUserRegisterMissingFields()
    {
		$response = $this->json('POST', route('users.register'), [
		   'password'	=> $this->faker->password,
		   'first_name'	=> $this->faker->name,
		   'last_name'	=> $this->faker->name,
	   ]);

        $response->assertStatus(422);
		
		$response = $this->json('POST', route('users.register'), [
		   'email'		=> $this->faker->email,
		   'first_name'	=> $this->faker->name,
		   'last_name'	=> $this->faker->name,
	   ]);

        $response->assertStatus(422);
		
		$response = $this->json('POST', route('users.register'), [
		   'email'		=> $this->faker->email,
		   'password'	=> $this->faker->password,
		   'last_name'	=> $this->faker->name,
	   ]);

        $response->assertStatus(422);
		
		$response = $this->json('POST', route('users.register'), [
		   'email'		=> $this->faker->email,
		   'password'	=> $this->faker->password,
		   'first_name'	=> $this->faker->name,
	   ]);

        $response->assertStatus(422);
    }
}
