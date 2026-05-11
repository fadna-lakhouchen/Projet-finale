<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\UserService;
use App\Models\User;
use Mockery;
use Illuminate\Support\Facades\Hash;

class UserServiceTest extends TestCase
{
    
    public static function userDataProvider()
    {
        $data = self::getSeedData('users.csv');
        return array_map(fn($item) => [$item], $data);
    }

    
    public function test_it_can_load_user_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('nom', $data);
        $this->assertArrayHasKey('prenom', $data);
        $this->assertArrayHasKey('email', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(User::class);
        $service = new UserService($model);
        
        $this->assertInstanceOf(UserService::class, $service);
    }
}
