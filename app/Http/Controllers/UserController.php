<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index', [
            'roles' => Role::all(),
        ]);
    }

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users|max:30',
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
        ]);

        $user = User::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'avatar' => $request->hasFile('avatar') ? $request->file('avatar')->store('avatars', 'public') : null,
            ]
        );
        $user->assignRole(Role::findOrFail(request()->role));
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        return $user->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->select('users.id', 'users.name', 'users.email', 'roles.name as role_name', 'roles.id as role_id')
            ->orderBy('role_name', 'asc')
            ->get()
            ->where('id', $user->id)
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

        $user->update(
            [
                'name' => request()->name,
                'email' => request()->email,
                'avatar' => request()->hasFile('avatar') ? request()->file('avatar')->store('avatars', 'public') : null,
            ]
        );

        $user->first()->syncRoles(Role::findOrFail(request()->role));


        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    }
}
