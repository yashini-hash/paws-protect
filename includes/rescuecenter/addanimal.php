
<?php
session_start();
include("../page/dbconnect.php");



$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name   = $_POST['name'];
    $type   = $_POST['type'];
    $breed  = $_POST['breed'];
    $gender = $_POST['gender'];
    $age    = $_POST['age'];
    $health = $_POST['health'];
    $vaccination = $_POST['vaccination'];
    $rescue_date = $_POST['rescue_date'];
    $location = $_POST['location'];
    $rescue_center_id = $_SESSION['rescue_center_id'];

    // ✅ Image Upload
    $image_name = null;
    if (!empty($_FILES["animal_image"]["name"])) {
        $target_dir = "uploads/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $image_name = time() . "_" . basename($_FILES["animal_image"]["name"]);
        $target_file = $target_dir . $image_name;

        move_uploaded_file($_FILES["animal_image"]["tmp_name"], $target_file);
    }

    // ✅ Insert Query
    $sql = "INSERT INTO animals_details 
    (name, type, breed, gender, age, health, vaccination, rescue_date, location, animal_image, rescue_center_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param(
        "ssssisssssi",
        $name,
        $type,
        $breed,
        $gender,
        $age,
        $health,
        $vaccination,
        $rescue_date,
        $location,
        $image_name,
        $rescue_center_id
    );

    if ($stmt->execute()) {
        $message = "✅ Animal added successfully!";
    } else {
        $message = "❌ Error adding animal.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add New Animal</title>
<style>
body {
    margin: 0;
    font-family: Arial, sans-serif;
    background: #FFF8E7;
}
 .main {
        background: #FFF8E7;
    }
.container {
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

.message {
    text-align: center;
    margin-bottom: 15px;
    font-weight: bold;
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
    background: #3E2723;
}
</style>
</head>

<body>

<div class="container">
    <h2>Add New Animal</h2>

    <?php if ($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">

        <input type="text" name="name" placeholder="Animal Name" required>
        <select name="type" required>
            <option value="">Select Type</option>
            <option>Dog</option>
            <option>Cat</option>
            <option>Bird</option>
            <option>Rabbit</option>
            <option>Hamsters</option>
        </select>

        <input type="text" name="breed" placeholder="Breed (optional)">
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
        </select>

        <input type="number" name="age" placeholder="Age" required>

        <select name="health">
            <option value="">Health Status</option>
            <option>Healthy</option>
            <option>Injured</option>
            <option>Recovering</option>
        </select>

        <input type="text" name="vaccination" placeholder="Vaccination (optional)">
        <input type="date" name="rescue_date" required>

        <input type="text" name="location" placeholder="Location" class="full" required>

        <input type="file" name="animal_image" class="full">

        <button type="submit">Add Animal</button>

    </form>
</div>

</body>
</html>
