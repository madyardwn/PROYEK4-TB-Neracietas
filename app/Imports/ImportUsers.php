<?php

namespace App\Imports;

use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Periode;
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
            && $row[5] == 'nama_bagus'
            && $row[6] == 'na'
            && $row[7] == 'department_name'
            && $row[8] == 'avatar'
            && $row[9] == 'role'
        ) { return null;}

        $user = User::where('nim', $row[0])->first();
        if ($user) {
            $user->update([                
                'name' => $row[1],
                'email' => $row[2],
                'password' => bcrypt($row[3]),
                'year' => $row[4],
                'nama_bagus' => $row[5],
                'na' => $row[6],
                'avatar' => $row[8],
            ]);
            $user->assignRole($row[9]);

            // update periode latest
            $cabinet = Cabinet::where('is_active', true)->first();
            $department = Department::where('short_name', $row[7])
                ->first();
            $periode = Periode::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($periode) {
                $periode->update([
                    'is_active' => false,
                ]);
            }

            Periode::create([
                'user_id' => $user->id,
                'cabinet_id' => $cabinet->id,
                'department_id' => $department->id ?? 1,
                'role_id' => $user->roles->first()->id,
                'is_active' => true,
            ]);
            
            return null;
        }

        $user = User::create([
            'nim' => $row[0],
            'name' => $row[1],
            'email' => $row[2],
            'password' => bcrypt($row[3]),
            'year' => $row[4],
            'nama_bagus' => $row[5],
            'na' => $row[6] ?? null,
            'avatar' => $row[8],
        ]);
        $user->assignRole($row[9]);

        $cabinet = Cabinet::where('is_active', true)->first();

        $department = Department::where('short_name', $row[7])
            ->first();

        Periode::create([
            'user_id' => $user->id,
            'cabinet_id' => $cabinet->id,
            'department_id' => $department->id ?? 1,
            'role_id' => $user->roles->first()->id,
            'is_active' => true,
        ]);

        return $user;
    }
}
