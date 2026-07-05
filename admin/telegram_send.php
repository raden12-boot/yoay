<?php

function sendTelegramOrder($order_id, $status)
{
    include "../config/database.php";

    $q = mysqli_query($conn, "
    SELECT o.*, g.nama_game, v.nama_variant
    FROM orders o
    JOIN games g ON o.game_id=g.id
    JOIN product_variants v ON o.variant_id=v.id
    WHERE o.id=$order_id
    ");

    $o = mysqli_fetch_assoc($q);

    $token = "BOT_TOKEN_LO";        // GANTI
    $chat_id = "CHAT_ID_LO";        // GANTI

    $caption =
"📦 *PESANAN $status*
🎮 Produk: {$o['nama_game']}
📦 Item: {$o['nama_variant']}
🆔 Data: {$o['data_1']} {$o['data_2']}
💰 Status: {$status}
🧾 Pengirim: {$o['nama_pengirim']}
🕒 {$o['created_at']}";

    $url = "https://api.telegram.org/bot$token/sendPhoto";

    $post = [
        'chat_id' => $chat_id,
        'caption' => $caption,
        'parse_mode' => 'Markdown',
        'photo' => new CURLFile("../uploads/".$o['bukti_tf'])
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
