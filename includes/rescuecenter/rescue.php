<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    die("❌ Rescue center not logged in.");
}

$rescue_center_id = (int) $_SESSION['rescue_center_id'];

$query = "SELECT * FROM rescue_requests WHERE rescue_center_id = ? ORDER BY request_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$result = $stmt->get_result();

$alertStmt = $conn->prepare(
    "SELECT COUNT(*) AS total 
     FROM rescue_requests 
     WHERE rescue_center_id = ? AND status = 'Pending'"
);
$alertStmt->bind_param("i", $rescue_center_id);
$alertStmt->execute();
$alert = $alertStmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rescue Requests</title>

<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
     background: #FFF8E7;
    padding: 30px;
    margin-left: 120px;
}

/* MAIN CARD */
.card {
    max-width: 1100px;
    margin: auto;
    background: #ffffff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h2 {
    margin: 0;
    color: #2E2E2E;
}

/* ALERT */
.alert {
    background: #9f2525;
    color: #fff;
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
}

/* TABLE */
.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #5C3A21;
    color: #fff;
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

td {
    padding: 14px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #0a0a0a;
}

tr:hover {
    background: #FAFAFA;
}

/* STATUS BADGES */
.status {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status.pending {
    background: #FFF3CD;
    color: #856404;
}

.status.in-progress {
    background: #E3F2FD;
    color: #0D47A1;
}

.status.completed {
    background: #E6F4EA;
    color: #1B5E20;
}

/* BUTTONS */
.action-btns a {
    display: inline-block;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 12px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 6px;
}

.btn-progress {
    background: #FF9800;
    color: #fff;
}

.btn-complete {
    background: #28A745;
    color: #fff;
}

.action-btns a:hover {
    opacity: 0.85;
}

/* EMPTY */
.empty {
    text-align: center;
    color: #777;
    padding: 40px 0;
}
</style>
</head>

<body>

<div class="card">

    <div class="card-header">
        <h2>🐾 Rescue Requests</h2>

        <?php if ($alert['total'] > 0): ?>
            <div class="alert">
                🔔 <?= $alert['total'] ?> New Request(s)
            </div>
        <?php endif; ?>
    </div>

    <?php if ($result->num_rows === 0): ?>
        <div class="empty">No rescue requests assigned yet.</div>
    <?php else: ?>
        <div class="table-wrapper">
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
                    <td class="action-btns">
                        <?php if ($row['status'] !== 'Completed'): ?>
                            <a class="btn-progress" href="update_status.php?id=<?= $row['request_id'] ?>&status=In Progress">Start</a><br>
                            <a class="btn-complete" href="update_status.php?id=<?= $row['request_id'] ?>&status=Completed">Complete</a>
                        <?php else: ?>
                            ✅ Completed
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    <?php endif; ?>

</div>

</body>
</html>
