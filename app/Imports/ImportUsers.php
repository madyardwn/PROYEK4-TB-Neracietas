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
        // ignore header or first row
        if ($row[0] == 'nim'
            && $row[1] == 'name'
            && $row[2] == 'email'
            && $row[3] == 'password'
            && $row[4] == 'year'
        ) { return null;
        }

        // check if user already exists just update it
        $user = User::where('nim', $row[0])->first();
        if ($user) {
            $user->update(
                [
                'name' => $row[1],
                'email' => $row[2],
                'password' => bcrypt($row[3]),
                'year' => $row[4],
                ]
            );
            return null;
        }

        return new User(
            [
            'nim' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'password' => bcrypt($row[3]),
            'year' => $row[4],
            ]
        );
    }
}
