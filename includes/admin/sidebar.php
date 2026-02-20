<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

include("../page/dbconnect.php");

$current_page = basename($_SERVER['PHP_SELF']);

$rescue_pages = ['rescue_centers.php','rescue_new.php'];
$open_rescue_menu = in_array($current_page, $rescue_pages) ? 'block' : 'none';

$request_pages = ['all_request.php','pending_rescue.php','inprogress_rescue.php','complete_rescue.php'];
$open_request_menu = in_array($current_page, $request_pages) ? 'block' : 'none';

$lost_pages = ['lost_animals.php','found_animals.php'];
$open_lost_menu = in_array($current_page, $lost_pages) ? 'block' : 'none';

$adoptanimal_pages = [
    'view_all_adopt.php',
    'pending_adopt.php',
    'approved_adopt.php',
    'rejected_adopt.php'
];
$open_adopt_menu = in_array($current_page, $adoptanimal_pages) ? 'block' : 'none';

$admin_name = "Admin";
$admin_logo = "/paws&protect/includes/uploads/profiles/admin.png";

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $stmt = $conn->prepare("SELECT name FROM admin WHERE admin_id=?");
    $stmt->bind_param("i", $admin_id);
    $stmt->execute();
    if ($row = $stmt->get_result()->fetch_assoc()) {
        $admin_name = $row['name'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>

<link rel="stylesheet" href="sidebar.css">
<link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

<div class="hamburger" id="hamburger">
    <i class="fa-solid fa-bars"></i>
</div>

<div class="sidebar" id="sidebar">

    <div class="logo">
        <img src="<?= htmlspecialchars($admin_logo) ?>" alt="Admin">
        <h3><?= htmlspecialchars($admin_name) ?></h3>
    </div>

    <ul class="menu">

        <li class="<?= $current_page=='dashboard.php'?'active':'' ?>">
            <a href="dashboard.php"><i class="fa-solid fa-chart-line"></i> Dashboard</a>
        </li>

        <li class="<?= $current_page=='users.php'?'active':'' ?>">
            <a href="users.php"><i class="fa-solid fa-users"></i> Users</a>
        </li>

        <li class="has-submenu <?= in_array($current_page,$rescue_pages)?'active':'' ?>" id="rescueCenterBtn">
            <a href="#"><i class="fa-solid fa-hospital"></i> Rescue Centers</a>
            <ul class="submenu" id="rescueCenterMenu" style="display: <?= $open_rescue_menu ?>;">
                <li><a href="rescue_new.php">New</a></li>
                <li><a href="rescue_centers.php">View All</a></li>
            </ul>
        </li>

        <li class="has-submenu <?= in_array($current_page,$request_pages)?'active':'' ?>" id="rescueRequestBtn">
            <a href="#"><i class="fa-solid fa-truck-medical"></i> Rescue Requests</a>
            <ul class="submenu" id="rescueRequestMenu" style="display: <?= $open_request_menu ?>;">
                <li><a href="all_request.php">View All</a></li>
                <li><a href="pending_rescue.php">Pending</a></li>
                <li><a href="inprogress_rescue.php">In Progress</a></li>
                <li><a href="complete_rescue.php">Completed</a></li>
            </ul>
        </li>

        <li class="<?= $current_page=='animals.php'?'active':'' ?>">
            <a href="animals.php"><i class="fa-solid fa-paw"></i> Animals</a>
        </li>

        <li class="has-submenu <?= in_array($current_page,$lost_pages)?'active':'' ?>" id="lostBtn">
            <a href="#"><i class="fa-solid fa-dog"></i> Lost Animals</a>
            <ul class="submenu" id="lostMenu" style="display: <?= $open_lost_menu ?>;">
                <li><a href="lost_animals.php">View All Lost</a></li>
                <li><a href="found_animals.php">Found Animals</a></li>
            </ul>
        </li>

        <li class="has-submenu <?= in_array($current_page,$adoptanimal_pages)?'active':'' ?>" id="adoptBtn">
            <a href="#"><i class="fa-solid fa-heart"></i> Adoptions</a>
            <ul class="submenu" id="adoptanimalMenu" style="display: <?= $open_adopt_menu ?>;">
                <li><a href="view_all_adopt.php">View All</a></li>
                <li><a href="pending_adopt.php">Pending</a></li>
                <li><a href="approved_adopt.php">Approved</a></li>
                <li><a href="rejected_adopt.php">Rejected</a></li>
            </ul>
        </li>

        <li class="<?= $current_page=='donations.php'?'active':'' ?>">
            <a href="donations.php"><i class="fa-solid fa-hand-holding-dollar"></i> Donations</a>
        </li>

        <li class="<?= $current_page=='feedback.php'?'active':'' ?>">
            <a href="feedback.php"><i class="fa-solid fa-comment-dots"></i> Feedback</a>
        </li>

        <li class="<?= $current_page=='staff.php'?'active':'' ?>">
            <a href="staff.php"><i class="fa-solid fa-user-tie"></i> Staff</a>
        </li>

        <li class="<?= $current_page=='generatereport.php'?'active':'' ?>">
            <a href="generatereport.php"><i class="fa-solid fa-book"></i> Generate Report</a>
        </li>

        <li>
            <a href="/paws&protect/home.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
        </li>

    </ul>
</div>

<script>
const toggleMenu = (btnId, menuId) => {
    const btn = document.getElementById(btnId);
    if (!btn) return;
    btn.addEventListener("click", () => {
        const menu = document.getElementById(menuId);
        menu.style.display = menu.style.display === "block" ? "none" : "block";
    });
};

toggleMenu("rescueCenterBtn", "rescueCenterMenu");
toggleMenu("rescueRequestBtn", "rescueRequestMenu");
toggleMenu("lostBtn", "lostMenu");
toggleMenu("adoptBtn", "adoptanimalMenu");

document.getElementById("hamburger").addEventListener("click", () => {
    document.getElementById("sidebar").classList.toggle("show");
});
</script>

</body>
</html>
