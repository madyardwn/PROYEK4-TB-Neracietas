<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Notification;
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

    /**
     * @OA\Get(
     *    path="/api/events/notification",
     *   summary="GET notification",
     *  description="Return data notification yang akan ditampilkan pada halaman notification mobile",
     * tags={"Events"},
     * security={{"sanctum":{}}},
     * @OA\Response(
     *        response=200,
     *       description="Success"
     *   ),
     * @OA\Response(
     *       response=403,
     *      description="Access Denied"
     * )
     * )     
     */
    public function notification(Request $request)
    {
        $notifications = DB::table('notifications')
            ->select(
                [
                    'notifications.id',
                    'notifications.title',
                    'notifications.body',
                    'notifications.link',
                    'notifications.poster',
                    'users_notifications.is_read'
                ]
            )
            ->leftJoin('users_notifications', 'users_notifications.notification_id', '=', 'notifications.id')
            ->where('users_notifications.user_id', $request->user()->id)
            ->where('users_notifications.is_read', 0)
            ->orderBy('notifications.created_at', 'desc')
            ->get();

        return response()->json(
            [
                'status' => 'success',
                'data' => $notifications
            ]
        );
    }

    /**
     * @OA\Get(
     *     path="/api/events/notification/{notification}/read",
     *     summary="GET notification read",
     *     description="Return data notification yang akan ditampilkan pada halaman notification mobile",
     *     tags={"Events"},
     *     security={{"sanctum":{}}},
     *    @OA\Parameter(    
     *         name="notification",
     *         in="path",
     *         description="Notification ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     * @OA\Response(
     *         response=200,
     *         description="Success"
     *     ),
     * @OA\Response(
     *         response=403,
     *         description="Access Denied"
     *     ),
     * @OA\Response(
     *         response=404,
     *         description="Notification not found"
     *     )
     * )
    */        
    public function notificationRead(Request $request, Notification $notification)
    {
        $userNotification = DB::table('users_notifications')
            ->where('user_id', $request->user()->id)
            ->where('notification_id', $notification->id)
            ->first();

        if ($userNotification) {
            DB::table('users_notifications')
                ->where('user_id', $request->user()->id)
                ->where('notification_id', $notification->id)
                ->update(['is_read' => 1]);

            return response()->json(
                [
                    'status' => 'success',
                    'data' => $notification
                ]
            );
        }

        return response()->json(
            [
                'status' => 'error',
                'message' => 'Notification not found'
            ],
            404
        );
    }
}
