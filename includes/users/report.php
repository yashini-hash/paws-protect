<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['user_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$user_id = $_SESSION['user_id'];

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {

    $animal_type = $_POST['animal_type'];
    $breed = !empty($_POST['breed']) ? $_POST['breed'] : NULL;
    $color = $_POST['color'];
    $lost_location = $_POST['lost_location'];
    $lost_date = $_POST['lost_date'];
    $owner_name = $_POST['owner_name'];
    $contact_number = $_POST['contact_number'];
    $status = "notfound";

    $image = NULL;
    if (!empty($_FILES['image']['name'])) {
        $image = time() . "_" . basename($_FILES['image']['name']);
        $target = "../uploads/lost/" . $image;

        if (!is_dir("../uploads/lost")) {
            mkdir("../uploads/lost", 0777, true);
        }

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $error = "Failed to upload image!";
        }
    } else {
        $error = "Please upload an image!";
    }

    if (empty($error)) {
        $sql = "INSERT INTO lost_animals 
                (user_id, animal_type, breed, color, lost_location, lost_date, owner_name, contact_number, status, image)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "isssssssss",
            $user_id, $animal_type, $breed, $color, $lost_location, $lost_date,
            $owner_name, $contact_number, $status, $image
        );

        if ($stmt->execute()) {
            $success = "Lost animal details added successfully!";
        } else {
            $error = "Something went wrong: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Report Lost Animal</title>
        <link rel="stylesheet" href="report.css">

</head>
<body>

<div class="main-container">
    <div class="details-card">

        <h1 style="text-align:center; color:#4b2e1e;">Report Lost Animal</h1>

        <?php if ($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if ($error): ?>
            <div class="alert alert-error"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="section-title">Animal Information</div>

            <label>Animal Type</label>
            <select name="animal_type" required>
                <option value="">Select type</option>
                <option>Dog</option>
                <option>Cat</option>
                <option>Bird</option>
                <option>Other</option>
            </select>

            <label>Breed (Optional)</label>
            <input type="text" name="breed">

            <label>Animal Image</label>
            <input type="file" name="image" required>

            <label>Color / Description</label>
            <input type="text" name="color" required>

            <label>Lost Location</label>
            <input type="text" name="lost_location" required>

            <label>Lost Date</label>
            <input type="date" name="lost_date" required>

            <div class="section-title">Owner Information</div>

            <label>Owner Name</label>
            <input type="text" name="owner_name" required>

            <label>Contact Number</label>
            <input type="text" name="contact_number" required>

            <button type="submit" class="submit-btn">Submit</button>
        </form>

    </div>
</div>

</body>
</html>
