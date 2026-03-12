<?php

namespace App\Services;

use App\Models\Document;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DocumentService extends BaseService
{
    public function __construct(Document $document)
    {
        $this->model = $document;
    }

    /**
     * Upload a file securely and create a document record.
     */
    public function uploadDocument(UploadedFile $file, array $data, string $folder = 'documents')
    {
        $path = $file->store($folder, 'public');
        
        return $this->create(array_merge($data, [
            'fichier_path' => $path,
        ]));
    }
}
