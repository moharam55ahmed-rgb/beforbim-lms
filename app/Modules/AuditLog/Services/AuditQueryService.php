<?php

namespace App\Modules\AuditLog\Services;

use App\Modules\AuditLog\Models\AuditLog;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AuditQueryService
{
    /**
     * Query and filter immutable audit logs.
     *
     * @param  array<string, mixed>  $filters
     */
    public function getFilteredLogs(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = AuditLog::with('actor')->latest('created_at');

        if (! empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (! empty($filters['action'])) {
            $query->where('action', 'like', '%'.$filters['action'].'%');
        }

        if (! empty($filters['actor_id'])) {
            $query->where('actor_id', $filters['actor_id']);
        }

        if (! empty($filters['date_from'])) {
            $query->where('created_at', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }

        if (! empty($filters['date_to'])) {
            $query->where('created_at', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Get distinct modules recorded in audit logs.
     *
     * @return array<int, string>
     */
    public function getRecordedModules(): array
    {
        return AuditLog::distinct()->pluck('module')->filter()->values()->toArray();
    }
}
