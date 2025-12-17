<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

// Security check
if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$rescue_center_id = $_SESSION['rescue_center_id'];

// Fetch rescue center district
$query = "SELECT district FROM rescue_center WHERE rescue_center_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();
$center_district = $res['district'];

// Fetch matching lost animals
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

<style>
.main-container {
    margin-left: 260px;
    padding: 40px;
   
}

.main-container h1{
     text-align: center;
    margin: 6px 0;
    color: #3e2c1c;
   
}
.card {
     background: #ddbc8b;
    border-radius: 16px;
    padding: 20px;
    width: 300px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin: 15px;
    display: inline-block;
    vertical-align: top;
}
.card:hover{
     background: #9e6c40ff;
    
}

.card img {
    width: 100%;
    height: 230px;
    object-fit: cover;
    border-radius: 12px;
}

.card h3 {
    text-align: center;
    margin: 6px 0;
    color: #3e2c1c;
    font-size: 30px;
}

.info {
    font-size: 15px;
    color: #333;
    margin-top: 8px;
}




.empty {
    text-align:center;
    color:#777;
    font-size:20px;
    margin-top:30px;
}
.card-link {
    text-decoration: none;
    color: inherit;
}
</style>

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
