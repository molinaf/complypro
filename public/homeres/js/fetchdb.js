document.addEventListener('DOMContentLoaded', function() {
    // Fetch data from your database and render it dynamically
    fetch('/api/data')
        .then(response => response.json())
        .then(data => {
            const contentDiv = document.querySelector('.content');
            contentDiv.innerHTML = data.htmlContent; // Replace with your dynamic content
        });
});