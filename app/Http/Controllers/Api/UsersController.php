<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="GET users",
     *     description="Return data users yang akan ditampilkan pada halaman dashboard mobile",
     *     @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     )
     * )
     */
    public function index()
    {
        // get all users
        $users = User::query()
            ->select([
                'users.name',
                DB::raw(
                    // "IF(roles.name = 'Ketua Himpunan', roles.name, IF(roles.name = 'Wakil Ketua Himpunan', roles.name, IF(roles.name = 'Bendahara', roles.name, IF(roles.name = 'Ketua', CONCAT(roles.name, ' ', departments.short_name), IF(roles.name = 'Wakil', CONCAT(roles.name, ' ', departments.short_name), roles.name))))) as role"
                    // use if and else if
                    "IF(roles.name = 'Ketua Himpunan' OR
                        roles.name = 'Wakil Ketua Himpunan' OR
                        roles.name = 'Bendahara', 
                        roles.name,
                        IF(roles.name = 'Ketua Divisi' OR
                            roles.name = 'Wakil Ketua Divisi', 
                            CONCAT(roles.name, ' ', departments.name),
                            roles.name
                        )
                    ) as role"
                ),
                DB::raw("CONCAT('" . asset('/storage') . "/', users.avatar) as avatar"),
            ])
            ->leftJoin('users_departments', 'users.id', '=', 'users_departments.user_id')
            ->leftJoin('roles', 'users_departments.position', '=', 'roles.id')
            ->leftJoin('departments', 'users_departments.department_id', '=', 'departments.id')
            ->where(function ($query) {
                $query->where('roles.name', 'like', '%Ketua Himpunan%')
                    ->orWhere('roles.name', 'like', '%Wakil Ketua Himpunan%')
                    ->orWhere('roles.name', 'like', '%Bendahara%')
                    ->orWhere('roles.name', 'like', '%Ketua%')
                    ->orWhere('roles.name', 'like', '%Wakil%');
            })
            ->where('users_departments.is_active', 1)
            ->get();


        // return response
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }
}
