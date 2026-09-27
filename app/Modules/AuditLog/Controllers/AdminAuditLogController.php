<?php

namespace App\Modules\AuditLog\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AuditLog\Models\AuditLog;
use App\Modules\AuditLog\Services\AuditQueryService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuditLogController extends Controller
{
    public function __construct(
        protected AuditQueryService $auditQueryService
    ) {}

    /**
     * Display filtered audit logs.
     */
    public function index(Request $request): View
    {
        $filters = $request->only(['module', 'action', 'actor_id', 'date_from', 'date_to']);
        $logs = $this->auditQueryService->getFilteredLogs($filters);
        $modules = $this->auditQueryService->getRecordedModules();

        return view('admin.audit-logs.index', [
            'logs' => $logs,
            'filters' => $filters,
            'modules' => $modules,
        ]);
    }

    /**
     * View detailed audit log entry including values diff.
     */
    public function show(AuditLog $auditLog): View
    {
        $auditLog->load('actor');

        return view('admin.audit-logs.show', [
            'log' => $auditLog,
        ]);
    }
}
