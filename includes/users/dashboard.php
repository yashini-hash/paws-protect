<?php
include("sidebar.php");
include("../page/dbconnect.php");

$user_id = $_SESSION['user_id'];

$sql = "SELECT name, profile_image FROM users WHERE user_id='$user_id'";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);
$profile_img = !empty($user['profile_image'])
    ? "../uploads/profiles/" . $user['profile_image']
    : "../uploads/profiles/default.png";

$donation_sql = "SELECT rescue_center, amount, payment_status, donated_at
                 FROM donations
                 WHERE user_id='$user_id'
                 ORDER BY donated_at DESC LIMIT 3";
$donation_result = mysqli_query($conn, $donation_sql);


$total_donations_sql = "SELECT COUNT(*) as count, SUM(amount) as total FROM donations WHERE user_id='$user_id'";
$total_donations = mysqli_fetch_assoc(mysqli_query($conn, $total_donations_sql));


$adoption_sql = "SELECT a.animal_id, a.name AS animal_name, 
                        a.animal_image AS animal_image, r.status 
                 FROM adopt_requests r
                 JOIN animals_details a ON r.animal_id = a.animal_id
                 WHERE r.user_id='$user_id'
                 AND r.status IN ('pending', 'approved')
                 ORDER BY r.request_date DESC 
                 LIMIT 3";
$adoption_result = mysqli_query($conn, $adoption_sql);


$total_adoptions_sql = "SELECT COUNT(*) as count FROM adopt_requests WHERE user_id='$user_id'";
$total_adoptions = mysqli_fetch_assoc(mysqli_query($conn, $total_adoptions_sql));


$lost_sql = "SELECT animal_type, breed, image, status 
             FROM lost_animals 
             WHERE user_id='$user_id'
             ORDER BY created_at DESC LIMIT 3";
$lost_result = mysqli_query($conn, $lost_sql);


$total_lost_sql = "SELECT COUNT(*) as count FROM lost_animals WHERE user_id='$user_id'";
$total_lost = mysqli_fetch_assoc(mysqli_query($conn, $total_lost_sql));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Paws & Protect Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
     <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="welcome-box">
    <img src="<?php echo $profile_img; ?>" alt="Profile">
    <div>
        <h2>Welcome back, <?php echo htmlspecialchars($user['name']); ?> 👋</h2>
        <p>Paws & Protect Dashboard</p>
    </div>
</div>

<div class="dashboard">

    <div class="card">
        <h3>🐾 My Adoptions</h3>
        <?php while($adoption = mysqli_fetch_assoc($adoption_result)) { ?>
            <div class="item">
                <img src="<?php echo !empty($adoption['animal_image']) ? '../uploads/addanimal/'.$adoption['animal_image'] : '../uploads/animals/default.png'; ?>" alt="Animal">
                <div>
                    <strong><?php echo htmlspecialchars($adoption['animal_name']); ?></strong><br>
                    <small>Status: <?php echo ucfirst($adoption['status']); ?></small>
                    
                </div>
                 <a href="my_adopt.php" class="btn">View </a>
            </div>
        <?php } ?>
        <div class="total">Total Adoption Requests: <?php echo $total_adoptions['count']; ?> Adoption(s)</div>
    </div>

    <div class="card">
        <h3>🐶 Your Lost Animals</h3>
        <?php while($lost = mysqli_fetch_assoc($lost_result)) { ?>
            <div class="item">
                <img src="<?php echo !empty($lost['image']) ? '../uploads/lost/'.$lost['image'] : '../uploads/lost/default.png'; ?>" alt="Lost Animal">
                <div>
                    <strong><?php echo htmlspecialchars($lost['animal_type']); ?></strong><br>
                    <small><?php echo ucfirst($lost['status']); ?></small>
                </div>
                <a href="view_lost_animal.php" class="btn">View </a>
            </div>
        <?php } ?>
        <div class="total">Total: <?php echo $total_lost['count']; ?> Lost Animal(s)</div>
    </div>

    <div class="card">
        <h3>💰 Recent Donations</h3>
        <?php while($donation = mysqli_fetch_assoc($donation_result)) { ?>
            <div class="item">
                <div>
                    <strong><?php echo htmlspecialchars($donation['rescue_center']); ?></strong><br>
                    <small>LKR <?php echo number_format($donation['amount'],2); ?></small>
                </div>
            </div>
        <?php } ?>
        <div class="total">Total Donations: LKR <?php echo number_format($total_donations['total'] ?? 0,2); ?></div>
    </div>

    <div class="card">
        <h3>🔍 Other Lost Animals</h3>
        <p>View animals reported by others</p>
        <a href="/paws&protect/includes/page/lost.php" class="btn">View All</a>
    </div>

</div>

</body>
</html>
