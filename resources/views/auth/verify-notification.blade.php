@extends('layouts.app_auth')

@section('content')
<div class="flex justify-center items-center min-h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xl font-bold px-6 py-4">
            {{ __('Verify Your Email Address') }}
        </div>

        <!-- Card Body -->
        <div class="px-6 py-4">
            @if (session('resent'))
                <div class="mb-4 p-4 text-sm text-green-600 bg-green-50 border border-green-400 rounded-lg">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <p class="text-gray-700 mb-4">
                {{ __('Before proceeding, please check your email for a verification link.') }}
            </p>
            <p class="text-gray-700">
                {{ __('If you did not receive the email') }},
            </p>

            <form method="POST" action="{{ route('verification.resend') }}" class="inline-block mt-4">
                @csrf
                <button type="submit" class="text-blue-500 hover:underline">
                    {{ __('click here to request another') }}
                </button>.
            </form>
        </div>
    </div>
</div>
@endsection
