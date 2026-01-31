<?php
session_start();
include("dbconnect.php"); 


$centers = [];
$q = "SELECT rescue_center_id, center_name, latitude, longitude FROM rescue_center";
$result = $conn->query($q);
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $centers[] = $row;
    }
} else {
    die("Error fetching rescue centers: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Protect | Rescue</title>
    <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
    <link rel="stylesheet" href="rescue.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">


</head>
<body>

<header>
    <div class="logo">
        <img src="/paws&protect/includes/image/paw.png" alt="Logo">
    </div>
    <nav class="nav-links">
        <li><a href="/paws&protect/home.php">HOME</a></li>
        <li><a href="/paws&protect/includes/page/about.php">ABOUT</a></li>
        <li><a href="adopt.php">ADOPT</a></li>
        <li><a href="rescue.php" class="active">RESCUE</a></li>
        <li><a href="donate.php">DONATE</a></li>
        <li><a href="lost.php">LOST</a></li>
    </nav>
    <div class="menu-toggle" id="menu-toggle">
        <i class="fa fa-bars"></i>
    </div>
</header>

<div class="mobile-nav" id="mobile-nav">
    <a href="/paws&protect/home.php">HOME</a>
    <a href="/paws&protect/includes/page/about.php">ABOUT</a>
    <a href="adopt.php">ADOPT</a>
    <a href="rescue.php">RESCUE</a>
    <a href="donate.php">DONATE</a>
    <a href="lost.php">LOST</a>
</div>

<script>
document.getElementById("menu-toggle").onclick = function() {
    document.getElementById("mobile-nav").classList.toggle("active");
};
</script>

<div class="hero">
    <img src="/paws&protect/includes/image/rescuehero.png" alt="Rescue Hero">
    <div class="hero-text">Rescue Animals</div>
</div>


<div class="rescue-form">
    <h2>🐾 Submit a Rescue Request</h2>

    <form action="rescue_submit.php" method="POST">
        <label>Animal Type</label>
        <select name="animal_type" required>
            <option value="">Select</option>
            <option>Dog</option>
            <option>Cat</option>
            <option>Bird</option>
            <option>Other</option>
        </select>

        <label>Description of Injury</label>
        <textarea name="description" rows="4" placeholder="Describe the injury..." required></textarea>

        <label>Contact Number</label>
        <input type="tel" name="contact_number" placeholder="Enter your Contact Number" required>

       
        <input type="hidden" name="lat" id="lat">
        <input type="hidden" name="lng" id="lng">

        <div id="map"></div>

        <button type="submit">Submit Rescue Request</button>
    </form>
    <?php if (isset($_SESSION['success_msg'])): ?>
<div class="message-box" id="successBox">
    <?= $_SESSION['success_msg']; ?>
    <br>
    <button onclick="closeMessage('successBox')">OK</button>
</div>
<?php unset($_SESSION['success_msg']); endif; ?>

<?php if (isset($_SESSION['error_msg'])): ?>
<div class="message-box error" id="errorBox">
    <?= $_SESSION['error_msg']; ?>
    <br>
    <button onclick="closeMessage('errorBox')">OK</button>
</div>
<?php unset($_SESSION['error_msg']); endif; ?>

<script>

window.onload = function() {
    const success = document.getElementById('successBox');
    const error = document.getElementById('errorBox');
    if(success) success.style.display = 'block';
    if(error) error.style.display = 'block';
}

function closeMessage(id) {
    const box = document.getElementById(id);
    if(box) box.style.display = 'none';
}

</script>
    

</div>

<script>
const rescueCenters = <?= json_encode($centers); ?>;

function getDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; 
    const dLat = (lat2 - lat1) * Math.PI / 180;
    const dLon = (lon2 - lon1) * Math.PI / 180;
    const a = Math.sin(dLat/2)**2 +
              Math.cos(lat1*Math.PI/180) * Math.cos(lat2*Math.PI/180) *
              Math.sin(dLon/2)**2;
    return R * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)));
}

function initMap() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(position => {
            const userLat = position.coords.latitude;
            const userLng = position.coords.longitude;

            document.getElementById("lat").value = userLat;
            document.getElementById("lng").value = userLng;

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: { lat: userLat, lng: userLng }
            });

            
            new google.maps.Marker({
                position: { lat: userLat, lng: userLng },
                map,
                label: "You"
            });

            let nearestCenter = null;
            let minDistance = Infinity;

            rescueCenters.forEach(center => {
                const distance = getDistance(userLat, userLng, center.latitude, center.longitude);
                if (distance < minDistance) {
                    minDistance = distance;
                    nearestCenter = center;
                }
                new google.maps.Marker({
                    position: { lat: parseFloat(center.latitude), lng: parseFloat(center.longitude) },
                    map,
                    title: center.center_name
                });
            });

           
            if (nearestCenter) {
                new google.maps.Marker({
                    position: { lat: parseFloat(nearestCenter.latitude), lng: parseFloat(nearestCenter.longitude) },
                    map,
                    icon: "http://maps.google.com/mapfiles/ms/icons/green-dot.png",
                    title: "Nearest Rescue Center"
                });
            }
        });
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}
</script>


<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvmv0SLEBxBzt4EFJ9VzWvby0Is2cHNjQ&callback=initMap" async defer></script>


<footer>
    <img src="/paws&protect/includes/image/paw.png" alt="paw Logo">
    <div class="right">
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-linkedin"></i></a>
        <a href="#"><i class="fab fa-youtube"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
    </div>
    <p>&copy; 2025 Paws & Protect | Together for Animals 🐾</p>
</footer>

</body>
</html>  