<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\users;
use App\login_tokens;

class UserController extends Controller
{
    public function login(Request $request){
        $this->validate($request, [
            'username'=>'required',
            'password'=>'required',
        ]);

        $user = users::where('username', $request->username)->first();

        if(!$user){
            return response()->json(['message'=>'Wrong username or password'],401);
        }
        else if($user){
            if(Hash::check($request->password, $user->password)){
                $generateToken = bin2hex(random_bytes(20));
                
                \DB::table('login_tokens')->where('user_id', $user->id)->delete();

                $token = login_tokens::create([
                    'user_id' => $user->id,
                    'token' => $generateToken,
                ]);

                return response()->json([
                    'message'=>'login successful',
                    'token'=>$token->token,
                ],200);
            }else{
                return response()->json(['message'=>'Wrong username or password'],401);
            }
        }
    }

    public function register(Request $request){
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
