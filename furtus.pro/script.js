document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();

    var username = document.getElementById('username').value;

    var xhr = new XMLHttpRequest();
    xhr.open('GET', 'search.php?username=' + username, true);

    xhr.onload = function() {
        if (xhr.status >= 200 && xhr.status < 300) {
            document.getElementById('searchResults').innerHTML = xhr.responseText;
        } else {
            // Обрабатываем ошибку
            document.getElementById('searchResults').innerHTML = '<p>Произошла ошибка при выполнении запроса.</p>';
        }
    };

    xhr.onerror = function() {
        document.getElementById('searchResults').innerHTML = '<p>Произошла сетевая ошибка.</p>';
    };

    xhr.send();
});