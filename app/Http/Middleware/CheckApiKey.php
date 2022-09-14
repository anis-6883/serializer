<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiKey
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->api_key == "")
        {
            return response()->json(['status' => false, 'message' => "Please, Provide Key!"], 401);
            exit();
        }
        else
        {
            if($request->api_key == env('API_KEY'))
                return $next($request);
            else
            {
                return response()->json(['status' => false, 'message' => "Please, Provide Valid Key!"], 401);
                exit();
            }
        }
    }
}
