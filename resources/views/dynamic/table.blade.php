@extends('layouts.link')

@section('content')
    <h1 class="text-2xl font-bold text-center mt-6">Authorisations and Requisite Table</h1>

    <form action="{{ route('pivot.store') }}" method="POST" class="mt-6">
        @csrf
        
        <!-- Hidden field to pass related table name -->
        <input type="hidden" name="related_table" value="{{ $relatedTableName }}">

        <div class="text-center mb-6">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Submit Selection Here</button>
        </div>

        <div class="flex gap-6">
            <!-- Authorisations Table -->
            <div class="flex-1 overflow-x-auto">
                <h2 class="text-xl font-semibold text-center mb-4">Authorisations</h2>
                @foreach ($authorisations->groupBy('category_id') as $categoryId => $groupedAuthorisations)
                    <table class="w-full border border-gray-300 mb-6">
                        <thead>
                            <tr>
                                <th colspan="3" 
                                    class="bg-blue-100 text-left px-4 py-2 cursor-pointer category-heading" 
                                    data-category-id="{{ $categoryId }}">
                                    {{ $categories->find($categoryId)->name ?? 'Uncategorized' }}
                                </th>
                            </tr>
                            <tr class="bg-gray-100">
                                <th class="w-16 text-center px-4 py-2">ID</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="w-16 text-center px-4 py-2">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedAuthorisations as $authorisation)
                                <tr class="border-t">
                                    <td class="text-center px-4 py-2">{{ $authorisation->id }}</td>
                                    <td class="px-4 py-2">{{ $authorisation->name }}</td>
                                    <td class="text-center px-4 py-2">
                                        <input 
                                            type="checkbox" 
                                            name="authorisation_ids[]" 
                                            value="{{ $authorisation->id }}" 
                                            class="authorisation-checkbox" 
                                            data-category-id="{{ $categoryId }}">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endforeach
            </div>

            <!-- Related Table -->
            <div class="flex-1 overflow-x-auto">
                <h2 class="text-xl font-semibold text-center mb-4">{{ ucfirst($relatedTableName) }}</h2>
                <table class="w-full border border-gray-300">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="w-16 text-center px-4 py-2">Select</th>
                            <th class="w-16 text-center px-4 py-2">ID</th>
                            <th class="px-4 py-2">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($relatedTable as $item)
                            <tr class="border-t">
                                <td class="text-center px-4 py-2">
                                    <input type="radio" name="related_item_id" value="{{ $item->id }}">
                                </td>
                                <td class="text-center px-4 py-2">{{ $item->id }}</td>
                                <td class="px-4 py-2">{{ $item->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    <!-- Add JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const categoryHeadings = document.querySelectorAll('.category-heading');

            categoryHeadings.forEach(heading => {
                heading.addEventListener('click', function() {
                    const categoryId = this.getAttribute('data-category-id');
                    const checkboxes = document.querySelectorAll(`.authorisation-checkbox[data-category-id='${categoryId}']`);

                    // Toggle all checkboxes in this category
                    const allChecked = Array.from(checkboxes).every(checkbox => checkbox.checked);
                    checkboxes.forEach(checkbox => {
                        checkbox.checked = !allChecked; // Check if all are currently checked and toggle
                    });
                });
            });
        });
    </script>
@endsection
