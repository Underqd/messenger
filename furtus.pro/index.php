<?php
session_start();
require_once('bazacamaconn.php');



function generateQRCode($data, $size = '150x150') {
    $url = "https://api.qrserver.com/v1/create-qr-code/?size=" . $size . "&data=" . urlencode($data);
    return '<img src="" alt="QR Code">';
}


function isLoggedIn() {
    return isset($_SESSION['user_id']);
}


if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель регистрации/входа</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        .container {
            background-color: rgba(0, 0, 0, 0.84);;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(255, 254, 254, 0.84);
            padding: 20px;
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
        }
        p {
            color: #333;
        }

        .button-container {
            margin-top: 20px;
        }

        .button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin: 5px;
            text-decoration: none;
            display: inline-block;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .qr-code-container {
            margin-top: 20px;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }
    </style>
</head>
<body>

<div class="container">

    <h2>Панель регистрации/входа</h2>

    <?php if (isLoggedIn()): ?>
        <p class="success-message">Вы авторизованы!</p>
        <p>Добро пожаловать, <?php echo $_SESSION['username']; ?>!</p>
        <a href="start.php" class="button">Войти</a>
        <a href="index.php?logout=true" class="button">Выйти</a>
    <?php else: ?>

        <div class="qr-code-container">
            <?php
            $qr_token = bin2hex(random_bytes(16));
            $_SESSION['qr_token'] = $qr_token;

            $qr_data = "http://вашнагенератор.com/qr_login.php?token=" . $qr_token;

            // Выводим QR-код
            echo generateQRCode($qr_data);
            ?>
            <p>Отсканируйте QR-код для входа.</p>
        </div>

        <div class="button-container">
            <a href="register.php" class="button">Зарегистрировать аккаунт</a>
            <a href="login.php" class="button">Войти в аккаунт</a>
        </div>

    <?php endif; ?>

</div>

</body>
</html>