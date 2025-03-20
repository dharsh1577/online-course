<?php
session_start();

// Logout logic: If the user clicks logout, the session is destroyed
if (isset($_GET['logout'])) {
    session_unset(); // Clear all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page after logout
    exit;
}

// Default user details if logged in
$user_details = null;
if (isset($_SESSION['user_id'])) {
    // Fetch user details from the database if the user is logged in
    require_once '../db.php';
    $conn = new mysqli('localhost', 'root', '', 'online'); // Adjust connection details as needed
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_sql = "SELECT username, email FROM Users WHERE user_id = ?";
    $user_stmt = $conn->prepare($user_sql);
    $user_stmt->bind_param("i", $_SESSION['user_id']);
    $user_stmt->execute();
    $user_result = $user_stmt->get_result();
    $user_details = $user_result->fetch_assoc();
    $user_stmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harza</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
    <a class="navbar-brand" href="#">
        <img src="logo.png" alt="Logo"> Harza Tech
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
                <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'guide.php' ? 'active' : '' ?>" href="guide.php">Testimonial</a>
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
               
                <?php if ($user_details): ?>
                    <!-- User Dropdown Menu -->
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                       <span style="font-size: 24px; color: #007bff;">👤</span> <?php echo htmlspecialchars($user_details['username']); ?> <!-- Display username -->
                      </a>
                      <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><span class="dropdown-item-text"><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                       <li><a class="dropdown-item text-danger" href="?logout=true">Logout</a></li>
                     </ul>
                 </li>

                <?php else: ?>
                    <!-- Show login button when the user is not logged in -->
                    <li class="nav-item">
                        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>" href="login.php"></a>
                    </li>
                <?php endif; ?>
        </ul>
    </div>
</nav>
<style>
    /* Style for User Dropdown Menu */
.nav-item .nav-link {
    display: flex;
    align-items: center;
    font-size: 18px;
    padding: 10px 15px;
}

/* User Icon styling */
.nav-item .nav-link span {
    margin-right: 8px; /* Space between the emoji and the username */
    font-size: 24px;
    color: blue;
}

/* Dropdown Menu Styling */
.dropdown-menu {
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 8px 0;
    min-width: 200px;
}

/* Style the individual dropdown items */
.dropdown-menu .dropdown-item {
    font-size: 16px;
    padding: 10px 20px;
    transition: background-color 0.3s ease;
}

/* Hover effect for dropdown items */
.dropdown-menu .dropdown-item:hover {
    background-color: #007bff;
    color: white;
}

/* Style for the text inside the dropdown */
.dropdown-menu .dropdown-item-text {
    font-size: 14px;
    color: #555;
}

/* Adjust dropdown divider */
.dropdown-divider {
    border-color: #ddd;
}

/* Style for the Logout button */
.dropdown-menu .text-danger {
    font-weight: bold;
    color: #dc3545 !important;
}

/* Right align the dropdown menu */
.dropdown-menu-end {
    right: 0;
    left: auto;
}

/* Show login button when the user is not logged in */
.nav-item .nav-link {
    font-size: 16px;
}

</style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1>
                Make waves in Harza tech
            </h1>
            <div class="typewriter" id="typewriter"></div>
            <div class="stats">
                <div>
                    <h4>1000+</h4>
                    <p>Impact on student</p>
                </div>
                <div>
                    <h4>2k+</h4>
                    <p>Digitally Reached</p>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <img src="hero.png" alt="Hero Image"> <!-- Replace with your hero image -->
        </div>
    </section>

    <section class="slider-section">
    <h2 class="slider-heading">Top-Rated Learning Program</h2>
    <div class="slider">
        <div class="list">
            <div class="item card">
                <img src="ui.jpg" class="card-img-top" alt="Image 1">
                <div class="card-body">
                    <p class="card-text">3D with Blender</p>
                </div>
            </div>
            <div class="item card">
                <img src="ml.jpg" class="card-img-top" alt="Image 2">
                <div class="card-body">
                    <p class="card-text">Machine Learning</p>
                </div>
            </div>
            <div class="item card">
                <img src="cloud.jpg" class="card-img-top" alt="Image 3">
                <div class="card-body">
                    <p class="card-text">Cloud Computing</p>
                </div>
            </div>
            <div class="item card">
                <img src="digital.jpg" class="card-img-top" alt="Image 4">
                <div class="card-body">
                    <p class="card-text">Digital Marketing</p>
                </div>
            </div>
            <div class="item card">
                <img src="full stack.jpg" class="card-img-top" alt="Image 4">
                <div class="card-body">
                    <p class="card-text">Full Stack</p>
                </div>
            </div>
            <div class="item card">
                <img src="ui.jpg" class="card-img-top" alt="Image 5">
                <div class="card-body">
                    <p class="card-text">3D with Blender</p>
                </div>
            </div>
            <div class="item card">
                <img src="ml.jpg" class="card-img-top" alt="Image 6">
                <div class="card-body">
                    <p class="card-text">Machine Learning</p>
                </div>
            </div>
            <div class="item card">
                <img src="cloud.jpg" class="card-img-top" alt="Image 7">
                <div class="card-body">
                    <p class="card-text">Cloud Computing</p>
                </div>
            </div>
            <div class="item card">
                <img src="digital.jpg" class="card-img-top" alt="Image 8">
                <div class="card-body">
                    <p class="card-text">Digital Marketing</p>
                </div>
            </div>
        </div>
        <div class="enroll-container">
            <a href="ourcourse.php#enroll-form" class="btn-enroll">Enroll</a>
        </div>

    </div>
</section>
<section class="tools-section">
    <h2 class="tools-heading">Tools <span>Covered</span></h2>
    <p class="tools-subheading">Master 12+ Tools Directly from Top Industry Experts</p>

    <!-- Tools Container -->
    <div class="tools-carousel">
        <div class="tools-slide active">
            <div class="tool-card1">
                <img src="vscode.png" alt="VS Code" >
                <p>VS Code</p>
            </div>
            <div class="tool-card">
                <img src="info.png" alt="HTML">
                <p>HTML</p>
            </div>
            <div class="tool-card">
                <img src="css.png" alt="CSS">
                <p>CSS</p>
            </div>
            <div class="tool-card">
                <img src="tailwind.png" alt="Tailwind CSS">
                <p>Tailwind CSS</p>
            </div>
            <div class="tool-card1">
                <img src="boot.png" alt="Tailwind CSS">
                <p>Bootstrap</p>
            </div>
        </div>
        <div class="tools-slide">
            <div class="tool-card1">
                <img src="firebase.png" alt="Firebase">
                <p>Firebase</p>
            </div>
            <div class="tool-card">
                <img src="git.png" alt="Git">
                <p>Git</p>
            </div>
            <div class="tool-card">
                <img src="js.png" alt="JavaScript">
                <p>JavaScript</p>
            </div>
            <div class="tool-card">
                <img src="mango.png" alt="MongoDB">
                <p>MongoDB</p>
            </div>
            <div class="tool-card">
                <img src="php.png" alt="MongoDB">
                <p>Php</p>
            </div>
        </div>
        <div class="tools-slide">
            <div class="tool-card1">
                <img src="nodejs.png" alt="NodeJS">
                <p>NodeJS</p>
            </div>
            <div class="tool-card1">
                <img src="angular.png" alt="Angular">
                <p>Angular</p>
            </div>
            <div class="tool-card1">
                <img src="react.png" alt="React">
                <p>React</p>
            </div>
            <div class="tool-card1">
                <img src="firebase.png" alt="Firebase">
                <p>Firebase</p>
            </div>
           
        </div>
    </div>

    <!-- Dots for Navigation -->
    <div class="tools-dots">
        <span class="dot active" data-slide="0"></span>
        <span class="dot" data-slide="1"></span>
        <span class="dot" data-slide="2"></span>
    </div>
    <script>
        // Select all dots and slides
        const dots = document.querySelectorAll('.dot');
        const slides = document.querySelectorAll('.tools-slide');

        // Add event listeners to each dot
        dots.forEach(dot => {
            dot.addEventListener('click', () => {
                // Remove 'active' class from all dots and slides
                dots.forEach(d => d.classList.remove('active'));
                slides.forEach(slide => slide.classList.remove('active'));

                // Add 'active' class to the clicked dot and corresponding slide
                dot.classList.add('active');
                const slideIndex = dot.getAttribute('data-slide');
                slides[slideIndex].classList.add('active');
            });
        });

        // Automatically activate the first dot and slide 0 on page load
        document.addEventListener('DOMContentLoaded', () => {
            dots.forEach(d => d.classList.remove('active'));
            slides.forEach(slide => slide.classList.remove('active'));
            dots[0].classList.add('active');
            slides[0].classList.add('active');
        });
    </script>
</section>



    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Typing Effect Script -->
    <script>
        const words = ["Learn", "Build", "Succeed"];
        let currentWordIndex = 0;
        let currentCharIndex = 0;
        const typewriterElement = document.getElementById("typewriter");
        let isDeleting = false;

        function type() {
            const currentWord = words[currentWordIndex];
            const displayText = isDeleting
                ? currentWord.substring(0, currentCharIndex--)
                : currentWord.substring(0, currentCharIndex++);

            typewriterElement.textContent = displayText;

            if (!isDeleting && currentCharIndex === currentWord.length) {
                isDeleting = true;
                setTimeout(type, 500); // Pause before deleting
            } else if (isDeleting && currentCharIndex === 0) {
                isDeleting = false;
                currentWordIndex = (currentWordIndex + 1) % words.length;
                setTimeout(type, 200); // Pause before typing next word
            } else {
                setTimeout(type, isDeleting ? 100 : 100); // Adjusted speed
            }
        }

        type();
    </script>
    <!-- Join a Global Movement Section -->
<div class="community-section">
    <h3>COMMUNITY</h3>
    <h2>Join a global environment</h2>
    <p>Our  vibrant  community  produces  content, teaches courses,  and  leads  students  all  over.</p>
    <div class="community-avatars"> 
        <img src="avatar1.png" alt="Avatar 1">
        <img src="avatar2.png" alt="Avatar 2">
        <img src="avatar3.png" alt="Avatar 3">
        <img src="avatar4.png" alt="Avatar 4">
        <img src="avatar5.png" alt="Avatar 5">
        <img src="avatar6.png" alt="Avatar 5">
        <img src="avatar7.png" alt="Avatar 5">
    </div>
    <div class="community-stats">
        <div class="stat">
            <h3>1000+</h3>
            <p>Learners</p>
        </div>
        <div class="stat">
            <h3>10k+</h3>
            <p>Subscribers on YouTube</p>
        </div>
        <div class="stat">
            <h3>2000+</h3>
            <p>Students on WhatsApp Channel</p>
        </div>
        <div class="stat">
            <h3>1000+</h3>
            <p>Followers on LinkedIn</p>
        </div>
    </div>
</div>
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

<!-- Optional Footer Script for Scroll to Top -->
<script>
    // Smooth scroll to top button
    document.addEventListener('DOMContentLoaded', () => {
        const scrollToTopBtn = document.createElement('button');
        scrollToTopBtn.innerHTML = '↑';
        scrollToTopBtn.classList.add('scroll-to-top');
        document.body.appendChild(scrollToTopBtn);
        
        scrollToTopBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });

        window.addEventListener('scroll', () => {
            if (window.scrollY > 300) {
                scrollToTopBtn.style.display = 'block';
            } else {
                scrollToTopBtn.style.display = 'none';
            }
        });
    });
</script>

<style>
    /* Footer Styling */
    .footer {
        background-color: #007bff; /* Blue background */
        color: white;
    }

    .footer h5 {
        font-size: 24px;
        margin-bottom: 10px;
    }

    .footer p {
        font-size: 16px;
    }

    .footer a {
        color: white;
        text-decoration: none;
    }

    .footer a:hover {
        text-decoration: underline;
    }

    .scroll-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        background-color: black;
        color: white;
        border: none;
        padding: 10px;
        font-size: 18px;
        cursor: pointer;
        display: none;
    }

    .scroll-to-top:hover {
        background-color: #0056b3;
    }
</style>


</body>
</html>
