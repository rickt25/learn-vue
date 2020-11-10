<?php

namespace App\Http\Middleware;

use Closure;
use App\login_tokens;
use Route;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->token){
            $user = login_tokens::where('token', $request->token)->first();
                if(is_null($user)){
                    
                    return response()->json(['message'=>'unauthorized user',],401);
                }
                else{
                    return $next($request);
                }
        }else{
            return response()->json(['message'=>'unauthorized user',],401);
        }
        
    }
}
