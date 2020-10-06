<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\users;
use App\login_tokens;

class UserController extends Controller
{
    public function logout(Request $request, $token){
        $tokens = login_tokens::where('token', $token)->first();
        if(is_null($tokens)){
            return response()->json(['message'=>'invalid token'],401);
        }else{
            $tokens->delete();
            return response()->json(['message'=>'berhasil logout'],200);
        }
    }
}
