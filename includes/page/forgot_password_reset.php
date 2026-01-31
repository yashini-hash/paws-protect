<?php
session_start();
include "dbconnect.php";

date_default_timezone_set("Asia/Colombo"); 

$error = "";
$success = "";


if (!isset($_GET['token'])) {
    die("Invalid request.");
}

$token = $_GET['token'];

$sql = "SELECT * FROM users WHERE reset_token = ? AND reset_expires > NOW()";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $token);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows != 1) {
    die("Invalid or expired token.");
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql2 = "UPDATE users SET password = ?, reset_token = NULL, reset_expires = NULL WHERE user_id = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("si", $hashed_password, $user['user_id']);
        $stmt2->execute();

        $success = "Password reset successful. <a href='login.php'>Login now</a>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password</title>
  <link rel="stylesheet" href="loginstyle.css">
</head>
<body>
  <div class="container">
    <div class="right-side">
      <div class="form-box">
        <h2>Reset Password</h2>

        <?php if (!empty($error)) echo "<p style='color:#ffb703;'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <?php if (empty($success)) : ?>
        <form method="POST">
          <div class="input-box">
            <input type="password" name="password" placeholder=" " required>
            <label>New Password</label>
          </div>
          <div class="input-box">
            <input type="password" name="confirm_password" placeholder=" " required>
            <label>Confirm Password</label>
          </div>
          <button type="submit" class="btn">Reset Password</button>
        </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
