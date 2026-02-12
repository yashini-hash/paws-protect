<?php
session_start();
include "dbconnect.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../../PHPMailer-master/src/Exception.php';
require '../../PHPMailer-master/src/PHPMailer.php';
require '../../PHPMailer-master/src/SMTP.php';

date_default_timezone_set("Asia/Colombo"); 

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $email = $_POST['email'];

  
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

       
        $token = bin2hex(random_bytes(50));
        $expires = date("Y-m-d H:i:s", strtotime("+1 hour")); 
       
        $sql2 = "UPDATE users SET reset_token = ?, reset_expires = ? WHERE email = ?";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("sss", $token, $expires, $email);
        $stmt2->execute();

       
        $reset_link = "http://localhost/paws&protect/includes/page/forgot_password_reset.php?token=$token";

       
        $mail = new PHPMailer(true);

        try {
           
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'animalcarelove01@gmail.com'; 
            $mail->Password   = 'ncufnnhhoezkbkcp';           
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

           
            $mail->setFrom('animalcarelove01@gmail.com', 'Paws & Protect');
            $mail->addAddress($email, $user['name']); 

           
            $mail->isHTML(false);
            $mail->Subject = 'Password Reset Request';
            $mail->Body    = "Hi, click the link below to reset your password:\n\n$reset_link\n\nThis link expires in 1 hour.";

            $mail->send();
            $success = "Password reset link has been sent to your email.";

        } catch (Exception $e) {
            $error = "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

    } else {
        $error = "Email not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paws & Protect - Forgot Password</title>
  <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
  <link rel="stylesheet" href="loginstyle.css">
</head>
<body>
  <div class="container">
    <div class="right-side">
      <div class="form-box">
        <h2>Forgot Password</h2>

        <?php if (!empty($error)) echo "<p style='color:#ffb703;'>$error</p>"; ?>
        <?php if (!empty($success)) echo "<p style='color:green;'>$success</p>"; ?>

        <form method="POST">
          <div class="input-box">
            <input type="email" name="email" placeholder=" " required>
            <label>Email</label>
          </div>
          <button type="submit" class="btn">Send Reset Link</button>
          <p class="signup-text"><a href="login.php">Back to Login</a></p>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
