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
     *     tags={"Users"},
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
        // get all users
        $users = User::query()
            ->select(
                [
                    'users.name',
                    DB::raw(
                        "
                        CASE
                            WHEN roles.name IN (
                                'Ketua Himpunan',
                                'Wakil Ketua Himpunan',
                                'Bendahara',
                                'Sekretaris'
                               ) THEN CONCAT(UPPER(LEFT(roles.name, 1)), SUBSTRING(roles.name, 2))
                            WHEN roles.name = 'Ketua Divisi'
                                THEN CONCAT('Ketua', ' ', departments.name)
                            WHEN roles.name = 'Wakil Ketua Divisi'
                                THEN CONCAT('Wakil Ketua', ' ', departments.name)
                            ELSE
                                CONCAT(UPPER(LEFT(roles.name, 1)), SUBSTRING(roles.name, 2))
                        END as role
                    "
                    ),
                    // DB::raw("CONCAT('" . asset('/storage') . "/', users.avatar) as avatar"),
                    DB::raw("IFNULL(CONCAT('" . asset('/storage') . "/', users.avatar), CONCAT('" . asset('img/default_avatar.png') . "')) as avatar"),
                ]
            )
            ->leftJoin('users_departments', 'users.id', '=', 'users_departments.user_id')
            ->leftJoin('roles', 'users_departments.position', '=', 'roles.id')
            ->leftJoin('departments', 'users_departments.department_id', '=', 'departments.id')
            ->where(
                function ($query) {
                    $query->where('roles.name', 'like', '%Ketua Himpunan%')
                        ->orWhere('roles.name', 'like', '%Wakil Ketua Himpunan%')
                        ->orWhere('roles.name', 'like', '%Bendahara%')
                        ->orWhere('roles.name', 'like', '%Ketua%')
                        ->orWhere('roles.name', 'like', '%Wakil%');
                }
            )
            ->where('users_departments.is_active', 1)
            // sort by role
            ->orderByRaw(
                "
            CASE
                WHEN roles.name IN (
                    'Ketua Himpunan',
                    'Wakil Ketua Himpunan',
                    'Bendahara',
                    'Sekretaris'
                    ) THEN 1
                WHEN roles.name = 'Ketua Divisi'
                    THEN 2
                WHEN roles.name = 'Wakil Ketua Divisi'
                    THEN 3
                ELSE
                    4
            END
            "
            )
            ->get();

        // return response
        return response()->json(
            [
                'status' => 'success',
                'data' => $users
            ]
        );
    }


    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="GET user",
     *     description="Return data user yang akan ditampilkan pada halaman profile mobile",
     *     tags={"Users"},
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
    public function user(Request $request)
    {
        return response()->json(
            [
                'status' => 'success',
                'data' => $request->user()
            ]
        );
    }
}
