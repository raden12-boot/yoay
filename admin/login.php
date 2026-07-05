<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Login</title>
</head>
<body>
<h2>Login Admin</h2>
<form method="POST" action="proses_login.php">
  <input type="text" name="username" placeholder="Username" required><br><br>
  <input type="password" name="password" placeholder="Password" required><br><br>
  <button type="submit">LOGIN</button>
</form>
</body>
</html>
