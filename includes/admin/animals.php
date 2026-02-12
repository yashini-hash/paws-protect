<?php
include('auth.php'); 
include("sidebar.php");
include("../page/dbconnect.php");


$type_filter   = $_GET['type_filter'] ?? 'all';
$rescue_filter = $_GET['rescue_filter'] ?? 'all';

$sql = "SELECT * FROM animals_details WHERE 1=1";
$params = [];
$types  = "";

if($type_filter != 'all'){
    $sql .= " AND type=?";
    $params[] = $type_filter;
    $types .= "s";
}

if($rescue_filter != 'all'){
    $sql .= " AND location=?";
    $params[] = $rescue_filter;
    $types .= "s";
}

$sql .= " ORDER BY animal_id DESC";
$stmt = $conn->prepare($sql);

if(count($params) > 0){
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();


$types_list  = $conn->query("SELECT DISTINCT type FROM animals_details");
$rescue_list = $conn->query("SELECT DISTINCT location FROM animals_details");
?>

<!DOCTYPE html>
<html>
<head>
<title>Animals Dashboard - Admin</title>
<link rel="stylesheet" href="animals.css">
<meta name="viewport" content="width=device-width, initial-scale=1.0">


</head>

<body>

<h2 class="page-title">Animals</h2>


<form class="filter-box" method="GET">
    <select name="type_filter">
        <option value="all">All Animal Types</option>
        <?php while($t = $types_list->fetch_assoc()){ ?>
            <option value="<?= $t['type']; ?>" <?= ($type_filter==$t['type'])?'selected':''; ?>>
                <?= $t['type']; ?>
            </option>
        <?php } ?>
    </select>

    <select name="rescue_filter">
        <option value="all">All Rescue Centers</option>
        <?php while($r = $rescue_list->fetch_assoc()){ ?>
            <option value="<?= $r['location']; ?>" <?= ($rescue_filter==$r['location'])?'selected':''; ?>>
                <?= $r['location']; ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Filter</button>
</form>


<div class="card-grid">
<?php if($result->num_rows > 0){
while($row = $result->fetch_assoc()){ ?>
   <div class="animal-card">

    <img src="../uploads/addanimal/<?= htmlspecialchars($row['animal_image'] ?: 'no-image.png'); ?>" 
         alt="<?= htmlspecialchars($row['name']); ?>">

    <h3><?= htmlspecialchars($row['name']); ?></h3>

    <div class="animal-details">
        <p><span>Type:</span> <?= htmlspecialchars($row['type']); ?></p>
        <p><span>Breed:</span> <?= htmlspecialchars($row['breed']); ?></p>
        <p><span>Gender:</span> <?= htmlspecialchars($row['gender']); ?></p>
        <p><span>Age:</span> <?= htmlspecialchars($row['age']); ?></p>
        <p><span>Health:</span> <?= htmlspecialchars($row['health']); ?></p>
        <p><span>Vaccinated:</span> <?= htmlspecialchars($row['vaccination']); ?></p>
        <p><span>Rescue Date:</span> <?= htmlspecialchars($row['rescue_date']); ?></p>
        <p><span>Location:</span> <?= htmlspecialchars($row['location']); ?></p>
    </div>

   
    <span class="status <?= htmlspecialchars($row['adoption_status']); ?>">
        <?= ucwords(str_replace("_", " ", $row['adoption_status'])); ?>
    </span>

</div>

<?php }} else { ?>
    <p class="no-data">No animals found</p>
<?php } ?>
</div>

</body>
</html>
