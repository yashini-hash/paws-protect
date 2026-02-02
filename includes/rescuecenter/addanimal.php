<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$rescue_center_id = $_SESSION['rescue_center_id'];
$successMsg = "";
$errorMsg = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = $_POST['name'];
    $type     = $_POST['type'];
    $breed    = $_POST['breed'];
    $gender   = $_POST['gender'];
    $age      = $_POST['age'];
    $health   = $_POST['health'];
    $vacc     = $_POST['vaccination'];
    $date     = $_POST['rescue_date'];
    $status   = $_POST['adoption_status'];
    $location = $_POST['location'];
     $details  = $_POST['details'];
  
    $image_name = NULL;

    if (!empty($_FILES['animal_image']['name'])) {
        $target_dir = "../uploads/addanimal/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_tmp  = $_FILES["animal_image"]["tmp_name"];
        $image_name = time() . "_" . basename($_FILES["animal_image"]["name"]);
        $target_file = $target_dir . $image_name;

       
        $allowed_types = ['jpg','jpeg','png','gif'];
        $file_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if(in_array($file_ext, $allowed_types)){
            move_uploaded_file($image_tmp, $target_file);
        } else {
            $errorMsg = "❌ Invalid image type. Only JPG, PNG, GIF allowed.";
        }
    }

   if(empty($errorMsg)){
        $sql = "INSERT INTO animals_details 
        (name, type, breed, gender, age, health, vaccination, rescue_date, adoption_status, animal_image, location, details, rescue_center_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssssssssssi",
            $name, $type, $breed, $gender, $age, $health, $vacc, $date,
            $status, $image_name, $location, $details, $rescue_center_id
        );

        if($stmt->execute()){
            $successMsg = "✅ Animal Added Successfully!";
        } else {
            $errorMsg = "❌ Database Error: " . $conn->error;
        }
    }
}
?>


 <link rel="stylesheet" href="addanimal.css">
<div class="container">
    <h2>Add New Animal</h2>

    <form method="POST" enctype="multipart/form-data" action="addanimal.php">

        
        <?php if (!empty($successMsg)) { ?>
            <div class="success-msg"><?php echo $successMsg; ?></div>
        <?php } ?>

        <?php if (!empty($errorMsg)) { ?>
            <div class="error-msg"><?php echo $errorMsg; ?></div>
        <?php } ?>

        <input type="text" name="name" placeholder="Animal Name" required>

        <select name="type" required>
            <option value="">Select Type</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Bird">Bird</option>
            <option value="Rabbit">Rabbit</option>
            <option value="Hamsters">Hamstes</option>
        </select>

        <input type="text" name="breed" placeholder="Breed">

        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        <input type="text" name="age" placeholder="Age" required>

        <select name="health">
            <option value="">Health Status</option>
            <option value="Healthy">Healthy</option>
            <option value="Injured">Injured</option>
            <option value="Recovering">Recovering</option>
        </select>

        <input type="text" name="vaccination" placeholder="Vaccination Details">

        <input type="date" name="rescue_date" required>

        <select name="adoption_status" class="full">
            <option value="available">Available</option>
            <option value="adopted">Adopted</option>
            <option value="not_available">Not Available</option>
        </select>

        <input type="file" name="animal_image" class="full" accept="image/*">

        <input type="text" name="location" placeholder="Rescue Location" required class="full">
        <textarea name="details" class="full" rows="4" placeholder="Enter animal details..." required></textarea>


        <button type="submit">Add Animal</button>

    </form>
</div>
