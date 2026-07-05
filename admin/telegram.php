<?php
function sendTelegram($message){
    $token = "7406726074:AAEzvMu-ihMwGJF-FMFOTMGCLKsM_au8Atk";
    $chat_id = "ISI_CHAT_ID_KAMU";

    $url = "https://api.telegram.org/bot$token/sendMessage";

    file_get_contents($url."?chat_id=$chat_id&text=".urlencode($message));
}
