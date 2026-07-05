<?php
include "../config/database.php";

$id = $_GET['id'] ?? '';
$action = $_GET['action'] ?? '';

if (!$id || !in_array($action, ['SELESAI','BATAL'])) {
    die("INVALID");
}

/* Ambil order */
$o = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM orders WHERE id='$id'
"));

if (!$o) die("ORDER NOT FOUND");

/* Update DB */
mysqli_query($conn,"
UPDATE orders SET status='$action'
WHERE id='$id'
");

/* Edit Telegram */
if ($o['telegram_chat_id'] && $o['telegram_msg_id']) {

    $BOT_TOKEN = "7406726074:AAEzvMu-ihMwGJF-FMFOTMGCLKsM_au8Atk";

    $caption = preg_replace(
        "/Status:\s*\*[A-Z]+\*/",
        "Status: *$action*",
        $o['telegram_caption'] ?? ''
    );

    $url = "https://api.telegram.org/bot$BOT_TOKEN/editMessageCaption";

    $post = [
        'chat_id' => $o['telegram_chat_id'],
        'message_id' => $o['telegram_msg_id'],
        'caption' => $caption,
        'parse_mode' => 'Markdown'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}

header("Location: dashboard.php");
