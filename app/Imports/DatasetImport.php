<?php

namespace App\Imports;

use App\Models\Dataset;
use Maatwebsite\Excel\Concerns\ToModel;

class DatasetImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Dataset([
            'tweets' => $row[0],
        ]);
    }

    public function batchSize(): int
    {
        return 100;
    }
}
