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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #FFF8E7;
            padding: 20px;
        }

        h2 {
            text-align: center;
            margin: 20px 0;
            color: #5C3A21;
        }

        .profile-card {
            background: #ddbc8b;
            max-width: 500px;
            margin: auto;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .profile-img {
            display: block;
            margin: auto;
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #5C3A21;
        }

        label {
            font-weight: bold;
            color: #5C3A21;
            margin-top: 10px;
            display: block;
        }

        input[type="text"],
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 12px;
            border: none;
            outline: none;
        }

        input[type="file"] {
            margin-top: 8px;
        }

<<<<<<< HEAD
    .save-btn {
        width: 100%;
        margin-top: 20px;
<<<<<<< HEAD
        background-color:#5C3A21;
=======
        background-color: #00648bff;
>>>>>>> 904b504fb461b2172c8a1bb5a5fc4c82272666f6
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 25px;
        cursor: pointer;
        font-size: 15px;
    }

    .save-btn:hover {
<<<<<<< HEAD
        background-color:#9d6e4c;
=======
        background-color: #5C3A21;
>>>>>>> 904b504fb461b2172c8a1bb5a5fc4c82272666f6
    }

    .success-msg {
    max-width: 500px;
    margin: 15px auto;
    padding: 12px;
    background-color: #4CAF50;
    color: white;
    text-align: center;
    border-radius: 12px;
    font-weight: bold;
}

</style>
=======
        .save-btn {
            width: 100%;
            margin-top: 20px;
            background: #5C3A21;
            color: #fff;
            border: none;
            padding: 12px;
            border-radius: 25px;
            cursor: pointer;
            font-size: 15px;
        }

        .save-btn:hover {
           background:#9d6e4c; transform:scale(1.03); 
        }
>>>>>>> 1ab800698d03bef2318b883c2af780d64608c07d

        .success-msg {
            max-width: 500px;
            margin: 15px auto;
            padding: 12px;
            background-color: #4CAF50;
            color: white;
            text-align: center;
            border-radius: 12px;
            font-weight: bold;
        }
    </style>
</head>

<body>

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

<script>
function togglePassword() {
    const pwd = document.getElementById("password");
    pwd.type = (pwd.type === "password") ? "text" : "password";
}
</script>


</body>
</html>
