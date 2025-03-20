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

$query = "SELECT c.title, COUNT(e.enrollment_id) AS student_count 
          FROM Courses c
          LEFT JOIN Enrollments e ON c.course_id = e.course_id
          GROUP BY c.course_id";
$result = mysqli_query($conn, $query);

// Initialize arrays for course names and student counts
$courseNames = [];
$studentCounts = [];

// Fetch the results
while ($row = mysqli_fetch_assoc($result)) {
    $courseNames[] = $row['title'];
    $studentCounts[] = $row['student_count'];
}

// Check if Reviews table exists
$tableCheckQuery = "SHOW TABLES LIKE 'Reviews'";
$tableCheckResult = mysqli_query($conn, $tableCheckQuery);

$courseNames = [];  // Array to hold course titles
$averageRatings = [];  // Array to hold the average ratings

// Assuming you're fetching the courses and their average ratings
$queryRatings = "SELECT c.title, COALESCE(AVG(f.rating), 0) AS average_rating
                 FROM Courses c
                 LEFT JOIN feedback f ON c.course_id = f.course_id
                 GROUP BY c.course_id";

$result = mysqli_query($conn, $queryRatings);

while ($row = mysqli_fetch_assoc($result)) {
    $courseNames[] = $row['title'];
    $averageRatings[] = $row['average_rating'];
}

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
            margin: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .dark-mode {
            background-color: #121212;
            color: #ffffff;
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

        .header-row {
            margin-left: 250px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            height: auto;
            background-color: #f8f9fa;
        }

        .dashboard-title {
            margin: 0;
            font-size: 1.8rem;
            font-weight: bold;
        }

        .right-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            background-color: #fff;
            border-radius: 20px;
            padding: 5px 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .search-bar input {
            border: none;
            outline: none;
            flex-grow: 1;
            margin-left: 10px;
            width: 200px;
        }

        .search-bar i {
            color: #888;
        }

        .dark-mode-btn {
            font-size: 1.5rem;
            cursor: pointer;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
            flex-grow: 1;
        }

        .dashboard-cards {
            display: flex;
            justify-content: space-between;
            align-items: stretch;
            gap: 20px;
            margin-top: 20px;
        }

        .dashboard-card {
            background-color: black;
            border-radius: 10px;
            padding: 20px;
            flex: 1;
            position: relative;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .dashboard-card h2 {
            font-size: 3rem;
            color: #00bfff;
        }

        .dashboard-card h4 {
            color: grey;
        }
        .dashboard-card img {
            position: absolute;
            bottom: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
        .welcome-card {
            background: url('dash7.jpg') no-repeat right center;
            background-size: contain;
            background-color: white;
            height: 150px;
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

        @media (max-width: 767px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .header-row {
                margin-left: 0;
                width: 100%;
                flex-direction: column;
                gap: 10px;
            }

            .main-content {
                margin-left: 0;
            }

            .dashboard-cards {
                flex-direction: column;
            }

            .dashboard-card {
                margin: 10px 0;
            }
        }
        .chart-row {
    display: flex;
    justify-content: space-between;
    gap: 10px; /* Reduce the gap between charts */
    margin-top: 20px;
}

.chart-container {
    flex: 1;
    max-width: 60%; /* Increased width for the charts */
    height: 200px; /* Adjust to your preferred height */
}

        canvas {
            background:black;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.3);
            border-radius: 10px;
        }
        .tooltip {
            position: absolute;
            background-color: #3B71CA;
            padding: 5px 10px;
            border-radius: 5px;
            color: white;
            font-size: 14px;
            display: none;
            pointer-events: none;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <a href="#dashboard" class="d-flex align-items-center mb-3 text-decoration-none">
            <i class="bi bi-person-circle me-2"></i>
            <span class="fs-4">Harsha</span>
        </a>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active"><i class="bi bi-house-door me-2"></i> Dashboard</a>
            </li>
            <li><a href="users.php" class="nav-link"><i class="bi bi-people me-2"></i> Users</a></li>
            <li><a href="courses.php" class="nav-link"><i class="bi bi-journal me-2"></i> Courses</a></li>
            <li><a href="enrollments.php" class="nav-link"><i class="bi bi-book me-2"></i> Enrollments</a></li>
            <li><a href="enquiry.php" class="nav-link"><i class="bi bi-envelope me-2"></i> Enquiry</a></li>
            <li><a href="feedback.php" class="nav-link"><i class="bi bi-chat-left-dots me-2"></i> Feedback</a></li><br><br><br><br>
            <li><a href="logout.php" class="nav-link"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Header Row -->
    <div class="header-row">
        <h1 class="dashboard-title">Dashboard</h1>
        <div class="right-controls">
            <div class="search-bar">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search..." aria-label="Search">
            </div>
            <div class="dark-mode-btn" id="dark-mode-toggle">
                <i class="bi bi-moon-fill"></i>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Card -->
       
                <div class="card welcome-card mb-4">
                    <div class="card-body">
                        <h1>Welcome Back Dharsh !</h1>
                        <p>Take a step closer to achieving your goals</p>
                    </div>
                </div>

        <!-- Dashboard Cards -->
        <div class="dashboard-cards">
            <div class="dashboard-card">
                <h4>Enquiries</h4>
                <h2><?php echo $totalEnquiries; ?></h2>
                <img src="msg.jpg" alt="Message Icon">
            </div>
            <div class="dashboard-card">
                <h4>Courses</h4>
                <h2><?php echo $totalCourses; ?></h2>
                <img src="book.jpg" alt="Book Icon">
            </div>
            <div class="dashboard-card">
                <h4>Enrollments</h4>
                <h2><?php echo $totalEnrollments; ?></h2>
                <img src="bar.jpg" alt="Bar Icon">
            </div>
        </div>
         <!-- Chart Section (Below Cards) -->
        <!-- Chart Section (Below Cards) -->
<div class="chart-row">
    <div class="chart-container">
        <canvas id="lineChart" width="700" height="300"></canvas>
        <div class="tooltip" id="tooltip"></div>
    </div>
    <div class="chart-container">
        <canvas id="barChart" width="600" height="300"></canvas>
        <div class="tooltip" id="tooltip"></div>
    </div>
</div>

    </div>

    <!-- Include JS libraries -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // PHP Data for Chart
        const courseNames = <?php echo json_encode($courseNames); ?>;
        const studentCounts = <?php echo json_encode($studentCounts); ?>;

        // Chart.js Configuration
        const ctx = document.getElementById('lineChart').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line', // Type of chart (Line Chart)
            data: {
                labels: courseNames, // Course names on X-axis
                datasets: [{
                    label: 'Number of Students',
                    data: studentCounts, // Number of students on Y-axis
                    fill: true,
                    backgroundColor: 'rgba(59, 113, 202, 0.4)',
                    borderWidth: 2,
                    borderColor: 'rgba(59, 113, 202, 0.4)',
                    pointBackgroundColor: 'rgba(26, 188, 156, 1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: Math.max(...studentCounts) + 10 // Adjust maximum value for better scaling
                    }
                },
                plugins: {
                    tooltip: {
                        enabled: true,
                        mode: 'nearest',
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.raw + ' Students';
                            }
                        }
                    }
                }
            }
        });
    </script>
    <script>
    // Bar chart data
    const averageRatings = <?php echo json_encode($averageRatings); ?>;
    const barChartData = {
    labels: <?= json_encode($courseNames); ?>,  // X-axis labels
    datasets: [{
        label: 'Average Review (1-5)',
        data: averageRatings.map(rating => (rating * 20)),  // Convert to percentage (1-5 scale to 0-100%)
        backgroundColor: '#00bfff',
        borderColor: '#00bfff',
        borderWidth: 1
    }]
};



// Bar Chart options
const barChartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    scales: {
        x: {
            beginAtZero: true,
        },
        y: {
            min: 0,  // Y-axis starts at 0
            max: 100,  // Max value 100% for percentage
            stepSize: 20,  // 20% increments
            ticks: {
                callback: function(value) {
                    return value + '%';  // Add percentage sign on the Y-axis
                }
            },
            title: {
                display: true,
                text: 'Rating (%)',
            }
        }
    },
    plugins: {
        tooltip: {
            callbacks: {
                label: function(tooltipItem) {
                    return tooltipItem.raw.toFixed(0) + '%';  // Show percentage in tooltip
                }
            }
        }
    }
};


// Create the bar chart
const ctxBar = document.getElementById('barChart').getContext('2d');
new Chart(ctxBar, {
    type: 'bar',  // Bar chart type (vertical)
    data: barChartData,
    options: barChartOptions
});
console.log(courseNames);  // Logs the course names
console.log(averageRatings);  // Logs the average ratings

</script>

    
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            // Dark mode toggle
            $('#dark-mode-toggle').click(function () {
                $('body').toggleClass('dark-mode');
                const icon = $(this).find('i');
                if (icon.hasClass('bi-moon-fill')) {
                    icon.removeClass('bi-moon-fill').addClass('bi-sun-fill');
                } else {
                    icon.removeClass('bi-sun-fill').addClass('bi-moon-fill');
                }
            });
        });
    </script>
</body>
</html>
