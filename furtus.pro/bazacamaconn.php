<?php
$servername = "localhost";   // суда пихайте все данные вашей бдешки
$username = "";  // суда пихайте name bd
$password = "";   // Пароль бД
$database = "";  // Суда тоже ник


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
