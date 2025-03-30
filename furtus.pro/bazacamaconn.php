<?php
$servername = "localhost";   // суда пихайте все данные вашей бдешки
$username = "";
$password = "";
$database = "";


$conn = new mysqli($servername, $username, $password, $database);


if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
