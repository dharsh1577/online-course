<?php
session_start();
require_once '../db.php'; // Adjust the path as per your project structure

// Fetch feedback from the database
$conn = new mysqli('localhost', 'root', '', 'online');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "
    SELECT 
        f.feedback_id, f.feedback_text, f.rating, f.feedback_date, u.username 
    FROM 
        Feedback f 
    INNER JOIN 
        Users u 
    ON 
        f.user_id = u.user_id
    ORDER BY 
        f.feedback_date DESC
    LIMIT 20"; // Fetch the latest 20 feedbacks
$result = $conn->query($sql);

$feedbacks = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $feedbacks[] = $row;
    }
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harza</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="guide.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="#">
            <img src="logo.png" alt="Logo"> <!-- Replace with your logo image -->
            Harza Tech
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : '' ?>" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'ourcourse.php' ? 'active' : '' ?>" href="ourcourse.php">Our Course</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'guide.php' ? 'active' : '' ?>" href="guide.php"> Testimonial</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'upcoming.php' ? 'active' : '' ?>" href="upcoming.php">Crash Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'Needhelp.php' ? 'active' : '' ?>" href="Needhelp.php">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>" href="login.php">Log In</a>
                </li>
            </ul>
        </div>
    </nav>

    <section class="slider-section">
        <h1>Hear What Our Students Say</h1><br>
        <div class="slider">
            <div class="list">
                <?php foreach ($feedbacks as $feedback): ?>
                    <div class="item">
                        <p class="username"><?php echo htmlspecialchars($feedback['username']); ?></p>
                        <p class="feedback-text"><?php echo htmlspecialchars($feedback['feedback_text']); ?></p>
                        <div class="rating">
                            <?php for ($i = 0; $i < $feedback['rating']; $i++): ?>
                                <i class="bi bi-star-fill"></i>
                            <?php endfor; ?>
                            <?php for ($i = $feedback['rating']; $i < 5; $i++): ?>
                                <i class="bi bi-star"></i>
                            <?php endfor; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Why Us Section -->
    <section class="why-us-section text-center">
        <!-- Subheading -->
        <div class="subheading">Why us</div>
        <!-- Description -->
        <p class="description">Why We're the Best Choice for Your Learning</p>
        <!-- Cards Row -->
        <div class="container">
            <div class="row gy-4">
                <!-- Card 1 -->
                <div class="col-md-4">
                    <div class="card card-custom card-1">
                        <h4>Simplified Teaching</h4>
                        <p>90% of our students love our easy-to-understand teaching methods.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-4">
                    <div class="card card-custom card-2">
                        <h4>24/7 Doubt Support</h4>
                        <p>Get your doubts cleared anytime through various channels.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-4">
                    <div class="card card-custom card-3">
                        <h4>Experienced Educators</h4>
                        <p>Learn from experts who've worked at top companies like Zoho, Accenture, and Infosys.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="full-stack-course" class="py-5">
    <div class="container">
        <!-- Section Heading -->
        <h2 class="text-center mb-4">Learn Full Stack Web Development from Mentors</h2>
        <p class="text-center mb-5">Our mentors have worked with top companies like:</p>

        <!-- Cards Section -->
        <div class="row g-3">
            <!-- Card 1 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="accen.png" class="card-img-top" alt="EMC-1">
                    <div class="card-body text-center">
                        <h5 class="card-title">ACCENTURE</h5>
                    </div>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="zoho.png" class="card-img-top" alt="EMC-2">
                    <div class="card-body text-center">
                        <h5 class="card-title">ZOHO</h5>
                    </div>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="infos.png" class="card-img-top" alt="EMC-3">
                    <div class="card-body text-center">
                        <h5 class="card-title">INFOSYS</h5>
                    </div>
                </div>
            </div>
            <!-- Card 4 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="tcs.png" class="card-img-top" alt="EMC-4">
                    <div class="card-body text-center">
                        <h5 class="card-title">TCS</h5>
                    </div>
                </div>
            </div>
            <!-- Card 5 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="v.png" class="card-img-top" alt="EMC-5">
                    <div class="card-body text-center">
                        <h5 class="card-title">VIMEO</h5>
                    </div>
                </div>
            </div>
            <!-- Card 6 -->
            <div class="col-md-4">
                <div class="card">
                    <img src="logo.png" class="card-img-top" alt="EMC-6">
                    <div class="card-body text-center">
                        <h5 class="card-title">HARZA</h5>
                    </div>
                </div>
            </div>
           
</section>

<section id="faq" class="py-5 bg-light">
    <div class="container">
        <!-- Section Heading -->
        <h2 class="text-center mb-4">Frequently Asked Questions</h2>
        
        <!-- FAQ Accordion -->
        <div class="accordion" id="faqAccordion">
            <!-- Question 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        What is Full Stack Web Development?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Full Stack Web Development refers to the development of both the front-end (client-side) and back-end (server-side) of web applications. A full-stack developer is proficient in handling all aspects of web development.
                    </div>
                </div>
            </div>
            <!-- Question 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        How long does it take to learn Web Development?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        The time it takes to learn  Web Development depends on your prior knowledge and dedication. On average, it may take around 6-12 months if you're learning part-time. If you dedicate more time, you can learn faster.
                    </div>ack
                </div>
            </div>
            <!-- Question 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Do I need a degree to become Developer?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        No, you don't need a formal degree to become a Developer. Many developers are self-taught or have completed coding bootcamps. A strong portfolio and practical experience are more important.
                    </div>
                </div>
            </div>
            <!-- Question 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        What are the essential skills for a  web Developer?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        A Web Developer should have skills in HTML, CSS, JavaScript (for front-end), and be familiar with back-end languages such as Node.js, Python, Ruby, or PHP. Knowledge of databases (MySQL, MongoDB) and version control systems (Git) is also essential.
                    </div>
                </div>
            </div>
            <!-- Question 5 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFive">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                        What are the job opportunities after completing Full Stack Web Development?
                    </button>
                </h2>
                <div id="collapseFive" class="accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        After completing Full Stack Web Development, you can work as a Full Stack Developer, Front-End Developer, Back-End Developer, or a Web Developer in various industries. Many tech companies offer lucrative positions for skilled developers.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Section: Why You Shouldn't Miss This Course -->
<section class="bg-white py-5 text-center">
  <h2 class="fs-4 fw-bold text-dark">Why You Shouldn't Miss This Course</h2>
  <div class="container">
    <div class="row justify-content-center mt-4">
      <!-- Card 1 -->
      <div class="col-12 col-md-3 mb-4">
        <div class="card border-primary rounded-3 p-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA; height: 100%;">
          <h3 class="fs-5 fw-bold text-dark">👩‍💻 Working Professionals</h3>
          <p class="fs-6 text-muted">Looking to upskill or pivot into tech.</p>
        </div>
      </div>
      <!-- Card 2 -->
      <div class="col-12 col-md-3 mb-4">
        <div class="card border-primary rounded-3 p-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA; height: 100%;">
          <h3 class="fs-5 fw-bold text-dark">🎓 Freshers</h3>
          <p class="fs-6 text-muted">Aiming to break into the tech industry.</p>
        </div>
      </div>
      <!-- Card 3 -->
      <div class="col-12 col-md-3 mb-4">
        <div class="card border-primary rounded-3 p-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA; height: 100%;">
          <h3 class="fs-5 fw-bold text-dark">📚 Students</h3>
          <p class="fs-6 text-muted">Eager to gain real-world insights and skills.</p>
        </div>
      </div>
      <!-- Card 4 -->
      <div class="col-12 col-md-3 mb-4">
        <div class="card border-primary rounded-3 p-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA; height: 100%;">
          <h3 class="fs-5 fw-bold text-dark">🔄 Career Gap</h3>
          <p class="fs-6 text-muted">Individuals ready to re-enter the workforce with in-demand skills.</p>
        </div>
      </div>
    </div>
    <div class="row justify-content-center">
      <!-- Card 5 -->
      <div class="col-12 col-md-5 mb-4">
        <div class="card border-primary rounded-3 p-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA; height: 100%;">
          <h3 class="fs-5 fw-bold text-dark">🚀 Job Switchers</h3>
          <p class="fs-6 text-muted">Exploring new opportunities in tech fields.</p>
        </div>
      </div>
      <!-- Card 6 -->
      <div class="col-12 col-md-5 mb-4">
        <div class="card border-primary rounded-3 p-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA; height: 100%;">
          <h3 class="fs-5 fw-bold text-dark">💡 Aspiring Tech Enthusiasts</h3>
          <p class="fs-6 text-muted">Interested in AI, Data Science, and Full Stack.</p>
        </div>
      </div>
    </div>
  </div>
  <!-- Register Now Button -->
  <a href="ourcourse.php" class="btn btn-primary btn-lg mt-4" style=" box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA;">Register Now</a>
</section>



<!-- Footer -->
<footer class="bg-primary text-light pt-5 pb-4">
    <div class="container">
        <div class="row">
            <!-- Company Info -->
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="text-uppercase text-warning">Harza Tech</h5>
                <p class="small">
                    Harza Tech is your trusted partner for top-notch tech solutions and learning. Let us help you achieve your goals with expert guidance and support.
                </p>
            </div>

            <!-- Quick Links -->
            <div class="col-lg-2 col-md-6 mb-4">
                <h5 class="text-uppercase text-warning">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="home.php" class="text-light text-decoration-none">Home</a></li>
                    <li><a href="ourcourse.php" class="text-light text-decoration-none">Our Course</a></li>
                    <li><a href="guide.php" class="text-light text-decoration-none">Guidance</a></li>
                    <li><a href="upcoming.php" class="text-light text-decoration-none">Crash Courses</a></li>
                    <li><a href="login.php" class="text-light text-decoration-none">Log In</a></li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-3 col-md-6 mb-4">
                <h5 class="text-uppercase text-warning">Contact</h5>
                <ul class="list-unstyled">
                    <li><i class="fas fa-phone-alt me-2"></i> +91 0000000000</li>
                    <li><i class="fas fa-envelope me-2"></i> enquiry@harzatech.com</li>
                    <li><i class="fas fa-map-marker-alt me-2"></i> Chennai, Tamil Nadu</li>
                </ul>
            </div>

            <!-- Social Media Links -->
           <!-- Social Media Links -->
<div class="col-lg-3 col-md-6 mb-4">
    <h5 class="text-uppercase text-warning">Follow Us</h5>
    <div class="d-flex">
        <a href="#" class="btn social-btn rounded-circle me-2">
            <i class="fab fa-facebook-f"></i>
        </a>
        <a href="#" class="btn social-btn rounded-circle me-2">
            <i class="fab fa-twitter"></i>
        </a>
        <a href="#" class="btn social-btn rounded-circle me-2">
            <i class="fab fa-instagram"></i>
        </a>
        <a href="#" class="btn social-btn rounded-circle">
            <i class="fab fa-linkedin-in"></i>
        </a>
    </div>
</div>

        </div>

        <hr class="border border-light opacity-25">

        <!-- Footer Bottom -->
        <div class="row">
            <div class="col-md-12 text-center">
                <p class="mb-0 text-muted">
                    © 2025 Harza Tech. All Rights Reserved. Designed with <span class="text-danger">❤️</span> by Harza Tech.
                </p>
            </div>
        </div>
    </div>
</footer>

<!-- FontAwesome for Icons -->
<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</body>
</html>