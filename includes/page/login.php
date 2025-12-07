<?php
session_start();
include "dbconnect.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];
    $password = $_POST['password'];

   
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            
            if ($user['role'] == 'rescuecenter') {

                $sql2 = "SELECT status, rescue_center_id, center_name 
                         FROM rescue_center WHERE email = ?";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bind_param("s", $email);
                $stmt2->execute();
                $res2 = $stmt2->get_result();
                $center = $res2->fetch_assoc();

                if ($center['status'] == 'inactive') {
                    $error = " Your account is waiting for admin approval.";
                } 
                else if ($center['status'] == 'rejected') {
                    $error = " Your registration was rejected by admin.";
                } 
                else {
                   
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['role'] = 'rescuecenter';
                    $_SESSION['rescue_center_id'] = $center['rescue_center_id'];
                    $_SESSION['center_name'] = $center['center_name'];

                    header("Location: /paws&protect/includes/rescuecenter/dashboard.php");
                    exit();
                }

            } 
            else if ($user['role'] == 'admin') {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = 'admin';
                header("Location: admin_dashboard.php");
                exit();

            } 
            else {

                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['role'] = 'user';
                header("Location: user_dashboard.php");
                exit();
            }

        } else {
            $error = " Incorrect password or email.";
        }

    } else {
        $error = " Email not found.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paws & Protect - Login</title>
  <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
  <link rel="stylesheet" href="loginstyle.css">
</head>
<body>

  <div class="container">
    <div class="left-side">
      <img src="/paws&protect/includes/image/dog.png" alt="Dog Image">
    </div>

    <div class="right-side">
      <div class="form-box">
        <h2>Login</h2>

        <?php if(!empty($error)): ?>
            <p style="color:#ffb703; text-align:center;"><?= $error ?></p>
        <?php endif; ?>

        <form method="POST" action="">
          <div class="input-box">
            <input type="email" name="email" placeholder=" " required>
            <label>Email</label>
          </div>

          <div class="input-box">
            <input type="password" name="password" placeholder=" " required>
            <label>Password</label>
          </div>

          <div class="forget">
            <label for="remember">
              <input type="checkbox" id="remember" name="remember">
              <p>Remember me</p>
            </label>
            <a href="#">Forgot password?</a>
          </div>

          <button type="submit" class="btn">Login</button>
          <p class="signup-text">Don’t have an account? <a href="typeofregister.php">Register</a></p>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
