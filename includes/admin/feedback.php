<?php
session_start();
include("auth.php");
include("sidebar.php"); 
include("../page/dbconnect.php"); 

$selected_center = isset($_GET['rescue_center_id']) ? $_GET['rescue_center_id'] : "all";

if ($selected_center == "all") {
    $query = "
        SELECT f.*, u.name AS user_name, u.email, r.center_name
        FROM feedback f
        JOIN users u ON f.user_id = u.user_id
        JOIN rescue_center r ON f.rescue_center_id = r.rescue_center_id
        ORDER BY f.created_at DESC
    ";
} else {
    $selected_center = mysqli_real_escape_string($conn, $selected_center);
    $query = "
        SELECT f.*, u.name AS user_name, u.email, r.center_name
        FROM feedback f
        JOIN users u ON f.user_id = u.user_id
        JOIN rescue_center r ON f.rescue_center_id = r.rescue_center_id
        WHERE f.rescue_center_id='$selected_center'
        ORDER BY f.created_at DESC
    ";
}

$feedbacks = mysqli_query($conn, $query);

$centers = mysqli_query($conn, "SELECT rescue_center_id, center_name FROM rescue_center");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin | Feedback Management</title>
    <link rel="stylesheet" href="sidebar.css">
    <link rel="stylesheet" href="feedback.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

</head>
<body>

<div class="main-content">
<h2><i class="fa-solid fa-comment-dots"></i> Feedback Management</h2>

<div class="filter-box">
    <form method="GET">
        <select name="rescue_center_id" onchange="this.form.submit()">
            <option value="all" <?= $selected_center=="all" ? "selected" : "" ?>>All Rescue Centers</option>
            <?php while($c = mysqli_fetch_assoc($centers)) { ?>
                <option value="<?= $c['rescue_center_id'] ?>" <?= $selected_center==$c['rescue_center_id'] ? "selected" : "" ?>>
                    <?= htmlspecialchars($c['center_name']) ?>
                </option>
            <?php } ?>
        </select>
    </form>
</div>

<?php if(mysqli_num_rows($feedbacks)==0){ ?>
    <p style="text-align:center;color:#777;">No feedback found.</p>
<?php } ?>

<?php while($row = mysqli_fetch_assoc($feedbacks)) { ?>
<div class="feedback-card">
    <div class="feedback-header">
        <h4><?= htmlspecialchars($row['user_name']) ?> (<?= htmlspecialchars($row['email']) ?>)</h4>
        <span class="tag"><?= htmlspecialchars($row['center_name']) ?></span>
    </div>

    <p class="feedback-msg"><?= htmlspecialchars($row['message']) ?></p>

    <p>
    <b>Rating:</b>
    <?php
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $row['rating']) {
                echo '<i class="fa-solid fa-star" style="color:#5C3A21;"></i>';
            } else {
                echo '<i class="fa-regular fa-star" style="color:#5C3A21;"></i>';
            }
        }
    ?>
    | <b>Date:</b> <?= $row['created_at'] ?>
</p>


    <p>
        <b>Type:</b>
        <?php
        if($row['rating'] <= 2) echo "Complaint / System Issue";
        elseif($row['rating'] == 3) echo "Suggestion";
        else echo "Positive Feedback";
        ?>
    </p>

</div>
<?php } ?>

</div>
</body>
</html>
