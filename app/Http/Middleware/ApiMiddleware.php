<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->input('api_token')){
            $user=User::where('api_token',$request->input('api_token'))->first();
            if (! is_null($user)){
                return $next($request);
            }
            else{
                return response()->json(['data' => [], 'result' => 0, 'description' => 'authentication failed', 'message' => 'Token Not Created']);

            }
        }
        return response()->json(['data' => [], 'result' => 0, 'description' => 'no api token', 'message' => 'Token Not Created']);

    }
}
