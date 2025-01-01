@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-6">Create New {{ ucfirst($model) }}</h1>

<div class="container mx-auto max-w-lg">
    <!-- Form for creating a new record -->
    <form action="{{ route('dynamic.store', ['model' => Str::plural($model)]) }}" method="POST" class="space-y-6">
        @csrf
        @method('POST')

        <!-- Generate input fields dynamically based on model attributes -->
        @php
            // Dynamically retrieve attributes for the model
            $dummyInstance = new ("App\\Models\\" . ucfirst($model)); 
            $attributes = collect($dummyInstance->getFillable());
        @endphp
    
        @foreach ($attributes as $attribute)
            <div class="flex items-center">
                <label for="{{ $attribute }}" class="w-1/3 text-right pr-4 font-medium">
                    {{ ucfirst(str_replace('_', ' ', $attribute)) }}
                </label>
                <div class="w-2/3">
                    <input 
                        type="text" 
                        name="{{ $attribute }}" 
                        id="{{ $attribute }}" 
                        class="w-full px-4 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        required>
                </div>
            </div>
        @endforeach

        <!-- Submit Button -->
        <div class="flex justify-end space-x-4">
            <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-md shadow-md hover:bg-green-600">
                Create {{ ucfirst($model) }}
            </button>
            <a href="{{ route('dynamic.index', ['model' => Str::plural($model)]) }}" class="px-6 py-2 bg-gray-500 text-white rounded-md shadow-md hover:bg-gray-600">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
