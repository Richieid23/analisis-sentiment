<?php

namespace App\Imports;

use App\Models\Training;
use Maatwebsite\Excel\Concerns\ToModel;

class TrainImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Training([
            'tweets'    => $row[0],
            'label'     => $row[1],
        ]);
    }

    public function batchSize(): int
    {
        return 200;
    }
}
