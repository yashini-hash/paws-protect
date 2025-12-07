<?php
include("dbconnect.php");

$success = "";
$error = "";

// Only run this when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = $_POST['name'];
    $email    = $_POST['email'];
    $phone    = $_POST['contact'];
    $password = $_POST['password'];

    $role = "user";
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Check if email already exists
    $check = "SELECT email FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $check);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered!";
    } else {

        // Insert data
        $sql = "INSERT INTO users (name, email, phone, password, role)
                VALUES ('$name', '$email', '$phone', '$hashed_password', '$role')";

        if (mysqli_query($conn, $sql)) {
            $success = "Registration successful!";
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Paws & Protect - User Registration</title>
  <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
  <link rel="stylesheet" href="user_registerstyle.css">
</head>
<body>

  <div class="container">
    <div class="form-box">
      <div class="logo-container">
        <img src="/paws&protect/includes/image/paw.png" alt="Logo">
      </div>

      <h2>User Registration</h2>

      <!-- SUCCESS MESSAGE -->
      <?php if($success != "") { ?>
        <p style="color: green; text-align:center;"><?php echo $success; ?></p>
      <?php } ?>

      <!-- ERROR MESSAGE -->
      <?php if($error != "") { ?>
        <p style="color: red; text-align:center;"><?php echo $error; ?></p>
      <?php } ?>

      <form action="" method="POST">

        <div class="input-box">
          <input type="text" name="name" placeholder=" " required>
          <label>Full Name</label>
        </div>

        <div class="input-box">
          <input type="email" name="email" placeholder=" " required>
          <label>Email</label>
        </div>

        <div class="input-box">
          <input type="tel" name="contact" placeholder=" " pattern="[0-9]{10}" required>
          <label>Contact Number</label>
        </div>

        <div class="input-box">
          <input type="password" name="password" placeholder=" " required>
          <label>Password</label>
        </div>

        <button type="submit" class="btn">Register</button>

        <p class="signup-text">
          Already have an account? <a href="login.php">Login</a>
        </p>
      </form>
    </div>
  </div>

</body>
</html>
