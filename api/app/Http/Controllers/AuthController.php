<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Validator;
use Response;
use Hash;

class AuthController extends Controller
{
    public function register()
    {
        $credentials = request(['name', 'email', 'password']);

        $rules = [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|max:255'
        ];

        $validator = Validator::make($credentials, $rules);
        if($validator->fails()) {
            return response()->json(['status'=> 'error',
                'error'=> $validator->messages()],
                400);
        }

        $name = $credentials['name'];
        $email = $credentials['email'];
        $password = $credentials['password'];

        User::create(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);

        return response()->json(['status'=> 'success', 'message'=> 'Successfully registration!']);
    }
}
