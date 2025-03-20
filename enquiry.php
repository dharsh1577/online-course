<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "online");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle the reply submission via POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reply_message'], $_POST['enquiry_id'])) {
    $enquiry_id = $_POST['enquiry_id'];
    $reply_message = $_POST['reply_message'];

    // Fetch the user's email from the database
    $stmt = $conn->prepare("SELECT email FROM Enquiries WHERE enquiry_id = ?");
    $stmt->bind_param("i", $enquiry_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $enquiry = $result->fetch_assoc();
    $user_email = $enquiry['email'];

    // Send the email reply
    $subject = "Reply to your enquiry";
    $headers = "From: support@yourwebsite.com\r\n";
    $headers .= "Reply-To: support@yourwebsite.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $message = "<p>Hello,</p><p>Here is our reply to your enquiry:</p><p>" . nl2br($reply_message) . "</p>";
    
    // Send the email
    mail($user_email, $subject, $message, $headers);

    // Update the enquiry with the reply message
    $stmt = $conn->prepare("UPDATE Enquiries SET reply_message = ? WHERE enquiry_id = ?");
    $stmt->bind_param("si", $reply_message, $enquiry_id);
    $stmt->execute();
    $stmt->close();

    // Return the reply message for dynamic display
    echo json_encode(['success' => true, 'reply_message' => nl2br($reply_message)]);
    exit;
}

// Fetch all enquiries
$sql_enquiries = "SELECT * FROM Enquiries";
$result_enquiries = $conn->query($sql_enquiries);
$enquiries = $result_enquiries->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enquiries</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
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

        /* Enquiry Styles */
        .enquiry-card {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            background-color: white;
            box-shadow:0 0 5px #3B71CA, 0 0 10px #3B71CA, 0 0 20px #3B71CA;
        }

        .enquiry-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .enquiry-header .username {
            font-weight: bold;
            color: #333;
        }

        .enquiry-header .date {
            color: #aaa;
            font-size: 0.9rem;
        }

        .enquiry-text {
            margin-top: 10px;
            font-size: 1rem;
            color: #555;
        }

        .reply-section {
            margin-top: 15px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 8px;
        }

        .reply-section .reply {
            font-size: 1rem;
            color: #333;
        }

        .email {
            color: #007bff;
            text-decoration: none;
        }

        .reply-message {
            color: green;
            font-weight: bold;
        }

        .reply-form textarea {
            width: 100%;
            height: 100px;
        }
        .bi-person-circle{
            margin-left: 10px;
        }
       .fs-4{
         margin-left: 10px;
       }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="d-flex flex-column flex-shrink-0 sidebar" id="sidebar">
        <a href="#dashboard" class="d-flex align-items-center mb-3 text-decoration-none">
            <i class="bi bi-person-circle me-2" ></i>
            <span class="fs-4" style="font-size: 1.5rem"> Harsha</span>
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

    <!-- Content Area -->
    <div class="main-content" id="main-content">
        <button class="btn btn-primary mb-3" id="toggle-sidebar"><i class="bi bi-list"></i> Toggle Sidebar</button>
        <h2 class="mt-4">Enquiries</h2>
        <br>

        <?php if ($enquiries): ?>
            <?php foreach ($enquiries as $enquiry): ?>
                <div class="enquiry-card" id="enquiry-<?php echo $enquiry['enquiry_id']; ?>">
                    <div class="enquiry-header">
                        <div class="left-side">
                            <span class="username"><?php echo $enquiry['name']; ?></span>
                        </div>
                        <div class="right-side">
                            <div class="date"><?php echo date('M d, Y', strtotime($enquiry['created_at'])); ?></div>
                        </div>
                    </div>

                    <div class="enquiry-text">
                        <p><?php echo nl2br($enquiry['message']); ?></p>
                    </div>

                    <!-- Reply Section -->
                    <?php if (!empty($enquiry['reply_message'])): ?>
                        <div class="reply-section">
                            <strong>Reply: </strong>
                            <p class="reply-message" id="reply-<?php echo $enquiry['enquiry_id']; ?>">
                                <?php echo nl2br($enquiry['reply_message']); ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <div class="reply-section">
                            <form action="" method="POST" class="reply-form" id="reply-form-<?php echo $enquiry['enquiry_id']; ?>">
                                <input type="hidden" name="enquiry_id" value="<?php echo $enquiry['enquiry_id']; ?>">
                                <textarea name="reply_message" placeholder="Type your reply..." required></textarea>
                                <button type="submit" class="btn btn-primary mt-2">Submit Reply</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No enquiries found.</p>
        <?php endif; ?>
    </div>

    <!-- JavaScript for toggle sidebar -->
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
