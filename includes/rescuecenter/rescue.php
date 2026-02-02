<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    die("❌ Rescue center not logged in.");
}

$rescue_center_id = (int) $_SESSION['rescue_center_id'];


$centerQuery = $conn->prepare(
    "SELECT latitude, longitude FROM rescue_center WHERE rescue_center_id = ?"
);
$centerQuery->bind_param("i", $rescue_center_id);
$centerQuery->execute();
$center = $centerQuery->get_result()->fetch_assoc();

$centerLat = $center['latitude'];
$centerLng = $center['longitude'];

$query = "SELECT * FROM rescue_requests WHERE rescue_center_id = ? ORDER BY request_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$result = $stmt->get_result();


$alertStmt = $conn->prepare(
    "SELECT COUNT(*) AS total FROM rescue_requests WHERE rescue_center_id = ? AND status = 'Pending'"
);
$alertStmt->bind_param("i", $rescue_center_id);
$alertStmt->execute();
$alert = $alertStmt->get_result()->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Rescue Requests</title>
<link rel="stylesheet" href="rescue.css">

<script 
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvmv0SLEBxBzt4EFJ9VzWvby0Is2cHNjQ&callback=initMap" 
  async defer>
</script>

</head>

<body>

<div class="card">

<div class="card-header">
    <h2>🐾 Rescue Requests</h2>
    <?php if ($alert['total'] > 0): ?>
        <div class="alert">🔔 <?= $alert['total'] ?> New</div>
    <?php endif; ?>
</div>

<table>
<tr>
    <th>Animal</th>
    <th>Description</th>
    <th>Contact</th>
    <th>Location</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php while ($row = $result->fetch_assoc()):
    preg_match('/Lat:\s*([0-9\.\-]+),\s*Lng:\s*([0-9\.\-]+)/', $row['rescue_location'], $m);
    $lat = $m[1] ?? null;
    $lng = $m[2] ?? null;
?>
<tr>
    <td><?= htmlspecialchars($row['animal_type']) ?></td>
    <td><?= htmlspecialchars($row['description']) ?></td>
    <td><?= htmlspecialchars($row['contact_number']) ?></td>
    <td>
        <div class="map"
             data-lat="<?= $lat ?>"
             data-lng="<?= $lng ?>"
             onclick="openFullMap(this)">
        </div>
    </td>
    <td>
        <span class="status <?= strtolower(str_replace(' ','-',$row['status'])) ?>">
            <?= $row['status'] ?>
        </span>
    </td>
    <td class="action-btns">
        <?php if ($row['status'] !== 'Completed'): ?>
            <a class="btn-progress" href="update_status.php?id=<?= $row['request_id'] ?>&status=In Progress">Start</a><br>
            <a class="btn-complete" href="update_status.php?id=<?= $row['request_id'] ?>&status=Completed">Complete</a>
        <?php else: ?>
            ✅ Completed
        <?php endif; ?>
    </td>
</tr>
<?php endwhile; ?>
</table>
</div>

<div id="mapModal">
    <div id="mapBox">
        <span id="closeModal">&times;</span>
        <div id="fullMap" style="width:100%; height:100%;"></div>
    </div>
</div>

<script>
const rescueCenter = {
    lat: <?= $centerLat ?>,
    lng: <?= $centerLng ?>
};

let modal = document.getElementById("mapModal");
let fullMapDiv = document.getElementById("fullMap");
let fullMap;

function initMap() {
    document.querySelectorAll(".map").forEach(div => {
        const lat = parseFloat(div.dataset.lat);
        const lng = parseFloat(div.dataset.lng);

        if (isNaN(lat) || isNaN(lng)) {
            div.innerHTML = "📍 Not Set";
            return;
        }

        const map = new google.maps.Map(div, {
            zoom: 13,
            center: { lat, lng }
        });

        new google.maps.Marker({
            position: { lat, lng },
            map,
            icon: "http://maps.google.com/mapfiles/ms/icons/red-dot.png"
        });

        new google.maps.Marker({
            position: rescueCenter,
            map,
            icon: "http://maps.google.com/mapfiles/ms/icons/blue-dot.png"
        });
    });
}

function openFullMap(div) {
    const lat = parseFloat(div.dataset.lat);
    const lng = parseFloat(div.dataset.lng);

    modal.style.display = "flex";

    fullMap = new google.maps.Map(fullMapDiv, {
        zoom: 15,
        center: { lat, lng }
    });

new google.maps.Marker({
    position: { lat, lng },
    map: fullMap,
    icon: "https://maps.google.com/mapfiles/ms/icons/red-dot.png",
    label: {
        text: "Rescue Point",
        color: "white",
        fontWeight: "bold"
    },
    title: "Rescue Point"
});

new google.maps.Marker({
    position: rescueCenter,
    map: fullMap,
    icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
    label: {
        text: "You",
        color: "white",
        fontWeight: "bold"
    },
    title: "You (Rescue Center)"
});

    setTimeout(() => {
        google.maps.event.trigger(fullMap, "resize");
        fullMap.setCenter({ lat, lng });
    }, 300);
}

document.getElementById("closeModal").onclick = () => {
    modal.style.display = "none";
    fullMapDiv.innerHTML = "";
};

window.onclick = e => {
    if (e.target === modal) {
        modal.style.display = "none";
        fullMapDiv.innerHTML = "";
    }
};
</script>

</body>
</html>
