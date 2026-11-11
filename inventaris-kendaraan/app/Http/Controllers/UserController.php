<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\RespondsWithJson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;

class UserController extends Controller
{
    use RespondsWithJson;

    private const ROLES = ['admin', 'operator', 'viewer'];

    public function index()
    {
        return view('users.index', ['roles' => self::ROLES]);
    }

    public function data(Request $request)
    {
        $query = DB::table('users as u')->select('u.id', 'u.name', 'u.email', 'u.role', 'u.created_at');

        if ($search = trim((string) $request->input('search.value', ''))) {
            $like = '%'.$this->escapeLike($search).'%';
            $query->where(fn ($q) => $q->where('u.name', 'like', $like)->orWhere('u.email', 'like', $like));
        }
        if ($role = $request->input('role')) {
            $query->where('u.role', $role);
        }

        return $this->dataTableResponse(
            $request, $query, DB::table('users')->count(),
            [null, 'u.name', 'u.email', 'u.role', 'u.created_at', null],
            'u.id'
        );
    }

    public function show($id)
    {
        $row = DB::table('users')->where('id', $id)->first(['id', 'name', 'email', 'role']);
        if (! $row) {
            return $this->fail('Data user tidak ditemukan.', 404);
        }

        return $this->ok('Detail user', $row);
    }

    public function store(Request $request)
    {
        [$data, $error] = $this->validateInput($request, $this->rules(null, true), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            $id = DB::table('users')->insertGetId([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal disimpan');
        }

        return $this->ok('User berhasil ditambahkan', ['id' => $id], 201);
    }

    public function update(Request $request, $id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (! $user) {
            return $this->fail('Data user tidak ditemukan.', 404);
        }

        [$data, $error] = $this->validateInput($request, $this->rules($id, false), $this->messages());
        if ($error) {
            return $error;
        }

        try {
            return DB::transaction(function () use ($id, $user, $data) {
                // Kunci seluruh admin agar pemeriksaan "admin terakhir" konsisten.
                $adminIds = DB::table('users')->where('role', 'admin')->lockForUpdate()->pluck('id');

                if ($user->role === 'admin' && $data['role'] !== 'admin' && $adminIds->count() <= 1) {
                    return $this->fail('Role admin terakhir tidak dapat diubah.', 409, ['role' => ['Harus tersisa minimal satu admin.']]);
                }
                if ((int) $id === (int) auth()->id() && $data['role'] !== 'admin') {
                    return $this->fail('Anda tidak dapat menurunkan role akun Anda sendiri.', 409, ['role' => ['Tidak dapat mengubah role akun sendiri.']]);
                }

                $payload = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['role'],
                    'updated_at' => now(),
                ];
                if (! empty($data['password'])) {
                    $payload['password'] = Hash::make($data['password']);
                }
                DB::table('users')->where('id', $id)->update($payload);

                return $this->ok('User berhasil diperbarui');
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal diperbarui');
        }
    }

    public function destroy($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if (! $user) {
            return $this->fail('Data user tidak ditemukan.', 404);
        }
        if ((int) $id === (int) auth()->id()) {
            return $this->fail('Anda tidak dapat menghapus akun Anda sendiri.', 409);
        }

        try {
            return DB::transaction(function () use ($id, $user) {
                $adminIds = DB::table('users')->where('role', 'admin')->lockForUpdate()->pluck('id');
                if ($user->role === 'admin' && $adminIds->count() <= 1) {
                    return $this->fail('Admin terakhir tidak dapat dihapus.', 409);
                }

                DB::table('users')->where('id', $id)->delete();

                return $this->ok('User berhasil dihapus');
            });
        } catch (Throwable $e) {
            return $this->serverError($e, 'Data gagal dihapus');
        }
    }

    private function rules($ignoreId, bool $passwordRequired): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($ignoreId)],
            'role' => ['required', Rule::in(self::ROLES)],
            'password' => [$passwordRequired ? 'required' : 'nullable', 'string', 'min:8', 'confirmed'],
        ];
    }

    private function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'role.required' => 'Role wajib dipilih.',
            'role.in' => 'Role tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
