<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="GET events",
     *     description="Return data events yang akan ditampilkan pada halaman events mobile",
     *     tags={"Events"},
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
        $events = Event::query()
            ->select(
                [
                    'events.id',
                    DB::raw("CONCAT('" . asset('/storage') . "/', events.poster) as poster"),
                    'events.name',
                    'events.description',
                    'events.date',
                    'events.time',
                    'events.location',
                    'events.type'
                ]
            )
            ->where('events.is_active', 1)
            ->orderBy('events.date', 'desc')
            ->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $events
            ]
        );
    }
}
