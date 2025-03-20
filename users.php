<?php
include '../db.php'; // Ensure the correct path to your database connection file

// Function to fetch user data
function fetchUsers($conn) {
    $sql = "SELECT username, email, password FROM users";
    $result = $conn->query($sql);

    $users = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }
    return $users;
}

$users = fetchUsers($conn);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
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
        .table{
            box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA, 0 0 20px #3B71CA;
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
                <a href="dashboard.php" class="nav-link text-white" id="dashboard-link"><i class="bi bi-house-door me-2"></i> Dashboard</a>
            </li>
            <li><a href="users.php" class="nav-link text-white" id="users-link"><i class="bi bi-people me-2"></i> Users</a></li>
            <li><a href="courses.php" class="nav-link text-white" id="courses-link"><i class="bi bi-journal me-2"></i> Courses</a></li>
            <li><a href="enrollments.php" class="nav-link text-white" id="enrollments-link"><i class="bi bi-book me-2"></i> Enrollments</a></li>
            <li><a href="enquiry.php" class="nav-link text-white" id="enquiry-link"><i class="bi bi-envelope me-2"></i> Enquiry</a></li>
            <li><a href="feedback.php" class="nav-link text-white" id="feedback-link"><i class="bi bi-chat-left-dots me-2"></i> Feedback</a></li><break><br><br><br>
            <li><a href="logout.php" class="nav-link text-white" id="logout-link"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content" id="main-content">
        <button class="btn btn-primary mb-3" id="toggle-sidebar"><i class="bi bi-list"></i> Toggle Sidebar</button>
        <h1>Users</h1>
        <div class="container mt-4">
            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td><?php echo $user['username']; ?></td>
                            <td><?php echo $user['email']; ?></td>
                            <td><?php echo $user['password']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
