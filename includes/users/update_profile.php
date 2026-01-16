<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'];

$name     = $_POST['name'];
$phone    = $_POST['phone'];
$password = $_POST['password'];

$sql = "UPDATE users SET 
        name='$name',
        phone='$phone'";

if (!empty($_FILES['profile_image']['name'])) {
    $image = time() . "_" . $_FILES['profile_image']['name'];
    $path = "../uploads/profiles/" . $image;
    move_uploaded_file($_FILES['profile_image']['tmp_name'], $path);

    $sql .= ", profile_image='$image'";
}

if (!empty($password)) {
    $hashed = password_hash($password, PASSWORD_DEFAULT);
    $sql .= ", password='$hashed'";
}

$sql .= " WHERE user_id='$user_id'";

mysqli_query($conn, $sql);

header("Location: edit_profile.php?success=1");
exit;
?>
