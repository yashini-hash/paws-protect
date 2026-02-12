<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    header("Location: login.php");
    exit();
}

$rescue_center_id = $_SESSION['rescue_center_id'];

$sql = "
    SELECT 
        f.rating,
        f.message,
        f.created_at,
        u.name AS user_name
    FROM feedback f
    JOIN users u ON f.user_id = u.user_id
    WHERE f.rescue_center_id = '$rescue_center_id'
    ORDER BY f.created_at DESC
";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Rescue Center Feedback</title>
  <link rel="stylesheet" href="feedback.css">
</head>
<body>

<h2>Feedback Received</h2>

<div class="feedback-container">

<?php
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $stars = str_repeat("★", $row['rating']) . str_repeat("☆", 5 - $row['rating']);
        ?>
        <div class="feedback-card">
            <div class="feedback-header">
                <span><?php echo htmlspecialchars($row['user_name']); ?></span>
                <span class="rating"><?php echo $stars; ?></span>
            </div>

            <div class="message">
                <?php echo nl2br(htmlspecialchars($row['message'])); ?>
            </div>

            <div class="date">
                <?php echo date("d M Y, h:i A", strtotime($row['created_at'])); ?>
            </div>
        </div>
        <?php
    }
} else {
    echo "<div class='no-feedback'>No feedback received yet.</div>";
}
?>

</div>

</body>
</html>

