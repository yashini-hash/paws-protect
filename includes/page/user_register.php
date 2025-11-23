<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paws & Protect - User Registration</title>
  <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paws.png" />
  <link rel="stylesheet" href="user_register.css">
</head>
<body>

  <div class="container">
    <div class="form-box">
      <div class="logo-container">
        <img src="/paws&protect/includes/image/rd.png" alt="Logo">
      </div>

      <h2>User Registration</h2>

      <form action="user_register_process.php" method="POST">

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
