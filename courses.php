<?php
require_once '../db.php'; // Ensure the correct path to your database connection file

// Assuming $conn is defined in db.php

// Function to fetch course data
function fetchCourses($conn) {
    $sql = "SELECT course_id, title, description, price,lessons, image_url FROM courses";
    $result = $conn->query($sql);

    $courses = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = $row;
        }
    }
    return $courses;
}

// Handle course deletion
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $course_id = intval($_GET['id']);
    $sql = "DELETE FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);

    if ($stmt->execute()) {
        echo "<script>alert('Course deleted successfully!'); window.location.href = 'courses.php';</script>";
    } else {
        echo "<script>alert('Error deleting course: " . $stmt->error . "');</script>";
    }

    $stmt->close();
}

$courses = fetchCourses($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" crossorigin="anonymous" />

    <style>
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            font-family: 'Roboto', sans-serif;
        }

        /* Sidebar Styles */
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

        /* Main Courses Styles */
        .main-courses-container {
            padding: 15px;
        }

        .main-card {
            margin-bottom: 15px;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .main-card:hover {
            transform: translateY(-5px);
        }

        .main-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 10px 10px 0 0;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .card-title:hover {
            text-decoration: underline;
        }

        .card-description {
            font-size: 0.9rem;
            color: #34495e;
            margin-bottom: 10px;
        }

        .icon {
            font-size: 1.2rem;
            margin-right: 8px;
        }

        .edit-icon, .delete-icon {
            cursor: pointer;
            margin-left: 15px;
            transition: color 0.2s;
        }

        .add-icon {
            font-size: 2rem;
            color: #007bff;
            text-decoration: none;
            position: relative;
            top: -5px;
            margin-left: 10px;
        }

        .add-icon:hover {
            color: #0056b3;
        }

        .edit-icon {
            color: rgb(248, 208, 88);
        }

        .delete-icon {
            color: rgb(240, 64, 81);
        }

        .card-details {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
            color: #6c757d;
        }

        /* Upcoming Section Styles */
        .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease-in-out;
    }

    .card:hover {
        transform: scale(1.05);
    }

    .image-container {
        position: relative;
        height: 400px; /* Fixed height for images */
        overflow: hidden;
    }

    .card img {
        height: 100%;
        width: 100%;
        object-fit: cover;
        display: block;
    }

    .card-title {
        margin-top: 10px;
        font-size: 1.2rem;
        color: #343a40;
    }

    /* Skeleton loader for description */
    .details {
        height: 15px;
        width: 80%;
        background: linear-gradient(90deg, #ececec 25%, #f3f3f3 50%, #ececec 75%);
        background-size: 200% 100%;
        border-radius: 5px;
        animation: shimmer 1.5s infinite linear;
        margin: 10px auto 0;
    }

    .btn-placeholder {
        margin: 10px auto 0;
        width: 60%;
        height: 35px;
        background: linear-gradient(90deg, #ececec 25%, #f3f3f3 50%, #ececec 75%);
        background-size: 200% 100%;
        border-radius: 5px;
        animation: shimmer 1.5s infinite linear;
    }

    @keyframes shimmer {
        0% {
            background-position: -450px 0;
        }
        100% {
            background-position: 450px 0;
        }
    }
        .loading {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1rem;
            font-weight: bold;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
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
        <div class="container">
            <!-- Main Courses Section -->
            <div class="main-courses-container">
                <div class="d-flex align-items-center mb-3">
                    <h3 class="mb-0">Main Courses</h3>
                    <a href="createcourse.php" class="add-icon" title="Add Course">
                        <i class="fas fa-plus-circle"></i>
                    </a>
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
                                            <i class="fas fa-book icon"></i>
                                            <?php echo $course['price']; ?>
                                        </span>
                                        <span>
                                        <a href="upload.php?course_id=<?php echo $course['course_id']; ?>" class="upload-icon">
                                                <i class="fas fa-upload"></i>
                                            </a>
                                            <a href="updatecourse.php?id=<?php echo $course['course_id']; ?>" class="edit-icon">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="course.php?action=delete&id=<?php echo $course['course_id']; ?>" class="delete-icon" onclick="return confirm('Are you sure you want to delete this course?');">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

           
<!-- Upcoming Courses Section -->
<div class="upcoming-section">
<div class="d-flex align-items-center mb-3">
    <h3>Upcoming Courses</h3><br>
    <a href="newup.php" class="add-icon" title="Add Course">
                        <i class="fas fa-plus-circle"></i>
                    </a>
        </div>
    <div class="row">
        <?php
        $sql = "SELECT * FROM Upcoming";
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
            echo "<p>No upcoming courses available.</p>";
        }
        $conn->close();
        ?>
    </div>
</div>

        </div>
    </div>

    <!-- Sidebar Toggle Button (For small screens) -->
    <button id="toggle-sidebar" class="btn btn-primary">☰</button>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function () {
            // Toggle sidebar visibility
            $('#toggle-sidebar').click(function () {
                $('#sidebar').toggleClass('show');
                $('#main-content').toggleClass('sidebar-hidden');
            });

            // Set active class for the current page
            var path = window.location.pathname.split("/").pop();
            if (path === '') {
                path = 'dashboard.php';
            }

            $('.nav-link').each(function () {
                var linkPath = $(this).attr('href').split("/").pop();
                if (path === linkPath) {
                    $(this).addClass('active');
                }
            });
        });
    </script>
</body>
</html>
