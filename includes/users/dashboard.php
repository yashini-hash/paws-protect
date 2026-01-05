<?php
include("sidebar.php"); 


include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, email, phone, profile_image, created_at 
        FROM users 
        WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$profile_img = !empty($user['profile_image'])
    ? "../uploads/profiles/" . $user['profile_image']
    : "../uploads/profiles/default.png";
?>

<!DOCTYPE html>
<html>
<head>
    <title>My Adoptions</title>
<style>
    .welcome-box {
    background: linear-gradient(135deg, #FFD8B4, #E6B48A);
    padding: 20px;
    border-radius: 15px;
    color: #5C3A21;
    box-shadow: 0 4px 10px rgba(0,0,0,0.15);
    margin-bottom: 25px;
}

    .welcome-box h2 {
    margin-bottom: 8px;
}
</style>
</head>

<body>

<div class="main">

    <div class="welcome-box">
        <h2>Hi, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
        <p>Welcome back to <strong>Paws & Protect</strong>.</p>
    </div>

</div>
</body>
</html>









