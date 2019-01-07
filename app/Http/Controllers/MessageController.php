<?php

namespace App\Http\Controllers;

use App\Http\Resources\MessageResource;
use App\Message;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::with('user')
		->orderBy('created_at', 'desc')
		->paginate(25);
		
		$response = [
			'data' => $messages,
		];
		return response()->json($response, 200);
    }

    /**
     * Create a new message in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
		$user = JWTAuth::parseToken()->toUser();
		
		$validator = Validator::make($request->all(), [
			'content' => 'required|string',
		]);
		if ($validator->fails())
		{
			$response = [
				'errors' => $validator->errors()
			];
			
			return response()->json($response, 422);
		}
		
		$message = Message::create([
			'user_id'	=> $user->id,
			'content'	=> filter_var($request->content, FILTER_SANITIZE_STRING),
		]);
		
		$response = [
			'data' => $message->load('user'),
		];
		return response()->json($response, 201);
    }

    /**
     * Remove a message from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
		$user = JWTAuth::parseToken()->toUser();
		
		$message = Message::findOrFail($id);
		if ($message->user_id == $user->id)
			$message->delete();
		
		$response = [
			'success' => $message->user_id == $user->id,
		];
		return response()->json($response, 204);
    }
}
