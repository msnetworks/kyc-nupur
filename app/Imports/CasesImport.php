<?php

namespace App\Imports;

use App\Models\Cases;
use Maatwebsite\Excel\Concerns\ToModel;

class CasesImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Cases([
            'created_at'     => $row[0], // assuming first column is the name
            'refrence_number'    => $row[1],
        ]);
    }
}
