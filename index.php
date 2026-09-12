<?php
include 'config.php';
   
if(isset($_POST['name']) && isset($_POST['email'])) {

    $name    = $_POST['name'];
    $email   = $_POST['email'];
    $phone   = $_POST['phone'];
    $message = $_POST['message'];

    try {
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, message) VALUES (?, ?, ?, ?)");
        
        if($stmt->execute([$name, $email, $phone, $message])){
            setFlash("Data submitted successfully! We will contact you soon.", "success");
        } else {
            setFlash("There was an error submitting your message. Please try again.", "danger");
        }
    } catch (PDOException $e) {
        setFlash("Database error occurred.", "danger");
    }

    header("Location: index.php#contact");
    exit();
}
$flash = getFlash();
?>

<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>M.A SoftHub | Software Solutions</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/typed.js@2.0.12"></script>
      <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
      <style>
         /* ===== GLOBAL ===== */
         html, body { width: 100%; scroll-behavior: smooth; margin: 0; overflow-x: hidden; font-family: 'Montserrat', sans-serif; }
         section, #home, #about, #services, #portfolio, #team, #contact { scroll-margin-top: 100px; }
         img { max-width: 100%; height: auto; display: block; }
         section { padding: 40px 15px; }
         section, .hero-section, .container, .row {
         overflow: visible !important;}
         /* ===== PRELOADER ===== */
         #preloader { position: fixed; width: 100%; height: 100%; background: #0F172A; display: flex; justify-content: center; align-items: center; z-index: 9999; }
         .loader-logo { font-size: 32px; font-weight: bold; color: #fff; letter-spacing: 2px; animation: pulse 1.5s infinite; }
         @keyframes pulse { 0% { opacity: 0.4; transform: scale(0.9); } 50% { opacity: 1; transform: scale(1); } 100% { opacity: 0.4; transform: scale(0.9); } }
         /* ===== SCROLL PROGRESS BAR ===== */
         #progress-bar { position: fixed; top: 0; left: 0; height: 4px; width: 0%; background: linear-gradient(90deg,#0DCAF0,#0B5ED7); z-index: 9999; }
         /* ===== NAVBAR ===== */
         .navbar { background: linear-gradient(90deg, #0F172A, #0B5ED7); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); transition: 0.4s ease; height: 85px; border: none; margin-bottom: 0; z-index: 9999; }
         .navbar-brand .brand-text { display: inline-block; white-space: normal; word-wrap: break-word; overflow: hidden;}
         .navbar .navbar-brand { display: flex; align-items: center; color: #fff; font-size: 25px; font-weight: bold; text-decoration: none; }
         .navbar .navbar-brand .logo { height: 50px; width: auto; border-radius: 14px; object-fit: cover; margin-right: 10px; transition: all 0.35s ease-in-out; }
         .navbar .navbar-brand .logo:hover { transform: scale(1.1) rotate(-2deg); }
         .navbar-default .navbar-brand,
         .navbar-default .navbar-brand:hover,
         .navbar-default .navbar-brand:focus {background: transparent !important; color: #ffffff !important; }
         .navbar-default .navbar-nav > li > a { color: #fff !important; font-size: 18px; font-weight: bold; }
         .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus { color: #fff !important; background-color: #0d2566; }
         .navbar .container { margin-top: 15px; }
         .navbar-toggle { display: none; margin-top: 20px; color: #fff; background-color: white !important; }
         .navbar-toggle .icon-bar { background-color: #fff; }
         /* ===== HERO ===== */
         .hero-section { background: linear-gradient(135deg, #0F172A, #0B5ED7, #0DCAF0); background-attachment: scroll; will-change: transform; background-size: cover; color: white; padding: 80px 25px; margin-top: 5000px; text-shadow: 1px 1px 3px rgba(0,0,0,0.2); text-align: center; }
         .hero-section h1 { font-size: 40px; font-weight: bold; margin-bottom: 20px; min-height: 110px; }
         .hero-section p { font-weight: 200; font-size: 24px; max-width: 600px; margin: 0 auto 20px; }
         .hero-section .btn { background: #fff; border-radius: 50px; padding: 10px 15px; width: 150px; font-size: 18px; color: #000; font-weight: bold; }
         .hero-section .btn:hover { background-color: #0d2566; color: #fff; }
         .hero-section .hero-image { max-height: 100%; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); }
         .hero-section .col-md-6 { display: flex; flex-direction: column; align-items: center; max-width: 100%;}
         /* ===== ABOUT ===== */
         .about-section h1 { color: #334155; text-align: center; margin-top: 50px; font-weight: bold; }
         .about-section p { font-size: 20px; line-height: 1.6; color: #334155; }
         .about-section .about-image { max-height: 100%; border-radius: 15px; margin-top: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.25); }
         /* ===== SERVICES ===== */
         .service-section { text-align: center; margin-top: 50px; }
         .service-section h1 { color: #334155; font-weight: bold; margin-bottom: 10px; }
         .service-card { background-color: #fff; border-radius: 10px; padding: 20px; margin: 15px; min-height: 260px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); transition: all 0.3s ease-in-out; }
         .service-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); }
         .service-card i { color: #0B5ED7; font-size: 40px; margin-bottom: 15px; }
         .service-title { font-size: 20px; font-weight: bold; }
         .service-decs { font-size: 18px; }
         /* ===== PORTFOLIO ===== */
         .portfolio-section h1 { margin-top: 50px; text-align: center; color: #334155; font-weight: bold; font-size: 36px; }
         .portfolio-card { position: relative; overflow: hidden; border-radius: 10px; margin-bottom: 30px; height: 230px; cursor: pointer; }
         .portfolio-card img { width: 100%; height: 230px; object-fit: cover; transition: transform 0.3s ease; }
         .portfolio-card:hover img { transform: scale(1.05); }
         .portfolio-overlay { position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(15,23,42,0.8); color:#fff; opacity:0; display:flex; flex-direction:column; justify-content:center; align-items:center; text-align:center; padding:15px; border-radius:10px; transition:opacity 0.3s ease; }
         .portfolio-card:hover .portfolio-overlay { opacity:1; }
         .portfolio-overlay h3 { font-size:22px; margin-bottom:10px; }
         .portfolio-overlay p { font-size:16px; }
         /* ===== TEAM ===== */
         .team-section h1 { margin-top: 50px; text-align: center; color: #334155; font-weight: bold; font-size: 36px; }
         .team-member-card { background: #fff; padding: 30px 20px; border-radius: 12px; box-shadow: 0 6px 15px rgba(0,0,0,0.1); transition: transform 0.3s ease, box-shadow 0.3s ease; margin-bottom: 30px; }
         .team-member-card:hover { transform: translateY(-8px); box-shadow: 0 12px 25px rgba(0,0,0,0.2); }
         .team-member-image { width: 150px; height: 150px; margin-left: 100px; object-fit: cover; border-radius: 50%; border:4px solid #0B5ED7; transition: transform 0.3s ease, border-color 0.3s ease; }
         .team-member-card:hover .team-member-image { transform: scale(1.05); border-color: #0DCAF0; }
         .team-member-name { font-size:20px; font-weight:bold; margin-top:15px; color:#0F172A; }
         .team-member-role { font-size:15px; color:#64748B; }
         /* ===== CONTACT ===== */
         .contact-section { margin-top: 80px; padding: 60px 0; }
         .contact-section h1 { text-align: center; margin-bottom:40px; font-weight:bold; font-size:36px; color:#0F172A; }
         .contact-info li { font-size:18px; margin-bottom:15px; color:#334155; display:flex; align-items:center; }
         .contact-info li i { background:#0B5ED7; color:#fff; width:35px; height:35px; border-radius:50%; text-align:center; line-height:35px; margin-right:12px; font-size:16px; }
         .contact-form { background:#fff; padding:30px; border-radius:12px; box-shadow:0 8px 25px rgba(0,0,0,0.08); }
         .contact-form .form-control { height:45px; border-radius:6px; border:1px solid #cbd5e1; font-size:15px; transition:0.3s; }
         .contact-form textarea { height:auto; resize:none; }
         .contact-form .form-control:focus { border-color:#0B5ED7; box-shadow:0 0 6px rgba(11,94,215,0.3); }
         .contact-form .btn { background: linear-gradient(90deg, #0F172A, #0B5ED7); border:none; font-size:18px; font-weight:bold; padding:12px; border-radius:6px; transition:0.3s; }
         .contact-form .btn:hover { background:#0d2566; transform: translateY(-2px); box-shadow:0 5px 15px rgba(0,0,0,0.2); }
         /* ===== FOOTER ===== */
         .main-footer { background-color:#001a3d; color:#fff; padding:60px 0 30px 0; font-size:14px; }
         .footer-logo { width:60px; border-radius:10px; }
         .footer-brand { font-weight:700; font-size:24px; margin-bottom:15px; }
         .footer-desc { color:#cbd5e0; line-height:1.6; max-width:300px; }
         .footer-heading { font-weight:600; font-size:18px; margin-bottom:25px; }
         .footer-links li a { color:#cbd5e0; text-decoration:none; transition:0.3s; }
         .footer-links li a:hover { color:#fff; padding-left:5px; }
         .social-links a { color:#fff; margin-right:20px; font-size:20px; transition:0.3s; }
         .social-links a.gmail:hover { color:#EA4335; } /* Gmail red */
         .social-links a.whatsapp:hover { color: #25D366; } /* WhatsApp green */
         .newsletter-form .newsletter-input { width: 100%; height: 45px; padding: 10px 18px; border: none; outline: none; border-radius: 40px; font-size: 15px; background-color: #ffffff; color: #000;}
         .newsletter-form .input-group { display: column; margin-left: 10px; }
         .newsletter-form .btn-subscribe { border-radius: 40px; margin-left: 10px; }
         .btn-subscribe { background-color:#0d6efd; color:#fff; border:none; padding:10px 25px; border-radius:40px; font-weight:500; margin-top:10px; }
         .btn-subscribe:hover { background-color:#0b5ed7; color:#fff; }
         .footer-divider { border-top:1px solid rgba(255, 255, 255, 255); margin:40px 0 20px 0; }
         .copyright { color:#cbd5e0; margin-bottom:0; }
         /* ===== MODALS ===== */
         .modal-dialog { position: relative; margin-top: 150px; }
         .modal-body { max-height: calc(100vh - 200px); overflow-y:auto; font-size:16px; line-height:1.6; }
         .modal-header h4 { font-weight:bold; }
         .modal-footer { text-align:right; }
         /* ===== ALERTS ===== */
         .alert { margin-top: 20px; }
         /* ===== RESPONSIVE ===== */
         @media (max-width:991px) {
         .navbar { height:auto; padding:10px 0;}
         .navbar-header { display:flex; align-items:center; justify-content:space-between; width:100%; }
         .navbar-toggle { display:block; margin-top:0; border:none; }
         .navbar-nav { margin-top:10px; }
         .navbar-nav > li > a { font-size:16px; padding:10px 15px; }
         .navbar-brand .logo {margin-bottom: 10px;}
         .navbar-brand .brand-text { max-width: 150px; margin-bottom: 10px; white-space: normal; word-wrap: break-word;}
         .hero-section { padding:60px 15px; }
         .hero-section h1 { font-size:28px; }
         .hero-section p { font-size:18px; }
         .hero-section .btn { width:auto; margin-bottom:20px; }
         .hero-image { max-height:300px; }
         .about-section { margin-top:80px; text-align:center; }
         .about-section h1 { font-size:26px; }
         .about-section p { font-size:16px; }
         .about-image { margin-top:20px; max-height:300px; }
         }
         @media (max-width:767px) {
         .navbar-brand .brand-text {flex-wrap: wrap;}
         .service-card { margin:10px 0; }
         .service-section h1 { font-size:26px; }
         .portfolio-card { height:auto; }
         .portfolio-card img { height:auto; }
         .team-member-card { margin-bottom:20px; }
         .team-member-image { width:120px; height:120px; }
         .contact-section { padding:40px 15px; }
         .contact-info li { font-size:16px; }
         .contact-form { margin-top:30px; }
         .main-footer { text-align:center; }
         .footer-logo { margin-left: 43%; }
         .footer-desc { max-width:100%; }
         .footer-links li a { display: block; margin-bottom: 10px; }
         .social-links a { margin-right:10px; }
         .modal-dialog { width:95%; margin:20px auto; }
         .modal-body { max-height: calc(100vh - 160px); font-size:15px; }
         .modal-body ul { padding-left:18px; }
         .modal-body .btn { width:100%; margin-top:10px; }
         }
         @media (max-width:480px) {
         .modal-body { font-size:14px; }
         .modal-header h4 { font-size:18px; }
         }
      </style>
   </head>
   <body>
      <div id="progress-bar"></div>
      <div id="preloader">
         <div class="loader-logo">M.A SoftHub</div>
      </div>
      <!-- Flash Message -->
      <?php if($flash): ?>
      <div class="container" style="margin-top: 100px;">
         <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $flash['message']; ?>
         </div>
      </div>
      <?php endif; ?>
      <!-- NAVBAR -->
      <nav class="navbar navbar-default navbar-fixed-top">
         <div class="container">
            <!-- Navbar Header -->
            <div class="navbar-header">
               <!-- Brand/Logo -->
               <a class="navbar-brand" href="#home">
               <img src="static/logo.png" class="logo" alt="M.A SoftHub Logo">
               <span class="brand-text">M.A SoftHub</span>
               </a>
               <!-- Toggle button -->
               <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#myNavbar">
               <span class="sr-only">Toggle navigation</span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               <span class="icon-bar"></span>
               </button>
            </div>
            <!-- Collapsible Menu -->
            <div class="collapse navbar-collapse" id="myNavbar">
               <ul class="nav navbar-nav navbar-right">
                  <li><a href="#home">Home</a></li>
                  <li><a href="#services">Services</a></li>
                  <li><a href="#portfolio">Portfolio</a></li>
                  <li><a href="#about">About</a></li>
                  <li><a href="#contact">Contact</a></li>
               </ul>
            </div>
         </div>
      </nav>
      <!-- HERO SECTION -->
      <div id="home" class="hero-section" data-aos="fade-down">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <h1 data-aos="fade-up" data-aos-duration="500">
                     <span id="typed-text"></span>
                  </h1>
                  <p data-aos="fade-up" data-aos-delay="200">
                     We design and develop innovative software solutions that empower businesses 
                     and elevate user experiences. From scalable web applications to high-performance 
                     mobile solutions, we craft technology that delivers real impact.
                  </p>
                  <a href="#contact" class="btn" data-aos="zoom-in" data-aos-delay="200">
                  Get Started
                  </a>
               </div>
               <div class="col-md-6 text-center">
                  <img src="static/image.png"
                     class="hero-image img-responsive"
                     data-aos="fade-left"
                     data-aos-delay="300">
               </div>
            </div>
         </div>
      </div>
      <!-- ABOUT SECTION -->
      <div id="about" class="about-section" data-aos="fade-up">
         <div class="container">
            <div class="row">
               <div class="col-md-6">
                  <h1 data-aos="fade-up">
                     About Us
                  </h1>
                  <p data-aos="fade-right" data-aos-delay="100">
                     M.A SoftHub is a software development company that builds simple, reliable, and innovative digital solutions for businesses. 
                     We focus on quality and performance to help turn ideas into useful technology.
                     <br><br>
                     Our team uses technical skills and creative thinking to create custom software that improves efficiency, scalability, and user experience. 
                     We believe technology should solve problems and help businesses grow successfully.
                  </p>
               </div>
               <div class="col-md-6 text-center">
                  <img src="static/about.png"
                     class="about-image img-responsive"
                     data-aos="fade-left"
                     data-aos-delay="300">
               </div>
            </div>
         </div>
      </div>
      <!-- SERVICES SECTION -->
      <div id="services" class="service-section">
         <div class="container">
            <h1 data-aos="fade-up">
               Our Services
            </h1>
            <div class="row">
               <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="service-card text-center" data-aos="zoom-in" data-aos-delay="100">
                     <i class="fas fa-laptop-code service-icon"></i>
                     <div class="service-title">Web Development</div>
                     <div class="service-decs">
                        We create responsive, high-performance websites and web applications using the latest technologies and frameworks.
                     </div>
                  </div>
               </div>
               <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="service-card text-center" data-aos="zoom-in" data-aos-delay="100">
                     <i class="fas fa-mobile-alt service-icon"></i>
                     <div class="service-title">Mobile Apps Development</div>
                     <div class="service-decs">
                        We create responsive, high-performance mobile applications for iOS and Android using the latest technologies and frameworks.
                     </div>
                  </div>
               </div>
               <div class="col-md-4 col-sm-6 col-xs-12">
                  <div class="service-card text-center" data-aos="zoom-in" data-aos-delay="200">
                     <i class="fas fa-database service-icon"></i>
                     <div class="service-title">Database Management System</div>
                     <div class="service-decs">
                        We design and manage robust, secure, and scalable database systems to store, organize, and retrieve data efficiently.
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- PORTFOLIO SECTION -->
      <section id="portfolio" class="portfolio-section">
         <div class="container">
            <h1 data-aos="fade-up">
               Our Portfolio
            </h1>
            <div class="text-center" style="margin-bottom:30px;">
               <button class="btn btn-primary filter-btn" data-filter="all">All</button>
               <button class="btn btn-default filter-btn" data-filter="web">Web Apps</button>
               <button class="btn btn-default filter-btn" data-filter="management">Management Systems</button>
               <button class="btn btn-default filter-btn" data-filter="academic">Academic Projects</button>
            </div>
            <div class="row portfolio-container">
               <!-- Project 1 -->
               <div class="col-sm-4 portfolio-item web" data-toggle="modal" data-target="#project1" data-aos="flip-up" data-aos-delay="100">
                  <div class="portfolio-card">
                     <img src="static/ecommerce.png" alt="Project 1">
                     <div class="portfolio-overlay">
                        <h3>E-Commerce Website</h3>
                        <p>Secure & scalable shopping platform</p>
                        <span class="label label-info">PHP</span><br>
                        <span class="label label-warning">MySQL</span><br>
                        <span class="label label-success">Bootstrap</span><br>
                     </div>
                  </div>
               </div>
               <!-- Project 2 -->
               <div class="col-sm-4 portfolio-item management" data-toggle="modal" data-target="#project2" data-aos="flip-up" data-aos-delay="100">
                  <div class="portfolio-card">
                     <img src="static/resturent.jpeg" alt="Project 2">
                     <div class="portfolio-overlay">
                        <h3>Restaurant Management</h3>
                        <p>Orders, inventory & staff</p>
                        <span class="label label-success">Flask</span><br>
                        <span class="label label-warning">MySQL</span><br>
                        <span class="label label-primary">Bootstrap</span><br>
                     </div>
                  </div>
               </div>
               <!-- Project 3 -->
               <div class="col-sm-4 portfolio-item academic" data-toggle="modal" data-target="#project3" data-aos="flip-up" data-aos-delay="100">
                  <div class="portfolio-card">
                     <img src="static/vote.jpg" alt="Project 3">
                     <div class="portfolio-overlay">
                        <h3>Online Voting</h3>
                        <p>Secure election platform</p>
                        <span class="label label-success">Flask</span><br>
                        <span class="label label-warning">MySQL</span><br>
                        <span class="label label-primary">Bootstrap</span><br>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </section>
      <!-- PORTFOLIO MODALS -->
      <!-- Project 1 Modal -->
      <div class="modal fade" id="project1">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button class="close" data-dismiss="modal">&times;</button>
                  <h4>E-Commerce Website</h4>
               </div>
               <div class="modal-body">
                  <p>
                     A complete online shopping website where users can browse products,
                     add items to cart and place orders through a simple and responsive interface.
                  </p>
                  <h4>Key Features</h4>
                  <ul>
                     <li>Product Listing and Product Details</li>
                     <li>Search Products</li>
                     <li>Add to Cart and Remove from Cart</li>
                     <li>Shopping Cart Management</li>
                     <li>Order Placement System</li>
                     <li>Responsive Design for Mobile and Desktop</li>
                  </ul>
                  <h4>Technologies Used</h4>
                  <h4>FrontEnd</h4>
                  <span class="label label-info">HTML</span><br>
                  <span class="label label-info">CSS</span><br>
                  <span class="label label-primary">Bootstrap</span><br>
                  <span class="label label-warning">JavaScript</span><br>
                  <h4>BackEnd</h4>
                  <span class="label label-success">PHP</span>
                  <h4>DataBase</h4>
                  <span class="label label-success">MySQL</span>
                  <hr>
                  <a href="#contact" class="btn btn-success close-modal-scroll">Request Demo</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Project 2 Modal -->
      <div class="modal fade" id="project2">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button class="close" data-dismiss="modal">&times;</button>
                  <h4>Restaurant Website</h4>
               </div>
               <div class="modal-body">
                  <p>
                     A responsive restaurant website that displays restaurant information,
                     food menu, gallery and contact details for customers.
                  </p>
                  <h4>Key Features</h4>
                  <ul>
                     <li>Restaurant Home Page</li>
                     <li>Food Menu with Dish Name and Price</li>
                     <li>Restaurant Gallery Section</li>
                     <li>About Restaurant Section</li>
                     <li>Contact Information</li>
                     <li>Responsive Design for Mobile and Desktop</li>
                  </ul>
                  <h4>Technologies Used</h4>
                  <h4>FrontEnd</h4>
                  <span class="label label-info">HTML</span><br>
                  <span class="label label-info">CSS</span><br>
                  <span class="label label-primary">Bootstrap</span><br>
                  <span class="label label-warning">JavaScript</span><br>
                  <h4>BackEnd</h4>
                  <span class="label label-success">Flask</span>
                  <h4>DataBase</h4>
                  <span class="label label-success">MySQL</span>
                  <hr>
                  <a href="#contact" class="btn btn-success close-modal-scroll">Request Demo</a>
               </div>
            </div>
         </div>
      </div>
      <!-- Project 3 Modal -->
      <div class="modal fade" id="project3">
         <div class="modal-dialog modal-lg">
            <div class="modal-content">
               <div class="modal-header">
                  <button class="close" data-dismiss="modal">&times;</button>
                  <h4>Online Voting System</h4>
               </div>
               <div class="modal-body">
                  <p>
                     A web-based voting system that allows registered users to vote
                     for candidates online through a secure and simple interface.
                  </p>
                  <h4>Key Features</h4>
                  <ul>
                     <li>Voter Registration System</li>
                     <li>User Login Authentication</li>
                     <li>Candidate List Display</li>
                     <li>Vote Casting System</li>
                     <li>One Vote Per User</li>
                     <li>Vote Counting and Result Display</li>
                     <li>Simple and Responsive Interface</li>
                  </ul>
                  <h4>Technologies Used</h4>
                  <h4>FrontEnd</h4>
                  <span class="label label-info">HTML</span><br>
                  <span class="label label-info">CSS</span><br>
                  <span class="label label-primary">Bootstrap</span><br>
                  <span class="label label-warning">JavaScript</span><br>
                  <h4>BackEnd</h4>
                  <span class="label label-success">Flask</span>
                  <h4>DataBase</h4>
                  <span class="label label-success">MySQL</span>
                  <hr>
                  <a href="#contact" class="btn btn-success close-modal-scroll">Request Demo</a>
               </div>
            </div>
         </div>
      </div>
      <!--OUR TEAM SECTION -->
      <div id="team" class="team-section">
         <div class="container">
            <h1 data-aos="fade-up">
               Our Team
            </h1>
            <div class="row">
               <!-- Team Member 1 -->
               <div class="col-sm-4 col-sm-offset-2">
                  <div class="team-member-card text-center"  data-aos="fade-up" data-aos-delay="100">
                     <img src="static/team_member1.png" alt="Murtaza Ahmad" class="team-member-image img-circle">
                     <div class="team-member-name">Murtaza Ahmad</div>
                     <div class="team-member-role">Web Developer</div>
                  </div>
               </div>
               <!-- Team Member 2 -->
               <div class="col-sm-4">
                  <div class="team-member-card text-center" data-aos="fade-up" data-aos-delay="100">
                     <img src="static/team_member2.jpeg" alt="Suleman Hassan" class="team-member-image img-circle">
                     <div class="team-member-name">Suleman Hassan</div>
                     <div class="team-member-role">Web & App Developer</div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- CONTACT SECTION -->
      <div id="contact" class="contact-section" data-aos="zoom-in">
         <div class="container">
            <h1 data-aos="fade-up">
               Contact Us
            </h1>
            <div class="row">
               <!-- Contact Info -->
               <div class="col-sm-6" data-aos="fade-right" data-aos-delay="100">
                  <p>Have questions or want to get in touch with us? Feel free to reach out!</p>
                  <ul class="contact-info">
                     <li>
                        <i class="fa fa-envelope info"></i>
                        masofthub@gmail.com
                     </li>
                     <li>
                        <i class="fa fa-phone info"></i>
                        +92 337 2513067<br>
                        +92 332 5370248
                     </li>
                  </ul>
               </div>
               <!-- Contact Form -->
               <div class="col-sm-6" data-aos="fade-left" data-aos-delay="200">
                  <form action="index.php#contact" method="post" class="contact-form">
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                           <i class="fa fa-user"></i>
                           </span>
                           <input type="text" name="name" class="form-control" placeholder="Your Name" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                           <i class="fa fa-envelope"></i>
                           </span>
                           <input type="email" name="email" class="form-control" placeholder="Your Email" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <div class="input-group">
                           <span class="input-group-addon">
                           <i class="fa fa-phone"></i>
                           </span>
                           <input type="tel" name="phone" class="form-control" placeholder="Your Phone Number" required>
                        </div>
                     </div>
                     <div class="form-group">
                        <textarea name="message" class="form-control" rows="4" placeholder="Your Message" required></textarea>
                     </div>
                     <button type="submit" class="btn btn-primary btn-block">
                     Send Message
                     </button>
                  </form>
               </div>
            </div>
         </div>
      </div>
      <!-- FOOTER SECTION-->
      <footer class="main-footer" data-aos="fade-up" data-aos-delay="100">
         <div class="container">
            <div class="row">
               <div class="col-md-4 mb-4">
                  <img src="static/logo.png" alt="M.A SoftHub" class="footer-logo mb-3">
                  <h4 class="footer-brand">M.A SoftHub</h4>
                  <p class="footer-desc">
                     Innovative software solutions for modern businesses. We transform ideas into powerful digital experiences.
                  </p>
                  <div class="social-links">
                     <!-- Gmail -->
                     <a href="mailto:masofthub@gmail.com" class="gmail">
                     <i class="fas fa-envelope"></i>
                     </a>
                     <!-- WhatsApp -->
                     <a href="https://wa.me/+923372513067" target="_blank" class="whatsapp">
                     <i class="fab fa-whatsapp"></i>
                     </a>
                  </div>
               </div>
               <div class="col-md-3 mb-4 ps-md-5">
                  <h5 class="footer-heading">Quick Links</h5>
                  <ul class="list-unstyled footer-links">
                     <li><a href="#home">Home</a></li>
                     <li><a href="#services">Services</a></li>
                     <li><a href="#portfolio">Portfolio</a></li>
                     <li><a href="#about">About</a></li>
                     <li><a href="#contact">Contact</a></li>
                  </ul>
               </div>
               <div class="col-md-5 mb-4">
                  <h5 class="footer-heading">Newsletter</h5>
                  <p class="footer-desc">Subscribe to our newsletter for the latest updates and offers.</p>
                  <form class="newsletter-form mt-3" method="POST" action="newsletter.php">
                     <div class="input-group">
                        <input type="email" name="email" class="newsletter-input" placeholder="Your Email" required>
                        <button class="btn-subscribe" type="submit">Subscribe</button>
                     </div>
                  </form>
               </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
               <div class="col-12 text-center">
                  <p class="copyright">© 2026 M.A SoftHub. All Rights Reserved.</p>
               </div>
            </div>
         </div>
      </footer>
      <script>
         $(window).on("load", function () {
             $("#preloader").fadeOut(600, function () {
                 AOS.init({
                     duration: 900,
                     easing: 'ease-in-out',
                     once: true
                 });
             });
         });
      </script>
      <script>
         $(window).on("scroll", function () {
             let scrollTop = $(this).scrollTop();
         
             $('.navbar').css(
                 "background",
                 scrollTop > 50 ? "#0F172A" : "rgba(15,23,42,0.75)"
             );
         
             let docHeight = $(document).height() - $(window).height();
             let progress = (scrollTop / docHeight) * 100;
             $("#progress-bar").css("width", progress + "%");
         });
      </script>
      <script>
         var typed = new Typed("#typed-text", {
             strings: [
                 "Innovative Software Solutions",
                 "We Build Scalable Web Apps",
                 "Modern Digital Experiences"
             ],
             typeSpeed: 60,
             backSpeed: 40,
             backDelay: 1500,
             loop: true
         });
      </script>
      <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
      <script>
         $('.filter-btn').on('click', function () {
         
             $('.filter-btn')
                 .removeClass('btn-primary')
                 .addClass('btn-default');
         
             $(this)
                 .removeClass('btn-default')
                 .addClass('btn-primary');
         
             let filter = $(this).data('filter');
             $('.portfolio-item').hide();
         
             filter === 'all'
                 ? $('.portfolio-item').fadeIn()
                 : $('.' + filter).fadeIn();
         });
      </script>
      <script>
         $('.close-modal-scroll').on('click', function () {
         
             $('.modal').modal('hide');
         
             setTimeout(function () {
                 $('html, body').animate({
                     scrollTop: $('#contact').offset().top - 90
                 }, 700);
             }, 500);
         });
      </script>
      <script>
         $('.contact-form').on('submit', function () {
             $(this).find('button[type="submit"]').prop('disabled', true).text('Sending...');
         });
      </script>
   </body>
</html>