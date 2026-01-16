<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

// Check rescue center login
if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$rescue_center_id = $_SESSION['rescue_center_id'];

// Get filter from query string
$status_filter = $_GET['status'] ?? '';

// Build SQL with optional filter
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
<style>
body {
    background: #FFF8E7;
    font-family: Arial, sans-serif;
    margin: 0;
    padding: 0;
}

/* Main content wrapper */
.main-container {
    margin-left: 270px;  /* sidebar width */
    padding: 30px;
}

/* Card container */
.table-card {
    width: 90%;
    margin: auto;
    background: white;
    padding: 25px;
    border-radius: 18px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Title */
.card-title {
    font-size: 22px;
    font-weight: bold;
    color: #5C4033;
    margin-bottom: 20px;
    text-align: center;
}

/* Filter bar */
.filter-bar {
    text-align: center;
    margin-bottom: 15px;
}
.filter-bar select, .filter-bar button {
    padding: 6px 12px;
    margin-left: 5px;
    margin-bottom:10px;
    border-radius: 5px;
    border: 1px solid #ccc;
    cursor: pointer;
}

/* Table styling */
table {
    width: 100%;
    border-collapse: collapse;
    border-radius: 12px;
    overflow: hidden;
}

table th {
    background: #5C4033;
    color: white;
    padding: 12px;
    font-size: 15px;
}

table td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
    font-size: 14px;
}

/* Row hover */
table tr:hover td {
    background: #f5f0eb;
}

/* Status colors */
.status-pending { color: #e38b06; font-weight: bold; }
.status-approved { color: #33c83dff ; font-weight: bold; }
.status-rejected { color: #d43636ff; font-weight: bold; }

/* Empty message */
.empty-msg {
    text-align: center;
    padding: 20px;
    color: #7a5f50;
    font-size: 16px;
}

/* Column widths */
table th:nth-child(1), table td:nth-child(1) { width: 20%; text-align:center; }
table th:nth-child(2), table td:nth-child(2) { width: 25%; text-align:center;}
table th:nth-child(3), table td:nth-child(3) { width: 25%; text-align:center;}
table th:nth-child(4), table td:nth-child(4) { width: 15%; text-align:center; }
table th:nth-child(5), table td:nth-child(5) { width: 15%; text-align:center; }

/* ================= MOBILE RESPONSIVE (≤768px) ================= */
@media screen and (max-width: 768px) {

    body {
        padding: 0;
    }

    /* Remove sidebar offset */
    .main-container {
        margin-left: 0;
        padding: 15px;
    }

    /* Card */
    .table-card {
        width: 100%;
        padding: 15px;
        border-radius: 12px;
    }

    .card-title {
        font-size: 20px;
        margin-bottom: 15px;
    }

    /* Filter bar */
    .filter-bar form {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: stretch;
    }

    .filter-bar select,
    .filter-bar button {
        width: 100%;
        padding: 10px;
        font-size: 15px;
    }

    /* TABLE → CARD STYLE */
    table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
    }

    table tr {
        margin-bottom: 15px;
        border-radius: 10px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        overflow: hidden;
       background: #ddbc8b;
    }

    table th {
        display: none; /* hide headers */
    }

    table td {
        border: none;
        padding: 10px;
        font-size: 14px;
        text-align: left !important;
    }

    table td::before {
        font-weight: bold;
        color: #5C4033;
        display: block;
        margin-bottom: 3px;
    }

    /* Labels for each column */
    table td:nth-child(1)::before { content: "User Name"; }
    table td:nth-child(2)::before { content: "Email"; }
    table td:nth-child(3)::before { content: "Animal"; }
    table td:nth-child(4)::before { content: "Status"; }
    table td:nth-child(5)::before { content: "Requested On"; }

    /* Hover fix */
    table tr:hover td {
        background: none;
    }
}

</style>
</head>

<body>

<div class="main-container">
    <div class="table-card">
        <div class="card-title">Adoption Requests</div>

        <!-- Filter -->
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
