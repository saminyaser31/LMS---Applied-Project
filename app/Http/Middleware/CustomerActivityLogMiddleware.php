<?php

namespace App\Http\Middleware;

use App\Models\CustomerActivityLogTracking;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CustomerActivityLogMiddleware
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
        try {
            CustomerActivityLogTracking::insertOrIgnore(
                [
                    'customer_id'    => auth()->check() ? auth()->user()->id : null,
                    'url'            => request()->fullUrl(),
                    'method'         => request()->method(),
                    'ip'             => request()->ip(),
                    'agent'          => request()->header('user-agent'),
                    'created_at'     => Carbon::now(),
                    'updated_at'     => Carbon::now(),
                ]
            );
        } catch (Exception $exception) {
            Log::error("Customer log middleware error", [$exception]);
        }

        return $next($request);
    }
}
