<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Paws & Protect - Register</title>
  <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
  <link rel="stylesheet" href="loginstyle.css"> 
</head>
<body>
<style>

    .register-buttons {
  display: flex;
  flex-direction: column;
  gap: 20px; 
  margin: 20px 0;
}

.register-buttons .btn {
  width: 80%;
  text-decoration: none;
  margin: 0 auto; 
  font-size: 1rem;
}
    </style>


  <div class="container">
    <div class="left-side">
      <img src="/paws&protect/includes/image/frd.png" alt="Register Image">
    </div>

    
    <div class="right-side">
      <div class="form-box">
        <h2>Register as</h2>
        <div class="register-buttons">
          <a href="user_register.php" class="btn">User (Adopter)</a>
          <a href="rescuecenter_register.php" class="btn">Rescue Center</a>
        </div>
        <p class="signup-text">Already have an account? <a href="login.php">Login</a></p>
      </div>
    </div>
  </div>

</body>
</html>
