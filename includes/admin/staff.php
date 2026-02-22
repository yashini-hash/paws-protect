<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

$selected_center = $_GET['rescue_center_id'] ?? "all";

if ($selected_center == "all") {
    $query = "
        SELECT s.*, r.center_name
        FROM staff s
        JOIN rescue_center r ON s.rescue_center_id = r.rescue_center_id
        ORDER BY s.staff_id DESC
    ";
} else {
    $selected_center = mysqli_real_escape_string($conn, $selected_center);
    $query = "
        SELECT s.*, r.center_name
        FROM staff s
        JOIN rescue_center r ON s.rescue_center_id = r.rescue_center_id
        WHERE s.rescue_center_id = '$selected_center'
        ORDER BY s.staff_id DESC
    ";
}

$staffs = mysqli_query($conn, $query);
$centers = mysqli_query($conn, "SELECT rescue_center_id, center_name FROM rescue_center");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Staff Management</title>
<link rel="stylesheet" href="sidebar.css">
<link rel="stylesheet" href="staff.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
<div class="main-content">

<h2><i class="fa-solid fa-users"></i> Staff Management</h2>

<!-- Filter Section -->
<div class="filter-box">
<form method="GET">
<select name="rescue_center_id" onchange="this.form.submit()">
    <option value="all">All Rescue Centers</option>
    <?php while($c = mysqli_fetch_assoc($centers)) { ?>
        <option value="<?= $c['rescue_center_id'] ?>"
            <?= ($selected_center == $c['rescue_center_id']) ? "selected" : "" ?>>
            <?= htmlspecialchars($c['center_name']) ?>
        </option>
    <?php } ?>
</select>
</form>
</div>

<?php if(mysqli_num_rows($staffs) == 0){ ?>
    <p style="text-align:center;">No staff found.</p>
<?php } else { ?>

<table>
<thead>
<tr>
    <th></th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Rescue Center</th>
    <th>Status</th>
</tr>
</thead>

<tbody>
<?php 
$i = 1; 
while($s = mysqli_fetch_assoc($staffs)) { 
?>
<tr>
    <td><?= $i++ ?></td>
    <td><?= htmlspecialchars($s['name']) ?></td>
    <td><?= htmlspecialchars($s['email'] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($s['phone'] ?? 'N/A') ?></td>
    <td><?= htmlspecialchars($s['center_name']) ?></td>
    <td>
        <?php if($s['status'] == "active"){ ?>
            <span class="status-active">Active</span>
        <?php } else { ?>
            <span class="status-inactive">Inactive</span>
        <?php } ?>
    </td>
</tr>
<?php } ?>
</tbody>
</table>

<?php } ?>

</div>
</body>
</html>
