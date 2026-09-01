<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AuditLogService;
use App\Models\AuditLog;
use Mockery;

class AuditLogServiceTest extends TestCase
{
    public static function auditLogDataProvider()
    {
        $data = self::getSeedData('audit_logs.csv');
        return array_map(fn($item) => [$item], $data);
    }

    /**
     * @dataProvider auditLogDataProvider
     */
    public function test_it_can_load_audit_log_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('action', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(AuditLog::class);
        $service = new AuditLogService($model);
        $this->assertInstanceOf(AuditLogService::class, $service);
    }
}
