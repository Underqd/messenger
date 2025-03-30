<?php
session_start();
require_once('bazacamaconn.php');

$currentUserId = $_SESSION['user_id'];

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

<link rel="stylesheet" href="style.css">

<div class="chat-container">
    <h2>Чат</h2>

   
    </ul>

    <?php

    $recipientId = $_GET['recipientId'] ?? null;

    if ($recipientId) {
        $chatId = generateChatId($currentUserId, $recipientId);

        $sql_messages = "SELECT * FROM messages WHERE chat_id = '$chatId' ORDER BY timestamp ASC";
        $result_messages = $conn->query($sql_messages);
        ?>

        <?php
        $sql_user = "SELECT username FROM users WHERE id = '$recipientId'";
        $result_user = $conn->query($sql_user);
        if ($result_user && $result_user->num_rows > 0) {
            $row_user = $result_user->fetch_assoc();
            echo "<h3>Общение с: " . htmlspecialchars($row_user['username']) . "</h3>";
        }
        ?>

        <div class="messages">
            <?php
            if ($result_messages && $result_messages->num_rows > 0) {
                while ($row = $result_messages->fetch_assoc()) {
                    $messageClass = ($row["sender_id"] == $currentUserId) ? "sent" : "received";
                    echo "<div class='message " . $messageClass . "'>";
                    echo htmlspecialchars($row["message"]);
                    echo "</div>";
                }
            } else {
                echo "<p>Нет сообщений в этом чате.</p>";
            }
            ?>
        </div>

        <form method="post" action="chat.php">
            <input type="hidden" name="recipientId" value="<?php echo $recipientId; ?>">
            <textarea name="message" placeholder="Введите сообщение"></textarea>
            <button type="submit">Отправить</button>
        </form>

    <?php } else { ?>
        <p>Выберите пользователя для начала чата.</p>
    <?php } ?>
</div>