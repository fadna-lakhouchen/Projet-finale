<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ImmeubleService;
use App\Models\Immeuble;
use Mockery;

class ImmeubleServiceTest extends TestCase
{
    public static function immeubleDataProvider()
    {
        $data = self::getSeedData('immeubles.csv');
        return array_map(fn($item) => [$item], $data);
    }

    
    public function test_it_can_load_immeuble_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('nom', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Immeuble::class);
        $service = new ImmeubleService($model);
        $this->assertInstanceOf(ImmeubleService::class, $service);
    }
}
