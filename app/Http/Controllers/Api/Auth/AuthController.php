<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', trim($credentials['email']))->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => 'Failed',
                'message' => 'NRP atau password tidak sesuai.',
                'data' => [],
            ], 401);
        }

        $user->tokens()->where('name', 'menarmed-mobile')->delete();
        $token = $user->createToken('menarmed-mobile')->plainTextToken;

        return response()->json([
            'status' => 'Success',
            'message' => 'Berhasil masuk.',
            'data' => [$user],
            'token' => $token,
        ]);
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 3,
        ]);
        $token = $user->createToken('menarmed-mobile')->plainTextToken;

        return response()->json([
            'status' => 'Success',
            'message' => 'Akun berhasil dibuat.',
            'data' => [$user],
            'token' => $token,
        ], 201);
    }
}
