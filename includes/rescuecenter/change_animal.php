<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");
if(!isset($_SESSION['rescue_center_id'])) exit("Unauthorized");

$rescue_center_id = $_SESSION['rescue_center_id'];
$id = intval($_GET['id'] ?? 0);

// Fetch animal
$stmt = $conn->prepare("SELECT * FROM animals_details WHERE animal_id=? AND rescue_center_id=?");
$stmt->bind_param("ii",$id,$rescue_center_id);
$stmt->execute();
$animal = $stmt->get_result()->fetch_assoc();
if(!$animal) exit("Animal not found");

$types = ['Dog','Cat','Bird','Rabbit','Hamsters'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Update Animal</title>
<style>
body {
    background:#FFF8E7;
    font-family: Arial, sans-serif;
    margin-left:260px;
    padding:50px;
}
h2 { color:#5C3A21; text-align:center; margin-bottom:20px; }

form {
    background:#ddbc8b;
    padding:30px;
    border-radius:15px;
    max-width:500px;
    margin:auto;
    display:grid;
    gap:15px;
    box-shadow:0 4px 8px rgba(0,0,0,0.2);
}

form input, form select {
    padding:10px;
    border-radius:6px;
    border:1px solid #ccc;
    font-size:16px;
    width:100%;
    box-sizing:border-box;
}

form img {
    width:150px;
    height:150px;
    object-fit:cover;
    border-radius:10px;
    margin:auto;
    display:block;
}

form button {
    padding:12px;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    background:green;
    color:white;
}

form button:hover { opacity:0.9; }

#msg {
    text-align:center;
    font-weight:bold;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
    display:none;
}
#msg.success { background:#d4edda; color:#155724; }
#msg.error { background:#f8d7da; color:#721c24; }
</style>
</head>
<body>

<h2>Update Animal</h2>

<div id="msg"></div>

<form id="updateForm" enctype="multipart/form-data">
    <input type="hidden" name="update_id" value="<?= $animal['animal_id'] ?>">
    
    <label>Animal Image</label>
    <input type="file" name="animal_image">
    <?php if($animal['animal_image']): ?>
        <img src="../uploads/<?= $animal['animal_image'] ?>" alt="Current Image">
    <?php endif; ?>

    <label>Name</label>
    <input type="text" name="name" value="<?= htmlspecialchars($animal['name']) ?>" required>

    <label>Type</label>
    <select name="type" required>
        <?php foreach($types as $t): ?>
            <option value="<?= $t ?>" <?= $animal['type']==$t?"selected":"" ?>><?= $t ?></option>
        <?php endforeach; ?>
    </select>

    <label>Breed</label>
    <input type="text" name="breed" value="<?= htmlspecialchars($animal['breed']) ?>">

    <label>Gender</label>
    <select name="gender" required>
        <option value="male" <?= $animal['gender']=="male"?"selected":"" ?>>Male</option>
        <option value="female" <?= $animal['gender']=="female"?"selected":"" ?>>Female</option>
    </select>

    <label>Age</label>
    <input type="number" name="age" value="<?= $animal['age'] ?>" required>

    <label>Health</label>
    <select name="health">
        <option value="Healthy" <?= $animal['health']=="Healthy"?"selected":"" ?>>Healthy</option>
        <option value="Injured" <?= $animal['health']=="Injured"?"selected":"" ?>>Injured</option>
        <option value="Recovering" <?= $animal['health']=="Recovering"?"selected":"" ?>>Recovering</option>
    </select>

    <label>Vaccination</label>
    <input type="text" name="vaccination" value="<?= htmlspecialchars($animal['vaccination']) ?>">

    <label>Rescue Date</label>
    <input type="date" name="rescue_date" value="<?= $animal['rescue_date'] ?>" required>

    <label>Adoption Status</label>
    <select name="adoption_status">
        <option value="available" <?= $animal['adoption_status']=="available"?"selected":"" ?>>Available</option>
        <option value="adopted" <?= $animal['adoption_status']=="adopted"?"selected":"" ?>>Adopted</option>
        <option value="not_available" <?= $animal['adoption_status']=="not_available"?"selected":"" ?>>Not Available</option>
    </select>

    <label>Location</label>
    <input type="text" name="location" value="<?= htmlspecialchars($animal['location']) ?>">

    <button type="submit">Save Changes</button>
</form>

<script>
const form = document.getElementById('updateForm');
const msgDiv = document.getElementById('msg');

form.addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(form);

    fetch('animals_action.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(data => {
        data = data.trim();
        if(data === 'updated'){
            msgDiv.innerText = "Changes saved successfully!";
            msgDiv.className = "success";
            msgDiv.style.display = "block";
        } else if(data === 'error_upload'){
            msgDiv.innerText = "Error uploading image!";
            msgDiv.className = "error";
            msgDiv.style.display = "block";
        } else {
            msgDiv.innerText = "Update failed!";
            msgDiv.className = "error";
            msgDiv.style.display = "block";
        }
    })
    .catch(() => {
        msgDiv.innerText = "Server error!";
        msgDiv.className = "error";
        msgDiv.style.display = "block";
    });
});
</script>

</body>
</html>
