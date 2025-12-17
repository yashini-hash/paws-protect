<?php
// Determine current page to auto-open submenu
$current_page = basename($_SERVER['PHP_SELF']);
$open_animal_menu = in_array($current_page, ['viewall.php','addanimal.php','updateanimal.php']) ? 'block' : 'none';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>User Dashboard</title>
<link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
<link rel="stylesheet" href="sidebar.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="sidebar" id="sidebar">
    <div class="logo">
        <img src="user profile" alt="profile">
        <h3>user name</h3>
    </div>

   <ul class="menu">
    <li><a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a></li>
    <li><a href="view_animals.php"><i class="fa fa-paw"></i> View Animal for Adopt</a></li>
    <li><a href="my_adopt.php"><i class="fa fa-heart"></i> My Adoptions</a></li>
     <li class="has-submenu" id="animalBtn">
    <a href="javascript:void(0);"><i class="fa-solid fa-paw"></i> Report Lost Animal</a>
            <ul class="submenu" id="animalSubMenu" style="display: <?= $open_animal_menu ?>;">
                <li><a href="report.php">Add Report</a></li>
                <li><a href="view_lost_animal.php">View </a></li>
                
            </ul>
    <li><a href="feedback.php"><i class="fa fa-comment"></i> Feedback</a></li>
    <li><a href="donations.php"><i class="fa fa-hand-holding-usd"></i> Donations</a></li>
    <li><a href="profile.php"><i class="fa fa-user"></i> Edit Profile</a></li>
    <li><a href="/paws&protect/home.php"><i class="fa fa-out"></i> Logout</a></li>
</ul>
</div>
 
<script>
// Hamburger toggle
const hamburger = document.getElementById("hamburger");
if (hamburger) {
    hamburger.addEventListener("click", function() {
        const sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("show");
    });
}

// Submenu toggle
const animalBtn = document.getElementById("animalBtn");
if (animalBtn) {
    animalBtn.addEventListener("click", function() {
        const subMenu = document.getElementById("animalSubMenu");
        subMenu.style.display = subMenu.style.display === "block" ? "none" : "block";
    });
}
</script>
</body>
</html>