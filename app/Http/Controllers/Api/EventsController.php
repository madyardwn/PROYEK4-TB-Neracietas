<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    // /**
    //  * @OA\Get(
    //  *     path="/api/events",
    //  *     summary="GET events",
    //  *     description="Return data events yang akan ditampilkan pada halaman events mobile",
    //  *     tags={"Events"},
    //  *     security={{"sanctum":{}}},
    //  * @OA\Response(
    //  *         response=200,
    //  *         description="Success"
    //  *     ),
    //  * @OA\Response(
    //  *         response=403,
    //  *         description="Access Denied"
    //  *     )
    //  * )
    //  */
    // public function index()
    // {
    //     // get all events
    //     $events = Event::query()
    //         ->select(
    //             [
    //                 'events.id',
    //                 DB::raw("CONCAT('" . asset('/storage') . "/', events.poster) as poster"),
    //                 'events.name',
    //                 'events.description',
    //                 'events.date',
    //                 'events.time',
    //                 'events.location',
    //                 'events.type'
    //             ]
    //         )
    //         ->where('events.is_active', 1)
    //         ->get();

    //     // return response
    //     return response()->json(
    //         [
    //             'status' => 'success',
    //             'data' => $events
    //         ]
    //     );
    // }

    // use query parameter
    /**
     * @OA\Get(
     *     path="/api/events",
     *     summary="GET events",
     *     description="Return data events yang akan ditampilkan pada halaman events mobile",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     * @OA\Parameter(
     *         name="type",
     *         in="query",
     *         description="Type of event",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"proker", "kegiatan", "lomba"}
     *         )
     *     ),
     * @OA\Parameter(
     *         name="date",
     *         in="query",
     *         description="Date of event",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="date"
     *         )
     *     ),
     * @OA\Parameter(
     *         name="time",
     *         in="query",
     *         description="Time of event",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             format="time"
     *         )
     *     ),
     * @OA\Parameter(
     *         name="location",
     *         in="query",
     *         description="Location of event",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search event by name",
     *         required=false,
     *         @OA\Schema(
     *             type="string"
     *         )
     *     ),
     * @OA\Parameter(
     *         name="sort",
     *         in="query",
     *         description="Sort event by date",
     *         required=false,
     *         @OA\Schema(
     *             type="string",
     *             enum={"asc", "desc"}
     *         )
     *     ),
     * @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Limit event",
     *         required=false,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *    ),
     * @OA\Parameter(
     *        name="page",
     *       in="query",
     *      description="Page of event",
     *    required=false,
     *  @OA\Schema(
     *   type="integer"
     * )
     * ),
     * @OA\Response(
     *        response=200,
     *       description="Success"
     *  ),
     * @OA\Response(
     *       response=403,
     *     description="Access Denied"
     * )
     * )
     */
    public function index(Request $request)
    {
        // set default value
        $type = $request->query('type', null);
        $date = $request->query('date', null);
        $time = $request->query('time', null);
        $location = $request->query('location', null);
        $search = $request->query('search', null);
        $sort = $request->query('sort', 'asc');
        $limit = $request->query('limit', 10);
        $page = $request->query('page', 1);

        // get all events
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
            ->when($type, function ($query, $type) {
                return $query->where('events.type', $type);
            })
            ->when($date, function ($query, $date) {
                return $query->where('events.date', $date);
            })
            ->when($time, function ($query, $time) {
                return $query->where('events.time', $time);
            })
            ->when($location, function ($query, $location) {
                return $query->where('events.location', 'like', '%' . $location . '%');
            })
            ->when($search, function ($query, $search) {
                return $query->where('events.name', 'like', '%' . $search . '%');
            })
            ->orderBy('events.date', $sort)
            ->paginate($limit, ['*'], 'page', $page);

        // return response
        return response()->json(
            [
                'status' => 'success',
                'data' => $events
            ]
        );
    }
}
