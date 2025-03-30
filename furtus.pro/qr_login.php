<?php
session_start();

require_once('bazacamaconn.php');

if (isset($_GET['token']) && isset($_SESSION['qr_token']) && $_GET['token'] === $_SESSION['qr_token']) {

    $token = $_GET['token'];

    $sql = "SELECT user_id FROM qr_tokens WHERE token = '$token'"; 

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $user_id = $row['user_id'];


        $_SESSION['user_id'] = $user_id;


        $sql = "SELECT username FROM users WHERE id = $user_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $row['username'];
        }

        $sql = "DELETE FROM qr_tokens WHERE token = '$token'";
        $conn->query($sql);

        unset($_SESSION['qr_token']);
        header("Location: index.php");
        exit();
    } else {
        echo "Ошибка: токен не найден.";
    }
} else {
    echo "Ошибка: неверный токен.";
}

$conn->close();

?>