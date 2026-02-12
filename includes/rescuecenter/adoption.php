<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$rescue_center_id = $_SESSION['rescue_center_id'];

$status_filter = $_GET['status'] ?? '';

$sql = "
    SELECT 
        ar.request_id,
        ar.status,
        ar.request_date,
        u.name AS user_name,
        u.email AS user_email,
        a.name AS animal_name,
        a.type AS animal_type,
        a.breed AS animal_breed
    FROM adopt_requests ar
    INNER JOIN users u ON ar.user_id = u.user_id
    INNER JOIN animals_details a ON ar.animal_id = a.animal_id
    WHERE ar.rescue_center_id = ?
";

$params = [$rescue_center_id];
$types = "i";

if (in_array($status_filter, ['pending','approved','rejected'])) {
    $sql .= " AND ar.status = ?";
    $types .= "s";
    $params[] = $status_filter;
}

$sql .= " ORDER BY ar.request_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Adoption Requests</title>
<link rel="stylesheet" href="adoption.css">
</head>

<body>

<div class="main-container">
    <div class="table-card">
        <div class="card-title">Adoption Requests</div>

        <div class="filter-bar">
            <form method="get">
                <label for="status">Filter by Status:</label>
                <select name="status" id="status">
                    <option value="" <?= $status_filter=='' ? 'selected' : '' ?>>All</option>
                    <option value="pending" <?= $status_filter=='pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $status_filter=='approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $status_filter=='rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
                <button type="submit" style="background:#5C4033; color:white; border:none; hover:#9d6e4c;">Filter</button>
            </form>
        </div>

        <table>
            <tr>
                <th>User Name</th>
                <th>Email</th>
                <th>Animal</th>
                <th>Status</th>
                <th>Requested On</th>
            </tr>

            <?php if ($result->num_rows > 0): 
                while($row = $result->fetch_assoc()): ?>
                
                <tr onclick="window.location='adoption_request_view.php?request_id=<?= $row['request_id'] ?>'" style="cursor:pointer;">
                    <td><?= htmlspecialchars($row['user_name']) ?></td>
                    <td><?= htmlspecialchars($row['user_email']) ?></td>
                    <td><?= htmlspecialchars($row['animal_name']) ?> (<?= htmlspecialchars($row['animal_type']) ?>)</td>
                    <td class="status-<?= strtolower($row['status']) ?>">
                        <?= ucfirst($row['status']) ?>
                    </td>
                    <td><?= $row['request_date'] ?></td>
                </tr>

            <?php endwhile; else: ?>
                <tr>
                    <td colspan="5" class="empty-msg">No adoption requests found.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</div>

</body>
</html>
