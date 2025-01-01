@extends('layouts.link')

@section('content')
<!-- Authorisations Table -->
<h1 align="center">Authorisations and Requisite Table</h1>
<div style="display: flex; gap: 20px;">
    <div style="flex: 1;">
        <h2 align="center">Authorisations</h2>
        <table align="right" border="1" cellspacing="0" cellpadding="5" style="width: 95%; margin-bottom: 20px;">
            @foreach($authorisations as $categoryName => $authItems)
            <thead>
                <tr>
                    <th colspan=2 
                        style="background-color: lightblue; cursor: pointer;" 
                        class="category-heading">
                        {{ $categoryName }}
                    </th>
                </tr>
                <tr>
                    <th width=5% align="center">ID</th>
                    <th width=90%>Name</th>
                </tr>
            </thead>
            <tbody>
                @foreach($authItems as $auth)
                <tr class="auth-row" style="cursor: pointer;">
                    <td align="center">{{ $auth->id }}</td>
                    <td>{{ $auth->name }}</td>
                </tr>
                @endforeach            
            </tbody>
            @endforeach
        </table>
    </div>

    <!-- Related Table -->
    <div style="flex: 1;">
        <h2>&nbsp;</h2>
        <div id="related-items-container" class="shadow-box">
            <p>Select an authorisation to view related items.</p>
        </div>
    </div>
</div>

<!-- Add styles -->
<style>
    /* General Shadowed Box */
    .shadow-box {
        border: 1px solid #ddd;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        min-height: 100px;
        transition: all 0.3s ease-in-out;
    }

    /* Shadow effect for dynamically loaded AJAX content */
    .inner-shadow-box {
        border: 1px solid #555;
        padding: 10px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        margin: 5px 0;
    }

    /* Selected row effect */
    .auth-row.selected {
        background-color: #a3d5ff;
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
                    const shadowedContent = `<div class="inner-shadow-box">${data}</div>`;
                    $('#related-items-container').html(shadowedContent);
                },
                error: function () {
                    $('#related-items-container').html('<div class="inner-shadow-box" style="color:red;">Error loading related items. Please try again.</div>');
                }
            });
        });
    });
</script>
@endsection
