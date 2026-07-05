<?php
include "../config/database.php";

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['message']['reply_to_message'])) exit;

$text = trim($data['message']['text']);
$reply = $data['message']['reply_to_message'];

$msg_id = $reply['message_id'];
$chat_id = $reply['chat']['id'];

$o = mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM orders 
WHERE telegram_msg_id='$msg_id' AND telegram_chat_id='$chat_id'
"));

if (!$o) exit;

if ($text == '1') {
    $status = 'SELESAI';
    $emoji = '✅';
} elseif ($text == '2') {
    $status = 'BATAL';
    $emoji = '❌';
} else {
    exit;
}

mysqli_query($conn,"
UPDATE orders SET status='$status' WHERE id='{$o['id']}'
");

$caption = "$emoji *STATUS DIPERBARUI*\n\n".
           "Resi: {$o['resi']}\n".
           "Status: *$status*";

file_get_contents(
"https://api.telegram.org/botTOKEN/editMessageCaption?".
"http_build_query([
 'chat_id'=>$chat_id,
 'message_id'=>$msg_id,
 'caption'=>$caption,
 'parse_mode'=>'Markdown'
])");
