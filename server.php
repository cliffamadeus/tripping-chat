<?php
date_default_timezone_set("Asia/Manila");

$file = "messages.json";
$usersFile = "users.json";
$logFile = "logs.txt";

// Initialize files
if (!file_exists($file)) file_put_contents($file, json_encode([]));
if (!file_exists($usersFile)) file_put_contents($usersFile, json_encode([]));
if (!file_exists($logFile)) file_put_contents($logFile, "");

// Load data
$messages = json_decode(file_get_contents($file), true);
$users = json_decode(file_get_contents($usersFile), true);

// Current datetime
$datetime = date("Y-m-d H:i:s");

// Clear chat
if (isset($_GET['clear'])) {
    file_put_contents($file, json_encode([]));
    file_put_contents($usersFile, json_encode([]));

    file_put_contents($logFile, "[$datetime] Chat cleared\n", FILE_APPEND);

    echo "cleared";
    exit;
}

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = htmlspecialchars($_POST['user'] ?? 'Anonymous');
    $message = htmlspecialchars($_POST['message'] ?? '');

    // New user detection
    if (!in_array($user, $users)) {
        $users[] = $user;

        $messages[] = [
            "text" => "$user joined the chat",
            "type" => "system",
            "datetime" => $datetime
        ];

        file_put_contents($usersFile, json_encode($users));
        file_put_contents($logFile, "[$datetime] $user joined\n", FILE_APPEND);
    }

    // Add message
    if (!empty($message)) {
        $messages[] = [
            "text" => "$user: $message",
            "type" => "normal",
            "datetime" => $datetime
        ];

        file_put_contents($logFile, "[$datetime] $user: $message\n", FILE_APPEND);
    }

    file_put_contents($file, json_encode($messages));

    echo "sent";
    exit;
}

// Return messages
echo json_encode([
    "messages" => $messages
]);

?>