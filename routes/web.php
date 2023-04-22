<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware('auth')->group(function () {
    Route::view('about', 'about')->name('about');

    Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    Route::resource('users', \App\Http\Controllers\UserController::class);

    Route::resource('cabinets', \App\Http\Controllers\CabinetController::class);

    Route::resource('departments', \App\Http\Controllers\DepartmentController::class);

    Route::resource('programs', \App\Http\Controllers\ProgramController::class);

    Route::resource('events', \App\Http\Controllers\EventController::class);

    Route::resource('roles', \App\Http\Controllers\RoleController::class);

    Route::resource('permissions', \App\Http\Controllers\PermissionController::class);

    // Excel Import
    Route::get('import-users', [\App\Http\Controllers\ImportUsersController::class, 'index'])->name('import-users.index');
    Route::post('import-users', [\App\Http\Controllers\ImportUsersController::class, 'import'])->name('import-users.import');
});
