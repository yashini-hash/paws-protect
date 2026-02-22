<?php
include("auth.php");
include("sidebar.php");
include("../page/dbconnect.php");

$users = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM users WHERE role='user'"));
$animals = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM animals_details"));
$adoptions = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM adopt_requests"));
$centers = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rescue_center"));
$staff = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM staff"));
$donations = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(amount) AS total FROM donations WHERE payment_status='Success'"));
$lost = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM lost_animals"));
$rescue_requests = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM rescue_requests"));

$animalTypes = $animalTotals = [];
$q1 = mysqli_query($conn, "SELECT type, COUNT(*) total FROM animals_details GROUP BY type");
while ($r = mysqli_fetch_assoc($q1)) {
    $animalTypes[] = $r['type'];
    $animalTotals[] = $r['total'];
}

$donMonths = $donTotals = [];
$q4 = mysqli_query($conn,
    "SELECT MONTH(donated_at) month, SUM(amount) total
     FROM donations
     WHERE payment_status='Success'
     GROUP BY MONTH(donated_at)
     ORDER BY month"
);
while ($r = mysqli_fetch_assoc($q4)) {
    $donMonths[] = date("M", mktime(0,0,0,$r['month'],1));
    $donTotals[] = $r['total'];
}

$cards = [
    ['icon'=>'fa-users', 'label'=>'Users', 'count'=>$users['total'], 'class'=>'card'],
    ['icon'=>'fa-paw', 'label'=>'Animals', 'count'=>$animals['total'], 'class'=>'card'],
    ['icon'=>'fa-file', 'label'=>'Adoptions', 'count'=>$adoptions['total'], 'class'=>'card'],
    ['icon'=>'fa-home', 'label'=>'Centers', 'count'=>$centers['total'], 'class'=>'card'],
    ['icon'=>'fa-user-doctor', 'label'=>'Staff', 'count'=>$staff['total'], 'class'=>'card'],
    ['icon'=>'fa-hand-holding-dollar', 'label'=>'Donations', 'count'=>'Rs '.number_format($donations['total'] ?? 0,2), 'class'=>'card'],
    ['icon'=>'fa-search', 'label'=>'Lost', 'count'=>$lost['total'], 'class'=>'card'],
    ['icon'=>'fa-truck-medical', 'label'=>'Rescue', 'count'=>$rescue_requests['total'], 'class'=>'card'],
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard</title>
<link rel="stylesheet" href="dashboard.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>
<body>

<div class="main">

    <div class="welcome-box">
        <img src="../uploads/profiles/admin.png" alt="Admin">
        <div>
            <h2>Welcome back, Admin 👋</h2>
            <p>Paws & Protect Dashboard</p>
        </div>
    </div>

    <div class="dashboard-cards">
        <?php foreach($cards as $card): ?>
            <a href="#" class="card-link">
                <div class="<?= $card['class'] ?>">
                    <i class="fa-solid <?= $card['icon'] ?> fa-2x"></i>
                    <h3><?= $card['label'] ?></h3>
                    <p><?= $card['count'] ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

    <div class="chart-cards">
        <div class="chart-card">
            <h3>Animals by Type</h3>
            <canvas id="animalChart"></canvas>
        </div>
        <div class="chart-card">
            <h3>Donations by Month</h3>
            <canvas id="donationChart"></canvas>
        </div>
    </div>
</div>

<script>
new Chart(document.getElementById('animalChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($animalTypes) ?>,
        datasets: [{ label: 'Number of Animals', data: <?= json_encode($animalTotals) ?>, backgroundColor: '#9d6e4c' }]
    },
    options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
});

new Chart(document.getElementById('donationChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($donMonths) ?>,
        datasets: [{ label:'Donations (Rs)', data: <?= json_encode($donTotals) ?>, borderColor:'#4CAF50', fill:false, tension:0.3 }]
    },
    options: { responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
});
</script>

</body>
</html>