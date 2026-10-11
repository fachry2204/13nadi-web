<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    private function safeUsers()
    {
        return User::query()
            ->select(['id', 'name', 'username', 'email', 'created_at', 'updated_at'])
            ->orderBy('name')
            ->get();
    }

    public function index()
    {
        return response()->json(['data' => $this->safeUsers()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'alpha_dash', 'max:50', 'unique:users,username'],
            'email' => ['required', 'email', 'max:160', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12', 'confirmed', 'max:255'],
        ]);

        $user = User::create($data);
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'admin_user_created',
            'properties' => ['user_id' => $user->id, 'username' => $user->username],
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['data' => $user->only('id', 'name', 'username', 'email')], 201);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'username' => ['required', 'string', 'alpha_dash', 'max:50', Rule::unique('users', 'username')->ignore($user->id)],
            'email' => ['required', 'email', 'max:160', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:12', 'confirmed', 'max:255'],
        ]);

        DB::transaction(function () use ($request, $user, $data): void {
            $passwordChanged = filled($data['password'] ?? null);
            $user->fill(collect($data)->except('password')->all());
            if ($passwordChanged) {
                $user->password = $data['password'];
            }
            $user->save();

            if ($passwordChanged) {
                $tokens = $user->tokens();
                $currentToken = $request->user()->id === $user->id
                    ? $request->user()->currentAccessToken()
                    : null;
                if ($currentToken && method_exists($currentToken, 'getKey')) {
                    $tokens->where('id', '!=', $currentToken->getKey())->delete();
                } else {
                    $tokens->delete();
                }
            }
        });

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'admin_user_updated',
            'properties' => ['user_id' => $user->id, 'username' => $user->username],
            'ip_address' => $request->ip(),
        ]);

        return response()->json(['data' => $user->only('id', 'name', 'username', 'email')]);
    }

    public function destroy(Request $request, User $user)
    {
        if ($request->user()->id === $user->id) {
            return response()->json(['message' => 'Akun yang sedang digunakan tidak dapat dihapus.'], 422);
        }
        if (User::count() <= 1) {
            return response()->json(['message' => 'Minimal harus ada satu akun administrator.'], 422);
        }

        DB::transaction(function () use ($user): void {
            $user->tokens()->delete();
            $user->delete();
        });
        ActivityLog::create([
            'user_id' => $request->user()->id,
            'action' => 'admin_user_deleted',
            'properties' => ['user_id' => $user->id, 'username' => $user->username],
            'ip_address' => $request->ip(),
        ]);

        return response()->json([], 204);
    }
}
