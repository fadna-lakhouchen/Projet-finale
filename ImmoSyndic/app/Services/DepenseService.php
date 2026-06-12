<?php

namespace App\Services;

use App\Models\Depense;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class DepenseService extends BaseService
{
    public function __construct(Depense $depense)
    {
        $this->model = $depense;
    }

    /**
     * Create an expense and upload the receipt if provided.
     */
    public function storeExpense(array $data, ?UploadedFile $file = null, string $folder = 'justificatifs')
    {
        $justificatifPath = null;
        if ($file) {
            $justificatifPath = $file->store($folder, 'public');
        }

        return $this->create(array_merge($data, [
            'justificatif_path' => $justificatifPath,
        ]));
    }
}
