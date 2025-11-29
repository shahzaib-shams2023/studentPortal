<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Register new user
    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully',
            'data'    => $user
        ]);
    }

    // Login
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['status' => false, 'message' => 'Invalid credentials'], 401);
        }

        // Simple token without Sanctum/Passport (just native)
        $token = base64_encode(Str::random(40));

        // Optionally store token if you add a column in users table later
        return response()->json([
            'status'  => true,
            'message' => 'Login successful',
            'token'   => $token,
            'data'    => $user
        ]);
    }

    // Logout placeholder (client just removes token)
    public function logout()
    {
        return response()->json(['status' => true, 'message' => 'Logout success']);
    }
}
