<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lost_id'], $_POST['status'])) {

    $lost_id_post = intval($_POST['lost_id']);
    $status_post  = $_POST['status'];

    if (in_array($status_post, ['found', 'notfound'])) {
        $stmt = $conn->prepare("UPDATE lost_animals SET status = ? WHERE lost_id = ?");
        $stmt->bind_param("si", $status_post, $lost_id_post);
        $stmt->execute();

        $message = "Status has been updated to <strong>" . ucfirst($status_post) . "</strong>!";
    }
}

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
<link rel="stylesheet" href="lost_animal.css">


</head>
<body>

<div class="container">

    <?php if (isset($_GET['msg'])): ?>
        <div style="text-align:center; background:#4CAF50; color:white; padding:12px; margin-bottom:20px; border-radius:8px;">
            <?= htmlspecialchars($_GET['msg']) ?>
        </div>
    <?php endif; ?>

    <div class="wrapper">
        <div class="left">
            <img src="../uploads/lost/<?= $animal['image'] ?>" alt="Lost Animal">
        </div>

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
