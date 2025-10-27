<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

class CheckLogViewerPermissions
{
    public function handle(Request $request, Closure $next)
    {
        // Check if the user has permission to view the log viewer
        // if (Gate::denies('access_log_viewer')) {
        //     abort(403, 'You do not have permission to view the logs.');
        // }

        // Check if the user has permission to download logs
        if ($request->is('log-viewer/download/*') && Gate::denies('download_log_viewer')) {
            abort(403, 'You do not have permission to download this log file.');
        }

        // Check if the user has permission to delete logs
        if ($request->is('log-viewer/delete/*') && Gate::denies('delete_log_viewer')) {
            abort(403, 'You do not have permission to delete this log file.');
        }

        return $next($request);
    }
}

