<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\User;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index');
    }

    public function create()
    {
        return view('users.create', [
            'user' => new User(),
        ]);
    }

    public function show(User $user)
    {
        return view('users.show', [
            'user' => $user,
        ]);
    }

    public function store()
    {
        $user = User::create($this->validateRequest());

        return redirect()->route('users.index');
    }

    public function edit(User $user)
    {
        return view('users.create', [
            'user' => $user,
        ]);
    }

    public function update(User $user)
    {
        $user->update($this->validateRequest());

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index');
    }
}
