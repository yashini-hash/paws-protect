<?php
include("sidebar.php"); 
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    header("Location: login.php");
    exit();
}

$rescue_center_id = $_SESSION['rescue_center_id'];

// Fetch Rescue Center Info
$sql = "SELECT * FROM rescue_center WHERE rescue_center_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// Profile image
$profile_img = !empty($user['logo'])
    ? "../uploads/rescue_logos/" . $user['logo']
    : "../uploads/rescue_logos/default.png";

// Define the rescue center location for lost animals
$rescue_location = $user['district']; // assuming lost_animals.lost_location stores district

// Total Animals
$sql_animals = "SELECT COUNT(*) as total FROM animals_details WHERE rescue_center_id = ?";
$stmt_animals = $conn->prepare($sql_animals);
$stmt_animals->bind_param("i", $rescue_center_id);
$stmt_animals->execute();
$total_animals = $stmt_animals->get_result()->fetch_assoc()['total'];

// Total Rescue Operations
$sql_rescue = "SELECT COUNT(*) as total FROM rescue_requests WHERE rescue_center_id = ?";
$stmt_rescue = $conn->prepare($sql_rescue);
$stmt_rescue->bind_param("i", $rescue_center_id);
$stmt_rescue->execute();
$total_rescue = $stmt_rescue->get_result()->fetch_assoc()['total'];

// Pending Rescue Requests
$sql_pending_rescue = "SELECT COUNT(*) as total FROM rescue_requests WHERE rescue_center_id = ? AND status = 'Pending'";
$stmt_pending_rescue = $conn->prepare($sql_pending_rescue);
$stmt_pending_rescue->bind_param("i", $rescue_center_id);
$stmt_pending_rescue->execute();
$pending_rescue = $stmt_pending_rescue->get_result()->fetch_assoc()['total'];

// In Progress Rescue Requests
$sql_inprogress_rescue = "SELECT COUNT(*) as total FROM rescue_requests WHERE rescue_center_id = ? AND status = 'In Progress'";
$stmt_inprogress_rescue = $conn->prepare($sql_inprogress_rescue);
$stmt_inprogress_rescue->bind_param("i", $rescue_center_id);
$stmt_inprogress_rescue->execute();
$inprogress_rescue = $stmt_inprogress_rescue->get_result()->fetch_assoc()['total'];

// Total Approved Adoptions
$sql_adoptions = "SELECT COUNT(*) as total FROM adopt_requests WHERE rescue_center_id = ? AND status = 'approved'";
$stmt_adoptions = $conn->prepare($sql_adoptions);
$stmt_adoptions->bind_param("i", $rescue_center_id);
$stmt_adoptions->execute();
$total_adoptions = $stmt_adoptions->get_result()->fetch_assoc()['total'];

// Pending Adoptions
$sql_pending_adoptions = "SELECT COUNT(*) as total FROM adopt_requests WHERE rescue_center_id = ? AND status = 'pending'";
$stmt_pending_adoptions = $conn->prepare($sql_pending_adoptions);
$stmt_pending_adoptions->bind_param("i", $rescue_center_id);
$stmt_pending_adoptions->execute();
$pending_adoptions = $stmt_pending_adoptions->get_result()->fetch_assoc()['total'];

// Total Donations (Success)
$sql_donations = "SELECT SUM(amount) as total FROM donations WHERE rescue_center = ? AND payment_status = 'Success'";
$stmt_donations = $conn->prepare($sql_donations);
$stmt_donations->bind_param("s", $user['center_name']);
$stmt_donations->execute();
$total_donations = $stmt_donations->get_result()->fetch_assoc()['total'];
$total_donations = $total_donations ? $total_donations : 0;

// Lost Animals in the rescue center's district
$sql_lost_total = "SELECT COUNT(*) as total FROM lost_animals WHERE lost_location = ?";
$stmt_lost_total = $conn->prepare($sql_lost_total);
$stmt_lost_total->bind_param("s", $rescue_location);
$stmt_lost_total->execute();
$total_lost = $stmt_lost_total->get_result()->fetch_assoc()['total'];

// Lost Animals not found
$sql_lost_notfound = "SELECT COUNT(*) as total FROM lost_animals WHERE status='notfound' AND lost_location = ?";
$stmt_lost_notfound = $conn->prepare($sql_lost_notfound);
$stmt_lost_notfound->bind_param("s", $rescue_location);
$stmt_lost_notfound->execute();
$lost_notfound = $stmt_lost_notfound->get_result()->fetch_assoc()['total'];

// Lost Animals found
$sql_lost_found = "SELECT COUNT(*) as total FROM lost_animals WHERE status='found' AND lost_location = ?";
$stmt_lost_found = $conn->prepare($sql_lost_found);
$stmt_lost_found->bind_param("s", $rescue_location);
$stmt_lost_found->execute();
$lost_found = $stmt_lost_found->get_result()->fetch_assoc()['total'];

// Determine card colors
$rescue_card_class = "card";
if ($pending_rescue > 0) {
    $rescue_card_class .= " alert-red";
} elseif ($inprogress_rescue > 0) {
    $rescue_card_class .= " alert-yellow";
}

$lost_card_class = "card";
if ($lost_notfound > 0) {
    $lost_card_class .= " alert-red";
} elseif ($lost_found > 0) {
    $lost_card_class .= " alert-green";
}

$adoption_card_class = "card";
if ($pending_adoptions > 0) $adoption_card_class .= " alert-red";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Rescue Center Dashboard</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
.main { padding: 20px; }

/* Welcome Box */
.welcome-box {
    display: flex;
    align-items: center;
    gap: 18px;
    background: linear-gradient(135deg,#FFD8B4,#E6B48A);
    padding: 22px 28px;
    border-radius: 18px;
    box-shadow: 0 8px 18px rgba(0,0,0,.12);
    margin-bottom: 25px;
}
.welcome-box img { width: 65px; height: 65px; border-radius: 50%; border: 3px solid #fff; object-fit: cover; }
.welcome-box h2 { margin:0; font-size: 1.4rem; }
.welcome-box p { margin:3px 0 0; font-size:1rem; color:#333; }

/* Dashboard cards */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-top: 20px;
}
.card {
    height:180px;
    background: #ddbc8b;
    padding: 25px 20px;
    border-radius: 15px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    text-align: center;
    transition: 0.3s;
    cursor: pointer;
}
.card i { color:#3e2c1c; margin-bottom: 10px; }
.card h3 { margin:10px 0 5px; font-size:1.2rem; color:#3e2c1c; }
.card p { font-size:1.2rem; font-weight:bold; margin:0; color:#3e2c1c; }
.card small { display:block; font-size:0.9rem; margin-top:5px; color:#fff; }

/* Alert colors */
.alert-red { background: #e74c3c !important; color: #fff !important; }
.alert-yellow { background: #4abfd6 !important; color: #333 !important; }
.alert-green { background: #2ecc71 !important; color: #fff !important; }

.card-link { text-decoration: none; color: inherit; display: block; transition: transform 0.2s; }
.card-link:hover { transform: translateY(-5px); }

/* ---------------- Responsive for Mobile (≤768px) ---------------- */
@media screen and (max-width: 768px) {
    .dashboard-cards {
        grid-template-columns: 1fr; /* stack cards vertically */
        gap: 15px;
    }

   

    .card {
        height: auto;
        padding: 18px 15px;
    }

    .card h3 {
        font-size: 1.1rem;
    }

    .card p {
        font-size: 1rem;
    }

    .card small {
        font-size: 0.85rem;
    }
}


</style>
</head>
<body>

<div class="main">

    <!-- Welcome Box -->
    <div class="welcome-box">
        <img src="<?= htmlspecialchars($profile_img) ?>" alt="Profile">
        <div>
            <h2>Welcome back, <?= htmlspecialchars($user['center_name']) ?> 👋</h2>
            <p>Paws & Protect Dashboard</p>
        </div>
    </div>

    <!-- Dashboard Cards -->
    <div class="dashboard-cards">
        <!-- Animals -->
        <a href="viewall.php" class="card-link">
            <div class="card">
                <i class="fa-solid fa-paw fa-2x"></i>
                <h3>Total Animals</h3>
                <p><?= $total_animals ?></p>
            </div>
        </a>

        <!-- Rescue Operations -->
        <a href="rescue.php" class="card-link">
            <div class="<?= $rescue_card_class ?>">
                <i class="fa-solid fa-truck-medical fa-2x"></i>
                <h3>Total Rescue Operations</h3>
                <p><?= $total_rescue ?></p>
                <?php if ($pending_rescue > 0): ?>
                    <small><?= $pending_rescue ?> new rescue request(s)</small>
                <?php elseif ($inprogress_rescue > 0): ?>
                    <small><?= $inprogress_rescue ?> rescue(s) in progress</small>
                <?php endif; ?>
            </div>
        </a>

        <!-- Adoptions -->
        <a href="adoption.php" class="card-link">
            <div class="<?= $adoption_card_class ?>">
                <i class="fa-solid fa-hand-holding-heart fa-2x"></i>
                <h3>Total Adoptions</h3>
                <p><?= $total_adoptions ?></p>
                <?php if ($pending_adoptions > 0): ?>
                    <small><?= $pending_adoptions ?> pending adoption request(s)</small>
                <?php endif; ?>
            </div>
        </a>

        <!-- Donations -->
        <a href="donations.php" class="card-link">
            <div class="card">
                <i class="fa-solid fa-hand-holding-dollar fa-2x"></i>
                <h3>Total Donations</h3>
                <p>LKR <?= number_format($total_donations,2) ?></p>
            </div>
        </a>

        <!-- Lost Animals -->
        <a href="lost.php" class="card-link">
            <div class="<?= $lost_card_class ?>">
                <i class="fa-solid fa-search fa-2x"></i>
                <h3>Lost Animals</h3>
                <p><?= $total_lost ?></p>
                <?php if ($lost_notfound > 0): ?>
                    <small><?= $lost_notfound ?> not yet found</small>
                <?php elseif ($lost_found > 0): ?>
                    <small><?= $lost_found ?> found</small>
                <?php endif; ?>
            </div>
        </a>

    </div>

</div>
</body>
</html>
