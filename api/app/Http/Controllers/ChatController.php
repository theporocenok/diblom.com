<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Chat;
use Response;

class ChatController extends Controller
{
	public function __construct(){
		$this->middleware("auth:api");
	}

    public function create(){
        $from_id = Auth::user()->id;
        $to_id = request('user_id');
        $first_num=request('first_num');
        $second_num=request('second_num');
        $newChat = new Chat();
        $newChat->from_id = $from_id;
        $newChat->to_id = $to_id;
        $newChat->first_num = $first_num;
        $newChat->to_num = $second_num;
        $newChat->status = Chat::STATUS["request"];
        $newChat->setStatus("request");
        $newChat->save();

        return response()->json([
            "status" => 1,
            "response" => [
                "message"=>"Request create chat accepted",
                "chat_id"=>$newChat->id
            ]  
        ]);

    }

    public function acceptChatRequest(){
        $user_id = Auth::user()->id;

        $chat_id = request('chat_id');
        $first_num = request('number');

        $chat = Chat::where('id',$chat_id)->where('to_id',$user_id)->first();
        if (!$chat) return null;

        $chat->from_num = $first_num;
        $chat->setStatus("chatAccepted");
        $chat->save();
        return response()->json([
            "status" => 1,
            "response" => [
                "message"=>"Chat request accepted"
            ]  
        ]);
    }
}
