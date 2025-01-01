@extends('layouts.app2')

@section('content')
<center>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">User Dashboard</h1>
        <div class="bg-white shadow rounded-lg p-6">
            <ul class="space-y-4">
                <li>
                    <a href="{{ route('authorisations.view') }}" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">Show My Authorisations</a>
                </li>
                <li>
                    <a href="/home" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-basehover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">
                        Nothing yet... Back to home
                    </a>
                </li>
            </ul>
        </div>
    </div>
</center>
@endsection


