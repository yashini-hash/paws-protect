<?php
session_start();
include("../page/dbconnect.php");

// If user is not logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to adopt'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$animal_id = $_GET['animal_id'] ?? 0;

// Validate animal ID
if ($animal_id == 0) {
    echo "<script>alert('Invalid animal selected'); window.location='view_animals.php';</script>";
    exit;
}

// STEP 1: Check if user already sent a request for this animal
$check = "SELECT * FROM adopt_requests WHERE user_id = ? AND animal_id = ?";
$stmt_check = $conn->prepare($check);
$stmt_check->bind_param("ii", $user_id, $animal_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    // Show message and stay on the same page
    echo "<script>
            alert('You have already sent an adoption request for this animal.');
            window.history.back();
          </script>";
    exit;
}

// STEP 2: Get rescue center ID of this animal
$sql = "SELECT rescue_center_id FROM animals_details WHERE animal_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Animal not found'); window.history.back();</script>";
    exit;
}

$row = $result->fetch_assoc();
$rescue_center_id = $row['rescue_center_id'];

// STEP 3: Insert adoption request
$insert = "INSERT INTO adopt_requests (user_id, animal_id, rescue_center_id, status, request_date)
           VALUES (?, ?, ?, 'pending', NOW())";

$stmt2 = $conn->prepare($insert);
$stmt2->bind_param("iii", $user_id, $animal_id, $rescue_center_id);

if ($stmt2->execute()) {
    echo "<script>
            alert('Adoption request sent successfully!');
            window.history.back();
          </script>";
} else {
    echo "<script>
            alert('Error sending adoption request. Try again.');
            window.history.back();
          </script>";
}
?>
