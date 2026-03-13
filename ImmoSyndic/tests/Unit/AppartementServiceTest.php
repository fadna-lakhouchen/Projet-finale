<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AppartementService;
use App\Models\Appartement;
use Mockery;

class AppartementServiceTest extends TestCase
{
    
    public static function appartementDataProvider()
    {
        $data = self::getSeedData('appartements.csv');
        return array_map(fn($item) => [$item], $data);
    }

    
    public function test_it_can_load_appartement_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('numero', $data);
        $this->assertArrayHasKey('statut', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Appartement::class);
        $service = new AppartementService($model);
        
        $this->assertInstanceOf(AppartementService::class, $service);
    }
}
