<?php
session_start();
include("auth.php");
include('../page/dbconnect.php');
include('sidebar.php');

$rescueCenter = $_GET['rescue_center'] ?? '';
$status       = $_GET['status'] ?? '';
$fromDate     = $_GET['from_date'] ?? '';
$toDate       = $_GET['to_date'] ?? '';

$sql = "SELECT ar.*, 
               u.name AS user_name, 
               rc.center_name, 
               ad.name AS animal_name, 
               ad.animal_image
        FROM adopt_requests ar
        JOIN users u ON ar.user_id = u.user_id
        JOIN rescue_center rc ON ar.rescue_center_id = rc.rescue_center_id
        JOIN animals_details ad ON ar.animal_id = ad.animal_id
        WHERE 1=1";

$params = [];
$types  = "";

if ($rescueCenter) {
    $sql .= " AND ar.rescue_center_id=?";
    $params[] = $rescueCenter;
    $types .= "i";
}
if ($status) {
    $sql .= " AND ar.status=?";
    $params[] = $status;
    $types .= "s";
}
if ($fromDate) {
    $sql .= " AND ar.request_date >= ?";
    $params[] = $fromDate;
    $types .= "s";
}
if ($toDate) {
    $sql .= " AND ar.request_date <= ?";
    $params[] = $toDate;
    $types .= "s";
}

$sql .= " ORDER BY ar.request_date DESC";

$stmt = $conn->prepare($sql);
if ($params) $stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$centers = $conn->query("SELECT * FROM rescue_center ORDER BY center_name");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Adoption Requests</title>
<link rel="stylesheet" href="view_all_adopt.css">

</head>

<body>

<div class="content-wrapper">

<h2 class="page-title">Adoption Requests</h2>

<form class="filter-box" method="GET">
    <select name="rescue_center">
        <option value="">All Centers</option>
        <?php while($c=$centers->fetch_assoc()): ?>
            <option value="<?= $c['rescue_center_id'] ?>" <?= $rescueCenter==$c['rescue_center_id']?'selected':'' ?>>
                <?= htmlspecialchars($c['center_name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <select name="status">
        <option value="">All Status</option>
        <option value="approved" <?= $status=='approved'?'selected':'' ?>>Approved</option>
        <option value="pending" <?= $status=='pending'?'selected':'' ?>>Pending</option>
        <option value="rejected" <?= $status=='rejected'?'selected':'' ?>>Rejected</option>
    </select>

    <input type="date" name="from_date" value="<?= $fromDate ?>">
    <input type="date" name="to_date" value="<?= $toDate ?>">
    <button type="submit">Filter</button>
</form>

<?php if($result->num_rows>0): ?>
<div class="card-grid">
<?php while($row=$result->fetch_assoc()): ?>
    <div class="animal-card">
        <img src="/paws&protect/includes/uploads/addanimal/<?= htmlspecialchars($row['animal_image']) ?>">
        <h3><?= htmlspecialchars($row['animal_name']) ?></h3>

        <div class="adopt-details">
            <p><b>User:</b> <?= htmlspecialchars($row['user_name']) ?></p>
            <p><b>Center:</b> <?= htmlspecialchars($row['center_name']) ?></p>
            <p><b>Date:</b> <?= date("d M Y", strtotime($row['request_date'])) ?></p>
        </div>

        <span class="status <?= $row['status'] ?>">
            <?= strtoupper($row['status']) ?>
        </span>
    </div>
<?php endwhile; ?>
</div>
<?php else: ?>
<p style="text-align:center;font-weight:bold;">No adoption requests found</p>
<?php endif; ?>

</div>
</body>
</html>
