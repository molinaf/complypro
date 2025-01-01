@extends('layouts.app2')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-6">Registration Summary</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- User Details -->
        <div>
            <h3 class="text-lg font-semibold">User Details</h3>
            <p><strong>Name:</strong> {{ $user->name }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Date of Registration:</strong> {{ $registrationDate->format('d-m-Y H:i:s') }}</p>
        </div>

        <!-- Company Details -->
        <div>
            <h3 class="text-lg font-semibold">Company Details</h3>
            <p><strong>Name:</strong> {{ $user->company->name ?? 'N/A' }}</p>
            <p><strong>Address:</strong> {{ $user->company->address ?? 'N/A' }}</p>
            <p><strong>Contact:</strong> {{ $user->company->contact ?? 'N/A' }}</p>
        </div>
    </div>

    <!-- Endorsed Authorisations -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h3 class="text-lg font-semibold">Endorsed Authorisations</h3>
            <ul>
                @foreach ($authorisations as $auth)
                    <li>{{ $auth->name }}</li>
                @endforeach
            </ul>
        </div>

        <div>
            <h3 class="text-lg font-semibold">Endorsing Person</h3>
            <p><strong>Name:</strong> {{ $endorsingPerson->name }}</p>
            <p><strong>Email:</strong> {{ $endorsingPerson->email }}</p>
        </div>
    </div>

    <!-- Prerequisites -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach ($prerequisites as $type => $items)
            <div class="border rounded-lg p-4 bg-white shadow">
                <h4 class="text-md font-semibold mb-2">
                    @switch($type)
                        @case(1) Modules @break
                        @case(2) F2Fs @break
                        @case(3) Inductions @break
                        @case(4) Licenses @break
                        @default Other
                    @endswitch
                </h4>
                <ul>
                    @foreach ($items as $item)
                        <li>
                            {{ $item->module->name ?? $item->f2f->name ?? $item->induction->name ?? $item->license->name ?? 'Unknown' }} 
                            :- 
                            <span class="
                                @switch($item->status)
                                    @case('P') text-orange-400 @break
                                    @case('C') text-green-800 @break
                                    @case('R') font-bold text-red-600 @break
                                    @default text-gray-500
                                @endswitch
                            ">
                                @switch($item->status)
                                    @case('P') Pending @break
                                    @case('C') Completed @break
                                    @case('E') Expired @break
                                    @default Unknown
                                @endswitch
                            </span>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    <!-- Back Button -->
    <div class="mt-6">
        <a href="{{ route('authorisations.register') }}" class="bg-blue-600 text-white py-2 px-4 rounded">Back to Registration</a>
    </div>
</div>
@endsection
