<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Modern Design</title>
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
            <h1 class="text-3xl font-bold text-white">Create Your Account</h1>
            <p class="text-gray-200 mt-2">Join us to get started</p>
        </div>

        <!-- Form Section -->
        <div class="p-8">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name Input -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                        placeholder="Enter your full name"
                    />
                    @if ($errors->has('name'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->first('name') }}</p>
                    @endif
                </div>

                <!-- Email Input -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
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

                <!-- Confirm Password Input -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-300"
                        placeholder="Confirm your password"
                    />
                    @if ($errors->has('password_confirmation'))
                        <p class="text-sm text-red-500 mt-1">{{ $errors->first('password_confirmation') }}</p>
                    @endif
                </div>

                <!-- Submit Button -->
                <button
                    type="submit"
                    class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300"
                >
                    Register
                </button>
            </form>

            <!-- Login Link -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600">Already have an account? <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-800">Log in</a></p>
            </div>
        </div>
    </div>
</body>
</html>
