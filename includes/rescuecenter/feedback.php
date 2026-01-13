<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

// Check login
if (!isset($_SESSION['rescue_center_id'])) {
    header("Location: login.php");
    exit();
}

$rescue_center_id = $_SESSION['rescue_center_id'];

// Fetch feedback for this rescue center
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #FFF8E7;
            padding: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #5a3e1b;
        }

        .feedback-container {
            max-width: 900px;
            margin: auto;
        }

        .feedback-card {
            background: #ffffff;
            border-radius: 10px;
            padding: 18px 22px;
            margin-bottom: 18px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }

        .feedback-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .rating {
            color: #ff9800;
            font-size: 18px;
        }

        .message {
            margin: 10px 0;
            color: #555;
            line-height: 1.6;
        }

        .date {
            font-size: 13px;
            color: #888;
            text-align: right;
        }

        .no-feedback {
            text-align: center;
            background: #fff;
            padding: 30px;
            border-radius: 10px;
            color: #777;
            box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        }
    </style>
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
