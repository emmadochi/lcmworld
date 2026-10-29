<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    private function ensurePersonalAccessTokensTableExists(): void
    {
        if (!Schema::hasTable('personal_access_tokens')) {
            try {
                Schema::create('personal_access_tokens', function (Blueprint $table) {
                    $table->id();
                    $table->morphs('tokenable');
                    $table->text('name');
                    $table->string('token', 64)->unique();
                    $table->text('abilities')->nullable();
                    $table->timestamp('last_used_at')->nullable();
                    $table->timestamp('expires_at')->nullable()->index();
                    $table->timestamps();
                });
            } catch (\Throwable $e) {}
        }
    }

    private function ensureUserColumnsExist(): void
    {
        if (Schema::hasTable('users')) {
            if (!Schema::hasColumn('users', 'avatar')) {
                try {
                    Schema::table('users', function (Blueprint $table) {
                        $table->string('avatar', 500)->nullable()->after('email');
                    });
                } catch (\Throwable $e) {}
            }
            if (!Schema::hasColumn('users', 'google_id')) {
                try {
                    Schema::table('users', function (Blueprint $table) {
                        $table->string('google_id', 255)->nullable()->after('avatar');
                    });
                } catch (\Throwable $e) {}
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                try {
                    Schema::table('users', function (Blueprint $table) {
                        $table->timestamp('last_login_at')->nullable()->after('updated_at');
                    });
                } catch (\Throwable $e) {}
            }
        }
    }

    public function register(Request $request)
    {
        $this->ensurePersonalAccessTokensTableExists();
        $this->ensureUserColumnsExist();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_login_at' => now(),
        ]);

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function login(Request $request)
    {
        $this->ensurePersonalAccessTokensTableExists();
        $this->ensureUserColumnsExist();
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->last_login_at = now();
        $user->save();

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
        ]);
    }

    public function googleAuth(Request $request)
    {
        $this->ensurePersonalAccessTokensTableExists();
        $this->ensureUserColumnsExist();
        $request->validate([
            'email' => 'required|email',
            'name' => 'required|string',
            'google_id' => 'nullable|string',
            'avatar' => 'nullable|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'avatar' => $request->avatar,
                'google_id' => $request->google_id,
                'password' => Hash::make(Str::random(24)),
                'last_login_at' => now(),
            ]);
        } else {
            if ($request->avatar && empty($user->avatar)) {
                $user->avatar = $request->avatar;
            }
            if ($request->google_id && empty($user->google_id)) {
                $user->google_id = $request->google_id;
            }
            $user->last_login_at = now();
            $user->save();
        }

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token,
            'message' => 'Authenticated with Google successfully',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}
