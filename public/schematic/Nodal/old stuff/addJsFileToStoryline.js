<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inject Script Tag After Div Element</title>
</head>
<body>
    <!-- Existing div element -->
    <div id="existingDiv">This is an existing div.</div>

    <script>
        // Create a new script element
        const newScript = document.createElement('script');
        newScript.src = 'injectedJS.js';

        // Get the existing div element
        const existingDiv = document.getElementById('existingDiv');

        // Insert the new script element after the existing div
        existingDiv.parentNode.insertBefore(newScript, existingDiv.nextSibling);
    </script>
</bodty>
</html>
