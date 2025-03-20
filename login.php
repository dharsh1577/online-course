<?php
session_start();
include '../db.php'; // Ensure the database connection file is correctly included

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

$stmt = null; // Initialize $stmt to null

// Handle Login
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];

    // Prepare and bind for login
    $stmt = $conn->prepare("SELECT user_id FROM Users WHERE email = ? AND username = ?");
    $stmt->bind_param("ss", $email, $name);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($user_id);
    $stmt->fetch();

    if ($stmt->num_rows > 0) {
        $_SESSION['email'] = $email;
        $_SESSION['user_id'] = $user_id;
        $_SESSION['role'] = $role;
        // Redirect based on role
        if ($role === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: home.php");
        }
        exit();
    } else {
        $login_error = "Invalid email or username.";
    }

    if ($stmt) {
        $stmt->close();
    }
}

// Handle Signup
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['signup'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Check if username or email already exists
    $check_sql = "SELECT * FROM Users WHERE username = ? OR email = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $check_stmt->store_result();

    if ($check_stmt->num_rows > 0) {
        $signup_error = "Error: Email or username already exists.";
    } else {
        // Insert user into database
        $sql = "INSERT INTO Users (username, email, password, role, status) VALUES (?, ?, ?, ?, 'active')";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $password, $role);

        if ($stmt->execute()) {
            // Check the role and redirect accordingly
            if ($role === 'admin') {
                // Ensure the redirection points to the correct path
                echo "<script>alert('Signup successful! Redirecting to the admin dashboard...'); window.location.href='/dharsh/admin/dashboard.php';</script>";
            } else {
                echo "<script>alert('Signup successful! Redirecting to the homepage...'); window.location.href='/dharsh/homepage.php';</script>";
            }
        } else {
            $signup_error = "Error: Unable to sign up. " . $stmt->error;
        }
        
        
    }

    $check_stmt->close();
    $stmt->close();
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="login.css">
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

    <!--login form-->
    <div class="centered-container">
    <!-- Login Form -->
    <div class="form-container col-lg-4 col-md-6 col-sm-8" id="login-form">
        <h2 class="text-center mb-4">Login</h2>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                <i class="bi bi-person-heart"></i>
                
            </div>
            <div class="form-group mb-4">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <i class="bi bi-envelope-open-fill"></i>
            </div>
            <button type="submit" class="btn btn-primary btn-block w-100" name="login">Login</button>
            <p class="text-center mt-3">
                Don't have an account? 
                <span class="toggle-link text-primary" onclick="showSignup()">Create a new account</span>.
            </p>
        </form>
    </div>

    <!-- Signup Form -->
    <div class="form-container col-lg-4 col-md-6 col-sm-8 d-none" id="signup-form">
        <h2 class="text-center mb-4">Signup</h2>
        <form action="" method="post">
            <div class="form-group mb-3">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                <i class="bi bi-person-heart"></i>
            </div>
            <div class="form-group mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                <i class="bi bi-envelope-open-fill"></i>
            </div>
            <div class="form-group mb-3">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                <i class="bi bi-eye-slash"></i>
            </div>
            <div class="form-group mb-4">
                <label for="role" class="form-label">Role:</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="" disabled selected>Select your role</option>
                    <option value="student">Student</option>
                    <option value="admin">Admin</option>
                </select>
                <i class="bi bi-person-arms-up"></i>
            </div>
            <button type="submit" class="btn btn-primary btn-block w-100" name="signup">Sign Up</button>
            <p class="text-center mt-3">
                Already have an account? 
                <span class="toggle-link text-primary" onclick="showLogin()">Sign in here</span>.
            </p>
        </form>
    </div>
</div>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        function showSignup() {
            document.getElementById('login-form').classList.add('d-none');
            document.getElementById('signup-form').classList.remove('d-none');
        }

        function showLogin() {
            document.getElementById('signup-form').classList.add('d-none');
            document.getElementById('login-form').classList.remove('d-none');
        }
    </script>
    </body>
</html>
