@extends('layouts.app2')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold text-gray-800">Edit User</h1>
    <div class="mt-6 w-4/5 mx-auto">
    <form method="POST" action="{{ route('admin.update.user', $user->id) }}" class="mt-6 bg-white shadow rounded-lg p-6">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                   class="w-full border rounded px-4 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label for="email" class="block text-gray-700">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                   class="w-full border rounded px-4 py-2 mt-1" required>
        </div>

        <div class="mb-4">
            <label for="role" class="block text-gray-700">Role</label>
            <select name="role" id="role" class="w-full border rounded px-4 py-2 mt-1">
                <option value="U" {{ $user->role == 'U' ? 'selected' : '' }}>User</option>
                <option value="S" {{ $user->role == 'S' ? 'selected' : '' }}>Supervisor</option>
                <option value="G" {{ $user->role == 'G' ? 'selected' : '' }}>Global Supervisor</option>
                <option value="M" {{ $user->role == 'M' ? 'selected' : '' }}>Manager</option>
                <option value="A" {{ $user->role == 'A' ? 'selected' : '' }}>Admin</option>
            </select>
        </div>

        <div class="mb-4">
            <label for="company_id" class="block text-gray-700">Company</label>
            <select name="company_id" id="company_id" class="w-full border rounded px-4 py-2 mt-1">
                <option value="">No Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ $user->company_id == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
            Save Changes
        </button>
    </form>
    </div>
</div>
@endsection
