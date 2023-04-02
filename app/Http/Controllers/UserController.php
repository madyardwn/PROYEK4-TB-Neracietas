<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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
        $rules = [
            'nim' => 'required|unique:users,nim|numeric',
            'na' => 'required|unique:users,na|numeric',
            'nama_bagus' => 'required|max:20',
            'year' => 'required|numeric',
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'department' => 'required',
        ];

        $message = [
            'nim' => [
                'required' => 'NIM harus diisi',
                'unique' => 'NIM sudah terdaftar',
                'numeric' => 'NIM harus berupa angka',
            ],
            'na' => [
                'required' => 'Nomor Anggota harus diisi',
                'unique' => 'Nomor Anggota sudah terdaftar',
                'numeric' => 'Nomor Anggota harus berupa angka',
            ],
            'nama_bagus' => [
                'required' => 'Nama Bagus harus diisi',
                'max' => 'Nama Bagus maksimal 20 karakter',
            ],
            'year' => [
                'required' => 'Tahun harus diisi',
                'numeric' => 'Tahun harus berupa angka',
            ],
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'email' => 'Email tidak valid',
                'max' => 'Email maksimal 50 karakter',
                'unique' => 'Email sudah terdaftar',
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min' => 'Password minimal 8 karakter',
                'confirmed' => 'Password tidak sama',
            ],
            'role' => [
                'required' => 'Role harus diisi',
            ],
            'avatar' => [
                'required' => 'Avatar harus diisi',
                'image' => 'Avatar harus berupa gambar',
                'mimes' => 'Avatar harus berupa gambar dengan format jpeg, png, atau jpg',
                'max' => 'Avatar maksimal 2 MB',
            ],
            'department' => [
                'required' => 'User harus terdaftar di salah satu departemen',
            ],
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('avatar')) {
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('avatar')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;
            $name = $request->name;
            $department = Department::find($request->department)->first();
            $cabinet = Cabinet::find($department->cabinet_id)->first();

            $avatar = $request->file('avatar')->storeAs($cabinet->year . '-' . $cabinet->name . '/' . $department->name . '/' .  $name, $filename, 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'avatar' => $avatar ?? null,
            'nim' => $request->nim ?? null,
            'na' => $request->na ?? null,
            'nama_bagus' => $request->nama_bagus ?? null,
            'year' => $request->year ?? null,
            'department_id' => $request->department ?? null,
        ]);

        $user->assignRole(Role::findOrFail(request()->role));

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user): User
    {
        return $user
            ->load('roles')
            ->load('department');
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'nim' => 'required|unique:users,nim,' . $id . '|numeric',
            'na' => 'required|unique:users,na,' . $id . '|numeric',
            'nama_bagus' => 'required|max:20',
            'year' => 'required|numeric',
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'department' => 'required',
        ];

        $message = [
            'nim' => [
                'required' => 'NIM harus diisi',
                'unique' => 'NIM sudah terdaftar',
                'numeric' => 'NIM harus berupa angka',
            ],
            'na' => [
                'required' => 'Nomor Anggota harus diisi',
                'unique' => 'Nomor Anggota sudah terdaftar',
                'numeric' => 'Nomor Anggota harus berupa angka',
            ],
            'nama_bagus' => [
                'required' => 'Nama Bagus harus diisi',
                'max' => 'Nama Bagus maksimal 20 karakter',
            ],
            'year' => [
                'required' => 'Tahun harus diisi',
                'numeric' => 'Tahun harus berupa angka',
            ],
            'name' => [
                'required' => 'Nama harus diisi',
                'max' => 'Nama maksimal 50 karakter',
            ],
            'email' => [
                'required' => 'Email harus diisi',
                'email' => 'Email tidak valid',
                'max' => 'Email maksimal 50 karakter',
                'unique' => 'Email sudah terdaftar',
            ],
            'role' => [
                'required' => 'Role harus diisi',
            ],
            'avatar' => [
                'required' => 'Avatar harus diisi',
                'image' => 'Avatar harus berupa gambar',
                'mimes' => 'Avatar harus berupa gambar dengan format jpeg, png, atau jpg',
                'max' => 'Avatar maksimal 2 MB',
            ],
            'department' => [
                'required' => 'User harus terdaftar di salah satu departemen',
            ],
        ];

        $request->validate($rules, $message);

        $user = User::where('id', $id);
        if ($request->hasFile('avatar')) {
            $currentDate = date('Y-m-d-H-i-s');
            $originalName = $request->file('avatar')->getClientOriginalName();
            $filename = $currentDate . '_' . $originalName;

            $name = $user->first()->name;
            $department = Department::find($request->department)->first();
            $cabinet = Cabinet::find($department->cabinet_id)->first();

            $folder = $cabinet->year . '-' . $cabinet->name . '/' . $department->name . '/' .  $name;
            $avatar = $request->file('avatar')->storeAs($folder, $filename, 'public');

            // delete old avatar
            if ($user->first()->avatar) {
                unlink(public_path('storage/' . $folder . '/' . $user->first()->avatar));
            }
        }

        $user->update(
            [
                'name' => $request->name,
                'email' => $request->email,
                'avatar' => $avatar ?? $user->first()->avatar,
                'nim' => $request->nim ?? null,
                'na' => $request->na ?? null,
                'nama_bagus' => $request->nama_bagus ?? null,
                'year' => $request->year ?? null,
                'department_id' => $request->department ?? null,
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
            // find cabinet by department
            $cabinet = Cabinet::where('department_id', $user->first()->department_id)->first();
            $department = Department::find($user->first()->department_id);
            $name = $user->first()->name;
            $folder = $cabinet->name . '/' . $department->name . '/' .  $name . '/avatar';
            unlink(public_path('storage/' . $folder . '/' . $user->first()->avatar));
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully.',
        ], 200);
    }
}
