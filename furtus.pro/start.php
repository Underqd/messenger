<?php
session_start();
require_once('bazacamaconn.php');

$currentUserId = $_SESSION['user_id'];

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

function generateChatId($userId1, $userId2) {
    $ids = [$userId1, $userId2];
    sort($ids);
    return md5($ids[0] . '_' . $ids[1]);
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['recipientId']) && isset($_POST['message'])) {
    $recipientId = $_POST['recipientId'];
    $message = $_POST['message'] ?? '';
    if (!empty($message)) {
        $chatId = generateChatId($currentUserId, $recipientId);
        $message = mysqli_real_escape_string($conn, $message);

        $sql = "INSERT INTO messages (chat_id, sender_id, recipient_id, message, timestamp) VALUES ('$chatId', '$currentUserId', '$recipientId', '$message', NOW())";
        if ($conn->query($sql) === TRUE) {
            header("Location: chat.php?recipientId=" . $recipientId);
            exit();
        } else {
            echo "Ошибка при отправке сообщения: " . $conn->error;
        }
    }
}



$sql_chats = "SELECT DISTINCT chat_id FROM messages WHERE sender_id = '$currentUserId' OR recipient_id = '$currentUserId'";
$result_chats = $conn->query($sql_chats);

$chatIds = [];
if ($result_chats && $result_chats->num_rows > 0) {
    while ($row = $result_chats->fetch_assoc()) {
        $chatIds[] = $row['chat_id'];
    }
}

?>


<!DOCTYPE html>
<html>
<head>
    <title>Furtus - Главная</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #343a40;
        }

        .container {
            background-image: url('sre/fon.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            display: flex;
            height: 100vh;
        }

        .left-sidebar {
            background-image: url('sre/fon.jpg');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            width: 250px;
            padding: 20px;
            border-right: 1px solid #dee2e6;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
        }

        .left-sidebar h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #007bff;
            text-align: center;
        }

        .main-content {
            flex: 1;
            padding: 20px;
        }

        .right-sidebar {
            width: 250px;
            background-color: #fff; 
            padding: 20px;
            border-left: 1px solid #dee2e6; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05); 
        }

        .right-sidebar h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 1.5rem;
            color: #007bff;
            text-align: center;
        }

        .chat-list {
            list-style: none;
            padding: 0;
        }

        .chat-list li {
            padding: 15px; 
            border-bottom: 1px solid #dee2e6;
            cursor: pointer; 
            transition: background-color 0.2s ease-in-out;
            display: flex; 
            align-items: center;
        }

        .chat-list li:hover {
            background-color: #e9ecef; 
        }

        .chat-list li:last-child {
            border-bottom: none; 
        }

        .chat-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

      
        .last-message {
            color: #6c757d; 
            font-size: 0.9rem;
            overflow: hidden;         
            white-space: nowrap;       
            text-overflow: ellipsis;    
            max-width: 200px;          

        }

        .last-message-time {
            color: #6c757d; 
            font-size: 0.8rem;
            margin-left: auto; 
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%; 
            background-color: #adb5bd;
            margin-right: 10px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 1.2rem;
        }

        header {
            background-color: #007bff; 
            color: #fff; 
            padding: 15px;
            text-align: center;
            margin-bottom: 20px;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            background-color: #007bff; 
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.2s ease-in-out;
        }

        .btn:hover {
            background-color: #0056b3;
        }
        .icon-back{
            width: 45px;
        }
    </style>

</head>
<body>
    <header>
        <h1>Furtus</h1>
    </header>

    <div class="container">
        <div class="left-sidebar">
            <h2>Меню</h2>
            <ul>
                <li><a href="profil.php" style="width: 45px;"><img src="sre/home.jpg" alt="" class="icon-back"></a></li>
                <li><a href="user.php"><img src="sre/user.png" alt="" class="icon-back"></a></li>

                <div class="main-content">
            <h2>Мои чаты</h2>
            <ul class="chat-list">
                <?php
                foreach ($chatIds as $chatId) {
                    $sql_other_user = "SELECT sender_id, recipient_id FROM messages WHERE chat_id = '$chatId' LIMIT 1";
                    $result_other_user = $conn->query($sql_other_user);

                    if ($result_other_user && $result_other_user->num_rows > 0) {
                        $row_other_user = $result_other_user->fetch_assoc();

                        $otherUserId = ($row_other_user['sender_id'] == $currentUserId) ? $row_other_user['recipient_id'] : $row_other_user['sender_id'];

                        $sql_username = "SELECT username FROM users WHERE id = '$otherUserId'";
                        $result_username = $conn->query($sql_username);

                        if ($result_username && $result_username->num_rows > 0) {
                            $row_username = $result_username->fetch_assoc();
                            $otherUsername = htmlspecialchars($row_username['username']);

                            echo "<li> <b><a  href='chat.php?recipientId=" . $otherUserId . "'>" . $otherUsername . "</a></li>";
                        }
                    }
                }
                ?>
            </ul>
        </div>
    </div>

</body>
</html>