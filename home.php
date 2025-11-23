<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paws & Protect </title>
     <link rel="icon" type="image/x-icon" href="includes/image/paws.png" />
    <link rel="stylesheet" href="home.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.9/index.global.min.css" rel="stylesheet">

  

</head>
<body>
   
    <div class="top-bar">
        <div class="left">
            <h3>Welcome to Paws & Protect !</h3>
          
           
        </div>
        <div class="right">
            <a href="includes/page/login.php">Login/Register</a>
            
        </div>
    </div>


    <header>
        <div class="logo">
            <img src="includes/image/paws.png" alt=" Logo">
        </div>

      
        <nav class="nav-links">
           <li><a href="home.php" > HOME</a></li>
           <li><a href="includes/page/about.php">ABOUT</a></li>
           <li><a href="includes/page/adopt.php">ADOPT</a></li>
           <li> <a href="includes/page/rescue.php">RESCUE</a></li>
           <li> <a href="includes/page/donate.php">DONATE</a></li>
            <li><a href="includes/page/lost.php">LOST</a></li>
            
        </nav>

        
        <div class="menu-toggle" id="menu-toggle">
            <i class="fa fa-bars"></i>
        </div>
    </header>

   
    <div class="mobile-nav" id="mobile-nav">
        <a href="home.php">HOME</a>
            <a href="includes/page/about.php">ABOUT</a>
            <a href="includes/page/adopt.php">ADOPT</a>
            <a href="includes/page/rescue.php">RESCUE</a>
            <a href="includes/page/donate.php">DONATE</a>
            <a href="includes/page/lost.php">LOST</a>
    </div>





    <script>
 
    document.getElementById("menu-toggle").onclick = function() {
      document.getElementById("mobile-nav").classList.toggle("active");
    };

   


    </script>


    <section class="hero">
  <div class="hero-content">
    <h1>Adopt Love. Protect Life.</h1>
    <p>Join us in rescuing and giving homes to animals in need.</p>
    <a href="includes/uploads/aboutcourse.php" class="btn">View Animals</a>
    <a href="includes/uploads/aboutcourse.php" class="btn"> Rescue</a>
  </div>
</section>

<!-- Welcome Section -->
<section class="welcome">
  <h2>Welcome to Paws & Protect 🐾</h2>
  <p>
    Welcome to <strong>Paws & Protect</strong>, your trusted online platform devoted to the 
    <strong>care, rescue, and adoption of wild and domestic animals</strong>. Every day, countless 
    animals are abandoned, injured, or left without homes and that’s where we come in. 
    Our mission is to bring together <strong>animal shelters, volunteers, and kind-hearted individuals</strong> 
    who want to make a difference. 
  </p>
  <p>
    Through this platform, you can <strong>adopt a loving companion</strong>, 
    <strong>report animals in danger</strong>, <strong>donate to support shelters</strong> 
     to help save lives. 
    At Paws & Protect, we believe that <em>every paw deserves a safe home</em> and 
    <em>every animal deserves compassion</em>. 
  </p>
</section>

<section class="help">
  <div class="help-image">
    <img src="includes/image/cat.png" alt="Helping animals" >
  </div>

  <div class="help-text">
    <h2>How Can You Help! </h2>
    <p>
      You can make a difference by adopting, donating, or volunteering.
      Every small act of kindness helps animals find safety and love.
    </p>

    <div class="help-buttons">
      <button class="btn donate">Donate</button>
      <button class="btn adopt">Adopt</button>
      <button class="btn rescue">Rescue</button>
    </div>

    <img src="includes/image/foot.png" alt="Footprint" class="footprint">
  </div>
</section>

<section class="rescue-centres">
  <h2>Our Rescue Centres Across Sri Lanka 🇱🇰</h2>
  <p>We work across the island to rescue, protect, and care for animals in need.</p>

  <div class="map-container">
    <img src="includes/image/center.png" alt="Rescue Centres Map" class="sri-lanka-map">
  </div>
</section>



 <footer class="footer">
  <div class="footer-container">

    <!-- Left Section -->
    <div class="footer-section">
      <img src="includes/image/paws.png" alt="Logo" class="footer-logo">

      <h3>Contact us:</h3>
<a href="tel:+94112233445"><i class="fa fa-phone"></i> +94 11 223 3445</a>
<br><a href="mailto:paws&srilanka@gmail.com"><i class="fa fa-envelope"></i> paws&protect@gmail.com</a>
    </div>

    <!-- Right Section -->
    <div class="footer-section">
      <h2>Paws & Protect Sri Lanka:</h2>

      <div class="social-icons big">
        <a href="#"><i class="fas fa-globe"></i></a>
        <a href="#"><i class="fab fa-facebook"></i></a>
        <a href="#"><i class="fab fa-instagram"></i></a>
        <a href="#"><i class="fas fa-times"></i></a>
      </div>

       <br><p>&copy; 2025 Paws & Protect | Together for Animals 🐾</p>
      
    </div>

  </div>
</footer>


</body>
</html>
