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
        return $dataTable->render(
            'pages.users.index', [
            'cabinets' => Cabinet::where('is_active', 1)->get(),
            'roles' => Role::where('name', '!=', 'superadmin')->get(),
            'departments' => Department::where('is_active', 1)->get(),
            ]
        );
    }

    public function store(Request $request)
    {
        $rules = [
            'nim' => 'required|unique:users,nim|numeric',
            'nama_bagus' => 'required|max:20',
            'year' => 'required|numeric',
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required',
            'cabinet' => 'required',
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
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        try {
            $user = User::create([
                'nim' => $request->nim,
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),

                'avatar' => $request->file('avatar')->store('avatars', 'public'),
                'na' => $request->na ?? null,
                'nama_bagus' => $request->nama_bagus ?? null,
                'year' => $request->year ?? null,
            ]);

            $user->assignRole(Role::findOrFail(request()->role));
            
            $user->periode()->create([
                'cabinet_id' => $request->cabinet,
                'department_id' => $request->department,
                'role_id' => $request->role,
                'is_active' => 1,
            ]);

            return response()->json([
                'message' => 'User ' . $user->name . ' berhasil ditambahkan',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'User gagal ditambahkan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function edit(User $user): User
    {
        return $user
            ->select(
                'users.id',
                'users.na',
                'users.nim',
                'users.nama_bagus',
                'users.year',
                'users.name',
                'users.email',
                'users.avatar',
                'periodes.cabinet_id',
                'periodes.department_id',
                'periodes.role_id',
                'periodes.is_active',
            )
            ->leftJoin('periodes', 'users.id', '=', 'periodes.user_id')
            ->leftJoin('departments', 'periodes.department_id', '=', 'departments.id')            
            ->leftJoin('cabinets', 'periodes.cabinet_id', '=', 'cabinets.id')
            ->leftJoin('roles', 'periodes.role_id', '=', 'roles.id')
            ->where('users.id', $user->id)
            ->first();
    }


    public function update(Request $request, $id)
    {
        $rules = [
            'nim' => 'required|unique:users,nim,' . $id . '|numeric',
            'nama_bagus' => 'required|max:20',
            'year' => 'required|numeric',
            'name' => 'required|max:50',
            'email' => 'required|email|unique:users,email,' . $id,
            'avatar' => 'image|mimes:jpeg,png,jpg|max:2048',
            'role' => 'required',
            'department' => 'required',
            'cabinet' => 'required',
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
            'cabinet' => [
                'required' => 'Kabinet harus diisi',
            ],
        ];

        $request->validate($rules, $message);

        try {
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
                'avatar' => $path ?? $user->avatar,
                'na' => $request->na ?? null,
                'nama_bagus' => $request->nama_bagus ?? null,
                'year' => $request->year ?? null,
            ]);

            // attach role
            $user->syncRoles(Role::findOrFail(request()->role));

            // update periode
            $user->periode()->update([
                'cabinet_id' => $request->cabinet,
                'department_id' => $request->department,
                'role_id' => $request->role,
                'is_active' => 1,
            ]);

            return response()->json(
                [
                'message' => 'User ' . $user->name . ' berhasil diubah',
                ], 200
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                'message' => 'User gagal diubah',
                'error' => $e->getMessage(),
                ], 500
            );
        }
    }

    public function destroy($ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $count = 0;

        try {
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
                return response()->json(
                    [
                    'message' => 'Berhasil menghapus ' . $count . ' pengguna',
                    ], 200
                );
            } else {
                return response()->json(
                    [
                    'message' => 'Tidak ada pengguna yang berhasil dihapus',
                    ], 403
                );
            }
        } catch (\Exception $e) {
            return response()->json(
                [
                'message' => 'Pengguna gagal dihapus',
                'error' => $e->getMessage(),
                ], 500
            );
        }
    }
}
