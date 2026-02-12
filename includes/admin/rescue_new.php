<?php
include('auth.php');
include('../page/dbconnect.php');
include('sidebar.php');

if (isset($_GET['action'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $status = ($_GET['action'] === 'approve') ? 'active' : 'rejected';

    $stmt = $conn->prepare("UPDATE rescue_center SET status=? WHERE rescue_center_id=?");
    $stmt->bind_param("si", $status, $id);
    $stmt->execute();
    $stmt->close();
}

$result = $conn->query(
    "SELECT * FROM rescue_center WHERE status='inactive' ORDER BY rescue_center_id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rescue Center Requests</title>
<link rel="stylesheet" href="rescue_new.css">

</head>

<body>

<div class="main">
<div class="content-area">

<h2>Rescue Center Requests</h2>

<?php if ($result->num_rows > 0): ?>
<table class="rescue-table">
<thead>
<tr>
    <th>Center Name</th>
    <th>Address</th>
    <th>District</th>
    <th>Contact</th>
    <th>Email</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <td data-label="Center Name"><?= htmlspecialchars($row['center_name']) ?></td>
    <td data-label="Address"><?= htmlspecialchars($row['address']) ?></td>
    <td data-label="District"><?= htmlspecialchars($row['district']) ?></td>
    <td data-label="Contact"><?= htmlspecialchars($row['contact_number']) ?></td>
    <td data-label="Email"><?= htmlspecialchars($row['email']) ?></td>
    <td data-label="Action">
        <a href="?action=approve&id=<?= $row['rescue_center_id'] ?>" class="approve-btn">Approve</a>
        <a href="?action=reject&id=<?= $row['rescue_center_id'] ?>" class="reject-btn">Reject</a>
    </td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
<?php else: ?>
<div class="no-requests">No pending rescue center requests</div>
<?php endif; ?>

</div>
</div>

</body>
</html>
