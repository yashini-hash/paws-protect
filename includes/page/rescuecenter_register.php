<?php
include("dbconnect.php");
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $center_name    = $_POST['center_name'] ?? '';
    $address        = $_POST['address'] ?? '';
    $district       = $_POST['district'] ?? '';
    $contact_number = $_POST['contact_number'] ?? '';
    $email          = $_POST['email'] ?? '';
    $password       = $_POST['password'] ?? '';

    if ($center_name && $address && $district && $contact_number && $email && $password) {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $conn->begin_transaction();

        try {
            $sql1 = "INSERT INTO rescue_center 
                    (center_name, address, district, contact_number, email, password, status)
                    VALUES (?, ?, ?, ?, ?, ?, 'inactive')";

            $stmt1 = $conn->prepare($sql1);
            $stmt1->bind_param("ssssss", 
                $center_name, 
                $address, 
                $district, 
                $contact_number, 
                $email, 
                $hashed_password
            );
            $stmt1->execute();

            $sql2 = "INSERT INTO users (name, email, phone, password, role) 
                     VALUES (?, ?, ?, ?, 'rescuecenter')";

            $stmt2 = $conn->prepare($sql2);
            $stmt2->bind_param("ssss", 
                $center_name, 
                $email, 
                $contact_number, 
                $hashed_password
            );
            $stmt2->execute();

            $conn->commit();

            $message = "✅ Registration submitted! Admin will approve your account via email.";

        } catch (Exception $e) {
            $conn->rollback();
            $message = "❌ Registration failed: " . $e->getMessage();
        }

    } else {
        $message = "⚠️ Please fill in all required fields.";
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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

      <h3>RescueCenter Registration</h3>

      <form action="" method="POST">
<?php if($message): ?>
  <p style="color: #ffb703; text-align: center; margin-bottom: 15px;"><?= $message ?></p>
<?php endif; ?>

        <div class="input-box">
  <input type="text" name="center_name" placeholder=" " required>
  <label>Name of RescueCenter</label>
</div>

<div class="input-box">
  <input type="email" name="email" placeholder=" " required>
  <label>Email</label>
</div>

<div class="input-box">
  <input type="tel" name="contact_number" placeholder=" " pattern="[0-9]{10}" required>
  <label>Contact Number</label>
</div>

<div class="input-box">
  <input type="text" name="district" placeholder=" " required>
  <label>District</label>
</div>

<div class="input-box">
  <input type="text" name="address" placeholder=" " required>
  <label>Address</label>
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
