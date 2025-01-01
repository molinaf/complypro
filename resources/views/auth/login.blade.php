@extends('layouts.app_auth')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden mt-[-250px]">

        <!-- Card Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xl font-bold px-6 py-4">
            {{ __('Login') }}
        </div>

        <!-- Card Body (Adjusted spacing) -->
        <div class="px-6 py-4 mt-[-16px]"> <!-- Reduced top margin to lift the card body closer -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email Input -->
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-medium mb-2">{{ __('Email Address') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300 @error('email') border-red-500 @enderror">
                    
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div class="mb-4">
                    <label for="password" class="block text-gray-700 font-medium mb-2">{{ __('Password') }}</label>
                    <input id="password" type="password" name="password" required autocomplete="current-password"
                        class="w-full px-4 py-2 border rounded-lg shadow-sm focus:outline-none focus:ring focus:ring-blue-300 @error('password') border-red-500 @enderror">
                    
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="mb-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="remember" id="remember" 
                            {{ old('remember') ? 'checked' : '' }} 
                            class="h-4 w-4 text-blue-500 focus:ring focus:ring-blue-300 border-gray-300 rounded">
                        <label for="remember" class="ml-2 text-gray-700 text-sm">
                            {{ __('Remember Me') }}
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-between">
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 focus:outline-none focus:ring focus:ring-blue-300">
                        {{ __('Login') }}
                    </button>
                    @if (Route::has('password.request'))
                        <a class="text-sm text-blue-500 hover:underline" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Validation Errors -->
@if ($errors->any())
<div class="mt-4 max-w-md mx-auto bg-red-50 border border-red-400 text-red-600 text-sm rounded-lg p-4">
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
@endsection
