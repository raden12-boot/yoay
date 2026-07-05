<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "gudang_barokahstore";
$port = 3307;

$conn = mysqli_connect($host, $user, $pass, $db, $port);

if (!$conn) {
    die("Database gagal terkoneksi");
}
