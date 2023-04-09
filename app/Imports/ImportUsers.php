<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportUsers implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new User([
            'nim' => $row[1],
            'email' => $row[5],
            'name' => $row[6],
            'year' => $row[7],
            'password' => 'password',
        ]);
    }
}
