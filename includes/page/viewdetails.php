<?php
include("dbconnect.php");

if (!isset($_GET['id'])) {
    die("Animal not found");
}

$animal_id = intval($_GET['id']);

$query = "
    SELECT 
        a.*,
        r.district
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
<html>
<head>
    <title><?= htmlspecialchars($animal['name']) ?> | Paws & Protect</title>
</head>
<body>

<h1><?= htmlspecialchars($animal['name']) ?></h1>

<img src="../uploads/addanimal/<?= htmlspecialchars($animal['animal_image']) ?>" width="350">

<p><strong>Type:</strong> <?= htmlspecialchars($animal['type']) ?></p>
<p><strong>Breed:</strong> <?= htmlspecialchars($animal['breed'] ?? 'N/A') ?></p>
<p><strong>Gender:</strong> <?= htmlspecialchars($animal['gender']) ?></p>
<p><strong>Age:</strong> <?= htmlspecialchars($animal['age']) ?></p>
<p><strong>Health:</strong> <?= htmlspecialchars($animal['health'] ?? 'N/A') ?></p>
<p><strong>Vaccination:</strong> <?= htmlspecialchars($animal['vaccination'] ?? 'N/A') ?></p>
<p><strong>Location:</strong> <?= htmlspecialchars($animal['location']) ?></p>
<p><strong>Rescue Date:</strong> <?= htmlspecialchars($animal['rescue_date']) ?></p>

</body>
</html>
