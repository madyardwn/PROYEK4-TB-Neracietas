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
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);

// should be protected by auth:sanctum
Route::middleware('auth:sanctum')->group(
    function () {
        // protected routes
        Route::get(
            '/user',
            function (Request $request) {
                return $request->user();
            }
        );

        Route::get('/users', [App\Http\Controllers\Api\UsersController::class, 'index']);
        Route::get('/cabinets', [App\Http\Controllers\Api\CabinetsController::class, 'index']);
        Route::get('/events', [App\Http\Controllers\Api\EventsController::class, 'index']);
        Route::get('/departments', [App\Http\Controllers\Api\DepartmentsController::class, 'index']);

        // api logout
        Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
    }
);

// unauthenticated routes
Route::get(
    '/unauthenticated',
    function () {
        return response()->json(['message' => 'Unauthenticated.'], 401);
    }
)->name('unauthenticated');
