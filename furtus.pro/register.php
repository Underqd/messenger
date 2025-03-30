<?php
session_start();

require_once('bazacamaconn.php');

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];
    $names = $_POST["names"];


    if (empty($username) || empty($password) || empty($names)) {
        $error_message = "Пожалуйста, заполните все поля.";
    }


    if (strlen($username) < 4) {
      $error_message = "Имя пользователя должно быть не менее 4 символов.";
    }

    $sql = "SELECT id FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Имя пользователя уже занято.";
    }

    if (strlen($names) < 8) {
        $error_message = "Имя пользователя должно быть не менее 8 символов.";
    }

    $sql = "SELECT id FROM users WHERE names = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $names);
    $stmt->execute();
    $stmt->store_result();

    $stmt->close();



    if (empty($error_message)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);


        $sql = "INSERT INTO users (username, password, names) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $username, $hashed_password, $names);

        if ($stmt->execute()) {
            $success_message = "Регистрация прошла успешно! <a href='login.php'>Войти</a>";
        } else {
            $error_message = "Ошибка при регистрации: " . $conn->error;
        }

        $stmt->close();
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 400px;
            text-align: center;
        }

        h2 {
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            text-align: left;
            margin-bottom: 5px;
            color: #666;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .button {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .error-message {
            color: red;
        }

        .success-message {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Регистрация</h2>

    <?php if (!empty($error_message)): ?>
        <p class="error-message"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <?php if (!empty($success_message)): ?>
        <p class="success-message"><?php echo $success_message; ?></p>
    <?php else: ?>

        <form method="post" action="register.php">
            <div class="form-group">
                <label for="username">Логин</label>
                <input type="text" id="username" name="username" required>

                <label for="names">Ваше имя и фамилия:</label>
                <input type="text" id="names" name="names" required>
            </div>
            <div class="form-group">
                <label for="password">Пароль:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="button">Зарегистрироваться</button>
        </form>
        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>

    <?php endif; ?>

</div>

</body>
</html>