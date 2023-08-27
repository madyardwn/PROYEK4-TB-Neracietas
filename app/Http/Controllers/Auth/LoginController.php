<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }


    /**
     * @OA\Post(
     *     path="/api/loginApi",
     *     summary="Login",
     *     description="Autentikasi user dan mengembalikan token",
     *     tags={"Authentication"},
     * @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     * @OA\MediaType(
     *             mediaType="multipart/form-data",
     * @OA\Schema(
     * required={"email", "password"},
     * @OA\Property(property="email",    type="string", format="email", example="user@example.com", description="User email"),
     * @OA\Property(property="password", type="string", format="password", example="secret", description="User password"),
     *             )
     *         )
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     ),
     * @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     )
     * )
     */
    public function loginApi(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {
            $token = Auth::user()->createToken('authToken')->plainTextToken;

            $user = User::query()
                ->select(
                    [
                        'users.name',
                        'users.nim',
                        'users.email',
                        'users.na',
                        'users.year',
                        'nama_bagus',
                        DB::raw(
                            "
                        CASE
                            WHEN roles.name IN (
                                'Ketua Himpunan',
                                'Wakil Ketua Himpunan',
                                'Bendahara',
                                'Sekretaris'
                                ) THEN roles.name
                            WHEN roles.name = 'Ketua Divisi'
                                THEN CONCAT('Ketua', ' ', departments.name)
                            WHEN roles.name = 'Wakil Ketua Divisi'
                                THEN CONCAT('Wakil Ketua', ' ', departments.name)
                            ELSE roles.name
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
                ->where('email', $request->email)->first();

            return response()->json(
                [
                    'user' => $user,
                    'access_token' => $token,
                ],
                200
            );
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    /**
     * @OA\Post(
     *     path="/api/logoutApi",
     *     summary="Logout",
     *     description="Logout user",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     * @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *
     * @OA\Response(
     *       response=401,
     *       description="Unauthenticated"
     *  )
     * )
     */
    public function logoutApi(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out.'], 200);
    }
}
