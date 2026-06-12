<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\ChargeService;
use App\Models\Charge;
use Mockery;

class ChargeServiceTest extends TestCase
{
    public static function chargeDataProvider()
    {
        $data = self::getSeedData('charges.csv');
        return array_map(fn($item) => [$item], $data);
    }

    /**
     * @dataProvider chargeDataProvider
     */
    public function test_it_can_load_charge_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('montant', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Charge::class);
        $service = new ChargeService($model);
        $this->assertInstanceOf(ChargeService::class, $service);
    }
}
