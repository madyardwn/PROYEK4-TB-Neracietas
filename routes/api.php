<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
// api login
Route::post('/loginApi', [App\Http\Controllers\Auth\LoginController::class, 'loginApi']);

// should be protected by auth:sanctum
Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('/user', [App\Http\Controllers\Api\UsersController::class, 'user']);

        Route::get('/users', [App\Http\Controllers\Api\UsersController::class, 'index']);
        Route::get('/cabinets', [App\Http\Controllers\Api\CabinetsController::class, 'index']);
        Route::get('/events', [App\Http\Controllers\Api\EventsController::class, 'index']);
        Route::get('/departments', [App\Http\Controllers\Api\DepartmentsController::class, 'index']);                    
        Route::put('/user/device-token', [App\Http\Controllers\Api\UsersController::class, 'updateDeviceToken']);

        // api logout
        Route::post(
            '/logoutApi',
            function (Request $request) {
                $request->user()->currentAccessToken()->delete();
                return response()->json(['message' => 'Logged out.'], 200);
            }
        );
    }
);

// unauthenticated routes
// Route::get(
//     '/unauthenticated',
//     function () {
//         return response()->json(['message' => 'Unauthenticated.'], 401);
//     }
// )->name('unauthenticated');

// unauthenticated routes multiple method
Route::match(
    ['get', 'post', 'put', 'delete'],
    '/unauthenticated',
    function () {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
)->name('unauthenticated');