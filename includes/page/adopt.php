<?php
include("dbconnect.php");
$where = [];

if (!empty($_GET['type'])) {
    $type = mysqli_real_escape_string($conn, $_GET['type']);
    $where[] = "a.type = '$type'";
}

if (!empty($_GET['location'])) {
    $location = mysqli_real_escape_string($conn, $_GET['location']);
    $where[] = "r.district LIKE '%$location%'";
}

if (!empty($_GET['age'])) {
    $age = mysqli_real_escape_string($conn, $_GET['age']);
    $where[] = "a.age LIKE '%$age%'";
}

$whereSQL = "";
if (!empty($where)) {
    $whereSQL = " AND " . implode(" AND ", $where);
}

$query = "
    SELECT 
        a.animal_id,
        a.name,
        a.type,
        a.age,
        a.animal_image,
        r.district
    FROM animals_details a
    JOIN rescue_center r 
        ON a.rescue_center_id = r.rescue_center_id
    WHERE a.adoption_status = 'available'
      AND r.status = 'active'
      $whereSQL
";


$result = mysqli_query($conn, $query);
$animals = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Paws & Protect</title>

<link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
<link rel="stylesheet" href="adopt.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">





</head>

<body>

<header>
    <div class="logo">
        <img src="/paws&protect/includes/image/paw.png">
    </div>
    <nav class="nav-links">
        <li><a href="/paws&protect/home.php">HOME</a></li>
        <li><a href="/paws&protect/includes/page/about.php">ABOUT</a></li>
        <li><a href="adopt.php">ADOPT</a></li>
        <li><a href="rescue.php">RESCUE</a></li>
        <li><a href="donate.php">DONATE</a></li>
        <li><a href="lost.php">LOST</a></li>
    </nav>
</header>

<div class="hero">
    <img src="/paws&protect/includes/image/ad1.png">
    <div class="hero-text">Adopt Animals</div>
</div>

<form method="GET" class="filter-bar">
    <select name="type">
        <option value="">All Types</option>
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
        <option value="Bird">Bird</option>
        <option value="Rabbit">Rabbit</option>
        <option value="Hamsters">Hamsters</option>
    </select>

    <input type="text" name="location" placeholder="Location (District)"
           value="<?= $_GET['location'] ?? '' ?>">

    <input type="text" name="age" placeholder="Age"
           value="<?= $_GET['age'] ?? '' ?>">

    <button type="submit">Search</button>
</form>

<section class="animal-grid">

<?php if (!empty($animals)) { ?>
    <?php foreach ($animals as $row) { ?>
        <div class="animal-card">
         <img src="../uploads/addanimal/<?= htmlspecialchars($row['animal_image']) ?>"
 alt="<?= htmlspecialchars($row['name']) ?>">


            <div class="card-body">
                <h3><?= htmlspecialchars($row['name']) ?></h3>

                <div class="meta">
                    <i class="fa fa-map-marker-alt"></i>
                    <?= htmlspecialchars($row['district']) ?>
                </div>

                <div class="meta">
                    <i class="fa fa-clock"></i>
                    <?= htmlspecialchars($row['age']) ?>
                </div>

                <span class="tag"><?= htmlspecialchars($row['type']) ?></span>
            </div>

            <div class="card-footer">
    <a href="viewdetails.php?id=<?= $row['animal_id'] ?>" class="details-btn">
        View Details
    </a>
</div>



        </div>
    <?php } ?>
<?php } else { ?>
    <p style="text-align:center;">No animals available for adoption.</p>
<?php } ?>

</section>

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
