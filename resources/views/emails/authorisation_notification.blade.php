<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authorisation Notification</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <h1>Hello, {{ $user->name }}</h1>
    <p>You have been assigned the following authorisations:</p>
    @if($selectedUserDetails)
    <!-- User Details -->

    <!-- Applications -->
    <div class="mt-6">
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
    <p>Please complete the requisites for these authorisations as soon as possible.</p>
</body>
</html>
