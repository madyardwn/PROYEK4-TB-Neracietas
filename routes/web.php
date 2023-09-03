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
    return redirect()->route('login');
});

Auth::routes();

Route::middleware(['auth'])->group(
    function () {
        Route::view('about', 'about')->name('about');

        Route::get('home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

        Route::get('profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
        Route::put('profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

        Route::get('users', [\App\Http\Controllers\UserController::class, 'index'])->name('users.index')->middleware('permission:read user');
        Route::get('users/create', [\App\Http\Controllers\UserController::class, 'create'])->name('users.create')->middleware('permission:create user');
        Route::post('users', [\App\Http\Controllers\UserController::class, 'store'])->name('users.store')->middleware('permission:create user');
        Route::get('users/{user}', [\App\Http\Controllers\UserController::class, 'show'])->name('users.show')->middleware('permission:read user');
        Route::get('users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])->name('users.edit')->middleware('permission:update user');
        Route::put('users/{user}', [\App\Http\Controllers\UserController::class, 'update'])->name('users.update')->middleware('permission:update user');
        Route::delete('users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])->name('users.destroy')->middleware('permission:delete user');


        Route::get('cabinets', [\App\Http\Controllers\CabinetController::class, 'index'])->name('cabinets.index')->middleware('permission:read cabinet');
        Route::get('cabinets/create', [\App\Http\Controllers\CabinetController::class, 'create'])->name('cabinets.create')->middleware('permission:create cabinet');
        Route::post('cabinets', [\App\Http\Controllers\CabinetController::class, 'store'])->name('cabinets.store')->middleware('permission:create cabinet');
        Route::get('cabinets/{cabinet}', [\App\Http\Controllers\CabinetController::class, 'show'])->name('cabinets.show')->middleware('permission:read cabinet');
        Route::get('cabinets/{cabinet}/edit', [\App\Http\Controllers\CabinetController::class, 'edit'])->name('cabinets.edit')->middleware('permission:update cabinet');
        Route::put('cabinets/{cabinet}', [\App\Http\Controllers\CabinetController::class, 'update'])->name('cabinets.update')->middleware('permission:update cabinet');
        Route::delete('cabinets/{cabinet}', [\App\Http\Controllers\CabinetController::class, 'destroy'])->name('cabinets.destroy')->middleware('permission:delete cabinet');


        Route::get('departments', [\App\Http\Controllers\DepartmentController::class, 'index'])->name('departments.index')->middleware('permission:read department');
        Route::get('departments/create', [\App\Http\Controllers\DepartmentController::class, 'create'])->name('departments.create')->middleware('permission:create department');
        Route::post('departments', [\App\Http\Controllers\DepartmentController::class, 'store'])->name('departments.store')->middleware('permission:create department');
        Route::get('departments/{department}', [\App\Http\Controllers\DepartmentController::class, 'show'])->name('departments.show')->middleware('permission:read department');
        Route::get('departments/{department}/edit', [\App\Http\Controllers\DepartmentController::class, 'edit'])->name('departments.edit')->middleware('permission:update department');
        Route::put('departments/{department}', [\App\Http\Controllers\DepartmentController::class, 'update'])->name('departments.update')->middleware('permission:update department');
        Route::delete('departments/{department}', [\App\Http\Controllers\DepartmentController::class, 'destroy'])->name('departments.destroy')->middleware('permission:delete department');


        Route::get('programs', [\App\Http\Controllers\ProgramController::class, 'index'])->name('programs.index')->middleware('permission:read program');
        Route::get('programs/create', [\App\Http\Controllers\ProgramController::class, 'create'])->name('programs.create')->middleware('permission:create program');
        Route::post('programs', [\App\Http\Controllers\ProgramController::class, 'store'])->name('programs.store')->middleware('permission:create program');
        Route::get('programs/{program}', [\App\Http\Controllers\ProgramController::class, 'show'])->name('programs.show')->middleware('permission:read program');
        Route::get('programs/{program}/edit', [\App\Http\Controllers\ProgramController::class, 'edit'])->name('programs.edit')->middleware('permission:update program');
        Route::put('programs/{program}', [\App\Http\Controllers\ProgramController::class, 'update'])->name('programs.update')->middleware('permission:update program');
        Route::delete('programs/{program}', [\App\Http\Controllers\ProgramController::class, 'destroy'])->name('programs.destroy')->middleware('permission:delete program');


        Route::get('events', [\App\Http\Controllers\EventController::class, 'index'])->name('events.index')->middleware('permission:read event');
        Route::get('events/create', [\App\Http\Controllers\EventController::class, 'create'])->name('events.create')->middleware('permission:create event');
        Route::post('events', [\App\Http\Controllers\EventController::class, 'store'])->name('events.store')->middleware('permission:create event');
        Route::get('events/{event}', [\App\Http\Controllers\EventController::class, 'show'])->name('events.show')->middleware('permission:read event');
        Route::get('events/{event}/edit', [\App\Http\Controllers\EventController::class, 'edit'])->name('events.edit')->middleware('permission:update event');
        Route::put('events/{event}', [\App\Http\Controllers\EventController::class, 'update'])->name('events.update')->middleware('permission:update event');
        Route::delete('events/{event}', [\App\Http\Controllers\EventController::class, 'destroy'])->name('events.destroy')->middleware('permission:delete event');

        Route::get('roles', [\App\Http\Controllers\RoleController::class, 'index'])->name('roles.index')->middleware('permission:read role');
        Route::get('roles/create', [\App\Http\Controllers\RoleController::class, 'create'])->name('roles.create')->middleware('permission:create role');
        Route::post('roles', [\App\Http\Controllers\RoleController::class, 'store'])->name('roles.store')->middleware('permission:create role');
        Route::get('roles/{role}', [\App\Http\Controllers\RoleController::class, 'show'])->name('roles.show')->middleware('permission:read role');
        Route::get('roles/{role}/edit', [\App\Http\Controllers\RoleController::class, 'edit'])->name('roles.edit')->middleware('permission:update role');
        Route::put('roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])->name('roles.update')->middleware('permission:update role');
        Route::delete('roles/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])->name('roles.destroy')->middleware('permission:delete role');


        Route::get('permissions', [\App\Http\Controllers\PermissionController::class, 'index'])->name('permissions.index')->middleware('permission:read permission');
        Route::get('permissions/create', [\App\Http\Controllers\PermissionController::class, 'create'])->name('permissions.create')->middleware('permission:create permission');
        Route::post('permissions', [\App\Http\Controllers\PermissionController::class, 'store'])->name('permissions.store')->middleware('permission:create permission');
        Route::get('permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'show'])->name('permissions.show')->middleware('permission:read permission');
        Route::get('permissions/{permission}/edit', [\App\Http\Controllers\PermissionController::class, 'edit'])->name('permissions.edit')->middleware('permission:update permission');
        Route::put('permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'update'])->name('permissions.update')->middleware('permission:update permission');
        Route::delete('permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'destroy'])->name('permissions.destroy')->middleware('permission:delete permission');


        // Excel Import
        Route::get('import-users', [\App\Http\Controllers\ImportUsersController::class, 'index'])->name('import-users.index')->middleware('permission:import users');
        Route::post('import-users', [\App\Http\Controllers\ImportUsersController::class, 'import'])->name('import-users.import')->middleware('permission:import users');
    }
);
