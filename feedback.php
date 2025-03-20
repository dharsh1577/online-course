<?php
include '../db.php'; // Include your database connection

// Fetch total reviews
$totalReviewsQuery = "SELECT COUNT(*) as total_reviews FROM feedback";
$totalReviewsResult = mysqli_query($conn, $totalReviewsQuery);
$totalReviews = mysqli_fetch_assoc($totalReviewsResult)['total_reviews'];

// Fetch average rating
$averageRatingQuery = "SELECT AVG(rating) as average_rating FROM feedback";
$averageRatingResult = mysqli_query($conn, $averageRatingQuery);
$feedbackStats = mysqli_fetch_assoc($averageRatingResult);

// Fetch courses
$coursesQuery = "SELECT * FROM courses";
$coursesResult = mysqli_query($conn, $coursesQuery);
$courses = mysqli_fetch_all($coursesResult, MYSQLI_ASSOC);
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
            width: 250px;
            height: 100vh;
            position: fixed;
            display: flex;
            flex-direction: column;
            z-index: 1050;
        }

        .sidebar a {
            padding: 15px;
            text-decoration: none;
            color: white;
            font-size: 1.1rem;
        }

        .sidebar a:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2);
            color: white;
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

        /* Hide the toggle button on larger screens */
        @media (min-width: 768px) {
            #toggle-sidebar {
                display: none;
            }
        }

        /* Sidebar behavior for small screens */
        @media (max-width: 767px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }

        /* Common styles for all stat cards */
        .stat-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            background: linear-gradient(135deg, #f9f9f9, #e6e6e6);
            font-size: 0.9rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        /* Total Reviews card */
        .stat-card.total-reviews {
            background: linear-gradient(135deg, #e6f7ff, #cceeff);
            width: 80%;
            height: 90%;
        }
        
        /* Average Rating card */
        .stat-card.average-rating {
            background: linear-gradient(135deg, #e6ffe6, #ccffcc);
            width: 80%;
            margin-right: 100px;
        }
        
     

        /* Progress bar styling */
        .progress-container {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .progress-bar-container {
            flex: 1;
            margin-left: 10px;
        }
        .progress-bar {
            font-size: 0.8rem;
            height: 15px;
        }

   
        
        .header-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: left;
            margin: 20px 0;
        }
        
        /* Main card for courses */
        .card {
            height: 100%;
            min-height: 400px;
        }
        .card-img-top {
            height: 250px;
            object-fit: cover;
        }
        .card-body {
            padding: 20px;
        }
        .main-card-title {
            font-size: 1.2rem;
            font-weight: bold;
        }
        .average-rating {
            font-size: 1.5rem;
        }
        .stars {
            display: inline-block;
        }
        .stars .fas {
            font-size: 1.5rem;
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
            <li><a href="feedback.php" class="nav-link text-white"><i class="bi bi-chat-left-dots me-2"></i> Feedback</a></li><break><br><br><br>
            <li><a href="logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <button class="btn btn-primary mb-3" id="toggle-sidebar"><i class="bi bi-list"></i> Toggle Sidebar</button>
        <div class="container-fluid">
            <!-- Title Row -->
            <div class="row">
                <div class="col-md-12">
                    <div class="header-title">REVIEW</div>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="row">
                <div class="col-md-4">
                    <div class="stat-card total-reviews" style="margin-bottom: -10px;">
                        <h5>Total Reviews</h5>
                        <p class="display-5"><?php echo $totalReviews; ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card average-rating" style="margin-bottom: -10px;">
                        <h5>Average Rating</h5>
                        <p class="average-rating">
                            <?php
                            $averageRating = round($feedbackStats['average_rating'], 1);
                            ?>
                            <span class="stars">
                                <?php
                                for ($i = 1; $i <= 5; $i++) {
                                    echo $i <= floor($averageRating)
                                        ? '<i class="fas fa-star text-warning"></i>'
                                        : '<i class="fas fa-star text-muted"></i>';
                                }
                                ?>
                            </span>
                            <span><?php echo $averageRating; ?></span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4"></div>
            </div>

            <!-- Courses Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <h3 class="mb-3">Courses</h3>
                    <div class="row">
                        <?php foreach ($courses as $course): ?>
                            <div class="col-md-3 mb-3">
                                <div class="card">
                                    <img src="<?php echo $course['image_url'] ?: 'ui.jpg'; ?>" class="card-img-top" alt="Course Image">
                                    <div class="card-body">
                                        <h5 class="main-card-title"><?php echo $course['title']; ?></h5>
                                        <a href="feedbackdetails.php?id=<?php echo $course['course_id']; ?>" class="btn btn-primary btn-sm">View Feedback</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
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
