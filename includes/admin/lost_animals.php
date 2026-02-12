<?php
session_start();

include("auth.php");
include('../page/dbconnect.php');
include('sidebar.php');

$location = $_GET['location']  ?? '';
$status   = $_GET['status']    ?? '';
$fromDate = $_GET['from_date'] ?? '';
$toDate   = $_GET['to_date']   ?? '';

$locations = [];
$locResult = $conn->query(
    "SELECT DISTINCT lost_location 
     FROM lost_animals 
     WHERE lost_location IS NOT NULL 
     ORDER BY lost_location ASC"
);

while ($row = $locResult->fetch_assoc()) {
    $locations[] = $row['lost_location'];
}

$sql = "SELECT * FROM lost_animals WHERE 1=1";
$params = [];
$types  = "";

if ($location !== '') {
    $sql .= " AND lost_location LIKE ?";
    $params[] = "%{$location}%";
    $types   .= "s";
}

if ($status !== '') {
    $sql .= " AND status = ?";
    $params[] = $status;
    $types   .= "s";
}

if ($fromDate !== '') {
    $sql .= " AND lost_date >= ?";
    $params[] = $fromDate;
    $types   .= "s";
}

if ($toDate !== '') {
    $sql .= " AND lost_date <= ?";
    $params[] = $toDate;
    $types   .= "s";
}

$sql .= " ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
<title>Lost Animals</title>
<link rel="stylesheet" href="lost_animals.css">

</head>

<body>
<div class="main">

<h2 class="page-title">Lost Animals</h2>

<form class="filter-box" method="GET">

    <select name="location">
        <option value="">All Locations</option>
        <?php foreach($locations as $loc): ?>
            <option value="<?= htmlspecialchars($loc) ?>" <?= $location==$loc?'selected':'' ?>>
                <?= htmlspecialchars($loc) ?>
            </option>
        <?php endforeach; ?>
    </select>

    <select name="status">
        <option value="">All Status</option>
        <option value="found" <?= $status=='found'?'selected':'' ?>>Found</option>
        <option value="notfound" <?= $status=='notfound'?'selected':'' ?>>Not Found</option>
    </select>

    <input type="date" name="from_date" value="<?= htmlspecialchars($fromDate) ?>">
    <input type="date" name="to_date" value="<?= htmlspecialchars($toDate) ?>">

    <button type="submit"> Filter</button>
</form>

<?php if($result->num_rows>0): ?>
<div class="card-grid">
    <?php while($row = $result->fetch_assoc()): ?>
        <div class="lost-card">

            <img src="../uploads/lost/<?= htmlspecialchars($row['image'] ?: 'no-image.png') ?>" 
                 alt="<?= htmlspecialchars($row['animal_type']); ?>">

            <h3><?= htmlspecialchars($row['animal_type']); ?></h3>

            <div class="lost-details">
                <p><span>Breed:</span> <?= htmlspecialchars($row['breed']); ?></p>
                <p><span>Color:</span> <?= htmlspecialchars($row['color']); ?></p>
                <p><span>Location:</span> <?= htmlspecialchars($row['lost_location']); ?></p>
                <p><span>Date:</span> <?= htmlspecialchars($row['lost_date']); ?></p>
                <p><span>Owner:</span> <?= htmlspecialchars($row['owner_name']); ?></p>
                <p><span>Contact:</span> <?= htmlspecialchars($row['contact_number']); ?></p>
            </div>

            <span class="status <?= htmlspecialchars($row['status']); ?>">
                <?= strtoupper(str_replace('_',' ',$row['status'])); ?>
            </span>

        </div>
    <?php endwhile; ?>
</div>
<?php else: ?>
<p class="no-data">No records found</p>
<?php endif; ?>

</div>
</body>
</html>
