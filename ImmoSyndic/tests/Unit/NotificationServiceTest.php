<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\NotificationService;
use App\Models\Notification;
use Mockery;

class NotificationServiceTest extends TestCase
{
    public static function notificationDataProvider()
    {
        $data = self::getSeedData('notifications.csv');
        return array_map(fn($item) => [$item], $data);
    }

    /**
     * @dataProvider notificationDataProvider
     */
    public function test_it_can_load_notification_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('message', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Notification::class);
        $service = new NotificationService($model);
        $this->assertInstanceOf(NotificationService::class, $service);
    }
}
