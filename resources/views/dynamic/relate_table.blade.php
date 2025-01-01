@extends('layouts.link')

@section('content')
<!-- Authorisations Table -->
<h1 class="text-center text-2xl font-bold my-6">Authorisations and Requisite Table</h1>
<div class="flex gap-8">
    <!-- Authorisations Section -->
    <div class="flex-1">
        <h2 class="text-center text-xl font-semibold mb-4">Authorisations</h2>
        <table class="w-full border border-gray-300 text-left">
            @foreach($authorisations as $categoryName => $authItems)
            <thead>
                <tr>
                    <th colspan="2" class="bg-blue-100 text-gray-800 p-2 text-center cursor-pointer">
                        {{ $categoryName }}
                    </th>
                </tr>
                <tr class="bg-gray-200">
                    <th class="p-2 text-center w-16">ID</th>
                    <th class="p-2">Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authItems as $auth)
                <tr class="hover:bg-blue-50 cursor-pointer border-b border-gray-300 auth-row">
                    <td class="p-2 text-center">{{ $auth->id }}</td>
                    <td class="p-2">{{ $auth->name }}</td>
                </tr>
                @endforeach
            </tbody>
            @endforeach
        </table>
    </div>

    <!-- Related Items Section -->
    <div class="flex-1">
        <h2 class="text-xl font-semibold text-center mb-4">&nbsp;</h2>
        <div id="related-items-container" class="border border-gray-300 rounded-lg shadow-md p-4 text-gray-600">
            <p>Select an authorisation to view related items.</p>
        </div>
    </div>
</div>

<!-- Styles and Scripts -->
<style>
    .auth-row.selected {
        background-color: #bfdbfe;
    }
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $('.auth-row').on('click', function () {
            // Remove 'selected' class from all rows
            $('.auth-row').removeClass('selected');
            
            // Add 'selected' class to clicked row
            $(this).addClass('selected');
            
            let authId = $(this).find('td:first').text(); // Get the ID from clicked row
            console.log('Clicked ID: ', authId);

            // Make AJAX request
            $.ajax({
                url: '{{ route("authorisation.relatedItems", ":id") }}'.replace(':id', authId),
                method: 'GET',
                success: function (data) {
                    const shadowedContent = `<div class="border border-gray-400 rounded-lg p-3">${data}</div>`;
                    $('#related-items-container').html(shadowedContent);
                },
                error: function () {
                    $('#related-items-container').html('<div class="text-red-500">Error loading related items. Please try again.</div>');
                }
            });
        });
    });
</script>
@endsection
