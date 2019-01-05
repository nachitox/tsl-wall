<?php
namespace Tests\Feature;

use App\Message;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tests\JWT;

class MessageTest extends TestCase
{
	use RefreshDatabase;
	
	public function testMessagesAreCreatedCorrectly()
	{
		$user = factory(User::class)->create();
		
		$content = $this->faker->text;
		$data = [
			'content'	=> $content,
			'user_id'	=> $user->id,
		];

		$this->actingAs($user, 'api')->json('POST', route('messages.create'), $data)
		->assertStatus(201)
		->assertJson([
			'data' => [
				'content'	=> $content,
				'id'		=> 1,
				'user_id'	=> $user->id,
			]
		]);
	}

	public function testMessagesAreDeletedCorrectly()
	{
		$user = factory(User::class)->create();
		$message = factory(Message::class)->create([
			'content'	=> $this->faker->text,
			'user_id'	=> $user->id,
		]);

		$this->actingAs($user, 'api')->json('DELETE', route('messages.delete', ['id' => $message->id]))
		->assertStatus(204);
	}

	public function testArticlesAreListedCorrectly()
	{
		$expects = [];
		
		for ($i = 0; $i < 5; $i++)
		{
			$user = factory(User::class)->create();
			$content = $this->faker->text;
			$message = factory(Message::class)->create([
				'content'	=> $content,
				'user_id'	=> $user->id,
			]);
			
			$expects[] = [
				'content'	=> $content,
				'user_id'	=> $user->id,
			];
		}

		$response = $this->json('GET', route('messages.index'))
		->assertStatus(200)
		->assertJson(['data' => ['data' => $expects]])
		->assertJsonStructure(['data' => ['data' => ['*' => ['id', 'user_id', 'content', 'deleted_at', 'created_at', 'updated_at']]]]);
	}
}
