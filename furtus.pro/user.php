<?php

session_start();
require_once('bazacamaconn.php');

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск пользователя по имени</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Поиск пользователя</h1>
        <form id="searchForm" action="search.php" method="GET">
            <label for="username">имя пользователя:</label>
            <input type="text" id="username" name="username" placeholder="Введите имя пользователя" required>
            <button type="submit">Найти</button>
        </form>

        <div id="searchResults">

        </div>
    </div>

    <script src="script.js"></script>
</body>
</html>