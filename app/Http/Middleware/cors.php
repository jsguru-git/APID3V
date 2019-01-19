<?php

namespace App\Http\Middleware;
use Response;

use Closure;

class cors
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
        if(!isset($_SERVER['HTTP_X_ORIGIN']))
        {  
            return Response::json(array('error' => 'Origin header is missing.'));  
        }  
  
        if($_SERVER['HTTP_X_ORIGIN'] != 'www.example.com')
        {  
            return Response::json(array('error' => 'Invalid Origin header.'));  
        }  
  
        return $next($request);  
    }
}
