@extends('layouts.app2')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Authorisation Details</h2>

    @if(in_array(Auth::user()->role, ['A', 'G', 'M', 'H']))
        <!-- Company Dropdown -->
        <form method="GET" action="{{ route('authorisations.view') }}" class="mb-4">
            <label for="company" class="block text-sm font-medium">Select Company</label>
            <select id="company" name="company_id" class="w-1/2 p-2 border border-gray-300 rounded mb-4" onchange="this.form.submit()">
                <option value="">Choose a Company</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" {{ $selectedCompany == $company->id ? 'selected' : '' }}>
                        {{ $company->name }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif

    @if($users)
        <!-- User Dropdown -->
        <form method="GET" action="{{ route('authorisations.view') }}" class="mb-4">
            <input type="hidden" name="company_id" value="{{ $selectedCompany }}">
            <label for="user" class="block text-sm font-medium">Select User</label>
            <select id="user" name="user_id" class="w-1/2 p-2 border border-gray-300 rounded mb-4" onchange="this.form.submit()">
                <option value="">Choose a User</option>
                @foreach ($users as $u)
                    <option value="{{ $u->id }}" {{ $selectedUserDetails && $selectedUserDetails->id == $u->id ? 'selected' : '' }}>
                        {{ $u->name }}
                    </option>
                @endforeach
            </select>
        </form>
    @endif

    @if($selectedUserDetails)
        <!-- User Details -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h3 class="text-lg font-bold">User Details</h3>
                <p><strong>Name:</strong> {{ $selectedUserDetails->name }}</p>
                <p><strong>Email:</strong> {{ $selectedUserDetails->email }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold">Company Details</h3>
                <p><strong>Name:</strong> {{ $selectedUserDetails->company->name ?? 'N/A' }}</p>
                <p><strong>Contact:</strong> {{ $selectedUserDetails->company->contact ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Applications -->
        <div class="mt-6">
            <h3 class="text-lg font-bold">Applications</h3>
            @foreach ($applications as $application)
                <div class="mt-4 border rounded-lg p-4 bg-gray-50 shadow-lg">
                    <h4 class="text-md font-semibold">Application ID#: {{ $application->id }}</h4>
                    <p><strong>Endorser:</strong> {{ $application->endorser->name }}</p>
                    <p><strong>Endorsement Date:</strong> {{ $application->endorsement_date->format('d-m-Y H:i:s') }}</p>
                    <p><strong>Status:</strong>
                        <span class="
                            @switch($application->status)
                                @case('P') text-red-600 @break
                                @case('C') text-blue-600 @break
                                @case('A') text-green-600 @break
                                @default text-gray-600
                            @endswitch
                        ">
                            @switch($application->status)
                                @case('P') Pending @break
                                @case('C') Completed @break
                                @case('A') Approved @break
                                @default Unknown
                            @endswitch
                        </span>
                    </p>

                    <!-- Endorsed Authorisations -->
                    <div class="mt-4">
                        <h5 class="text-md font-semibold">Endorsed Authorisations</h5>
                        <ul class="list-disc list-inside">
                            @foreach ($application->userAuthorisations as $userAuth)
                                <li>{{ $userAuth->authorisation->name }}</li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Prerequisites -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <h5 class="text-md font-semibold">Prerequisites</h5>
                        @foreach ($application->userAuthorisations as $userAuth)
                            @foreach ($userAuth->prerequisites->groupBy('type') as $type => $items)
                                <div class="border rounded-lg p-4 bg-white shadow">
                                    <h6 class="text-md font-semibold mb-2">
                                        @switch($type)
                                            @case(1) Modules @break
                                            @case(2) F2Fs @break
                                            @case(3) Inductions @break
                                            @case(4) Licenses @break
                                            @default Other
                                        @endswitch
                                    </h6>
                                    <ul class="list-disc list-inside">
                                        @foreach ($items as $item)
                                            <li>
                                                {{ $item->module->name ?? $item->f2f->name ?? $item->induction->name ?? $item->license->name ?? 'Unknown' }}
                                                - Status:
                                                <span class="
                                                    @switch($item->status)
                                                        @case('P') text-red-600 @break
                                                        @case('C') text-green-800 @break
                                                        @case('R') font-bold text-red-600 @break
                                                        @default text-gray-500
                                                    @endswitch
                                                ">
                                                    @switch($item->status)
                                                        @case('P') Pending @break
                                                        @case('C') Completed @break
                                                        @case('R') Revoked @break
                                                        @default Unknown
                                                    @endswitch
                                                </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
