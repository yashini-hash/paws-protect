<?php
include("dbconnect.php");

/* FETCH 4 AVAILABLE ANIMALS */
$query = "
    SELECT 
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
<link rel="stylesheet" href="com.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* CARD GRID */
/* CARD GRID */
.animal-grid {
    margin-top: 40px;
    max-width: 1300px; /* wide enough for 5 cards */
    margin-left: auto;
    margin-right: auto;
    padding: 0 20px 50px;
    display: grid;
    grid-template-columns: repeat(5, 1fr); /* 5 cards per row */
    gap: 20px;
}

/* CARD */
.animal-card {
    background: #5C3A21;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* IMAGE */
.animal-card img {
    width: 100%;
    height: 200px; /* fixed height for uniformity */
    object-fit: cover; /* covers the space, no stretching */
}

/* CONTENT */
.card-body {
    padding: 12px 15px;
}

.card-body h3 {
    margin: 0;
    font-size: 18px;
    color: #d2a382ff;
}

.meta {
    margin: 6px 0;
    font-size: 13px;
   color: white;
}

.tag {
    display: inline-block;
    background: #f1e7df;
    color: #5C3A21;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    margin-top: 5px;
}

.card-footer {
    padding: 10px 15px 15px;
}

.card-footer button {
    width: 100%;
    padding: 8px;
    border: none;
    border-radius: 12px;
    background: #5C3A21;
    color: white;
    font-size: 14px;
    cursor: pointer;
}

.card-footer button:hover {
    background: #9d6e4c;
}


/* CARD */
.animal-card {
    background: white;
      margin-top: 40px;
       width: 250px;
    padding: 15px;
      border-radius: 15px;
      
    overflow: hidden;
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.animal-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 18px 40px rgba(0,0,0,0.18);
}

/* IMAGE */
.animal-card img {
    width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 6px;
}

/* CONTENT */
.card-body {
    padding: 18px;
}

.card-body h3 {
    margin: 0;
    color: #5C3A21;
}

.meta {
    margin: 8px 0;
    font-size: 14px;
    color: #555;
}

.meta i {
    color: #9d6e4c;
    margin-right: 6px;
}

/* TAG */
.tag {
    display: inline-block;
    background: #f1e7df;
    color: #5C3A21;
    padding: 5px 12px;
    border-radius: 20px;
    font-size: 13px;
    margin-top: 8px;
}

/* BUTTON */
.card-footer {
    padding: 15px 18px 20px;
}

.card-footer button {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 14px;
    background: #5C3A21;
    color: white;
    font-size: 15px;
    cursor: pointer;
}

.card-footer button:hover {
    background: #9d6e4c;
}
</style>
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
                <button>View Details</button>
            </div>
        </div>
    <?php } ?>
<?php } else { ?>
    <p style="text-align:center;">No animals available for adoption.</p>
<?php } ?>

</section>

<footer>
    <img src="/paws&protect/includes/image/paw.png">
    <p>&copy; 2025 Paws & Protect | Together for Animals 🐾</p>
</footer>

</body>
</html>
