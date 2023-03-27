<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index', [
            'roles' => Role::all(),
            'departments' => Department::all(),
            'cabinets' => Cabinet::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:30',
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('avatar')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;
            $avatar = $request->file('avatar')->storeAs('avatars', $filename, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatar ?? null,
        ]);

        $user->assignRole(Role::findOrFail(request()->role));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return $user
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.avatar',
                'users.nim',
                'users.year',
                'users.na',
                'users.nama_bagus',
                'roles.name as role',
                'roles.id as role_id',
                'departments.name as department',
                'departments.id as department_id',
                'cabinets.name as cabinet',
                'cabinets.id as cabinet_id',
            ])
            ->leftJoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->leftJoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->leftJoin('departments', function($join) {
                $join->on('departments.id', '=', 'users.department_id')
                    ->orWhereNull('users.department_id');
            })
            ->leftJoin('cabinets', function($join) {
                $join->on('cabinets.id', '=', 'users.cabinet_id')
                    ->orWhereNull('users.cabinet_id');
            })
            ->where('users.id', $user->id)
            ->first();
    }
    

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|max:30|unique:users,email,' . $id,
            'role' => 'required',
        ]);

        $user = User::where('id', $id);

        if ($request->hasFile('avatar')) {
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('avatar')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;
            $avatar = $request->file('avatar')->storeAs('avatars', $filename, 'public');

            // delete old avatar
            if ($user->first()->avatar) {
                unlink(public_path('storage/' . $user->first()->avatar));
            }
        }

        $user->update(
            [
                'name' => request()->name,
                'email' => request()->email,
                'avatar' => $avatar ?? $user->first()->avatar,
            ]
        );

        $user->first()->syncRoles(Role::findOrFail(request()->role));


        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        if (auth()->user()->id == $id) {
            return response()->json([
                'message' => 'You cannot delete yourself.',
            ], 403);
        }

        $user = User::where('id', $id);

        if ($user->first()->avatar) {
            unlink(public_path('storage/' . $user->first()->avatar));
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ], 200);
    }
}
