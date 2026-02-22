<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");


$totalUsers = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];

$totalStaff = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff")
)['total'];

$totalAnimals = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM animals_details")
)['total'];

$totalRescue = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM rescue_requests")
)['total'];

$successRescue = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM rescue_requests WHERE status='Completed'")
)['total'];

$rescueRate = ($totalRescue > 0) 
    ? round(($successRescue / $totalRescue) * 100, 2) 
    : 0;


$totalAdoptReq = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM adopt_requests")
)['total'];

$approvedAdopt = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM adopt_requests WHERE status='Approved'")
)['total'];


$monthlyData = mysqli_query($conn, "
    SELECT 
        MONTH(request_date) AS month,
        COUNT(*) AS total
    FROM rescue_requests
    WHERE YEAR(request_date) = YEAR(CURDATE())
    GROUP BY MONTH(request_date)
");

$months = [
    1=>"January", 2=>"February", 3=>"March", 4=>"April",
    5=>"May", 6=>"June", 7=>"July", 8=>"August",
    9=>"September", 10=>"October", 11=>"November", 12=>"December"
];
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin | Reports & Analytics</title>
<link rel="stylesheet" href="sidebar.css">
<link rel="stylesheet" href="generatereport.css">
</head>

<body>
<div class="main-content">

<h2><i class="fa-solid fa-file-lines"></i> Reports & Analytics</h2>

<div class="center-btn">
    <a href="download_report.php" class="btn-download">
        <i class="fa-solid fa-download"></i> Download Report
    </a>
</div>

<div class="report-box">
<h3>Complete System Report</h3>

<table class="report-table">
<tr>
    <th>Report Category</th>
    <th>Metric</th>
    <th>Value</th>
</tr>

<tr>
    <td rowspan="3"><b>System Usage</b></td>
    <td>Total Users</td>
    <td><?= $totalUsers ?></td>
</tr>
<tr>
    <td>Total Staff</td>
    <td><?= $totalStaff ?></td>
</tr>
<tr>
    <td>Animals Registered</td>
    <td><?= $totalAnimals ?></td>
</tr>

<tr>
    <td rowspan="3"><b>Rescue Statistics</b></td>
    <td>Total Rescue Requests</td>
    <td><?= $totalRescue ?></td>
</tr>
<tr>
    <td>Completed Rescues</td>
    <td><?= $successRescue ?></td>
</tr>
<tr>
    <td>Success Rate (%)</td>
    <td><?= $rescueRate ?>%</td>
</tr>

<tr>
    <td rowspan="2"><b>Adoption Statistics</b></td>
    <td>Total Adoption Requests</td>
    <td><?= $totalAdoptReq ?></td>
</tr>
<tr>
    <td>Approved Adoptions</td>
    <td><?= $approvedAdopt ?></td>
</tr>

</table>
</div>

</div>

</div>
</body>
</html>
