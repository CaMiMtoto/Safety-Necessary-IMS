<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function login()
    {
        $data = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember_me' => ['nullable', 'boolean']
        ]);

        $user = User::query()
//            ->with('roles.permissions')
            ->where('email', $data['email'])->first();


        if (!$user || !Hash::check($data['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!$user->is_active) {
            return response()->json(['message' => 'Your account is not active'], 401);
        }

        // Create a token for the user
//        $permissions = $user->roles->map->permissions->flatten()->pluck('name')->toArray();
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'message' => 'Successfully logged in!',
            'user' => $user,
            'token' => $token,
        ]);
    }

    // Logout user
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Successfully logged out!']);
    }

    public function me()
    {
        return response()->json(request()->user());
    }
}
