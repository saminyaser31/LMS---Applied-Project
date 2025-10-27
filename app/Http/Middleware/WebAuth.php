<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebAuth
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
        if (!auth()->check()) {
            $request->session()->put('url.intended', $request->url());
            return redirect()->guest(route('student.login-page'));

            return redirect()->guest(route('student.login-page'))->with('url.intended', $request->url());
        }

        return $next($request);
    }
}
