@extends('layouts.app2')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Register Authorisations</h2>

    <!-- Registration Form -->
    <form action="{{ route('authorisations.store') }}" method="POST">
        @csrf

        <!-- Display company dropdown if the user is an Admin or Global Supervisor -->
        @if(Auth::user()->role === 'A' || Auth::user()->role === 'G')
            <label for="company" class="block text-sm font-medium mb-2">Select Company</label>
            <select id="company" name="company_id" class="w-2/5 p-2 border border-gray-300 rounded mb-4">
                <option value="">Choose a Company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        @endif

        <!-- User Dropdown -->
        <label for="user" class="block text-sm font-medium mb-2">Select User</label>
        <select id="user" name="user_id" class="w-2/5 p-2 border border-gray-300 rounded mb-4" {{ isset($companies) ? 'disabled' : '' }}>
            <option value="">Choose a User</option>
            @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <!-- Authorisation Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach ($authorisations as $categoryName => $auths)
                <div class="border rounded-lg p-4 bg-white shadow">
                    <h3 class="text-lg font-semibold mb-2">{{ $categoryName }}</h3>
                    <ul>
                        @foreach ($auths as $auth)
                            <li class="mb-1">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="authorisations[]" value="{{ $auth->id }}" class="mr-2">
                                    {{ $auth->name }}
                                </label>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>

        <!-- Submit Button -->
        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded mt-4">Register</button>
    </form>
</div>

<!-- JavaScript for Dynamic User Loading -->
@if(Auth::user()->role === 'A' || Auth::user()->role === 'G')
    <script>
        document.getElementById('company').addEventListener('change', function () {
            const companyId = this.value;

            fetch(`/company/${companyId}/users`)
                .then(response => response.json())
                .then(users => {
                    const userDropdown = document.getElementById('user');
                    userDropdown.disabled = false;
                    userDropdown.innerHTML = '<option value="">Choose a User</option>';

                    users.forEach(user => {
                        const option = document.createElement('option');
                        option.value = user.id;
                        option.textContent = user.name;
                        userDropdown.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching users:', error));
        });
    </script>
@endif
@endsection
