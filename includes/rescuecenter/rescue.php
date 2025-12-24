<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

// Check if rescue center is logged in
if (!isset($_SESSION['rescue_center_id'])) {
    die("❌ Rescue center not logged in. Session missing.");
}

$rescue_center_id = (int) $_SESSION['rescue_center_id'];

// Fetch all rescue requests assigned to this center
$query = "SELECT * FROM rescue_requests WHERE rescue_center_id = ? ORDER BY request_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch count of pending requests for alert
$alertStmt = $conn->prepare("SELECT COUNT(*) AS total FROM rescue_requests WHERE rescue_center_id = ? AND status = 'Pending'");
$alertStmt->bind_param("i", $rescue_center_id);
$alertStmt->execute();
$alert = $alertStmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rescue Center | Requests</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #FFF8E7;
    padding: 40px;
    margin-left: 120px;
}

.container {
    max-width: 900px;
    background: white;
    margin: auto;
    padding: 30px;
    border-radius: 14px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

.alert {
    background: #ff4d4d;
    color: white;
    padding: 10px 16px;
    border-radius: 20px;
    display: inline-block;
    margin-bottom: 15px;
    font-weight: bold;
}

table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

th, td {
    padding: 12px;
    border: 1px solid #ddd;
    text-align: left;
}

th {
    background: #5C3A21;
    color: white;
}

.status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: bold;
    display: inline-block;
}

.status.pending {
    background: #ffeeba;
    color: #856404;
}

.status.in-progress {
    background: #cce5ff;
    color: #004085;
}

.status.completed {
    background: #d4edda;
    color: #155724;
}

.btn {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 6px;
    text-decoration: none;
    color: white;
    font-size: 13px;
    margin-bottom: 5px;
}

.btn.progress { background: #ff9800; }
.btn.complete { background: #28a745; }
.btn:hover { opacity: 0.85; }
</style>
</head>
<body>

<div class="container">
    <h2>🐾 Rescue Requests</h2>

    <?php if ($alert['total'] > 0): ?>
        <div class="alert">
            🔔 <?= $alert['total'] ?> New Rescue Request(s)
        </div>
    <?php endif; ?>

    <?php if ($result->num_rows === 0): ?>
        <p style="text-align:center; color:#777;">No rescue requests assigned yet.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Animal</th>
                <th>Description</th>
                <th>Contact</th>
                <th>Location</th>
                <th>Status</th>
                <th>Action</th>
            </tr>

            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['animal_type']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['contact_number']) ?></td>
                <td><?= htmlspecialchars($row['rescue_location']) ?></td>
                <td>
                    <span class="status <?= strtolower(str_replace(' ', '-', $row['status'])) ?>">
                        <?= htmlspecialchars($row['status']) ?>
                    </span>
                </td>
                <td>
                    <?php if ($row['status'] !== 'Completed'): ?>
                        <a class="btn progress" href="update_status.php?id=<?= $row['request_id'] ?>&status=In Progress">Start</a><br>
                        <a class="btn complete" href="update_status.php?id=<?= $row['request_id'] ?>&status=Completed">Complete</a>
                    <?php else: ?>
                        ✅ Done
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
