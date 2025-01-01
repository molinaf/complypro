@extends('layouts.app')

@section('content')
<div class="container mx-auto max-w-4xl px-4 py-6">
    <!-- Page Heading -->
    <h1 class="text-2xl font-bold text-center mb-6">{{ ucfirst($model) }} List</h1>

    <!-- Add New Button -->
    <div class="text-center mb-4">
        <a href="{{ route('dynamic.create', ['model' => request()->segment(1)]) }}" 
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
            Add New {{ request()->segment(1) }}
        </a>
    </div>

    @if ($items->isNotEmpty())
    <!-- Table Container -->
    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full border-collapse border border-gray-300 text-left">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border border-gray-300">ID</th>
                    <th class="p-2 border border-gray-300">Name</th>
                    <th class="p-2 border border-gray-300 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <?php $counter = 0; ?>
                <tr class="odd:bg-white even:bg-gray-50">
                    @foreach ($item->getAttributes() as $value)
                    <td class="p-2 border border-gray-300">{{ $value }}</td>
                    <?php $counter++; ?>
                    @if ($counter >= 2)
                        @break
                    @endif
                    @endforeach
                    <td class="p-2 border border-gray-300 text-center flex justify-center gap-2">
                        <!-- Edit Button -->
                        <form action="{{ route('dynamic.edit', ['model' => request()->segment(1), 'id' => $item->id]) }}" method="GET">
                            <button type="submit" 
                                    class="bg-yellow-400 text-white px-3 py-1 rounded hover:bg-yellow-500">
                                Edit
                            </button>
                        </form>

                        <!-- Delete Button -->
                        <button type="button" 
                                class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700 delete-btn" 
                                data-id="{{ $item->id }}">
                            Delete
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <p class="text-center text-gray-600 mt-4">No {{ $model }} found.</p>
    @endif

    <!-- Confirmation Modal -->
    <div id="confirmationModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg p-6 w-11/12 max-w-md text-center">
            <h3 class="text-lg font-bold mb-4">Are you sure you want to delete this record?</h3>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
                    Yes, Delete
                </button>
            </form>
            <button class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 cancel-btn ml-4">
                Cancel
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('confirmationModal');
        const deleteForm = document.getElementById('deleteForm');
        const cancelBtn = document.querySelector('.cancel-btn');

        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const model = '{{ request()->segment(1) }}';
                deleteForm.action = "{{ route('dynamic.delete', ['model' => Str::plural($model), 'id' => $item->id]) }}";
                modal.classList.remove('hidden');
            });
        });

        cancelBtn.addEventListener('click', function () {
            modal.classList.add('hidden');
        });
    });
</script>
@endsection
