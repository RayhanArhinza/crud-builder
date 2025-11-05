<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function register(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            DB::beginTransaction();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::login($user);

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Registration successful!',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->route('home')->with('success', 'Registration successful!');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Registration error: ' . $e->getMessage());

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Registration failed',
                    'errors' => ['general' => 'An error occurred during registration.']
                ], 500);
            }

            return back()
                ->withInput()
                ->withErrors(['general' => 'An error occurred during registration.']);
        }
    }

    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);

            if (!Auth::attempt($credentials, $request->boolean('remember'))) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }

            $request->session()->regenerate();

            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Logged in successfully!',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->route('crud.index')->with('success', 'Logged in successfully!');


        } catch (ValidationException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Login failed',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Logged out successfully!',
                'redirect' => route('home')
            ]);
        }

        return redirect()->route('home')->with('success', 'Logged out successfully!');
    }
}
