<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\InterventionService;
use App\Models\Intervention;
use Mockery;

class InterventionServiceTest extends TestCase
{
    public static function interventionDataProvider()
    {
        $data = self::getSeedData('interventions.csv');
        return array_map(fn($item) => [$item], $data);
    }

    
    public function test_it_can_load_intervention_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('date_planifiee', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Intervention::class);
        $service = new InterventionService($model);
        $this->assertInstanceOf(InterventionService::class, $service);
    }
}
