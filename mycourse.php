<?php
session_start();
require_once '../db.php'; // Ensure the correct path to your database connection file

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>
            alert('Please log in to view your courses.');
            window.location.href='login.php';
          </script>";
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$conn = new mysqli('localhost', 'root', '', 'online');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_sql = "SELECT username, email FROM Users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_details = $user_result->fetch_assoc();
$user_stmt->close();

// Fetch registered courses with status "Completed"
$sql = "
    SELECT 
        c.course_id, c.title 
    FROM 
        Enrollments e 
    INNER JOIN 
        Courses c 
    ON 
        e.course_id = c.course_id 
    WHERE 
        e.user_id = ? AND e.status = 'Completed'";  // Add the status condition here
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$courses = [];
while ($row = $result->fetch_assoc()) {
    $courses[] = $row;
}
$stmt->close();

// Handle feedback submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback_submit'])) {
    $course_id = $_POST['course_id'];
    $rating = $_POST['rating'];
    $feedback_text = $_POST['feedback'];
    $name = $user_details['username'];
    $email = $user_details['email'];

    $feedback_sql = "
        INSERT INTO Feedback (user_id, course_id, feedback_text, rating, reply_message) 
        VALUES (?, ?, ?, ?, CONCAT('Feedback from: ', ?, ' (', ?, ')'))";
    $feedback_stmt = $conn->prepare($feedback_sql);
    $feedback_stmt->bind_param("iissss", $user_id, $course_id, $feedback_text, $rating, $name, $email);
    if ($feedback_stmt->execute()) {
        // Set session variable for success message
        $_SESSION['feedback_success'] = "Feedback submitted successfully!";
    } else {
        $_SESSION['feedback_error'] = "Error submitting feedback.";
    }
    $feedback_stmt->close();
    // Redirect to avoid resubmitting the form on page refresh
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Check if there's a success message to display
$success_message = isset($_SESSION['feedback_success']) ? $_SESSION['feedback_success'] : null;
$error_message = isset($_SESSION['feedback_error']) ? $_SESSION['feedback_error'] : null;

// Clear the session message after displaying it
if (isset($_SESSION['feedback_success'])) {
    unset($_SESSION['feedback_success']);
}
if (isset($_SESSION['feedback_error'])) {
    unset($_SESSION['feedback_error']);
}
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
    <link rel="stylesheet" href="mycourse.css">
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
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'career.php' ? 'active' : '' ?>" href="guide.php"> Guidance</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'upcoming.php' ? 'active' : '' ?>" href="upcoming.php">Crash Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'Needhelp.php' ? 'active' : '' ?>" href="Needhelp.php">Need Help</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'login.php' ? 'active' : '' ?>" href="login.php">Log In</a>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container mt-5">
        <h1 class="text-center mb-4">My Courses</h1>
        <?php if (!empty($courses)): ?>
            <div class="accordion" id="courseAccordion">
                <?php foreach ($courses as $course): ?>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading<?php echo $course['course_id']; ?>">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $course['course_id']; ?>" aria-expanded="true" aria-controls="collapse<?php echo $course['course_id']; ?>">
                                <?php echo $course['title']; ?>
                            </button>
                        </h2>
                        <div id="collapse<?php echo $course['course_id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo $course['course_id']; ?>" data-bs-parent="#courseAccordion">
                            <div class="accordion-body">
                                <h4>Videos:</h4>
                                <!-- Video Section -->
                                <?php
                                $video_sql = "SELECT video_url FROM course_videos WHERE course_id = ?";
                                $video_stmt = $conn->prepare($video_sql);
                                $video_stmt->bind_param("i", $course['course_id']);
                                $video_stmt->execute();
                                $video_result = $video_stmt->get_result();
                                if ($video_result->num_rows > 0): 
                                ?>
                                    <div class="row">
                                        <?php while ($video = $video_result->fetch_assoc()): ?>
                                            <div class="col-md-6 mb-4">
                                                <?php 
                                                if (strpos($video['video_url'], 'youtube.com') !== false || strpos($video['video_url'], 'youtu.be') !== false): 
                                                    preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $video['video_url'], $matches);
                                                    $youtube_id = $matches[1] ?? null;
                                                    if ($youtube_id):
                                                ?>
                                                    <iframe 
                                                        width="100%" 
                                                        height="315" 
                                                        src="https://www.youtube.com/embed/<?php echo $youtube_id; ?>" 
                                                        title="YouTube video player" 
                                                        frameborder="0" 
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                        allowfullscreen>
                                                    </iframe>
                                                <?php 
                                                    endif;
                                                else: 
                                                ?>
                                                    <video width="100%" controls>
                                                        <source src="<?php echo $video['video_url']; ?>" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                <?php endif; ?>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                <?php else: ?>
                                    <p>No videos available for this course.</p>
                                <?php endif; ?>
                                <?php $video_stmt->close(); ?>

                                <!-- Feedback Section -->
                                <div class="mt-4">
                                    <h4>Leave Your Feedback</h4>
                                    <form method="POST" class="feedback-form">
                                        <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                        <div class="mb-3">
                                            <label><strong>Name:</strong> <?php echo htmlspecialchars($user_details['username']); ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <label><strong>Email:</strong> <?php echo htmlspecialchars($user_details['email']); ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <textarea name="feedback" class="form-control" rows="3" placeholder="Write your feedback here..." required></textarea>
                                        </div>
                                        <div class="rating-stars">
                                            <input type="radio" id="star5-<?php echo $course['course_id']; ?>" name="rating" value="5" required>
                                            <label for="star5-<?php echo $course['course_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star4-<?php echo $course['course_id']; ?>" name="rating" value="4">
                                            <label for="star4-<?php echo $course['course_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star3-<?php echo $course['course_id']; ?>" name="rating" value="3">
                                            <label for="star3-<?php echo $course['course_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star2-<?php echo $course['course_id']; ?>" name="rating" value="2">
                                            <label for="star2-<?php echo $course['course_id']; ?>">&#9733;</label>
                                            <input type="radio" id="star1-<?php echo $course['course_id']; ?>" name="rating" value="1">
                                            <label for="star1-<?php echo $course['course_id']; ?>">&#9733;</label>
                                        </div>
                                        <button type="submit" name="feedback_submit" class="btn btn-primary mt-3">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center" role="alert">
                You have not registered for any courses yet!
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>