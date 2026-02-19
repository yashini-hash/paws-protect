<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");  

$user_id = $_SESSION['user_id'];

$sql = "SELECT * FROM users WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

$profileImage = (!empty($user['profile_image'])) 
    ? $user['profile_image'] 
    : 'default.png';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
            <link rel="stylesheet" href="edit_profile.css">


</head>

<body>

<div class="main-content">

<h2>Edit Profile</h2>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div class="success-msg">
        ✅ Profile updated successfully!
    </div>
<?php endif; ?>

<form action="update_profile.php" method="POST" enctype="multipart/form-data">
    <div class="profile-card">

        <img class="profile-img"
             src="../uploads/profiles/<?php echo $profileImage; ?>"
             onerror="this.src='../uploads/profiles/default.png';">

        <br><br>

        <label>Change Profile Image</label>
        <input type="file" name="profile_image"><br><br>

        <label>Full Name</label>
        <input type="text" name="name" value="<?php echo $user['name']; ?>" required><br><br>

        <label>Email</label>
        <input type="email" value="<?php echo $user['email']; ?>" disabled><br><br>

        <label>Phone</label>
        <input type="text" name="phone" value="<?php echo $user['phone']; ?>"><br><br>

       <label>New Password (optional)</label>

<div style="position:relative;">
    <input type="password" name="password" id="password" style="padding-right:40px;">

    <span onclick="togglePassword()" 
          style="position:absolute; right:12px; top:50%; transform:translateY(-50%);
                 cursor:pointer; font-size:18px;">
        👁
    </span>
</div>


        <button type="submit" class="save-btn">Save Changes</button>

    </div>
</form>
</div>
<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    pwd.type = (pwd.type === "password") ? "text" : "password";
}
</script>


</body>
</html>
