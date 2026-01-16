<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['cancel_request_id'], $_POST['animal_id'])) {
    $request_id = $_POST['cancel_request_id'];
    $animal_id  = $_POST['animal_id'];

    $sql1 = "DELETE FROM adopt_requests WHERE request_id = ? AND user_id = ?";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bind_param("ii", $request_id, $user_id);
    $stmt1->execute();

    $sql2 = "UPDATE animals_details SET adoption_status = 'available' WHERE animal_id = ?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $animal_id);
    $stmt2->execute();

    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

$filter_status = isset($_GET['status']) ? $_GET['status'] : 'all';

$sql = "SELECT ar.request_id, ar.status, ar.request_date,
               a.animal_id,
               a.name AS animal_name, 
               a.type AS animal_type, 
               a.animal_image AS animal_image,
               rc.center_name AS rescue_center_name
        FROM adopt_requests ar
        INNER JOIN animals_details a ON ar.animal_id = a.animal_id
        INNER JOIN rescue_center rc ON ar.rescue_center_id = rc.rescue_center_id
        WHERE ar.user_id = ?";
if (in_array($filter_status, ['pending', 'approved', 'rejected'])) {
    $sql .= " AND ar.status = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $filter_status);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Adoptions</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8E7;
            padding: 50px;
            margin-left:120px;
        }
        h2 {
            font-size:30px;
            text-align: center;
            margin: 20px 0;
            color:#5C3A21;
        }

        .filter-container {
            text-align: center;
            margin-bottom: 30px;
        }
        select {
            padding: 8px 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .animal-card {
            background: #ddbc8b;
            padding: 15px;
            border-radius: 15px;
            width: 250px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .animal-card img {
            width: 220px;
            height: 220px;
            object-fit: cover;
            border-radius: 10px;
        }
        .animal-info {
            padding: 10px;
        }
        .animal-info h3 {
            margin: 10px 0 5px;
            color:#5C3A21;
        }
        .status {
            margin-top: 8px;
            padding: 5px 12px;
            border-radius: 20px;
            display: inline-block;
            color: #fff;
            font-size: 13px;
        }
        .status-approved { background-color: green; }
        .status-rejected { background-color: red; }
        .status-pending { background-color: #5C3A21; }
        .status-cancelled { background-color: gray; }

        .cancel-btn {
            margin-top: 10px;
            background-color: #00648bff;
            color: #fff;
            border: none;
            padding: 7px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 13px;
        }
        .cancel-btn:hover {
            background-color: #e62d2dff;
        }

@media (max-width: 768px) {
    body { padding: 20px; margin-left: 0; }
    .card-container { flex-direction: column; align-items: center; gap: 15px; }
    .animal-card { width: 90%; padding: 10px; }
    .animal-card img { width: 100%; height: auto; }
    .animal-info h3 { font-size: 20px; }
    .animal-info p { font-size: 14px; }
    .cancel-btn { padding: 6px 12px; font-size: 12px; }
}
    </style>
</head>

<body>

<h2>My Adoption Requests</h2>

<div class="filter-container">
    <form method="GET">
        <label for="status">Filter by Status: </label>
        <select name="status" id="status" onchange="this.form.submit()">
            <option value="all" <?php if($filter_status=='all') echo 'selected'; ?>>All</option>
            <option value="pending" <?php if($filter_status=='pending') echo 'selected'; ?>>Pending</option>
            <option value="approved" <?php if($filter_status=='approved') echo 'selected'; ?>>Approved</option>
            <option value="rejected" <?php if($filter_status=='rejected') echo 'selected'; ?>>Rejected</option>
        </select>
    </form>
</div>

<div class="card-container">
<?php if ($result->num_rows > 0): ?>
<?php while($row = $result->fetch_assoc()): ?>
<?php $status = strtolower($row['status']); ?>

    <div class="animal-card">
        <img src="../uploads/addanimal/<?php echo htmlspecialchars($row['animal_image']); ?>">
        <div class="animal-info">
            <h3><?php echo htmlspecialchars($row['animal_name']); ?></h3>
            <p>Type: <?php echo htmlspecialchars($row['animal_type']); ?></p>
            <p>Rescue Center: <?php echo htmlspecialchars($row['rescue_center_name']); ?></p>

            <span class="status status-<?php echo $status; ?>">
                <?php echo ucfirst($status); ?>
            </span>

            <?php if ($status == 'pending' || $status == 'approved'): ?>
                <form method="POST" 
                      onsubmit="return confirm('Are you sure you want to cancel this adoption?');">
                    <input type="hidden" name="cancel_request_id" value="<?php echo $row['request_id']; ?>">
                    <input type="hidden" name="animal_id" value="<?php echo $row['animal_id']; ?>">
                    <button type="submit" class="cancel-btn">Cancel Adoption</button>
                </form>
            <?php endif; ?>

        </div>
    </div>

<?php endwhile; ?>
<?php else: ?>
    <p style="text-align:center;">No adoption requests found for this filter.</p>
<?php endif; ?>
</div>

</body>
</html>
