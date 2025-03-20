
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harza</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="upcoming.css">
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
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'career.php' ? 'active' : '' ?>" href="guide.php"> Testimonial</a>
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

      <!-- Main Content -->
      <div class="container py-5">
        <h2 class="heading">Upcoming Courses</h2>
        <div class="row g-4">
            <?php
            // Database connection
            $conn = new mysqli("localhost", "root", "", "online");

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Fetch data from Upcoming table
            $sql = "SELECT course_name, course_image FROM Upcoming";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output each course as a card
                while ($row = $result->fetch_assoc()) {
                    echo '
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="image-container">
                                <img src="' . htmlspecialchars($row['course_image']) . '" alt="' . htmlspecialchars($row['course_name']) . '">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">' . htmlspecialchars($row['course_name']) . '</h5>
                                <!-- Skeleton loader for description -->
                                <div class="details"></div>
                                <div class="btn-placeholder"></div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No upcoming courses available.</p>';
            }

            $conn->close();
            ?>
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>