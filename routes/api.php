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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/users', [App\Http\Controllers\Api\UsersController::class, 'index']);
Route::get('/cabinets', [App\Http\Controllers\Api\CabinetsController::class, 'index']);
Route::get('/events', [App\Http\Controllers\Api\EventsController::class, 'index']);
Route::get('/departments', [App\Http\Controllers\Api\DepartmentsController::class, 'index']);
