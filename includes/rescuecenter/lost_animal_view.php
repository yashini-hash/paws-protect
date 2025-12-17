<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

// Security check
if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

// Initialize message
$message = "";

/* ---------- HANDLE STATUS UPDATE ---------- */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lost_id'], $_POST['status'])) {

    $lost_id_post = intval($_POST['lost_id']);
    $status_post  = $_POST['status'];

    // Only allow valid statuses
    if (in_array($status_post, ['found', 'notfound'])) {
        $stmt = $conn->prepare("UPDATE lost_animals SET status = ? WHERE lost_id = ?");
        $stmt->bind_param("si", $status_post, $lost_id_post);
        $stmt->execute();

        // Set success message
        $message = "Status has been updated to <strong>" . ucfirst($status_post) . "</strong>!";
    }
}

/* ---------- FETCH DETAILS ---------- */
if (!isset($_GET['lost_id'])) {
    echo "<p style='color:red;text-align:center;'>Invalid Request</p>";
    exit;
}

$lost_id = intval($_GET['lost_id']);

$sql = "SELECT * FROM lost_animals WHERE lost_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $lost_id);
$stmt->execute();
$animal = $stmt->get_result()->fetch_assoc();

if (!$animal) {
    echo "<p style='color:red;text-align:center;'>Animal not found</p>";
    exit;
}
?>


<!DOCTYPE html>
<html>
<head>
<title>Lost Animal Details</title>

<style>
body {
    background:#FFF8E7;
    font-family: Arial, sans-serif;
}

.container {
     margin-top:50px;
    margin-left: 260px;
    padding: 40px;
}

.wrapper {
    display: flex;
    justify-content: center;
    gap: 30px;
    background: #f0d9b5;
    padding: 25px;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.15);
    max-width: 900px;
    margin: auto;
}

.left img {
    width: 400px;
    height: 450px;
    object-fit: cover;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
}

.right {
    width: 450px;
    padding: 10px;
}

.title {
    font-size: 30px;
    font-weight: bold;
    color: #3e2c1c;
    margin-bottom: 15px;
    text-align: center;
}

.info-box {
    background: #fff3e0;
    padding: 18px;
    border-radius: 14px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.info-box p {
    font-size: 17px;
    margin: 12px 0;
    color: #3e2c1c;
}

strong {
    color: #2b1b10;
}

.btn-area {
    margin-top: 20px;
    text-align: center;
}

.btn {
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    cursor: pointer;
    font-size: 16px;
    margin: 5px;
    transition: 0.2s;
}

.found {
    background: #4CAF50;
    color: white;
}

.notfound {
    background: #d32f2f;
    color: white;
}

.back {
    background: #3e2c1c;
    color: white;
}

.btn:hover {
    opacity: 0.85;
}
</style>

</head>
<body>

<div class="container">

    <!-- SUCCESS MESSAGE -->
    <?php if (isset($_GET['msg'])): ?>
        <div style="text-align:center; background:#4CAF50; color:white; padding:12px; margin-bottom:20px; border-radius:8px;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <div class="wrapper">
        <!-- LEFT SIDE IMAGE -->
        <div class="left">
            <img src="../uploads/lost/<?= $animal['image'] ?>" alt="Lost Animal">
        </div>

        <!-- RIGHT SIDE DETAILS -->
        <div class="right">
            <div class="title"><?= htmlspecialchars($animal['animal_type']) ?></div>
            <div class="info-box">
                <p><strong>Color:</strong> <?= htmlspecialchars($animal['color']) ?></p>
                <p><strong>Lost Location:</strong> <?= htmlspecialchars($animal['lost_location']) ?></p>
                <p><strong>Date Lost:</strong> <?= htmlspecialchars($animal['lost_date']) ?></p>
                <p><strong>Breed:</strong> <?= htmlspecialchars($animal['breed']) ?></p>
                <p><strong>Owner Name:</strong> <?= htmlspecialchars($animal['owner_name']) ?></p>
                <p><strong>Contact Number:</strong> <?= htmlspecialchars($animal['contact_number']) ?></p>
                <p><strong>Status:</strong>
                    <span style="color:<?= $animal['status']=='found' ? '#388e3c':'#d32f2f' ?>;">
                        <?= ucfirst($animal['status']) ?>
                    </span>
                </p>
            </div>

            <div class="btn-area">
                <form method="POST" action="update_lost_status.php" style="display:inline;">
                    <input type="hidden" name="lost_id" value="<?= $lost_id ?>">
                    <button name="status" value="found" class="btn found">Mark as Found</button>
                    <button name="status" value="notfound" class="btn notfound">Mark Not Found</button>
                </form>

                <a href="lost.php">
                    <button class="btn back">← Back</button>
                </a>
            </div>
        </div>
    </div>
</div>

</body>

</html>
