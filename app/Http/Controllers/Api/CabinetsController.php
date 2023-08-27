<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cabinet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CabinetsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cabinets",
     *     summary="GET cabinets",
     *     description="Return data departments yang akan ditampilkan pada halaman cabinets mobile",
     *     tags={"Cabinets"},
     *     security={{"sanctum":{}}},
     * @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     * @OA\Response(
     *          response=403,
     *          description="Access Denied"
     *      ),
     *  )
     */
    public function index()
    {
        // get all cabinets
        $cabinets = Cabinet::query()
            ->select(
                [
                    'cabinets.name',
                    'cabinets.description',
                    // DB::raw("CONCAT('" . asset('/storage') . "/', cabinets.logo) as logo"),
                    // DB::raw("CONCAT('" . asset('/storage') . "/', cabinets.filosofy) as filosofy"),

                    DB::raw("IFNULL(CONCAT('" . asset('/storage') . "/', cabinets.logo), CONCAT('" . asset('img/default_avatar.png') . "')) as logo"),
                    DB::raw("IFNULL(CONCAT('" . asset('/storage') . "/', cabinets.filosofy), CONCAT('" . asset('img/philosophy.jpg') . "')) as filosofy"),
                ]
            )
            ->where('cabinets.is_active', 1)
            ->get();

        // return response
        return response()->json(
            [
                'status' => 'success',
                'data' => $cabinets
            ]
        );
    }
}
