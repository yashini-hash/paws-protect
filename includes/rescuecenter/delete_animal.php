<?php
session_start();
include("../page/dbconnect.php");

// Check if rescue center is logged in
if(!isset($_SESSION['rescue_center_id'])){
    http_response_code(401);
    exit("Unauthorized");
}

$rescue_center_id = $_SESSION['rescue_center_id'];

// Check if delete_id is passed
if(!isset($_GET['delete_id'])){
    http_response_code(400);
    exit("No animal ID provided");
}

$animal_id = intval($_GET['delete_id']);

// Fetch animal image
$imgQ = $conn->prepare("SELECT animal_image FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
$imgQ->bind_param("ii", $animal_id, $rescue_center_id);
$imgQ->execute();
$imgRes = $imgQ->get_result()->fetch_assoc();

// Delete image file if exists
if(!empty($imgRes['animal_image'])){
    $filePath = "../uploads/" . $imgRes['animal_image'];
    if(file_exists($filePath)){
        unlink($filePath);
    }
}

// Delete the animal record
$stmt = $conn->prepare("DELETE FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
$stmt->bind_param("ii", $animal_id, $rescue_center_id);

if($stmt->execute()){
    echo "deleted";
} else {
    http_response_code(500);
    echo "error";
}
?>
