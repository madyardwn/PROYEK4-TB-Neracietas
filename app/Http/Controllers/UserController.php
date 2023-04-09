<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Models\Cabinet;
use App\Models\Department;
use App\Models\Program;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('pages.users.index', [
            'roles' => Role::all(),
            'departments' => Department::all(),
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
                'mimes' => 'Avatar harus berupa gambar dengan format png',
                'max' => 'Avatar maksimal 2 MB',
            ],
            'department' => [
                'required' => 'Departemen harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        if ($request->hasFile('avatar')) {
            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->file('avatar')->getClientOriginalExtension();
            $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');
        }

        $user = User::create([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),

            'avatar' => $path ?? null,
            'na' => $request->na ?? null,
            'nama_bagus' => $request->nama_bagus ?? null,
            'year' => $request->year ?? null,
            'department_id' => $request->department ?? null,
        ]);

        $user->assignRole(Role::findOrFail(request()->role));

        if ($request->department) {
            $department = Department::find($request->department);
            $cabinet = Cabinet::find($department->cabinet_id);

            $user->update([
                'is_active' => $cabinet->is_active ? 1 : 0,
            ]);
        }

        return response()->json([
            'message' => 'User ' . $user->name . ' berhasil ditambahkan',
        ], 200);
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
                'required' => 'Departemen harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        $user = User::find($id);

        if ($request->hasFile('avatar')) {
            $currentDate = date('Y-m-d-H-i-s');
            $filename = $currentDate . '_' . $request->name . '.' . $request->file('avatar')->getClientOriginalExtension();

            $path = $request->file('avatar')->storeAs('avatars', $filename, 'public');

            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
        }

        $user->update([
            'nim' => $request->nim,
            'name' => $request->name,
            'email' => $request->email,
            'year' => $request->year,

            'avatar' => $path ?? $user->avatar ?? null,
            'na' => $request->na ?? $user->na ?? null,
            'nama_bagus' => $request->nama_bagus ?? $user->nama_bagus ?? null,
            'department_id' => $request->department ?? $user->department_id ?? null,
        ]);

        $user->syncRoles(Role::findOrFail(request()->role));

        if ($request->department) {
            $department = Department::find($request->department);
            $cabinet = Cabinet::find($department->cabinet_id);

            $user->update([
                'is_active' => $cabinet->is_active ? 1 : 0,
            ]);
        }

        return response()->json([
            'message' => 'User ' . $user->name . ' berhasil diubah',
        ], 200);
    }

    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $count = 0;

        foreach ($ids as $id) {
            if (auth()->user()->id == $id) {
                continue;
            }

            $user = User::find($id);

            $programs = Program::where('user_id', $id)->get();

            if ($programs->count() > 0) {
                continue;
            } else {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }

                $user->delete();
                $count++;
            }
        }

        if ($count > 0) {
            return response()->json([
                'message' => 'Berhasil menghapus ' . $count . ' pengguna',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Tidak ada pengguna yang berhasil dihapus',
            ], 403);
        }
    }
}
