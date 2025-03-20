<?php
$servername = "localhost";
$username = "root";
$password = ""; // Replace with your DB password
$dbname = "online"; // Replace with your DB name

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$successMessage = ""; // Declare a variable to hold the success message
$showPopup = false; // Variable to determine if the popup should be shown

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $number = $_POST['number'];
    $message = $_POST['message'];

    // Prepare the SQL query
    $stmt = $conn->prepare("INSERT INTO Enquiries (name, email, number, message) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $number, $message);

    // Execute the query and set the success message
    if ($stmt->execute()) {
        $successMessage = "Your message has been sent successfully!"; // Set success message
        $showPopup = true; // Set the flag to show the popup
    } else {
        $successMessage = "Error: Could not send your message. Please try again later."; // Set error message
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="need.css">
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


    <!-- Contact Section -->
   <div class="contact-container container">
    <div class="row">
        <!-- Contact Info -->
        <div class="col-lg-6">
            <h1>Contact Us</h1>
            <h4 class="mb-4">
                Let us know your queries, feedback, and inquiries. We are here to support you 24/7.
            </h4><br>
            <!-- Info Items -->
            <div class="info-item d-flex align-items-center mb-4">
                <h2 class="me-3">📞</h2>
                <div>
                    <h3>Give us a call:</h3>
                    <p>0000000000</p>
                </div>
            </div><br>
            <div class="info-item d-flex align-items-center mb-4">
                <h2 class="me-3">📧</h2>
                <div>
                    <h3>Write to us:</h3>
                    <p>enquiry@harzatech.com</p>
                </div>
            </div><br>
            <div class="info-item d-flex align-items-center">
                <h2 class="me-3">📍</h2>
                <div>
                    <h3>Visit us in Chennai:</h3>
                    <p>New no 31 Pandian Nagar, Manamadurai, Chennai - 600024</p>
                </div>
            </div>
        </div>
        <!-- Form -->
        <div class="col-lg-6">
            <div class="contact-form">
                <h2 class="text-center mb-4">Say <span class="text-primary">Hello!</span></h2>
                <form action="needhelp.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" id="name" name="name" class="form-control" required placeholder="Enter your name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email">
                    </div>
                    <div class="mb-3">
                        <label for="number" class="form-label">Mobile Number <span class="text-danger">*</span></label>
                        <input type="tel" id="number" name="number" class="form-control" required placeholder="Enter your mobile number">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">How can we help you?</label>
                        <textarea id="message" name="message" class="form-control" rows="4" placeholder="Type your message here..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="send">Send Mail</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- JavaScript to show the popup and hide it after a few seconds -->
<script>
    <?php if ($showPopup): ?>
        Swal.fire({
            title: 'Thank You!',
            text: '<?php echo $successMessage; ?>',
            icon: 'success',
            confirmButtonText: 'OK',
            background: '#f8f9fa',
            color: '#1d1d1d',
            timer: 5000, // Message will disappear after 5 seconds
            showConfirmButton: true,
            willClose: () => {
                window.location.href = "needhelp.php"; // Redirect after closing popup (optional)
            }
        });
    <?php endif; ?>
</script><br>
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

</body>
</html>
