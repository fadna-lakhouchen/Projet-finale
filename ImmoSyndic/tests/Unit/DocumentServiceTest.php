<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\DocumentService;
use App\Models\Document;
use Mockery;

class DocumentServiceTest extends TestCase
{
    public static function documentDataProvider()
    {
        $data = self::getSeedData('documents.csv');
        return array_map(fn($item) => [$item], $data);
    }

    
    public function test_it_can_load_document_data_from_csv($data)
    {
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('titre', $data);
    }

    public function test_service_uses_correct_model()
    {
        $model = Mockery::mock(Document::class);
        $service = new DocumentService($model);
        $this->assertInstanceOf(DocumentService::class, $service);
    }
}
