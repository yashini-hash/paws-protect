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
<link rel="stylesheet" href="com.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #f7f1ec;

}

/* MAIN CONTAINER */
.details-container {
    max-width: 1100px;
    margin: 40px auto;
    background:  #ddbc8b;
    border-radius: 20px;
    padding: 30px;
    display: grid;
    grid-template-columns: 1fr 1.2fr;
    gap: 30px;
}

/* IMAGE */
.details-image img {
    width: 100%;
    height: 490px;
    object-fit: cover;
    border-radius: 20px;
}

/* TEXT SIDE */
.details-content h1 {
    margin-top: 0;
    color: #5C3A21;
    font-size:60px;
}

.details-content p {
    line-height: 1.6;
    color: #444;
}

/* INFO ICON ROW */
.info-row {
    display: flex;
    justify-content: space-between;
    margin: 25px 0;
}

.info-box {
    text-align: center;
    flex: 1;
}

.info-box i {
    font-size: 20px;
    color: #5C3A21;
}

.info-box span {
    display: block;
    margin-top: 6px;
    font-size: 14px;
    color: #555;
}

/* ADOPT BUTTON */
.adopt-btn {
    display: inline-block;
    padding: 12px 30px;
    background: #5C3A21;
    color: white;
    border-radius: 14px;
    text-decoration: none;
    font-weight: bold;
    margin-top:20px;
}

.adopt-btn:hover {
    background: #9d6e4c;
}

/* FOOTER */

</style>
</head>

<body>

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
<a href="adopt_request.php?id=<?= $animal['animal_id'] ?>" class="adopt-btn">
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

