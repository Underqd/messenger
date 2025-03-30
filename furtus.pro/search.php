<?php

session_start();
require_once('bazacamaconn.php');


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit();
}

$searchUsername = $_GET['username'] ?? '';
$currentUserId = $_SESSION['user_id'];


$sql = "SELECT id, username FROM users WHERE username LIKE '%$searchUsername%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='searchResults'>";
    while($row = $result->fetch_assoc()) {
        $recipientId = $row["id"];
        echo "<div class='user-info'>";
        echo "<p><strong>Ник:</strong> " . htmlspecialchars($row["username"]) . "</p>";

        // Кнопка "Написать" с передачей ID получателя
        echo "<a href='chat.php?recipientId=" . $recipientId . "'>Написать</a>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "<div class='searchResults'><p>Пользователь с ником '" . htmlspecialchars($searchUsername) . "' не найден.</p></div>"; // Используем htmlspecialchars для предотвращения XSS
}

$conn->close();

?>