<?php

namespace App\Services;

use App\Models\AuditLog;

class AuditLogService extends BaseService
{
    public function __construct(AuditLog $auditLog)
    {
        $this->model = $auditLog;
    }

    
    public function logAction(int $userId, string $action, string $modelType, int $modelId, array $modifications = [])
    {
        return $this->create([
            'user_id' => $userId,
            'action' => $action,
            'model_type' => $modelType,
            'model_id' => $modelId,
            'modifications' => json_encode($modifications),
            'created_at' => now(),
        ]);
    }
}
