@php
    // Check if all related collections are empty
    $allEmpty = $relatedItems['modules']->isEmpty() &&
                $relatedItems['f2fs']->isEmpty() &&
                $relatedItems['inductions']->isEmpty() &&
                $relatedItems['licenses']->isEmpty();
@endphp

@if($allEmpty)
    <p class="text-gray-600 text-center mt-4">No related items linked to this authorisation.</p>
@else
    @if($relatedItems['modules']->isNotEmpty())
        <h2 class="text-xl font-semibold text-gray-700 mt-6">Modules</h2>
        <ul class="list-disc list-inside text-gray-600">
            @foreach($relatedItems['modules'] as $module)
                <li class="py-1">{{ $module->name }}</li>
            @endforeach
        </ul>
    @endif

    @if($relatedItems['f2fs']->isNotEmpty())
        <h2 class="text-xl font-semibold text-gray-700 mt-6">F2F</h2>
        <ul class="list-disc list-inside text-gray-600">
            @foreach($relatedItems['f2fs'] as $f2f)
                <li class="py-1">{{ $f2f->name }}</li>
            @endforeach
        </ul>
    @endif

    @if($relatedItems['inductions']->isNotEmpty())
        <h2 class="text-xl font-semibold text-gray-700 mt-6">Inductions</h2>
        <ul class="list-disc list-inside text-gray-600">
            @foreach($relatedItems['inductions'] as $induction)
                <li class="py-1">{{ $induction->name }}</li>
            @endforeach
        </ul>
    @endif

    @if($relatedItems['licenses']->isNotEmpty())
        <h2 class="text-xl font-semibold text-gray-700 mt-6">Licenses</h2>
        <ul class="list-disc list-inside text-gray-600">
            @foreach($relatedItems['licenses'] as $license)
                <li class="py-1">{{ $license->name }}</li>
            @endforeach
        </ul>
    @endif
@endif
