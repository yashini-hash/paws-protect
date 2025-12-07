<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    echo "<p style='color:red;text-align:center;'>Unauthorized Access</p>";
    exit;
}

$rescue_center_id = $_SESSION['rescue_center_id'];

// ===========================
// HANDLE TYPE FILTER
// ===========================
$type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : "";

if (!empty($type_filter) && $type_filter != "all") {
    $sql = "SELECT * FROM animals_details WHERE rescue_center_id = ? AND type = ? ORDER BY animal_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $rescue_center_id, $type_filter);
} else {
    $sql = "SELECT * FROM animals_details WHERE rescue_center_id = ? ORDER BY animal_id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $rescue_center_id);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
<title>Your Rescue Animals</title>
<style>
body {
    background-color: #FFF8E7;
    font-family: Arial, sans-serif;
    margin-left: 260px; /* to leave space for sidebar */
    padding: 20px;
}

.page-title {
    text-align: center;
    color: #5C3A21;
    margin: 30px 0 15px;
}

/* ===== FILTER BAR ===== */
.filter-box {
    max-width: 500px;
    margin: 0 auto 25px;
    display: flex;
    justify-content: center;
    gap: 10px;
}

.filter-box select {
    flex: 1;
    padding: 8px;
    border-radius: 6px;
}

.filter-box button {
    padding: 8px 16px;
    background: #5C3A21;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.filter-box button:hover{
    background:#9d6e4c;
}

/* ===== CARD GRID ===== */
.card-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: center;
    align-items: flex-start;
}

/* ===== ANIMAL CARD ===== */
.animal-card {
    background: #ddbc8b;
    padding: 15px;
    border-radius: 15px;
    width: 250px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
   
}

.animal-card img {
    width: 220px;
    height: 220px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 6px;
}

.animal-card h3 {
     text-align: center;
    margin: 6px 0;
    color: #3e2c1c;
    font-size: 25px;
}

.animal-card p {
    margin: 4px 0;
    color: #5c3a21;
    font-size: 20x;
}

/* ===== STATUS BADGES ===== */
.status { 
    display: inline-block;
    margin-top: 12px; 
    padding: 6px 14px; 
    border-radius: 12px; 
    font-size: 14px; 
    font-weight: 600; 
    letter-spacing: 0.5px; 
} 

.available { 
    background-color: #2ea44aff; 
    color: #fafafaff; 
} 

.adopted { 
    color: #d1ecf1; 
    background: #278899ff;
} 

.not_available {
    color: #f8d7da;
    background: #e48891ff;
}

/* ===== EMPTY MESSAGE ===== */
.no-data {
    text-align: center;
    color: red;
    font-size: 18px;
    margin-top: 40px;
}
</style>
</head>
<body>

<h2 class="page-title">Your Rescue Animals</h2>

<!-- ===== FILTER FORM ===== -->
<form class="filter-box" method="GET">
    <select name="type_filter">
        <option value="all" <?= ($type_filter == 'all') ? 'selected' : '' ?>>All Types</option>
        <option value="Dog" <?= ($type_filter == 'Dog') ? 'selected' : '' ?>>Dog</option>
        <option value="Cat" <?= ($type_filter == 'Cat') ? 'selected' : '' ?>>Cat</option>
        <option value="Bird" <?= ($type_filter == 'Bird') ? 'selected' : '' ?>>Bird</option>
        <option value="Rabbit" <?= ($type_filter == 'Rabbit') ? 'selected' : '' ?>>Rabbit</option>
        <option value="Hamsters" <?= ($type_filter == 'Hamster') ? 'selected' : '' ?>>Hamsters</option>
    </select>

    <button type="submit">Search</button> 
</form>

<div class="card-grid">
<?php if ($result->num_rows > 0) { ?>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="animal-card">
            <img src="../uploads/<?php echo !empty($row['animal_image']) ? $row['animal_image'] : 'no-image.png'; ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><b>Type:</b> <?php echo $row['type']; ?></p>
            <p><b>Breed:</b> <?php echo $row['breed']; ?></p>
            <p><b>Gender:</b> <?php echo $row['gender']; ?></p>
            <p><b>Age:</b> <?php echo $row['age']; ?></p>
            <p><b>Health:</b> <?php echo $row['health']; ?></p>
            <p><b>Location:</b> <?php echo $row['location']; ?></p>
            <p class="status <?php echo $row['adoption_status']; ?>">
                <?php echo ucwords(str_replace("_", " ", $row['adoption_status'])); ?>
            </p>
        </div>
    <?php } ?>
<?php } else { ?>
    <p class="no-data">No animals found.</p>
<?php } ?>
</div>

</body>
</html>
