<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepartmentsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/departments",
     *     summary="GET departments",
     *     description="Return data departments yang akan ditampilkan pada halaman departemen mobile",
     *     tags={"Departments"},
     *     security={{"sanctum":{}}},
     * @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     * @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     )
     * )
     */
    public function index()
    {
        $departments = Department::query()
            ->select(
                [
                    'departments.id',
                    'departments.name',
                    'departments.short_name',
                    'departments.description',
                    'cabinets.name as cabinet_name',
                    DB::raw("CONCAT('" . asset('/storage') . "/', departments.logo) as logo"),
                ]
            )
            ->leftJoin('cabinets', 'cabinets.id', '=', 'departments.cabinet_id')
            ->where('cabinets.is_active', 1)
            ->where('departments.name', '!=', 'Majelis Perwakilan Anggota')
            ->where('departments.name', '!=', 'Himpunan Mahasiswa Teknik Komputer Polban')
            ->get();

        $departments = $departments->map(
            function ($department) {
                $department->users = $department->users()
                    ->select(['users.id', 'users.name', 'users.avatar', 'roles.name as role'])
                    ->leftJoin('roles', 'users_departments.position', '=', 'roles.id')
                    ->where('users_departments.is_active', 1)
                    ->where(
                        function ($query) {
                            $query->where('roles.name', 'like', '%Ketua Divisi%')
                                ->orWhere('roles.name', 'like', '%Wakil Ketua Divisi%');
                        }
                    )
                    ->get()
                    ->map(
                        function ($user) {
                            $user->avatar = asset('/storage/' . $user->avatar);
                            return $user;
                        }
                    );
                return $department;
            }
        );

        // programs
        $departments = $departments->map(
            function ($department) {
                $department->programs = $department->programs()
                    ->select(
                        [
                            'programs.id',
                            'programs.name',
                            'programs.description',
                            'users.name as ketua_pelaksana'
                        ]
                    )
                    ->leftJoin('users', 'users.id', '=', 'programs.user_id')
                    ->get();
                return $department;
            }
        );

        // return response
        return response()->json(
            [
                'status' => 'success',
                'data' => $departments
            ]
        );
    }
}
