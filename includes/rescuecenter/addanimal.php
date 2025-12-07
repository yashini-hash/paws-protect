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

// ✅ FORM SUBMISSION
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

    // ✅ IMAGE UPLOAD
    $image_name = NULL;

    if (!empty($_FILES['animal_image']['name'])) {
        $target_dir = "../uploads/";

        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_tmp  = $_FILES["animal_image"]["tmp_name"];
        $image_name = time() . "_" . basename($_FILES["animal_image"]["name"]);
        $target_file = $target_dir . $image_name;

        // ✅ Validate file type
        $allowed_types = ['jpg','jpeg','png','gif'];
        $file_ext = strtolower(pathinfo($image_name, PATHINFO_EXTENSION));

        if(in_array($file_ext, $allowed_types)){
            move_uploaded_file($image_tmp, $target_file);
        } else {
            $errorMsg = "❌ Invalid image type. Only JPG, PNG, GIF allowed.";
        }
    }

    if(empty($errorMsg)){
        // ✅ SECURE INSERT USING PREPARED STATEMENT
        $sql = "INSERT INTO animals_details 
        (name, type, breed, gender, age, health, vaccination, rescue_date, adoption_status, animal_image, location, rescue_center_id) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "ssssi sssssi", 
            $name, $type, $breed, $gender, $age, $health, $vacc, $date, $status, $image_name, $location, $rescue_center_id
        );

        if($stmt->execute()){
            $successMsg = "✅ Animal Added Successfully!";
        } else {
            $errorMsg = "❌ Database Error: " . $conn->error;
        }
    }
}
?>

<!-- ✅ ADD ANIMAL FORM -->
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #FFF8E7;
    padding:50px;
    margin-left:120px;
}

.container {
    width: 100%;
    max-width: 650px;
    background: white;
    margin: 50px auto;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}

h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #5C3A21;
}

form {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

input, select {
    padding: 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    font-size: 15px;
    width: 100%;
}

.full {
    grid-column: 1 / 3;
}

button {
    grid-column: 1 / 3;
    padding: 12px;
    background: #5C3A21;
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    cursor: pointer;
}

button:hover {
    background:#9d6e4c;
}

/* ✅ SUCCESS & ERROR MESSAGE STYLE */
.success-msg {
    grid-column: 1 / 3;
    background: #d4edda;
    color: #155724;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
}

.error-msg {
    grid-column: 1 / 3;
    background: #f8d7da;
    color: #721c24;
    padding: 12px;
    border-radius: 6px;
    text-align: center;
    font-weight: bold;
}
</style>

<div class="container">
    <h2>Add New Animal</h2>

    <form method="POST" enctype="multipart/form-data" action="addanimal.php">

        <!-- ✅ MESSAGE OUTPUT -->
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

        <input type="number" name="age" placeholder="Age" required>

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

        <button type="submit">Add Animal</button>

    </form>
</div>
