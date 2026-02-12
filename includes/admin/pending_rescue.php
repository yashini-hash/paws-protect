<?php
session_start();
include('auth.php');
include('../page/dbconnect.php');
include('sidebar.php');

$sql = "SELECT rr.*, rc.center_name
        FROM rescue_requests rr
        LEFT JOIN rescue_center rc 
            ON rr.rescue_center_id = rc.rescue_center_id
        WHERE rr.status = 'Pending'
        ORDER BY rr.request_date DESC";

$result = $conn->query($sql);

function getLocationName($location){
    if(!$location) return 'Unknown';

    preg_match('/Lat:\s*([\d\.\-]+),\s*Lng:\s*([\d\.\-]+)/', $location, $m);
    if(count($m) !== 3) return $location;

    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat={$m[1]}&lon={$m[2]}";
    $opts = ["http"=>["header"=>"User-Agent: RescueApp/1.0\r\n"]];
    $context = stream_context_create($opts);

    $res = @file_get_contents($url,false,$context);
    if(!$res) return $location;

    $data = json_decode($res,true);
    return $data['display_name'] ?? $location;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pending Rescue Requests</title>
<link rel="stylesheet" href="pending_rescue.css">

</head>

<body>

<div class="main">
<div class="page-card">

<div class="page-header">
    <h2>Pending Rescue Requests</h2>
    <div class="search-box">
        <input type="text" id="searchInput" placeholder="Search requests">
    </div>
</div>

<?php if($result->num_rows>0): ?>
<div class="table-wrapper">
<table class="rescue-table" id="rescueTable">

<thead>
<tr>
<th>Animal</th>
<th>Location</th>
<th>Description</th>
<th>Contact</th>
<th>Date</th>
<th>Status</th>
<th>Rescue Center</th>
</tr>
</thead>

<tbody>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td data-label="Animal"><?= htmlspecialchars($row['animal_type']) ?></td>

<td data-label="Location">
<?= htmlspecialchars(getLocationName($row['rescue_location'])) ?>
</td>

<td data-label="Description"><?= htmlspecialchars($row['description']) ?></td>
<td data-label="Contact"><?= htmlspecialchars($row['contact_number']) ?></td>
<td data-label="Date"><?= date('d M Y',strtotime($row['request_date'])) ?></td>

<td data-label="Status">
<span class="status pending">Pending</span>
</td>

<td data-label="Rescue Center">
<?= htmlspecialchars($row['center_name'] ?? 'Not Assigned') ?>
</td>
</tr>
<?php endwhile; ?>
</tbody>

</table>
</div>
<?php else: ?>
<p style="text-align:center;font-weight:bold;">No pending rescue requests</p>
<?php endif; ?>

</div>
</div>

<script>
document.getElementById('searchInput').addEventListener('keyup',()=>{
    let v = searchInput.value.toLowerCase();
    document.querySelectorAll('#rescueTable tbody tr').forEach(r=>{
        r.style.display = r.innerText.toLowerCase().includes(v) ? '' : 'none';
    });
});
</script>

</body>
</html>
