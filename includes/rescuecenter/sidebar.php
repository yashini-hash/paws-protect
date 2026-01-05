<?php
include("../page/dbconnect.php");

// ---------------- Current page (submenu auto open & active) ----------------
$current_page = basename($_SERVER['PHP_SELF']);
$animal_pages = ['viewall.php','addanimal.php','updateanimal.php'];
$open_animal_menu = in_array($current_page, $animal_pages) ? 'block' : 'none';

// ---------------- Default values ----------------
$center_name = "Rescue Center";
$default_logo = "../uploads/rescue_logos/rescue-logo.png";
$center_logo = $default_logo;

// ---------------- Fetch rescue center details ----------------
if (isset($_SESSION['rescue_center_id'])) {
    $rescue_id = $_SESSION['rescue_center_id'];

    $stmt = $conn->prepare(
        "SELECT center_name, logo FROM rescue_center WHERE rescue_center_id = ?"
    );
    $stmt->bind_param("i", $rescue_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $center_name = $row['center_name'];

        // ✅ Check file exists before using
        if (!empty($row['logo']) && file_exists("../uploads/rescue_logos/" . $row['logo'])) {
            $center_logo = "../uploads/rescue_logos/" . $row['logo'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rescue Center Dashboard</title>

<link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
<link rel="stylesheet" href="dashboardstyle.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="sidebar" id="sidebar">

    <!-- ---------------- LOGO ---------------- -->
    <div class="logo">
        <img src="<?= htmlspecialchars($center_logo) ?>" alt="Rescue Logo">
        <h3><?= htmlspecialchars($center_name) ?></h3>
    </div>

    <!-- ---------------- MENU ---------------- -->
    <ul class="menu">

        <li class="<?= $current_page=='dashboard.php'?'active':'' ?>">
            <a href="dashboard.php">
                <i class="fa-solid fa-home"></i> Dashboard
            </a>
        </li>

        <li class="has-submenu <?= in_array($current_page,$animal_pages)?'active':'' ?>" id="animalBtn">
            <a href="javascript:void(0);">
                <i class="fa-solid fa-paw"></i> Animal Management
            </a>

            <ul class="submenu" id="animalSubMenu" style="display: <?= $open_animal_menu ?>;">
                <li><a href="viewall.php">View All</a></li>
                <li><a href="addanimal.php">Add New Animal</a></li>
                <li><a href="updateanimal.php">Update & Delete</a></li>
            </ul>
        </li>
<<<<<<< HEAD

        <li class="<?= $current_page=='adoption.php'?'active':'' ?>">
            <a href="adoption.php">
                <i class="fa-solid fa-users"></i> Adoption Request
            </a>
        </li>

        <li class="<?= $current_page=='rescue.php'?'active':'' ?>">
            <a href="rescue.php">
                <i class="fa-solid fa-truck-medical"></i> Rescue Operations
            </a>
        </li>

        <li class="<?= $current_page=='lost.php'?'active':'' ?>">
            <a href="lost.php">
                <i class="fa-solid fa-dog"></i> Lost Animal Details
            </a>
        </li>

        <li class="<?= $current_page=='feedback.php'?'active':'' ?>">
            <a href="feedback.php">
                <i class="fa-solid fa-comment-dots"></i> Feedback
            </a>
        </li>

        <li class="<?= $current_page=='profile.php'?'active':'' ?>">
            <a href="profile.php">
                <i class="fa-solid fa-user"></i> Edit Profile
            </a>
        </li>

        <li class="<?= $current_page=='staff.php'?'active':'' ?>">
            <a href="staff.php">
                <i class="fa-solid fa-user-tie"></i> Staff
            </a>
        </li>

        <li>
            <a href="/paws&protect/home.php">
                <i class="fa-solid fa-right-from-bracket"></i> Logout
            </a>
        </li>

=======
        <li><a href="adoption.php"><i class="fa-solid fa-users"></i> Adoption Request</a></li>
        <li><a href="rescue.php"><i class="fa-solid fa-truck-medical"></i> Rescue Operations</a></li>
        <li><a href="lost.php"><i class="fa-solid fa-dog"></i> Lost Animal Details</a></li>
        <li><a href="profile.php"><i class="fa-solid fa-user"></i> Edit Profile</a></li>
        <li><a href="staff.php"><i class="fa-solid fa-user-tie"></i> Staff</a></li>
          <li><a href="feedback.php"><i class="fa-solid fa-comment-dots"></i> Feedback</a></li>
         <li><a href="/paws&protect/home.php"><i class="fa fa-out"></i> Logout</a></li>
>>>>>>> feature-home
    </ul>
</div>

<!-- ---------------- SCRIPT ---------------- -->
<script>
const animalBtn = document.getElementById("animalBtn");
if (animalBtn) {
    animalBtn.addEventListener("click", function () {
        const subMenu = document.getElementById("animalSubMenu");
        subMenu.style.display = (subMenu.style.display === "block") ? "none" : "block";
    });
}
</script>

</body>
</html>
