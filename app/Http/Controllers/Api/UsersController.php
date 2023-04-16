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
                'roles.name as role',
                DB::raw("CONCAT('" . asset('/storage') . "/', users.avatar) as avatar"),
            ])
            ->leftJoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->leftJoin('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where(function ($query) {
                $query->where('roles.name', 'like', '%Ketua Himpunan%')
                    ->orWhere('roles.name', 'like', '%Wakil Ketua Himpunan%')
                    ->orWhere('roles.name', 'like', '%Bendahara%')
                    ->orWhere('roles.name', 'like', '%Ketua%')
                    ->orWhere('roles.name', 'like', '%Wakil%');
            })
            ->where('users.is_active', 1)
            ->get();


        // return response
        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }
}
