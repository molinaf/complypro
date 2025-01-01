@extends('layouts.app2')

@section('content')
<div class="container mx-auto px-4 py-6 mx-auto w-2/5">
    <h1 class="text-2xl font-bold text-gray-800">Supervisor Dashboard</h1>
    
    <div class="bg-white shadow rounded-lg p-6">
        <ul class="space-y-4">
            <li>
                <a href="{{ route('authorisations.register') }}" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">Register Authorisations</a>
            </li>
            <li>
                <a href="{{ route('authorisations.view') }}" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">View Authorisations</a>
            </li>
    </ul>
    </div>
    
    @if($pendingUsers->isEmpty())
        <p class="text-gray-500 mt-4">No pending endorsements at the moment.</p>
    @else
        <div class="mt-6">
            <table class="min-w-full bg-white border rounded-lg shadow">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                <a href="{{ route('endorse.user', $user->id) }}"
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Endorse
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
