@extends('layouts.app2')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800">Manager Dashboard</h1>
    <p class="text-gray-600">List of All Pending Endorsements</p>

    @if($pendingUsers->isEmpty())
        <p class="text-gray-500 mt-4">No pending endorsements at the moment.</p>
    @else
        <div class="mt-6 w-4/5 mx-auto">
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
                                    Approve
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
