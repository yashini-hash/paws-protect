<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    die("❌ Rescue center not logged in.");
}

$rescue_center_id = (int) $_SESSION['rescue_center_id'];

if (!isset($_GET['id'])) {
    die("Request ID missing.");
}
$request_id = (int) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM rescue_requests WHERE request_id = ? AND rescue_center_id = ?");
$stmt->bind_param("ii", $request_id, $rescue_center_id);
$stmt->execute();
$request = $stmt->get_result()->fetch_assoc();

if (!$request) {
    die("Request not found or not assigned to your center.");
}

$staffStmt = $conn->prepare("SELECT staff_id, name FROM staff WHERE rescue_center_id = ?");
$staffStmt->bind_param("i", $rescue_center_id);
$staffStmt->execute();
$staffResult = $staffStmt->get_result();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = $_POST['status'];
    $assigned_to = $_POST['assigned_to'] ?: NULL;
    $rescue_location = $_POST['rescue_location'];
    $notes = $_POST['notes'];

    $update = $conn->prepare("
        UPDATE rescue_requests 
        SET status = ?, assigned_to = ?, rescue_location = ?, notes = ? 
        WHERE request_id = ? AND rescue_center_id = ?
    ");
    $update->bind_param("sissii", $status, $assigned_to, $rescue_location, $notes, $request_id, $rescue_center_id);
    $update->execute();

    header("Location: rescue.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Rescue Request</title>
<link rel="stylesheet" href="update_status.css">
</head>
<body>

<div class="container">
    <h2>Update Rescue Request</h2>

    <form method="POST">
        <label>Status</label>
        <select name="status" required>
            <option value="Pending" <?= $request['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
            <option value="In Progress" <?= $request['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
            <option value="Completed" <?= $request['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
        </select>

        <label>Assign Staff</label>
        <select name="assigned_to">
            <option value="">--Select Staff--</option>
            <?php while ($staff = $staffResult->fetch_assoc()): ?>
                <option value="<?= $staff['staff_id'] ?>" <?= $staff['staff_id'] == $request['assigned_to'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($staff['name']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Rescue Location</label>
        <input type="text" name="rescue_location" value="<?= htmlspecialchars($request['rescue_location']) ?>">

        <label>Notes</label>
        <textarea name="notes"><?= htmlspecialchars($request['notes']) ?></textarea>

        <button type="submit">Update</button>
        <a href="rescue.php" class="back-btn">← Back</a>
    </form>
</div>

</body>
</html>
