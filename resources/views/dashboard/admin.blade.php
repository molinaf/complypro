@extends('layouts.app2')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800">Admin Dashboard</h1>

    <!-- Navigation Links -->
    <div class="bg-white shadow rounded-lg p-6 mx-auto w-2/5">
        <ul class="space-y-4">
            <li>
                <a href="/main" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">
                    Table Maintenance
                </a>
            </li>
            <li>
                <a href="/requisites" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">
                    PreRequisite
                </a>
            </li>
            <li>
                <a href="/supervisor/pending-users" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">
                    Pending Registrations
                </a>
            </li>
            <li>
                <a href="{{ route('admin.manage.roles') }}" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">
                    Manage Roles
                </a>
            </li>
            <li>
                <a href="{{ route('authorisations.register') }}" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">Register Authorisations</a>
            </li>
            <li>
                <a href="{{ route('authorisations.view') }}" class="flex justify-center items-center h-10 bg-white text-gray-700 font-semibold text-base hover:scale-105 hover:bg-blue-500 rounded-lg shadow-sm transition duration-300">View Authorisations</a>
            </li>
            
        </ul>
    </div>

    <!-- Pending Endorsements Section -->
    <p class="text-gray-600 mt-8">All Pending Applications</p>

    @if($pendingUsers->isEmpty())
        <p class="text-gray-500 mt-4">No pending application at the moment.</p>
    @else
        <div class="mt-6">
            <table class="min-w-full bg-white border rounded-lg shadow">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Company</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pendingUsers as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">
                                {{ $user->company ? $user->company->name : 'No Company' }}
                            </td>
                            <td class="px-4 py-2">
                                <a href="{{ route('endorse.user', $user->id) }}"
                                   class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                                    Accept
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
