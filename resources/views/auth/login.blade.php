@php
use Illuminate\Support\Facades\Route;
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Modern Design</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden w-full max-w-md fade-in">
        <!-- Header Section -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 p-8 text-center">
            <h1 class="text-3xl font-bold text-white">Welcome Back</h1>
            <p class="text-gray-200 mt-2">Sign in to continue to your account</p>
        </div>

        <!-- Form Section -->
        <div class="p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                        placeholder="Enter your email"
                    />
                    @if ($errors->has('email'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->first('email') }}</p>
                    @endif
                </div>

                <!-- Password Input -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                        placeholder="Enter your password"
                    />
                    @if ($errors->has('password'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->first('password') }}</p>
                    @endif
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="flex items-center justify-between mb-6">
                    <label for="remember_me" class="flex items-center">
                        <input
                            type="checkbox"
                            id="remember_me"
                            name="remember"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        />
                        <span class="ml-2 text-sm text-gray-600">Remember me</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300"
                >
                    Log In
                </button>
            </form>

            <!-- Sign Up Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Don't have an account? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800">Sign up</a></p>
            </div>
        </div>
    </div>
</body>
</html>
