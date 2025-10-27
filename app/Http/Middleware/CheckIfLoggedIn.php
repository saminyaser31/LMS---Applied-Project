<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckIfLoggedIn
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
        // Check if the user is already logged in
        if (Auth::check()) {
            // Redirect back to the previous page if logged in
            return redirect()->back();
        }

        // Allow the request to proceed if the user is not logged in
        return $next($request);
    }
}
