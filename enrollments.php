<?php
require_once '../db.php'; // Include database connection

// SQL query to fetch enrollment data along with user and course information
$sql = "
    SELECT e.enrollment_id, u.username, c.title AS course_name, e.enrollment_date, e.status
    FROM Enrollments e
    JOIN Users u ON e.user_id = u.user_id
    JOIN Courses c ON e.course_id = c.course_id
";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enrollments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        /* Custom darker colors for statuses */
        .status-completed {
            color: rgb(17, 100, 52) !important; /* Darker green */
            font-weight: bold;
            text-decoration: underline;
        }

        .status-dropped {
            color: rgb(231, 29, 29) !important; /* Darker red */
            font-weight: bold;
            text-decoration: underline;
        }

        .status-active {
            color: rgb(240, 199, 53) !important; /* Darker yellow */
            font-weight: bold;
            text-decoration: underline;
        }

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

        /* Table Styling */
        .table-striped tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
            
        }

        .table-bordered th, .table-bordered td {
            border: 1px solid #ddd;
        }

        .table th, .table td {
            text-align: center;
            box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA, 0 0 20px #3B71CA;
        }

        .container {
            padding-top: 20px;
            padding-bottom: 10px;
            box-shadow:0 0 5px rgb(8, 8, 8), 0 0 10px rgb(7, 7, 8);
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
            <h2 class="mb-4">Enrollments</h2>

            <!-- Enrollment Table -->
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Course Name</th>
                        <th>Enrollment Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['username']); ?></td>
                                <td><?php echo htmlspecialchars($row['course_name']); ?></td>
                                <td><?php echo date("F j, Y, g:i a", strtotime($row['enrollment_date'])); ?></td>
                                <td class="<?php 
                                    echo ($row['status'] === 'Completed') ? 'status-completed' : 
                                         (($row['status'] === 'Dropped') ? 'status-dropped' : 
                                         'status-active'); ?>">
                                    <?php echo htmlspecialchars($row['status']); ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No enrollments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
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

<?php $conn->close(); ?>
