<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_lost_id'])) {
    $lost_id = intval($_POST['delete_lost_id']);

    $stmt = $conn->prepare("SELECT image FROM lost_animals WHERE lost_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $lost_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        $stmt = $conn->prepare("DELETE FROM lost_animals WHERE lost_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $lost_id, $user_id);
        $stmt->execute();

        if (!empty($row['image']) && file_exists("../uploads/lost/" . $row['image'])) {
            unlink("../uploads/lost/" . $row['image']);
        }

        $message = "Report deleted successfully!";
    } else {
        $message = "Unauthorized or record not found!";
    }
}

$stmt = $conn->prepare("SELECT * FROM lost_animals WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Lost Animal Reports</title>
<link rel="stylesheet" href="view_lost_animal.css">
</head>
<body>

<div class="main-container">
    <h1>My Lost Animal Reports</h1>

    <?php if(!empty($message)): ?>
        <div class="alert alert-success"><?= $message ?></div>
    <?php endif; ?>

    <?php if($result->num_rows == 0): ?>
        <p style="text-align:center; color:#555; font-size:18px;">You have not reported any lost animals yet.</p>
    <?php else: ?>
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="card">
                <img src="../uploads/lost/<?= $row['image'] ?>" alt="Lost Animal">
                <h3><?= htmlspecialchars($row['animal_type']) ?></h3>
                <div class="info"><strong>Color:</strong> <?= htmlspecialchars($row['color']) ?></div>
                <div class="info"><strong>Location:</strong> <?= htmlspecialchars($row['lost_location']) ?></div>
                <div class="info"><strong>Date:</strong> <?= htmlspecialchars($row['lost_date']) ?></div>
                <div class="info status">
                    <strong>Status:</strong> 
                    <span style="color:<?= $row['status']=='found' ? '#2e7d32':'#b71c1c' ?>;"><?= ucfirst($row['status']) ?></span>
                </div>

                <form method="POST" onsubmit="return confirm('Delete this report?');">
                    <input type="hidden" name="delete_lost_id" value="<?= $row['lost_id'] ?>">
                    <button type="submit" class="delete-btn">Delete</button>
                </form>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>
</div>

</body>
</html>
