<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use Illuminate\Database\Seeder;

class AuditLogSeeder extends Seeder
{
    public function run(): void
    {
        $file = database_path('seeders/data/audit_logs.csv');
        $rows = array_map('str_getcsv', file($file));
        $header = array_shift($rows);

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            foreach ($data as $key => $value) {
                if ($value === '' || $value === 'NULL') {
                    $data[$key] = null;
                } elseif (is_string($value) && (str_starts_with($value, '{') || str_starts_with($value, '['))) {
                    $data[$key] = json_decode($value, true);
                }
            }

            AuditLog::unguarded(function () use ($data) {
                AuditLog::firstOrCreate(
                    ['id' => $data['id']],
                    $data
                );
            });
        }
    }
}
