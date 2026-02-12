<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'] ?? 1;
$success = false;
$error = "";


if (isset($_POST['submit'])) {

    $rescue_center_id = mysqli_real_escape_string($conn, $_POST['rescue_center_id']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO feedback (user_id, rescue_center_id, rating, message)
            VALUES ('$user_id', '$rescue_center_id', '$rating', '$message')";

    if (mysqli_query($conn, $sql)) {
        $success = true;
        $_POST = [];
    } else {
        $error = mysqli_error($conn);
    }
}


$centers = mysqli_query($conn, "SELECT rescue_center_id, center_name FROM rescue_center");

$feedbacks = mysqli_query(
    $conn,
    "SELECT f.*, r.center_name
     FROM feedback f
     JOIN rescue_center r ON f.rescue_center_id = r.rescue_center_id
     WHERE f.user_id='$user_id'
     ORDER BY f.created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>
        <link rel="stylesheet" href="feedback.css">

</head>

<body>
<h2 class="page-title">Give us a feedback</h2>
<div class="container">


<div class="card left-card">
<h2>Give Feedback</h2>

<?php if($success){ ?>
<div class="success" id="successMsg">✔ Feedback submitted successfully</div>
<?php } ?>

<?php if($error!=""){ ?>
<div class="error"><?= $error ?></div>
<?php } ?>

<form method="post">

<label>Rescue Center</label>
<select name="rescue_center_id" required>
<option value="">-- Select Rescue Center --</option>
<?php while($c = mysqli_fetch_assoc($centers)){ ?>
<option value="<?= $c['rescue_center_id'] ?>">
<?= $c['center_name'] ?>
</option>
<?php } ?>
</select>

<label>Rating</label>
<div class="stars">
<input type="radio" name="rating" id="r5" value="5" required><label for="r5">★</label>
<input type="radio" name="rating" id="r4" value="4"><label for="r4">★</label>
<input type="radio" name="rating" id="r3" value="3"><label for="r3">★</label>
<input type="radio" name="rating" id="r2" value="2"><label for="r2">★</label>
<input type="radio" name="rating" id="r1" value="1"><label for="r1">★</label>
</div>

<label>Feedback</label>
<textarea name="message" placeholder="Share your experience..." required></textarea>

<button type="submit" name="submit">Submit Feedback</button>

</form>
</div>


<div class="card right-card">
<h2>My Feedback</h2>

<?php
if(mysqli_num_rows($feedbacks)==0){
    echo "<p style='text-align:center;color:#777;'>No feedback yet</p>";
}

while($row = mysqli_fetch_assoc($feedbacks)){
?>
<div class="feedback-item">
<div class="center"><?= $row['center_name'] ?></div>

<?php
for($i=1;$i<=5;$i++){
echo ($i<=$row['rating']) ? "<span class='star-view'>★</span>" : "<span>☆</span>";
}
?>

<p><?= htmlspecialchars($row['message']) ?></p>
<div class="date"><?= $row['created_at'] ?></div>
</div>
<?php } ?>

</div>

</div>

<script>
setTimeout(()=>{
 let m=document.getElementById("successMsg");
 if(m) m.style.display="none";
},3000);
</script>

</body>
</html>
