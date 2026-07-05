<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

include "../config/database.php";

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    // Check if voucher exists
    $check = mysqli_query($conn, "SELECT id, kode_voucher FROM vouchers WHERE id = $id");
    
    if (mysqli_num_rows($check) > 0) {
        // Delete voucher (voucher_usage will be deleted automatically due to CASCADE)
        mysqli_query($conn, "DELETE FROM vouchers WHERE id = $id");
        
        header("Location: vouchers.php?success=deleted");
        exit;
    } else {
        header("Location: vouchers.php?error=not_found");
        exit;
    }
} else {
    header("Location: vouchers.php");
    exit;
}
?>