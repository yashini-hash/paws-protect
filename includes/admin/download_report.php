<?php
include("../page/dbconnect.php");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="system_report.csv"');

$output = fopen("php://output", "w");

// Header row
fputcsv($output, ["Report Type", "Value"]);

// System usage
$totalUsers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM users"))['total'];
$totalStaff = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM staff"))['total'];
$totalAnimals = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM animals_details"))['total'];

fputcsv($output, ["Total Users", $totalUsers]);
fputcsv($output, ["Total Staff", $totalStaff]);
fputcsv($output, ["Animals Registered", $totalAnimals]);

// Rescue stats
$totalRescue = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM rescue_requests"))['total'];
$completed = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM rescue_requests WHERE status='Completed'"))['total'];

fputcsv($output, ["Total Rescue Requests", $totalRescue]);
fputcsv($output, ["Completed Rescues", $completed]);

// Adoption stats
$totalAdopt = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM adopt_requests"))['total'];
$approved = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS total FROM adopt_requests WHERE status='Approved'"))['total'];

fputcsv($output, ["Total Adoption Requests", $totalAdopt]);
fputcsv($output, ["Approved Adoptions", $approved]);

fclose($output);
exit;
