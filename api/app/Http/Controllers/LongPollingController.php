<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Chat;

use Response;

class LongPollingController extends Controller
{
	public function __construct(){
		$this->middleware('auth:api');
	}

    public function longpolling(){
    	
    	$limitTime = 10;
    	set_time_limit($limitTime+5);

		$startTime = microtime(true);
		while ( round(microtime(true)-$startTime)<=$limitTime ){
			$newChats = $this->checkNewChats();
			if ($newChats){
				return response()->json(['newChat'=>json_encode($newChats)]);
			}
			sleep(1);
		}

		return response()->json(['']);

    }


    private function checkNewChats(){
    	$user_id = Auth::user()->id; 

    	$chats = Chat::where('to_id',$user_id)
                ->where('status',Chat::STATUS['request'])
                ->where('to_notified',false)
                ->get();
    	foreach($chats as $chat){
    		$chat->setNotified();
    		$chat->save();
    	}
    	if($chats->isEmpty()) return null;
    	return $chats;
    }

    private function checkAcceptedChats(){
        $user_id = Auth::user()->id; 
        $chats = Chat::where('to_id',$user_id)
                ->where('status',Chat::STATUS['chatAccepted'])
                ->where('from_notified',false)
                ->get();
        foreach($chats as $chat){
            $chat->setNotified();
            $chat->save();
        }
        if($chats->isEmpty()) return null;
        return $chats;
    }

}
