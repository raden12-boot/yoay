<?php
session_start();
if (!isset($_SESSION['admin'])) {
    die("AKSES DITOLAK");
}

include "../config/database.php";

$id     = $_GET['id'] ?? '';
$status = $_GET['status'] ?? '';

if (!$id || !in_array($status, ['SELESAI','BATAL'])) {
    die("PARAMETER TIDAK VALID");
}

mysqli_query($conn,"
UPDATE orders 
SET status='$status' 
WHERE id='$id'
");

header("Location: dashboard.php");
exit;
