<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Services\AuditLogService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class AuditLogsController extends Controller
{

    /**
     * @var AuditLogService
     */
    public $auditLogService;

    public function __construct(AuditLogService $auditLogService)
    {
        $this->auditLogService = $auditLogService;
    }

    public function index(Request $request)
    {
        abort_if(Gate::denies('audit_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $query = AuditLog::select('id', 'description', 'subject_id', 'subject_type', 'created_at', 'user_id');
            $auditLogs = $this->auditLogService->filter( $request->input('description'), $request->input('subject_id'), $request->input('subject_type'), $request->input('user_id'), $query, $request->per_page);

            return view('admin.auditLogs.index', compact('auditLogs'));

        } catch (Exception $exception) {
            Log::error("AuditLogsController::index()", [$exception]);
            return back()->withErrors("Something went wrong!");
        }
    }

    public function show(AuditLog $auditLog)
    {
        abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $auditLog;
    }
}
