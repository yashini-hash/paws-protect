<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle deletion
if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['delete_lost_id'])) {
    $lost_id = intval($_POST['delete_lost_id']);

    // Verify ownership and get image
    $stmt = $conn->prepare("SELECT image FROM lost_animals WHERE lost_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $lost_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Delete record
        $stmt = $conn->prepare("DELETE FROM lost_animals WHERE lost_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $lost_id, $user_id);
        $stmt->execute();

        // Delete image file if exists
        if (!empty($row['image']) && file_exists("../uploads/lost/" . $row['image'])) {
            unlink("../uploads/lost/" . $row['image']);
        }

        $message = "Report deleted successfully!";
    } else {
        $message = "Unauthorized or record not found!";
    }
}

// Fetch user's lost animals
$stmt = $conn->prepare("SELECT * FROM lost_animals WHERE user_id = ? ORDER BY created_at DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>My Lost Animal Reports</title>
<style>
.main-container { margin-left:260px; padding:40px; }
h1 { text-align:center; color:#5C3A21; margin-bottom:20px; }
.card { background:#ddbc8b; width:300px; padding:18px; border-radius:16px; display:inline-block; margin:10px; box-shadow:0 4px 15px rgba(0,0,0,0.1); vertical-align:top; }
.card img { width:100%; height:220px; object-fit:cover; border-radius:12px; }
.card h3 { text-align:center; margin-top:10px; color:#4b2e1e; }
.info { font-size:15px; margin:5px 0; }
.status { font-weight:bold; }
.delete-btn { background:#d32f2f; color:white; border:none; padding:10px; width:100%; margin-top:10px; border-radius:8px; cursor:pointer; }
.delete-btn:hover { opacity:0.8; }
.alert { padding:12px; border-radius:10px; margin-bottom:20px; font-weight:500; text-align:center; }
.alert-success { background:#d4edda; color:#155724; }
.alert-error { background:#f8d7da; color:#721c24; }
/* Mobile Responsiveness */
@media (max-width: 768px) {
    body {
        padding: 20px;
        margin-left: 0;
    }
    .main-container {
        margin-left: 10px;
        padding: 20px;
    }

    .card {
        width: 95%;
        display: block;
        margin: 15px auto;
        padding: 15px;
    }

    .card img {
        height: auto;
    }

    .card h3 {
        font-size: 20px;
    }

    .info {
        font-size: 14px;
    }

    .delete-btn {
        padding: 8px;
        font-size: 14px;
    }
}

</style>
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
