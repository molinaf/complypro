@extends('layout.app_auth')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Register Authorisations</h2>

    <form action="{{ route('authorisations.store') }}" method="POST">
        @csrf

        @if(Auth::user()->role === 'A' || Auth::user()->role === 'G')
            <label for="company" class="block text-sm font-medium">Select Company</label>
            <select id="company" name="company_id" class="w-full p-2 border border-gray-300 rounded mb-4">
                <option value="">Choose a Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>
        @endif

        <label for="user" class="block text-sm font-medium">Select User</label>
        <select id="user" name="user_id" class="w-full p-2 border border-gray-300 rounded mb-4">
            <option value="">Choose a User</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <div class="mb-4">
            <h3 class="text-lg font-bold mb-2">Authorisations</h3>
            @foreach($authorisations->groupBy('category') as $category => $auths)
                <h4 class="text-md font-semibold">{{ $category }}</h4>
                <ul class="pl-4">
                    @foreach($auths as $auth)
                        <li>
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="authorisations[]" value="{{ $auth->id }}" class="mr-2">
                                {{ $auth->name }}
                            </label>
                        </li>
                    @endforeach
                </ul>
            @endforeach
        </div>

        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Register</button>
    </form>
</div>
@endsection
