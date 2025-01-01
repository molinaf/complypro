@extends('layouts.app')

@section('content')
<h1>Edit {{ ucfirst($model) }}</h1>

<div class="container" style="max-width: 600px; margin: 0 auto;">
    <!-- Form for editing the model -->
    <form action="{{ route('dynamic.update', ['model' => $model, 'id' => $item->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Generate input fields dynamically for each attribute -->
        @foreach ($item->getAttributes() as $attribute => $value)
            <div class="form-group mb-3">
                <label for="{{ $attribute }}">{{ ucfirst(str_replace('_', ' ', $attribute)) }}</label>
                <input 
                    type="{{ is_numeric($value) ? 'number' : 'text' }}" 
                    name="{{ $attribute }}" 
                    id="{{ $attribute }}" 
                    class="form-control" 
                    value="{{ $value }}" 
                    {{ $attribute === 'id' ? 'readonly' : 'required' }}>
            </div>
        @endforeach

        <!-- Submit Button -->
        <div class="form-group mt-3">
            <button type="submit" class="btn btn-success">Update {{ ucfirst($model) }}</button>
            <a href="{{ route('dynamic.index', ['model' => $model]) }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

@endsection
