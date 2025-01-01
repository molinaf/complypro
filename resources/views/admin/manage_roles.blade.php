@extends('layouts.app2')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800">Manage User Roles</h1>
    <p class="text-gray-600">Select a company to view and manage users, or view all users grouped by company.</p>

    <!-- Company Selection Form -->
    <form method="GET" action="{{ route('admin.manage.roles') }}" class="mt-4">
        <div class="flex items-center">
            <select name="company_id" class="border rounded px-4 py-2 mr-4 w-full md:w-1/2">
                <option value="">Select a Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                View Users
            </button>
        </div>
    </form>

    <!-- Show All Users Button -->
    <form method="GET" action="{{ route('admin.manage.roles.showAll') }}" class="mt-4">
        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Show All Users
        </button>
    </form>
    <!-- User List -->
    @if(!empty($users))
    <div class="mt-6">
        @if($users->isEmpty())
            <p class="text-gray-500">No users found for the selected company.</p>
        @else
            <table class="min-w-full bg-white border rounded-lg shadow">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Current Role</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="border-t">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            <td class="px-4 py-2">{{ $user->role }}</td>
                            <td class="px-4 py-2">
                                <!-- Role Update Form -->
                                <form method="POST" action="{{ route('admin.update.role', $user->id) }}" class="flex items-center">
                                    @csrf
                                    <select name="role" class="border rounded px-2 py-1 mr-2">
                                        <option value="U" {{ $user->role == 'U' ? 'selected' : '' }}>User</option>
                                        <option value="S" {{ $user->role == 'S' ? 'selected' : '' }}>Supervisor</option>
                                        <option value="G" {{ $user->role == 'G' ? 'selected' : '' }}>Global Super</option>
                                        <option value="M" {{ $user->role == 'M' ? 'selected' : '' }}>Manager</option>
                                        <option value="A" {{ $user->role == 'A' ? 'selected' : '' }}>Admin</option>
                                    </select>
                                    <button type="submit" class="bg-green-500 text-white px-2 py-1 rounded hover:bg-green-600">
                                        Update
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
    @endif
    <!-- User List -->
    @if(!empty($groupedUsers))
        <div class="mt-6 w-4/5 mx-auto">
            <table class="min-w-full bg-white border rounded-lg shadow">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-2 text-left">Company</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Email</th>
                        <th class="px-4 py-2 text-left">Role</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($groupedUsers as $companyName => $users)
                        <tr class="bg-gray-100">
                            <td colspan="5" class="px-4 py-2 font-bold">{{ $companyName }}</td>
                        </tr>
                        @foreach($users as $user)
                            <tr class="border-t">
                                <td class="px-4 py-2"></td> <!-- Empty cell for alignment -->
                                <td class="px-4 py-2">{{ $user->name }}</td>
                                <td class="px-4 py-2">{{ $user->email }}</td>
                                <td class="px-4 py-2">{{ $user->role }}</td>
                                <td class="px-4 py-2 flex space-x-2">
                                    <a href="{{ route('admin.edit.user', $user->id) }}"
                                       class="bg-yellow-500 text-white px-2 py-1 rounded hover:bg-yellow-600">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<!-- Inline Styles -->
<style>
    table th, table td {
        padding: 12px 16px;
        text-align: left;
        vertical-align: middle;
    }
    table th {
        background-color: #2563eb; /* Tailwind's blue-500 */
        color: white;
    }
    table tbody tr:nth-child(odd) {
        background-color: #f9fafb; /* Tailwind's gray-100 */
    }
    table tbody tr:nth-child(even) {
        background-color: #ffffff; /* White for even rows */
    }
    table thead th {
        text-align: left;
    }
    table tbody tr.bg-gray-100 td {
        font-weight: bold;
        background-color: #e5e7eb; /* Tailwind's gray-200 for group headers */
    }
</style>
@endsection
