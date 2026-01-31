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




    <div class="about-content">
      <div class="about-box vision">
        <h3><i class="fa-solid fa-eye"></i> Our Vision</h3>
        <p>
          To build a compassionate society where every animal is treated with kindness,
          respect, and dignity — ensuring a safe environment for all living beings.
        </p>
      </div>

      <div class="about-box mission">
        <h3><i class="fa-solid fa-hand-holding-heart"></i> Our Mission</h3>
        <p>
          To rescue, rehabilitate, and rehome abandoned or injured animals while
          raising awareness about animal welfare. Through adoption drives, rescue operations,
          and community engagement, we aim to protect lives and inspire humane living.
        </p>
      </div>
    </div>
  </div>
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
