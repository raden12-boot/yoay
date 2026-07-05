<?php
session_start();
include "../config/database.php";

$username = $_POST['username'];
$password = $_POST['password'];

$q = mysqli_query($conn, "SELECT * FROM users WHERE username='$username' AND role='admin'");
$admin = mysqli_fetch_assoc($q);

if ($admin && password_verify($password, $admin['password'])) {
    $_SESSION['admin'] = $admin['id'];
    header("Location: dashboard.php");
} else {
    echo "Login gagal";
}
