<?php
require_once '../db.php'; // Ensure this file has the correct database connection
// Fetch course price from Courses table (assuming Digital Marketing course has course_id = 2)
$course_id = 3; // Assuming course_id for Digital Marketing
$stmt = $conn->prepare("SELECT price FROM Courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($course_price);
$stmt->fetch();
$stmt->close();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $course_id = 3; // Assuming "Machine Learning" has course_id = 3

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
    <title>Machine Learning</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />
    <!-- Custom CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: white;
            background-image: url('back1.png');
    background-size: cover; /* Ensures the image covers the full width */
    background-position: left center; /* Moves the image to the left */
    background-repeat: no-repeat; /* Prevents tiling */
}
           
        
        .hero-section {
            padding: 80px 0;
        }
        .highlight {
            color: #007bff;
            font-weight: bold;
        }
      
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-section {
            padding: 20px;
            background-color: transparent;
            border-radius: 10px;
            height: 100%;
            box-shadow: 0 0 5px #007bff, 0 0 10px #007bff;
            margin-bottom: 0;
        }
        .form-label {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .form-section h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .form-section .form-control {
            border-radius: 5px;
            padding: 10px;
            font-size: 1rem;
        }
        .form-section .btn {
    font-size: 1.2rem;
    padding: 12px;
    border-radius: 5px;
    font-weight: bold;
}

.form-control::placeholder {
    color: #aaa;
}

.form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 5px #007bff, 0 0 10px #007bff;
}

.btn {
    display: block;
    width: 50%;
    padding: 10px;
    font-size: 16px;
    background: #007bff;
    color: #000;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-transform: uppercase;
    transition: 0.3s ease;
}

.btn:hover {
    background: #007bff;
    box-shadow: 0 0 10px #007bff, 0 0 20px #007bff, 0 0 30px #007bff;
}

        h1{
            font-size: 3.5rem;
            font-weight: 700;
            
        }
        p{
            font-size: 1.2rem;
            font-weight: 500;

        }
        /* Course Details Section */
.course-details {
    background-color: transparent;
    padding-top: 0;
    margin-top: 0;
    position: relative;
    
}

.course-heading {
    font-size: 28px;
    font-weight: bold;
    text-transform: uppercase;
    margin-top: 0;
    color: #007bff;
    margin-bottom: 30px;
    margin-left: 30px;
}

.course-card {
    background-color: white;
    border-radius: 10px;
    box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
    padding: 15px 20px;
    margin-bottom: 18px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    align-items: center;
    margin-left: 30px;
    box-shadow: 0 0 5px #007bff, 0 0 10px #007bff;
}

.course-card:hover {
    transform: translateY(-5px);
    box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.2);
}

.course-card .course-icon {
    margin-right: 10px;
    font-size: 40px;
    vertical-align: middle;
}

.course-card h5 {
    font-size: 18px;
    font-weight: bold;
    color: #343a40;
    display: inline-flex;
    align-items: center;
    margin-right: 10px;
    margin-bottom: 0;
}

.course-card p {
    font-size: 20px;
    color: black;
    font-weight: bold;
    margin: 0;
    flex: 1;
}
.container-fluid{
    height: 700%;
}
.transparent-img {
    background-color: transparent; /* Ensures no background color */
    mix-blend-mode: multiply; /* Helps blend it with the background */
    filter: brightness(1.2);
}

    </style>
</head>
<body>
    <div class="container hero-section">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1>Become a <span class="highlight">Machine Learning</span> Expert!</h1>
                <p class="text-muted">Master the science behind intelligent systems!</p>
                <p>Learn the fundamentals of machine learning algorithms, Python programming, and data analysis to kickstart your career as a Data Scientist.</p>
                <img src="mlimage.png" alt="Machine Learning" class="img-fluid transparent-img mt-3">
            </div>
            <div class="col-md-5 offset-md-1">
                <div class="form-container form-section">
                    <h2 class="text-center">I'm Interested</h2>
                    <form action="payment.php" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">* Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your Name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">* Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" required>
    </div>
    <div class="mb-3">
        <label for="course" class="form-label">* Course Name</label>
        <input type="text" class="form-control" id="course" name="course" value="Machine Learning" readonly>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">* Price (₹)</label>
        <input type="text" class="form-control" id="price" name="price" value="<?php echo $course_price; ?>" readonly> <!-- Default price -->
    </div>
    <button type="submit" class="btn btn-custom w-80" style="display: block; margin: 0 auto;">Pay Now</button>

</form>
                </div>
            </div>
        </div>
    </div>
    <section id="course-details" class="course-details py-5">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Left Side - Image -->
                    <div class="col-md-6 text-center">
                        <img src="teach.png" alt="Course Image" class="img-fluid rounded shadow">
                    </div>
                    <!-- Right Side - Course Overview -->
                   <!-- Right Side - Course Overview -->
                      <!-- Right Side - Course Overview -->
<div class="col-md-6">
    <h2 class="course-heading">Course Overview</h2>
    <!-- Overview Cards -->
    <div class="course-card">
        <h5><span class="course-icon">🤖</span></h5>
        <p>Master Machine Learning Algorithms & AI Techniques</p>
    </div>
    <div class="course-card">
        <h5><span class="course-icon">📊</span></h5>
        <p>Hands-on Data Analysis & Model Training</p>
    </div>
    <div class="course-card">
        <h5><span class="course-icon">🧠</span></h5>
        <p>Deep Learning, Neural Networks & NLP</p>
    </div>
    <div class="course-card">
        <h5><span class="course-icon">💻</span></h5>
        <p>Work with Python, TensorFlow, and Scikit-learn</p>
    </div>
    <div class="course-card">
        <h5><span class="course-icon">📈</span></h5>
        <p>Industry Projects & Real-world Applications</p>
    </div>
</div>
            </div>
            </div>
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
        
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
