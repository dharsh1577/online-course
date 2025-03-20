<?php
require_once '../db.php'; // Ensure the correct path to your database connection file

function fetchCourses($conn) {
    $sql = "SELECT course_id, title, description, price, lessons, image_url FROM courses";
    $result = $conn->query($sql);

    $courses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    return $courses;
}
$courses = fetchCourses($conn);
$conn->close();

$conn = new mysqli('localhost', 'root', '', 'online');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course_id = $_POST['course'];

    // Check if the user exists with the provided name and email
    $user_check_query = "SELECT user_id FROM Users WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($user_check_query);
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        $enroll_query = "INSERT INTO enrollments (student_id, course_id, status) VALUES (?, ?, 'pending')";
        $stmt = $conn->prepare($enroll_query);
        $stmt->bind_param("ii", $user_id, $course_id);
        if ($stmt->execute()) {
            // Successfully enrolled, redirect to payment.php
            $enrollment_id = $stmt->insert_id;
            $reference_id = "payment-" . $enrollment_id;

            $response = generatepaymentlink($name, $course_price, $email, $reference_id, $phone);
            if (isset($response['short_url'])) {
            // Update payment ID in the database
            $payment_id = $response['id'];
            $update_payment_query = "UPDATE enrollments SET payment_id = ? WHERE enrollment_id = ?";
            $stmt = $conn->prepare($update_payment_query);
            $stmt->bind_param("si", $payment_id, $enrollment_id);
            $stmt->execute();
            echo "<script>
                window.location.href='" . $response['short_url'] . "';
                  </script>";
            } else {
            echo "<script>alert('Error generating payment link.');</script>";
            }
        } else {
            echo "<script>alert('Error during enrollment.');</script>";
        }
        $stmt->close();
    } else {
        // If user does not exist, show message and redirect to login page
        echo "<script>
                alert('You are not registered. Please log in first.');
                window.location.href='login.php';
              </script>";
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link rel="stylesheet" href="ourcourse.css">
   
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
  
    <!-- Main Content -->
    <div class="container">
        <!-- Main Courses Section -->
        <div class="main-courses-container">
            <div class="d-flex align-items-center mb-3">
                <h3 class="mb-0">Main Courses</h3>
                <a href="mycourse.php" class="my-courses-link">My Courses</a>
            </div>
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-md-6">
                        <div class="card main-card">
                            <img src="<?php echo $course['image_url'] ?: 'ui.jpg'; ?>" alt="Course Image">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <?php echo $course['title']; ?>
                                </h5>
                                <p class="card-description">
                                    <?php echo $course['description']; ?>
                                </p>
                                <div class="card-details">
                                    <span>
                                        <i class="fas fa-clock icon"></i>
                                        <?php echo $course['lessons']; ?> hours
                                    </span>
                                    <span>
                                        <i class="fas fa-tag icon"></i>
                                        <?php echo $course['price']; ?>
                                    </span>
                                    <span>
                                        <i class="fas fa-book icon"></i>
                                        <?php echo $course['lessons']; ?>
                                    </span>
                                    <span>
                                 <!-- Register button -->
                                 <a href="<?php 
                                    // Determine the registration page based on course title or ID
                                    if ($course['title'] == '3D with Blender') {
                                        echo 'blender.php';
                                    } elseif ($course['title'] == 'Digital Marketing') {
                                        echo 'digital.php';
                                    } elseif ($course['title'] == 'Machine Learning') {
                                        echo 'ml.php';
                                    } elseif ($course['title'] == 'Full stack developer') {
                                        echo 'fullstack.php';
                                    } ?>" 
                                      class="register-link mt-3">Register</a>
                                    </span>
                                </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Course Details Section -->
    <div class="container-fluid">
        <div class="row">
            <!-- Left side - Video Background -->
            <div class="col-md-6 video-container">
                <video autoplay loop muted>
                    <source src="video.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div class="overlay">
                    <h1 class="heading">Become a Web Developer</h1>
                </div>
            </div>

            <!-- Right side - Form -->
            <div class="col-md-6">
            <div class="form-section" id="enroll-form">

                    <h2 class="text-center">I'm Interested</h2>
                    <form action="payment1.php" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">* Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">* Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-4">
                            <label for="course" class="form-label">* Course Name</label>
                            <select class="form-control" id="course" name="course" required>
                                <option value="" disabled selected>Select a Course</option>
                                <?php
                    // PHP code to fetch courses with their price from the database
                    $conn = new mysqli('localhost', 'root', '', 'online');
                    $sql = "SELECT course_id, title, price FROM courses";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        // Format course name and price as "Course Name - Price"
                        $course_display = "{$row['title']} - ₹" . number_format($row['price'], 2);
                        echo "<option value='{$row['course_id']}'>{$course_display}</option>";
                    }
                    $conn->close();
                ?>
                            </select>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary btn-block w-50">Register </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Overview Section -->
    <div class="lusu">
        <section id="course-details" class="course-details py-5">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left Side - Image -->
                    <div class="col-md-6 text-center">
                        <img src="teach.png" alt="Course Image" class="img-fluid rounded shadow">
                    </div>
                    <!-- Right Side - Course Overview -->
                    <div class="col-md-6">
                        <h2 class="course-heading">Course Overview</h2>
                        <!-- Overview Cards -->
                        <div class="course-card">
                            <h5><span class="course-icon">👨‍🏫</span></h5>
                            <p>Experience with Industry Experts</p>
                        </div>
                        <div class="course-card">
                            <h5><span class="course-icon">🌐</span></h5>
                            <p>Lifetime access to community</p>
                        </div>
                        <div class="course-card">
                            <h5><span class="course-icon">🎥</span></h5>
                            <p>Lifetime Access to Recorded Sessions.</p>
                        </div>
                        <div class="course-card">
                            <h5><span class="course-icon">📋</span></h5>
                            <p>Placement Assistance Includes, Resume LinkedIn</p>
                        </div>
                        <div class="course-card">
                            <h5><span class="course-icon">💡</span></h5>
                            <p>Empower knowledge</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- Footer Section -->
<footer class="footer bg-primary text-white py-4">
    <div class="container">
        <div class="row">
            <!-- Footer Column 1: Contact Information -->
            <div class="col-md-4">
                <h5>Contact Us</h5>
                <p>Harza Tech, 123 Tech Avenue, Tech City, 12345</p>
                <p>Email: contact@harzatech.com</p>
                <p>Phone: +123 456 7890</p>
            </div>
            <!-- Footer Column 2: Social Media Links -->
            <div class="col-md-4">
                <h5>Follow Us</h5>
                <ul class="list-unstyled d-flex">
                    <li><a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a></li>
                    <li><a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a></li>
                    <li><a href="#" class="text-white me-3"><i class="fab fa-linkedin-in"></i></a></li>
                    <li><a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a></li>
                </ul>
            </div>
            <!-- Footer Column 3: Quick Links -->
            <div class="col-md-4">
                <h5>Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="home.php" class="text-white">Home</a></li>
                    <li><a href="ourcourse.php" class="text-white">Our Courses</a></li>
                    <li><a href="guide.php" class="text-white">Guidance</a></li>
                    <li><a href="upcoming.php" class="text-white">Crash Courses</a></li>
                </ul>
            </div>
        </div>
        <!-- Footer Bottom Text -->
        <div class="text-center mt-4">
            <p>&copy; 2025 Harza Tech. All rights reserved.</p>
        </div>
    </div>
</footer>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
