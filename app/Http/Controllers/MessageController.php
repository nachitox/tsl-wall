<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MessageResource;
use App\Message;

class MessageController extends Controller
{
	/*public function __construct()
	{
		$this->middleware('auth:api')->except(['index']);
	}*/
	
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::orderBy('created_at', 'desc')
		->paginate(25);
		
		$response = [
			'data'		=> $messages,
			'success'	=> true,
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
		$message = Message::create([
			'user_id'	=> $request->user()->id,
			'content'	=> $request->content,
		]);
		
		$response = [
			'data'		=> $message,
			'success'	=> true,
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
		$message = Message::findOrFail($id);
		$message->delete();
		
		$response = [
			'success'	=> true,
		];
		return response()->json($response, 204);
    }
}
