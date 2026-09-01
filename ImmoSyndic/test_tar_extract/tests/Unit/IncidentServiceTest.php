<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\IncidentService;
use App\Models\Incident;
use Mockery;

class IncidentServiceTest extends TestCase
{
    public static function incidentDataProvider()
    {
        $data = self::getSeedData('incidents.csv');
        return array_map(fn($item) => [$item], $data);
    }

    /**
     * @dataProvider incidentDataProvider
     */
    public function test_it_can_load_incident_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('description', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Incident::class);
        $service = new IncidentService($model);
        $this->assertInstanceOf(IncidentService::class, $service);
    }
}
