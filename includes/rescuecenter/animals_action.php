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

if(isset($_POST['update_id'])){

    $id = intval($_POST['update_id']);
    $name = trim($_POST['name']);
    $type = trim($_POST['type']);
    $breed = trim($_POST['breed']);
    $gender = trim($_POST['gender']);
    $age = trim($_POST['age']);   
    $health = trim($_POST['health']);
    $vacc = trim($_POST['vaccination']);
    $date = trim($_POST['rescue_date']);
    $status = trim($_POST['adoption_status']);
    $location = trim($_POST['location']);
    $details = trim($_POST['details']); 

    $oldQ = $conn->prepare("SELECT animal_image FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
    $oldQ->bind_param("ii", $id, $rescue_center_id);
    $oldQ->execute();
    $oldImg = $oldQ->get_result()->fetch_assoc()['animal_image'];
    $newImage = $oldImg;

    if(!empty($_FILES['animal_image']['name'])){
        $image_name = time() . "_" . basename($_FILES['animal_image']['name']);
        $targetPath = "../uploads/" . $image_name;

        $allowed_types = ['jpg','jpeg','png','gif'];
        $file_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if(!in_array($file_ext, $allowed_types)){
            echo "error_upload"; exit;
        }

        if(move_uploaded_file($_FILES['animal_image']['tmp_name'], $targetPath)){
            if(!empty($oldImg) && file_exists("../uploads/".$oldImg)){
                unlink("../uploads/".$oldImg);
            }
            $newImage = $image_name;
        } else {
            echo "error_upload"; exit;
        }
    }

    $stmt = $conn->prepare("
        UPDATE animals_details SET 
            name=?, type=?, breed=?, gender=?, age=?, health=?, vaccination=?, 
            rescue_date=?, adoption_status=?, location=?, details=?, animal_image=? 
        WHERE animal_id=? AND rescue_center_id=?
    ");

    $stmt->bind_param(
        "ssssssssssssii",
        $name, $type, $breed, $gender, $age, $health, $vacc,
        $date, $status, $location, $details, $newImage,
        $id, $rescue_center_id
    );

    echo $stmt->execute() ? "updated" : "error";
    exit;
}

http_response_code(400);
exit("No action specified");
?>
