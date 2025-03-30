<?php

session_start();

require_once('bazacamaconn.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];


?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль Аккаунта</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-image: url('sre/fon.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .profile-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            width: 80%;
            max-width: 800px;
            overflow: hidden;
            margin: 20px;
        }

        .profile-header {
            background-color:rgb(4, 31, 90);
            color: #fff;
            padding: 20px;
            text-align: center;
        }

        .profile-header img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            margin-bottom: 15px;
        }

        .profile-details {
            padding: 20px;
        }

        .profile-details h2 {
            margin-bottom: 10px;
            color: #333;
        }

        .profile-details p {
            color: #555;
            line-height: 1.6;
        }

        .profile-info {
            margin-bottom: 15px;
        }

        .profile-info strong {
            color: #3498db;
        }

        .profile-social {
            margin-top: 20px;
            text-align: center;
        }

        .profile-social a {
            display: inline-block;
            margin: 0 10px;
            color: #3498db;
            font-size: 24px;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .profile-social a:hover {
            color: #2980b9;
        }

        @media (max-width: 600px) {
            .profile-container {
                width: 95%;
            }
        }

    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

    <div class="profile-container">
        <div class="profile-header">
        <p style="font-size: 30px; padding: 4px;">Имя пользователя: <?php echo htmlspecialchars($username); ?> </p><br>
        <p style="font-size: 30px; padding: 4px;">Айди пользователя: <?php echo htmlspecialchars($user_id); ?> </p><br>
        <p style="font-size: 30px; padding: 4px;"><a href="start.php">Назад</a></p>
    </div>