
<?php
session_start();
include("sidebar.php"); 
include("../page/dbconnect.php"); 

if(!isset($_SESSION['user_id'])){
    
}

$user_id = $_SESSION['user_id'] ?? 0;

$type_filter = isset($_GET['type_filter']) ? $_GET['type_filter'] : "";
$age_filter = $_GET['age_filter'] ?? "all";
$location_filter = $_GET['location_filter'] ?? "all";

$sql = "SELECT * FROM animals_details WHERE 1=1"; 
$params = [];
$types = "";

if(!empty($type_filter) && $type_filter != "all"){
    $sql .= " AND type=?";
    $params[] = $type_filter;
    $types .= "s";
}

if(!empty($age_filter) && $age_filter != "all"){
    $sql .= " AND age=?";
    $params[] = $age_filter;
    $types .= "s";
}

if(!empty($location_filter) && $location_filter != "all"){
    $sql .= " AND location=?";
    $params[] = $location_filter;
    $types .= "s";
}

$sql .= " ORDER BY animal_id DESC";
$stmt = $conn->prepare($sql);

if(count($params) > 0){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Available Animals</title>
          <link rel="stylesheet" href="view_animal.css">

</head>
<body>

<h2 class="page-title">Animals Available for Adoption</h2>

<form class="filter-box" method="GET">
    <select name="type_filter">
        <option value="all" <?= ($type_filter == 'all') ? 'selected' : '' ?>>All Types</option>
        <option value="Dog" <?= ($type_filter == 'Dog') ? 'selected' : '' ?>>Dog</option>
        <option value="Cat" <?= ($type_filter == 'Cat') ? 'selected' : '' ?>>Cat</option>
        <option value="Bird" <?= ($type_filter == 'Bird') ? 'selected' : '' ?>>Bird</option>
        <option value="Rabbit" <?= ($type_filter == 'Rabbit') ? 'selected' : '' ?>>Rabbit</option>
        <option value="Hamsters" <?= ($type_filter == 'Hamsters') ? 'selected' : '' ?>>Hamsters</option>
    </select>
    <button type="submit">Search</button> 
</form>

<div class="card-grid">
<?php if($result->num_rows > 0){ ?>
    <?php while($row = $result->fetch_assoc()){ ?>
        <div class="animal-card">
            <img src="../uploads/addanimal/<?php echo !empty($row['animal_image']) ? $row['animal_image'] : 'no-image.png'; ?>" alt="<?php echo htmlspecialchars($row['name']); ?>">
            <h3><?php echo htmlspecialchars($row['name']); ?></h3>
            <p><b>Type:</b> <?php echo $row['type']; ?></p>
            <p><b>Gender:</b> <?php echo $row['gender']; ?></p>
            <p><b>Age:</b> <?php echo $row['age']; ?></p>
            <p><b>Health:</b> <?php echo $row['health']; ?></p>
            <p><b>Location:</b> <?php echo $row['location']; ?></p>
            <p class="status <?php echo $row['adoption_status']; ?>">
                <?php echo ucwords(str_replace("_", " ", $row['adoption_status'])); ?>
            </p>
            <?php if($row['adoption_status'] == 'available'){ ?>
                <a href="adopt_request.php?animal_id=<?php echo $row['animal_id']; ?>" class="adopt-btn">Adopt</a>
            <?php } else { ?>
                <span class="adopt-btn disabled">Not Available</span>
            <?php } ?>
        </div>
    <?php } ?>
<?php } else { ?>
    <p class="no-data">No animals found.</p>
<?php } ?>
</div>

</body>
</html>
