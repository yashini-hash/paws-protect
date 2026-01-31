<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

$msg = "";

if (!isset($_SESSION['rescue_center_id'])) {
    header("Location: login.php");
    exit();
}

$rescue_id = (int)$_SESSION['rescue_center_id'];

$stmt_check = $conn->prepare("SELECT * FROM rescue_center WHERE rescue_center_id=?");
$stmt_check->bind_param("i", $rescue_id);
$stmt_check->execute();
$data = $stmt_check->get_result()->fetch_assoc();

if (!$data) {
    die("❌ Invalid session: Rescue center not found.");
}


if (isset($_POST['update_profile'])) {

    $center_name = trim($_POST['center_name']);
    $address = trim($_POST['address']);
    $district = trim($_POST['district']);
    $contact = trim($_POST['contact_number']);
    $latitude = !empty($_POST['latitude']) ? trim($_POST['latitude']) : NULL;
    $longitude = !empty($_POST['longitude']) ? trim($_POST['longitude']) : NULL;

  
    $logo_name = $data['logo'];

    
    if (!empty($_FILES['logo']['name'])) {

        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            $msg = "❌ Invalid file type. Allowed: jpg, jpeg, png, webp";
        } else {
            $new_logo = "rescue_" . $rescue_id . "_" . time() . "." . $ext;
            $upload_path = "../uploads/rescue_logos/" . $new_logo;

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                
                if (!empty($logo_name) && file_exists("../uploads/rescue_logos/" . $logo_name)) {
                    unlink("../uploads/rescue_logos/" . $logo_name);
                }
                $logo_name = $new_logo;
            } else {
                $msg = "❌ Failed to upload new logo.";
            }
        }
    }

   
    if (!$msg) {
        $update = $conn->prepare("
            UPDATE rescue_center
            SET center_name=?, address=?, district=?, contact_number=?, logo=?, latitude=?, longitude=?
            WHERE rescue_center_id=?
        ");
        $update->bind_param(
            "sssssddi",
            $center_name,
            $address,
            $district,
            $contact,
            $logo_name,
            $latitude,
            $longitude,
            $rescue_id
        );

        if ($update->execute()) {
            header("Location: profile.php?updated=1");
            exit();
        } else {
            $msg = "❌ Failed to update profile. Please try again.";
        }
    }
}



if (isset($_GET['updated'])) {
    $msg = "✅ Profile updated successfully";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>




<style>
:root{
    --brown:#6b4f3f;
    --brown-dark:#4a342e;
    --brown-light:#f6f1eb;
    --accent:#c19a6b;
}
body{
    background: #FFF8E7;
    font-family:'Segoe UI', Tahoma, sans-serif;
}
.profile-box{
    max-width:650px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:14px;
    box-shadow:0 15px 35px rgba(0,0,0,0.15);
    border-top:6px solid var(--brown);
}
.profile-box h2{
    text-align:center;
    color:var(--brown-dark);
    margin-bottom:25px;
}
.profile-box img{
    width:130px;
    height:130px;
    border-radius:50%;
    object-fit:cover;
    display:block;
    margin:10px auto 20px;
    border:4px solid var(--accent);
}
.profile-box label{
    font-weight:600;
    color:var(--brown-dark);
    display:block;
    margin-top:10px;
}
.profile-box input,
.profile-box textarea{
    width:100%;
    padding:12px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:8px;
    border:1px solid #d6c5b5;
    font-size:14px;
    transition:0.3s ease;
}
.profile-box input:focus,
.profile-box textarea:focus{
    outline:none;
    border-color:var(--brown);
    box-shadow:0 0 0 3px rgba(107,79,63,0.15);
}
.profile-box input[readonly]{
    background:#eee;
    cursor:not-allowed;
}
.profile-box input[type="file"]{
    padding:8px;
    background:#faf7f3;
}
.profile-box button{
    width:100%;
    background:linear-gradient(135deg,var(--brown),var(--brown-dark));
    color:#fff;
    border:none;
    padding:14px;
    font-size:16px;
    border-radius:10px;
    cursor:pointer;
    transition:0.3s;
}
.profile-box button:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(0,0,0,0.25);
}
.msg{
    background:#e8f5e9;
    color:#2e7d32;
    padding:12px;
    border-radius:8px;
    margin-bottom:15px;
    text-align:center;
    font-weight:600;
}
</style>
</head>
<body>



<div class="profile-box">

    <h2>Edit Rescue Center Profile</h2>

    <?php if ($msg): ?>
        <div class="msg"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <label>Current Logo</label>
        <img src="../uploads/rescue_logos/<?= htmlspecialchars($data['logo'] ?: 'rescue-logo.png') ?>">

        <label>Change Logo</label>
        <input type="file" name="logo" accept="image/*">

        <label>Center Name</label>
        <input type="text" name="center_name" value="<?= htmlspecialchars($data['center_name']) ?>" required>

        <label>Address</label>
        <textarea name="address" required><?= htmlspecialchars($data['address']) ?></textarea>

        <label>District</label>
        <input type="text" name="district" value="<?= htmlspecialchars($data['district']) ?>" required>

        <label>Contact Number</label>
        <input type="text" name="contact_number" value="<?= htmlspecialchars($data['contact_number']) ?>" required>

        <label>Latitude</label>
        <input type="text" name="latitude" value="<?= htmlspecialchars($data['latitude']) ?>" placeholder="e.g., 6.9271">

        <label>Longitude</label>
        <input type="text" name="longitude" value="<?= htmlspecialchars($data['longitude']) ?>" placeholder="e.g., 79.8612">

        <label>Email (readonly)</label>
        <input type="email" value="<?= htmlspecialchars($data['email']) ?>" readonly>

        <button type="submit" name="update_profile">Update Profile</button>

    </form>
</div>

</body>
</html>
