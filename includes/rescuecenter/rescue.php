<?php
session_start();
include("sidebar.php");
include("../page/dbconnect.php");

if (!isset($_SESSION['rescue_center_id'])) {
    die("❌ Rescue center not logged in.");
}

$rescue_center_id = (int) $_SESSION['rescue_center_id'];

/* FETCH RESCUE CENTER LOCATION */
$centerQuery = $conn->prepare(
    "SELECT latitude, longitude FROM rescue_center WHERE rescue_center_id = ?"
);
$centerQuery->bind_param("i", $rescue_center_id);
$centerQuery->execute();
$center = $centerQuery->get_result()->fetch_assoc();

$centerLat = $center['latitude'];
$centerLng = $center['longitude'];

/* FETCH RESCUE REQUESTS */
$query = "SELECT * FROM rescue_requests WHERE rescue_center_id = ? ORDER BY request_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $rescue_center_id);
$stmt->execute();
$result = $stmt->get_result();

/* ALERT COUNT */
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

<script 
  src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBvmv0SLEBxBzt4EFJ9VzWvby0Is2cHNjQ&callback=initMap" 
  async defer>
</script>


<style>
body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #FFF8E7;
    padding: 30px;
    margin-left: 120px;
}

.card {
    max-width: 1100px;
    margin: auto;
    background: #ffffff;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.card-header h2 {
    margin: 0;
    color: #2E2E2E;
}

/* ALERT */
.alert {
    background: #9f2525;
    color: #fff;
    padding: 10px 18px;
    border-radius: 25px;
    font-size: 14px;
    font-weight: 600;
}

/* TABLE */
.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #5C3A21;
    color: #fff;
    padding: 14px;
    text-align: left;
    font-size: 14px;
}

td {
    padding: 14px;
    border-bottom: 1px solid #eee;
    font-size: 14px;
    color: #0a0a0a;
}

tr:hover {
    background: #FAFAFA;
}

/* STATUS BADGES */
.status {
    padding: 6px 14px;
    border-radius: 30px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
}

.status.pending {
    background: #FFF3CD;
    color: #856404;
}

.status.in-progress {
    background: #E3F2FD;
    color: #0D47A1;
}

.status.completed {
    background: #E6F4EA;
    color: #1B5E20;
}

/* BUTTONS */
.action-btns a {
    display: inline-block;
    padding: 7px 14px;
    border-radius: 6px;
    font-size: 12px;
    text-decoration: none;
    font-weight: 600;
    margin-bottom: 6px;
}

.btn-progress {
    background: #FF9800;
    color: #fff;
}

.btn-complete {
    background: #28A745;
    color: #fff;
}

.action-btns a:hover {
    opacity: 0.85;
}

/* EMPTY */
.empty {
    text-align: center;
    color: #777;
    padding: 40px 0;
}

/* MAP STYLE */
.map {
    width: 150px;
    height: 100px;
    border-radius: 8px;
    border: 1px solid #ccc;
}
#mapModal {
    display:none;
    position:fixed;
    top:0; left:0;
    width:100%; height:100%;
    background:rgba(0,0,0,0.7);
    z-index:9999;
    justify-content:center;
    align-items:center;
}

#mapBox {
    background:#fff;
    width:90%;
    max-width:800px;
    height:80%;
    border-radius:12px;
    position:relative;
}

#closeModal {
    position:absolute;
    top:10px;
    right:15px;
    font-size:28px;
    cursor:pointer;
}


/* RESPONSIVE */
@media (max-width: 768px) {

    .card {
        padding: 18px;
    }

    .card-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    table, thead, tbody, th, td, tr {
        display: block;
        width: 100%;
    }

    table th { display: none; }

    table tr {
        margin-bottom: 15px;
        padding: 15px;
        border-radius: 12px;
        background: #fff6e3;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    table td {
        padding: 6px 0;
        font-size: 13px;
        text-align: left;
        display: flex;
        justify-content: space-between;
    }

    table td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #5C3A33;
    }

    .action-btns a {
        font-size: 11px;
        padding: 5px 10px;
    }

    .alert {
        font-size: 13px;
        padding: 8px 14px;
    }
}

@media (max-width: 480px) {
    .card-header h2 {
        font-size: 18px;
    }

    table td {
        font-size: 12px;
        flex-direction: column;
        align-items: flex-start;
    }

    table td::before {
        margin-bottom: 3px;
        font-size: 12px;
    }

    .action-btns a {
        font-size: 10px;
        padding: 5px 8px;
    }

    .alert {
        font-size: 12px;
        padding: 6px 10px;
    }
}
</style>

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

<!-- MAP MODAL -->
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

/* SMALL MAPS */
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

/* FULL MAP */
function openFullMap(div) {
    const lat = parseFloat(div.dataset.lat);
    const lng = parseFloat(div.dataset.lng);

    modal.style.display = "flex";

    fullMap = new google.maps.Map(fullMapDiv, {
        zoom: 15,
        center: { lat, lng }
    });

  // 🔴 Rescue Point
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

// 🔵 You (Rescue Center)
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
