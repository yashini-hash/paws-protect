<?php
include("dbconnect.php");

$query = "SELECT lost_id, image, animal_type, breed, color, lost_location, owner_name, contact_number 
          FROM lost_animals 
          ORDER BY created_at DESC";

$result = mysqli_query($conn, $query);
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Protect </title>
     <link rel="icon" type="image/x-icon" href="/paws&protect/includes/image/paw.png" />
    <link rel="stylesheet" href="lost.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

  

</head>
<body>

   
    <header>
        <div class="logo">
            <img src="/paws&protect/includes/image/paw.png" alt=" Logo">
        </div>

      
        <nav class="nav-links">
           <li><a href="/paws&protect/home.php" > HOME</a></li>
           <li><a href="about.php">ABOUT</a></li>
           <li><a href="adopt.php">ADOPT</a></li>
           <li> <a href="rescue.php">RESCUE</a></li>
           <li> <a href="donate.php">DONATE</a></li>
            <li><a href="/paws&protect/includes/page/lost.php">LOST</a></li>
            
        </nav>

        
        <div class="menu-toggle" id="menu-toggle">
            <i class="fa fa-bars"></i>
        </div>
    </header>

   
    <div class="mobile-nav" id="mobile-nav">
        <a href="/paws&protect/home.php">HOME</a>
            <a href="about.php">ABOUT</a>
            <a href="adopt.php">ADOPT</a>
            <a href="rescue.php">RESCUE</a>
            <a href="donate.php">DONATE</a>
            <a href="/paws&protect/includes/page/lost.php">LOST</a>
    </div>





    <script>
 
    document.getElementById("menu-toggle").onclick = function() {
      document.getElementById("mobile-nav").classList.toggle("active");
    };
    </script>


       <div class="hero">
       <img src="/paws&protect/includes/image/lost.png" alt="lost">
  <div class="hero-text">Found Lost Animals</div>
</div>




<section class="animal-grid">

<?php while ($row = mysqli_fetch_assoc($result)) { ?>

    <div class="animal-card">

        <img src="/paws&protect/includes/uploads/lost/<?php echo $row['image']; ?>" alt="Lost Animal">

        <div class="card-body">
            <h3><?php echo $row['animal_type']; ?></h3>

            <div class="meta"><strong>Breed:</strong> <?php echo $row['breed']; ?></div>
            <div class="meta"><strong>Color:</strong> <?php echo $row['color']; ?></div>
            <div class="meta"><strong>Lost at:</strong> <?php echo $row['lost_location']; ?></div>
            <div class="meta"><strong>Owner:</strong> <?php echo $row['owner_name']; ?></div>
            <div class="meta"><strong>Contact:</strong> <?php echo $row['contact_number']; ?></div>
        </div>

        <div class="card-footer">
            <form method="post" action="rescue.php">
                <input type="hidden" name="lost_id" value="<?php echo $row['lost_id']; ?>">
                <button type="submit" class="details-btn">
                     FOUND
                </button>
            </form>
        </div>

    </div>

<?php } ?>

</section>




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
