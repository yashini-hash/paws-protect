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

<style>
body{
    font-family: 'Segoe UI', Arial, sans-serif;
    background:#FFF8E7;
    margin:0;
    margin-left:120px;
    padding:50px;
}


.container{
    display:flex;
    gap:40px;
    max-width:1200px;
    margin:60px auto;
    flex-wrap:wrap;
}


.card{
     
   background:#ddbc8b;
    border-radius:14px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

.left-card{ 
    margin-left:320px;
    width:580px; }
.right-card{ width:250px; }


h2{
    text-align:center;
     color: #3e2c1c;
    margin-bottom:20px;
}


label{
    font-weight:600;
    display:block;
    margin-top:15px;
}

select, textarea, button{
    width:100%;
    padding:12px;
    margin-top:8px;
    border-radius:8px;
    border:1px solid #ddd;
    font-size:15px;
}

textarea{
    height:110px;
    resize:none;
}

button{
    margin-top:20px;
     background: #5C3A21;
    color:white;
    border:none;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
}

button:hover{
    background:#9d6e4c;
}


.stars{
    display:flex;
    flex-direction:row-reverse;
    justify-content:flex-start;
    gap:5px;
    margin-top:8px;
}

.stars input{ display:none; }

.stars label{
    font-size:28px;
    color:#ccc;
    cursor:pointer;
}

.stars input:checked ~ label,
.stars label:hover,
.stars label:hover ~ label{
    color:yellow;
}


.success{
    background:#e6f7ec;
    color:#218838;
    padding:10px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
}

.error{
    background:#fdecea;
    color:#c82333;
    padding:10px;
    border-radius:6px;
    text-align:center;
    margin-bottom:15px;
}


.feedback-item{
    padding:15px 0;
    border-bottom:1px solid #eee;
}

.feedback-item:last-child{
    border-bottom:none;
}

.center{
    font-weight:600;
    color:#444;
}

.star-view{
    color:gold;
}

.date{
    font-size:12px;
    color:#888;
    margin-top:4px;
}
 .page-title {
            text-align: center;
            color: #5C3A21;
            font-size:30px;
            margin: 30px 0 15px;
        }

@media(max-width:1000px){
    .left-card,.right-card{
        width:100%;
    }
}
</style>
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
