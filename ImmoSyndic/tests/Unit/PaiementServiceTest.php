<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\PaiementService;
use App\Models\Paiement;
use Mockery;

class PaiementServiceTest extends TestCase
{
    public static function paiementDataProvider()
    {
        $data = self::getSeedData('paiements.csv');
        return array_map(fn($item) => [$item], $data);
    }

    /**
     * @dataProvider paiementDataProvider
     */
    public function test_it_can_load_paiement_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('montant', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Paiement::class);
        $service = new PaiementService($model);
        $this->assertInstanceOf(PaiementService::class, $service);
    }
}
