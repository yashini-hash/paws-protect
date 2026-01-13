<?php
include("sidebar.php"); 

if (!isset($_SESSION['rescue_center_id'])) {
    header("Location: login.php");
    exit();
}


$total_animals = 0;
$total_rescue = 0;
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


<!-- Main Content -->
<div class="main">
    <div class="topbar">
        <h2>Welcome to Rescue Center Dashboard!</h2>
        <i class="fas fa-bars hamburger" id="hamburger"></i>
    </div>
    
    <div class="content-area">
        <div class="dashboard-cards">
            <div class="card">
                <i class="fa-solid fa-paw fa-2x"></i>
                <h3>Total Animals</h3>
                <p><?= $total_animals ?></p>
            </div>

            <div class="card">
                <i class="fa-solid fa-truck-medical fa-2x"></i>
                <h3>Total Rescue Operations</h3>
                <p><?= $total_rescue ?></p>
            </div>
        </div>
    </div>
</div>



</body>
</html>

