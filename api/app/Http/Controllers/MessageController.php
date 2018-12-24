<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Message;
use App\Http\Resources\MessageResource;
use App\Http\Resources\MessageCollection;
use \Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Chat;

class MessageController extends Controller
{
    public function __construct(){
        $this->middleware('auth:api');
    }

    public function send(Request $request){
        $data = $request->json()->all();
        $chat_id=$data['chat_id'];
        $text_message = $data['message'];
        $user_id = Auth::user()->id;
        $chat=Chat::where('id',$chat_id)->first();

        if ($chat && ($chat->from_id==$user_id || $chat->to_id==$user_id)){
            $message=new Message();
            $message->chat_id=$chat_id;
            $message->message=$text_message;
            $message->save();
            return response()->json([
                "status" => 1,
                "response" => [
                    "message"=>"Message sended"
                ]  
            ]);
        }

        return response()->json([
            "status" => 0,
            "response" => [
                "message"=>"Access denied"
            ]  
        ]);
    }
}
