<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    const STATUS = [
        "request"               => 0, 
        "chatAccepted"          => 1,
        "active"                => 2,
        "delete"                => 3,
        "no"                    => 4,
    ];

    public function setStatus($status){
        $this->status = Chat::STATUS[$status];
        $user_id = Auth::user()->id;
        $this->to_notified = false;
        $this->from_notified = false;

        if ($this->from_id == $user_id) {
            $this->from_notified = true;
        } 
        else
        {
            $this->to_notified = true;
        }
    }

    public function setNotified(){
        $user_id = Auth::user()->id;
        if ($this->from_id == $user_id) {
            $this->from_notified = true;
        } 
        else
        {
            $this->to_notified = true;
        }
    }


}
  