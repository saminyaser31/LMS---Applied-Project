<?php

namespace App\Services;

use Illuminate\Http\Request;

class AuditLogService
{

    /**
     * @param Request $request
     * @param $query
     * @return mixed
     */
    public function filter( $description = null, $subjectId = null, $subjectType = null, $userId = null, $query = null, $perPage = null)
    {
        if ($description) {
            $query->where('audit_logs.description',  $description);
        }
        if ($subjectId) {
            $query->where('audit_logs.subject_id', $subjectId );
        }
        if ($subjectType) {
            $query->where('audit_logs.subject_type', 'LIKE', '%' .$subjectType .'%' );
        }
        if ($userId) {
            $query->where('audit_logs.user_id', $userId);
        }
        $query->orderBy('id', 'DESC');
        return $query->paginate($perPage ?? 20);
    }

}
