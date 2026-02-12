<?php
include("dbconnect.php");

if (!isset($_GET['id'])) {
    die("Animal not found");
}

$animal_id = intval($_GET['id']);

$query = "
    SELECT a.*, r.district
    FROM animals_details a
    JOIN rescue_center r 
        ON a.rescue_center_id = r.rescue_center_id
    WHERE a.animal_id = $animal_id
";

$result = mysqli_query($conn, $query);
$animal = mysqli_fetch_assoc($result);

if (!$animal) {
    die("Animal not found");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paws & Protect</title>

<link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
<link rel="stylesheet" href="viewdetails.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>

<body>
    <header>
    <div class="logo">
        <img src="/paws&protect/includes/image/paw.png" alt="Logo">
    </div>
    <nav class="nav-links">
        <li><a href="/paws&protect/home.php">HOME</a></li>
        <li><a href="/paws&protect/includes/page/about.php">ABOUT</a></li>
        <li><a href="adopt.php">ADOPT</a></li>
        <li><a href="rescue.php" class="active">RESCUE</a></li>
        <li><a href="donate.php">DONATE</a></li>
        <li><a href="lost.php">LOST</a></li>
    </nav>
    <div class="menu-toggle" id="menu-toggle">
        <i class="fa fa-bars"></i>
    </div>
</header>

<div class="mobile-nav" id="mobile-nav">
    <a href="/paws&protect/home.php">HOME</a>
    <a href="/paws&protect/includes/page/about.php">ABOUT</a>
    <a href="adopt.php">ADOPT</a>
    <a href="rescue.php">RESCUE</a>
    <a href="donate.php">DONATE</a>
    <a href="lost.php">LOST</a>
</div>

<script>
document.getElementById("menu-toggle").onclick = function() {
    document.getElementById("mobile-nav").classList.toggle("active");
};
</script>

<div class="details-container">

    <!-- LEFT IMAGE -->
    <div class="details-image">
        <img src="../uploads/addanimal/<?= htmlspecialchars($animal['animal_image']) ?>">
    </div>

    <!-- RIGHT CONTENT -->
    <div class="details-content">
        <h1><?= htmlspecialchars($animal['name']) ?></h1>

        <p>
    <strong>This lovely <?= htmlspecialchars($animal['type']) ?> is looking for a caring home.</strong>
</p>

<p>
    <?= nl2br(htmlspecialchars($animal['details'])) ?>
</p>
<a href="login.php" class="adopt-btn">
            Adopt Me
        </a>

        <!-- ICON INFO ROW -->
        <div class="info-row">
            <div class="info-box">
                <i class="fa-solid fa-paw"></i>
                <span><?= htmlspecialchars($animal['type']) ?></span>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-venus-mars"></i>
                <span><?= htmlspecialchars($animal['gender']) ?></span>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-clock"></i>
                <span><?= htmlspecialchars($animal['age']) ?></span>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-syringe"></i>
                <span><?= htmlspecialchars($animal['vaccination'] ?? 'N/A') ?></span>
            </div>

            <div class="info-box">
                <i class="fa-solid fa-location-dot"></i>
                <span><?= htmlspecialchars($animal['district']) ?></span>
            </div>
        </div>

        
    </div>

</div>

 <footer>
  <img src="/paws&protect/includes/image/paw.png" alt="paw Logo">
  <div class="right">
    <a href="#"><i class="fab fa-facebook"></i></a>
    <a href="#"><i class="fab fa-linkedin"></i></a>
    <a href="#"><i class="fab fa-youtube"></i></a>
    <a href="#"><i class="fab fa-instagram"></i></a>
  </div>
  <p>&copy; 2025 Paws & Protect | Together for Animals 🐾</p>
</footer>


</body>
</html>

