<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role']) ||
    $_SESSION['role'] !== 'rescuecenter'
) {
    session_unset();
    session_destroy();
    header("Location: /paws&protect/includes/page/login.php");
    exit();
}

$rescue_center_id = $_SESSION['rescue_center_id'];

$type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : "";

if (!empty($type_filter) && $type_filter != "all") {
    $sql = "SELECT * FROM animals_details WHERE rescue_center_id = ? AND type = ? ORDER BY animal_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $rescue_center_id, $type_filter);
} else {
    $sql = "SELECT * FROM animals_details WHERE rescue_center_id = ? ORDER BY animal_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rescue_center_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Rescue Animals</title>
<link rel="stylesheet" href="viewall.css">
</head>
<body>

<h2 class="page-title">Your Rescue Animals</h2>

<form class="filter-box" method="GET">
    <select name="type_filter">
        <option value="all" <?= ($type_filter == 'all') ? 'selected' : '' ?>>All Types</option>
        <option value="Dog" <?= ($type_filter == 'Dog') ? 'selected' : '' ?>>Dog</option>
        <option value="Cat" <?= ($type_filter == 'Cat') ? 'selected' : '' ?>>Cat</option>
        <option value="Bird" <?= ($type_filter == 'Bird') ? 'selected' : '' ?>>Bird</option>
        <option value="Rabbit" <?= ($type_filter == 'Rabbit') ? 'selected' : '' ?>>Rabbit</option>
        <option value="Hamsters" <?= ($type_filter == 'Hamster') ? 'selected' : '' ?>>Hamsters</option>
    </select>

    <button type="submit">Search</button> 
</form>

<div class="card-grid">
<?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="animal-card">
            <img src="../uploads/addanimal/<?php echo !empty($row['animal_image']) ? $row['animal_image'] : 'no-image.png'; ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><b>Type:</b> <?php echo $row['type']; ?></p>
            <p><b>Gender:</b> <?php echo $row['gender']; ?></p>
            <p><b>Age:</b> <?php echo $row['age']; ?></p>
            <p><b>Health:</b> <?php echo $row['health']; ?></p>
            <p><b>Location:</b> <?php echo $row['location']; ?></p>
            <p class="status <?php echo $row['adoption_status']; ?>">
                <?php echo ucwords(str_replace("_", " ", $row['adoption_status'])); ?>
            </p>
        </div>
    <?php } ?>
<?php } else { ?>
    <p class="no-data">No animals found.</p>
<?php } ?>
</div>

</body>
</html>
