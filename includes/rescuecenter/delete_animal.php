<?php
session_start();
include("../page/dbconnect.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (
    empty($_SESSION['user_id']) ||
    empty($_SESSION['role']) ||
    $_SESSION['role'] !== 'rescuecenter'
) {
    session_unset();
    session_destroy();
    header("Location: /paws&protect/includes/page/login.php");
    exit();
}

$rescue_center_id = $_SESSION['rescue_center_id'];

if(!isset($_GET['delete_id'])){
    http_response_code(400);
    exit("No animal ID provided");
}

$animal_id = intval($_GET['delete_id']);

$imgQ = $conn->prepare("SELECT animal_image FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
$imgQ->bind_param("ii", $animal_id, $rescue_center_id);
$imgQ->execute();
$imgRes = $imgQ->get_result()->fetch_assoc();

if(!empty($imgRes['animal_image'])){
    $filePath = "../uploads/" . $imgRes['animal_image'];
    if(file_exists($filePath)){
        unlink($filePath);
    }
}

$stmt = $conn->prepare("DELETE FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
$stmt->bind_param("ii", $animal_id, $rescue_center_id);

if($stmt->execute()){
    echo "deleted";
} else {
    http_response_code(500);
    echo "error";
}
?>
