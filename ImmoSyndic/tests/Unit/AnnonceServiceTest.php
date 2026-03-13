<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AnnonceService;
use App\Models\Annonce;
use Mockery;

class AnnonceServiceTest extends TestCase
{
    public static function annonceDataProvider()
    {
        $data = self::getSeedData('annonces.csv');
        return array_map(fn($item) => [$item], $data);
    }

    
    public function test_it_can_load_annonce_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('titre', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Annonce::class);
        $service = new AnnonceService($model);
        $this->assertInstanceOf(AnnonceService::class, $service);
    }
}
