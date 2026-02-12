<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role']) ||
    $_SESSION['role'] !== 'user'
) {
    session_unset();
    session_destroy();
    header("Location: /paws&protect/includes/page/login.php");
    exit();
}


include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, profile_image FROM users WHERE user_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

$profile_img = !empty($user['profile_image'])
    ? "../uploads/profiles/" . $user['profile_image']
    : "../uploads/profiles/default.png";

$current_page = basename($_SERVER['PHP_SELF']);
$open_animal_menu = in_array($current_page, ['report.php', 'view_lost_animal.php']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>

<link rel="icon" href="/paws&protect/includes/image/paw.png">
<link rel="stylesheet" href="sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="hamburger" id="hamburger">
    <i class="fa-solid fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">

    <div class="logo">
        <img src="<?= $profile_img ?>" class="sidebar-profile-img" alt="Profile">
        <h3><?= htmlspecialchars($user['name']) ?></h3>
    </div>

    <ul class="menu">

        <li>
            <a href="dashboard.php">
                <i class="fa-solid fa-house"></i> Dashboard
            </a>
        </li>

        <li>
            <a href="view_animals.php">
                <i class="fa-solid fa-paw"></i> View Animal for Adopt
            </a>
        </li>

        <li>
            <a href="my_adopt.php">
                <i class="fa-solid fa-heart"></i> My Adoptions
            </a>
        </li>

        <li class="has-submenu">
            <a href="#" id="animalBtn">
                <i class="fa-solid fa-paw"></i> Report Lost Animal
            </a>

            <ul class="submenu" id="animalSubMenu" <?= $open_animal_menu ? 'style="display:block"' : '' ?>>
                <li><a href="report.php">Add Report</a></li>
                <li><a href="view_lost_animal.php">View Reports</a></li>
            </ul>
        </li>

        <li>
            <a href="feedback.php">
                <i class="fa-solid fa-comment"></i> Feedback
            </a>
        </li>

        <li>
            <a href="donations.php">
                <i class="fa-solid fa-hand-holding-dollar"></i> Donations
            </a>
        </li>

        <li>
            <a href="edit_profile.php">
                <i class="fa-solid fa-user"></i> Edit Profile
            </a>
        </li>

        <li>
            <a href="/paws&protect/home.php">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </li>

    </ul>
</div>

<script>
const hamburger = document.getElementById("hamburger");
const sidebar = document.getElementById("sidebar");
const animalBtn = document.getElementById("animalBtn");
const animalSubMenu = document.getElementById("animalSubMenu");

hamburger.addEventListener("click", () => {
    sidebar.classList.toggle("show");
});

animalBtn.addEventListener("click", (e) => {
    e.preventDefault();
    animalSubMenu.style.display =
        animalSubMenu.style.display === "block" ? "none" : "block";
});

document.querySelectorAll(".menu a").forEach(link => {
    link.addEventListener("click", () => {
        if (window.innerWidth <= 768) {
            sidebar.classList.remove("show");
        }
    });
});
</script>

</body>
</html>
