<?php
session_start();

include("auth.php");
include('../page/dbconnect.php');
include('sidebar.php');

$sql = "SELECT * FROM lost_animals WHERE status='found' ORDER BY created_at DESC";
$result = $conn->query($sql);
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Found Animals - Admin Dashboard</title>
<link rel="stylesheet" href="found_animals.css">

</head>

<body>

<div class="main">
    <h2 class="page-title">Found Animals</h2>

    <div class="card-grid">
    <?php if($result && $result->num_rows > 0): 
        while($row = $result->fetch_assoc()): ?>
        <div class="lost-card">

    <img src="../uploads/lost/<?= $row['image'] ?: 'no-image.png'; ?>" 
         alt="<?= htmlspecialchars($row['animal_type']); ?>">

    <h3><?= htmlspecialchars($row['animal_type']); ?></h3>

    <div class="lost-details">
        <p><span>Breed:</span> <?= htmlspecialchars($row['breed']); ?></p>
        <p><span>Color:</span> <?= htmlspecialchars($row['color']); ?></p>
        <p><span>Lost Location:</span> <?= htmlspecialchars($row['lost_location']); ?></p>
        <p><span>Lost Date:</span> <?= htmlspecialchars($row['lost_date']); ?></p>
        <p><span>Owner:</span> <?= htmlspecialchars($row['owner_name']); ?></p>
        <p><span>Contact:</span> <?= htmlspecialchars($row['contact_number']); ?></p>
        <p><span>Created:</span> <?= date('d M Y', strtotime($row['created_at'])); ?></p>
    </div>


    <span class="status <?= $row['status']; ?>">
        <?= ucwords(str_replace("_"," ",$row['status'])); ?>
    </span>

</div>

    <?php endwhile; else: ?>
        <p style="grid-column:1/-1;text-align:center;color:red;">No records found</p>
    <?php endif; ?>
    </div>
</div>

</body>
</html>
