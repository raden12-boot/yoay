<?php
include "config/database.php";

$BOT_TOKEN = "7406726074:AAEzvMu-ihMwGJF-FMFOTMGCLKsM_au8Atk";

/* Ambil data webhook */
$update = json_decode(file_get_contents("php://input"), true);

if (!isset($update['message'])) exit;

$message = $update['message'];
$text    = trim($message['text'] ?? '');
$chat_id = $message['chat']['id'];
$reply_to = $message['reply_to_message'] ?? null;

if (!$reply_to) exit;

/* Ambil message_id yg direply */
$telegram_msg_id = $reply_to['message_id'];

/* Cari order */
$q = mysqli_query($conn,"
SELECT * FROM orders
WHERE telegram_msg_id='$telegram_msg_id'
LIMIT 1
");

$o = mysqli_fetch_assoc($q);
if (!$o) exit;

/* Tentukan status */
if ($text == '1') {
    $status = 'SELESAI';
} elseif ($text == '2') {
    $status = 'BATAL';
} else {
    exit;
}

/* Update DB */
mysqli_query($conn,"
UPDATE orders SET status='$status'
WHERE id='{$o['id']}'
");

/* Edit pesan Telegram */
$caption = preg_replace(
    "/Status:\s*\*[A-Z]+\*/",
    "Status: *$status*",
    $reply_to['caption']
);

$url = "https://api.telegram.org/bot$BOT_TOKEN/editMessageCaption";

$post = [
    'chat_id' => $chat_id,
    'message_id' => $telegram_msg_id,
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
