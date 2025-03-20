<?php
include '../db.php'; // Include your database connection

// Fetch counts from the database
$queryEnquiries = "SELECT COUNT(*) AS total FROM Enquiries";
$queryCourses = "SELECT COUNT(*) AS total FROM Courses";
$queryEnrollments = "SELECT COUNT(*) AS total FROM Enrollments";

$resultEnquiries = mysqli_query($conn, $queryEnquiries);
$resultCourses = mysqli_query($conn, $queryCourses);
$resultEnrollments = mysqli_query($conn, $queryEnrollments);

$totalEnquiries = mysqli_fetch_assoc($resultEnquiries)['total'];
$totalCourses = mysqli_fetch_assoc($resultCourses)['total'];
$totalEnrollments = mysqli_fetch_assoc($resultEnrollments)['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Harsha Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
    <style>
        body {
            display: flex;
        }

        .sidebar {
            background: url('dash2.jpg') no-repeat top left;
            background-size: cover;
            color: white;
            height: 100vh;
            width: 250px;
            position: fixed;
            z-index: 1050;
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
        }

        .nav-link {
            color: white;
            border-radius: 0.25rem;
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
            font-weight: bold;
        }

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
            transition: margin-left 0.3s ease-in-out;
        }

        .main-content.sidebar-hidden {
            margin-left: 0;
        }

        .welcome-card {
            background: url('dash7.jpg') no-repeat right center;
            background-size: contain;
            background-color: white;
            height: 200px;
            border-radius: 10px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .welcome-card h1 {
            padding-left: 15px;
            font-size: 3rem;
            font-weight: bold;
        }

        .welcome-card p {
            padding-left: 15px;
            font-size: 1.25rem;
            font-style: italic;
        }

        /* Three Cards Styling */
        .dashboard-cards {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .dashboard-card {
            background-color: black;
            border-radius: 10px;
            padding: 20px;
            flex: 1;
            margin: 10px;
            position: relative;
            text-align: center;
        }

        .dashboard-card img {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            box-shadow: 0 2px 8px rgba(255, 255, 255, 0.5);
        }

        .dashboard-card h2 {
            font-size: 3rem;
            color: #00bfff;
            text-shadow: 0 0 5px #00bfff, 0 0 10px #00bfff, 0 0 20px #00bfff, 0 0 40px #00bfff;
            margin: 0;
        }

        /* Responsive Adjustments */
        @media (max-width: 767px) {
            .dashboard-cards {
                flex-direction: column;
            }
            .dashboard-card {
                margin: 10px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="d-flex flex-column flex-shrink-0 sidebar" id="sidebar">
        <a href="#dashboard" class="d-flex align-items-center mb-3 text-decoration-none">
            <i class="bi bi-person-circle me-2"></i>
            <span class="fs-4">Harsha</span>
        </a>
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link text-white"><i class="bi bi-house-door me-2"></i> Dashboard</a>
            </li>
            <li><a href="users.php" class="nav-link text-white"><i class="bi bi-people me-2"></i> Users</a></li>
            <li><a href="courses.php" class="nav-link text-white"><i class="bi bi-journal me-2"></i> Courses</a></li>
            <li><a href="enrollments.php" class="nav-link text-white"><i class="bi bi-book me-2"></i> Enrollments</a></li>
            <li><a href="enquiry.php" class="nav-link text-white"><i class="bi bi-envelope me-2"></i> Enquiry</a></li>
            <li><a href="feedback.php" class="nav-link text-white"><i class="bi bi-chat-left-dots me-2"></i> Feedback</a></li>
            <li><a href="logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <button class="btn btn-primary mb-3 d-md-none" id="toggle-sidebar"><i class="bi bi-list"></i> Toggle Sidebar</button>

        <!-- Welcome Card -->
        <h1 class="mb-4">Dashboard</h1>
                <div class="card welcome-card mb-4">
                    <div class="card-body">
                        <h1>Welcome Back</h1>
                        <p>Take a step closer to achieving your goals</p>
                    </div>
                </div>
        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <!-- Card 1 -->
            <div class="dashboard-card">
            <h4>Enquiries</h4>
                <h2><?php echo $totalEnquiries; ?></h2>
                <img src="msg.jpg" alt="Message Icon">
            </div>
            <!-- Card 2 -->
            <div class="dashboard-card">
            <h4>Courses</h4>
                <h2><?php echo $totalCourses; ?></h2>
                <img src="book.jpg" alt="Book Icon">
            </div>
            <!-- Card 3 -->
            <div class="dashboard-card">
            <h4>Enrollments</h4>
                <h2><?php echo $totalEnrollments; ?></h2>
                <img src="bar.jpg" alt="Bar Icon">
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            var path = window.location.pathname.split("/").pop();
            if (path == '') {
                path = 'dashboard.php'; // Default page
            }
            var target = $('.nav-link[href="' + path + '"]');
            target.addClass('active');

            // Toggle sidebar functionality
            $('#toggle-sidebar').click(function () {
                $('#sidebar').toggleClass('hidden');
                $('#main-content').toggleClass('sidebar-hidden');
            });
        });
    </script>
</body>
</html>
