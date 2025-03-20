<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "online");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get course ID from the URL
$course_id = intval($_GET['id']);

// Fetch course details
$sql_course = "SELECT * FROM Courses WHERE course_id = $course_id";
$result_course = $conn->query($sql_course);
$course = $result_course->fetch_assoc();

// Fetch feedback for the course
$sql_feedback = "
    SELECT f.feedback_id, f.feedback_text, u.username, u.user_id, f.rating, f.feedback_date, f.reply_message
    FROM Feedback f 
    JOIN Users u ON f.user_id = u.user_id 
    WHERE f.course_id = $course_id";
$result_feedback = $conn->query($sql_feedback);
$feedbacks = $result_feedback->fetch_all(MYSQLI_ASSOC);

// Handle the reply submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_message'], $_POST['feedback_id'])) {
    $feedback_id = $_POST['feedback_id'];
    $reply_message = $_POST['reply_message'];

    // Update the reply message in the database
    $stmt = $conn->prepare("UPDATE Feedback SET reply_message = ? WHERE feedback_id = ?");
    $stmt->bind_param("si", $reply_message, $feedback_id);
    $stmt->execute();
    $stmt->close();

    // Refresh the page to show the reply
    header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $course_id);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback for <?php echo $course['title']; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
        }
        .feedback-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 2);
        }
        .feedback-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .feedback-header .username {
            font-weight: bold;
            color: #333;
        }
        .feedback-header .date {
            color: #aaa;
            font-size: 0.9rem;
        }
        .stars {
            font-size: 1.2rem;
        }
        .stars .fas {
            color: gold;
        }
        .feedback-text {
            margin-top: 10px;
            font-size: 1rem;
            color: #555;
        }
        .back-btn {
            margin-top: 20px;
        }
        .reply-section {
            margin-top: 15px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 8px;
        }
        .reply-section textarea {
            width: 100%;
            height: 100px;
        }
        .reply-message {
            font-size: 1rem;
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="mt-4">Feedback for <?php echo $course['title']; ?></h2>
        <a href="feedback.php" class="btn btn-secondary btn-sm mb-4 back-btn">Back to Courses</a>

        <?php if ($feedbacks): ?>
            <?php foreach ($feedbacks as $feedback): ?>
                <div class="feedback-card">
                    <div class="feedback-header">
                        <div class="left-side">
                            <!-- User Icon and Username -->
                            <i class="fas fa-user-circle" style="font-size: 2rem; color: #555;"></i>
                            <span class="username ml-2"><?php echo $feedback['username']; ?> </span>
                        </div>
                        
                        <div class="right-side">
                            <!-- Rating Stars -->
                            <div class="stars">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?php echo $i <= $feedback['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <!-- Posted Date -->
                            <div class="date"><?php echo date('M Y', strtotime($feedback['feedback_date'])); ?></div>
                        </div>
                    </div>

                    <!-- Feedback Text -->
                    <div class="feedback-text">
                        <p><?php echo nl2br($feedback['feedback_text']); ?></p>
                    </div>

                    <!-- Reply Section -->
                    <?php if ($feedback['reply_message']): ?>
                        <div class="reply-section">
                            <strong>Reply: </strong>
                            <p class="reply-message"><?php echo nl2br($feedback['reply_message']); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="reply-section">
                            <form action="" method="POST">
                                <input type="hidden" name="feedback_id" value="<?php echo $feedback['feedback_id']; ?>">
                                <textarea name="reply_message" placeholder="Enter your reply here..." required></textarea>
                                <button type="submit" class="btn btn-primary mt-2">Send Reply</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No feedback available for this course.</p>
        <?php endif; ?>
    </div>
</body>
</html>

<?php $conn->close(); ?>
