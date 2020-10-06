<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\users;
use App\login_tokens;

class RegisterController extends Controller
{
    public function store(Request $request){
        $this->validate($request, [
            'first_name'=>'required|string|min:2|max:20',
            'last_name'=>'required|string|min:2|max:20',
            'username'=>'required|unique:users,username|min:5|max:12|alpha_num',
            'password'=>'required',
        ]);

        $user = users::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        $generateToken = bin2hex(random_bytes(20));
        
        $token = login_tokens::create([
            'user_id' => $user->id,
            'token' => $generateToken,
        ]);

        return response()->json([
            'message'=>'success',
            'token'=>$token->token,
        ],200);
    }

    public function logout(){
        
    }
}
