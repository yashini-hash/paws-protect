<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$rescue_center_id = $_SESSION['rescue_center_id'];

$query = "SELECT district FROM rescue_center WHERE rescue_center_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$center_district = $res['district'];

$sql = "SELECT * FROM lost_animals 
        WHERE lost_location LIKE CONCAT('%', ?, '%')
        ORDER BY lost_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $center_district);
$stmt->execute();
$lost_animals = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Lost Animals Nearby</title>

<link rel="stylesheet" href="lost.css">

</head>
<body>

<div class="main-container">
    <h1 style="color:#4b2e1e;">Lost Animals in <?= $center_district ?></h1>

    <?php if ($lost_animals->num_rows == 0): ?>
        <p class="empty">No lost animals reported in your district.</p>
    <?php else: ?>

        <?php while ($row = $lost_animals->fetch_assoc()): ?>
       <a href="lost_animal_view.php?lost_id=<?= $row['lost_id'] ?>" class="card-link">
<div class="card">

            <img src="../uploads/lost/<?= $row['image'] ?>" alt="Lost Animal">

            <h3><?= htmlspecialchars($row['animal_type']) ?></h3>

            <div class="info"><strong>Color:</strong> <?= htmlspecialchars($row['color']) ?></div>
            <div class="info"><strong>Location:</strong> <?= htmlspecialchars($row['lost_location']) ?></div>
            <div class="info"><strong>Lost Date:</strong> <?= htmlspecialchars($row['lost_date']) ?></div>
            <div class="info"><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></div>
       </div>
</a>
        <?php endwhile; ?>

    <?php endif; ?>
</div>

</body>
</html>
