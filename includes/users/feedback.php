<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'] ?? 1;
$success = false;
$error = "";

/* Submit feedback */
if (isset($_POST['submit'])) {

    $center_name = mysqli_real_escape_string($conn, $_POST['center_name']);
    $rating = mysqli_real_escape_string($conn, $_POST['rating']);
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    $sql = "INSERT INTO feedback (user_id, center_name, rating, message)
            VALUES ('$user_id', '$center_name', '$rating', '$message')";

    if (mysqli_query($conn, $sql)) {
        $success = true;
        $_POST = array(); // Clear POST to prevent resubmission
    } else {
        $error = mysqli_error($conn);
    }
}

/* Get previous feedback */
$feedbacks = mysqli_query(
    $conn,
    "SELECT * FROM feedback WHERE user_id='$user_id' ORDER BY created_at DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
<title>Feedback</title>
<style>
body{
    font-family: Arial, sans-serif;
    background:#f0e4d2;
    margin:0;
    padding:0;
}

/* MAIN CONTAINER */
.container{
    display:flex;
    justify-content:center; /* horizontal center */
    align-items:flex-start;
    gap:50px;
    margin:50px auto;
    flex-wrap:wrap;
    max-width:1100px;
}

/* BOX COMMON */
.box{
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}

/* LEFT & RIGHT BOXES */
.left-box, .right-box{
    width:500px;
}

/* HEADER */
h2{
    text-align:center;
}

/* FORM ELEMENTS */
select, textarea, button{
    width:100%;
    padding:12px;
    margin-top:12px;
    font-size:15px;
}

textarea{
    height:100px;
    resize:none;
}

button{
    background:#28a745;
    color:#fff;
    border:none;
    cursor:pointer;
    font-size:16px;
    transition:0.3s;
}

button:hover{
    background:#218838;
}

/* STAR INPUT */
.stars{
    display:flex;
    flex-direction:row-reverse;
    justify-content:center;
    margin-top:10px;
}
.stars input{
    display:none;
}
.stars label{
    font-size:30px;
    color:#ccc;
    cursor:pointer;
}
.stars input:checked ~ label,
.stars label:hover,
.stars label:hover ~ label{
    color:gold;
}

/* MESSAGE */
.success{
    color:green;
    text-align:center;
    margin-bottom:10px;
    transition: opacity 0.5s ease;
}
.error{
    color:red;
    text-align:center;
    margin-bottom:10px;
}

/* FEEDBACK LIST */
.feedback-item{
    border-bottom:1px solid #ddd;
    padding:12px 0;
}
.center{
    font-weight:bold;
}
.date{
    font-size:12px;
    color:#777;
}
.star-view{
    color:gold;
}

/* RESPONSIVE */
@media (max-width: 1050px) {
    .left-box, .right-box{
        width:90%;
    }
}
</style>
</head>

<body>

<div class="container">

    <!-- ================= LEFT : FORM ================= -->
    <div class="box left-box">
        <h2>Give Feedback</h2>

        <?php if($success){ ?>
            <div class="success" id="successMsg">✅ Feedback submitted successfully!</div>
        <?php } ?>

        <?php if($error!=""){ ?>
            <div class="error">❌ <?= $error ?></div>
        <?php } ?>

        <form method="post" autocomplete="off">

            <label>Select Rescue Center</label>
            <select name="center_name" required>
                <option value="">-- Select --</option>
                <option value="animalcare">Animal Care</option>
                <option value="Animal care and love">Animal Care and Love</option>
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
            <textarea name="message" placeholder="Enter your feedback..." required><?= $_POST['message'] ?? '' ?></textarea>

            <button type="submit" name="submit">Submit Feedback</button>
        </form>
    </div>

    <!-- ================= RIGHT : PREVIOUS FEEDBACK ================= -->
    <div class="box right-box">
        <h2>My Previous Feedback</h2>

        <?php
        if (mysqli_num_rows($feedbacks) == 0) {
            echo "<p style='text-align:center;'>No feedback submitted yet.</p>";
        }

        while ($row = mysqli_fetch_assoc($feedbacks)) {
            echo "<div class='feedback-item'>";
            echo "<div class='center'>{$row['center_name']}</div>";

            for ($i=1; $i<=5; $i++){
                echo ($i <= $row['rating']) ? "<span class='star-view'>★</span>" : "<span>☆</span>";
            }

            echo "<p>{$row['message']}</p>";
            echo "<div class='date'>{$row['created_at']}</div>";
            echo "</div>";
        }
        ?>
    </div>

</div>

<!-- ================= JS AUTO HIDE ================= -->
<script>
    setTimeout(function(){
        let msg = document.getElementById("successMsg");
        if(msg){
            msg.style.display = "none";
        }
    }, 3000); // hide after 3 seconds
</script>

</body>
</html>
