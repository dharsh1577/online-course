<?php
require_once '../db.php';
require_once 'payment.php';
 // Ensure this file has the correct database connection

// Fetch course price from Courses table (assuming Digital Marketing course has course_id = 2)
$course_id = 2; // Assuming course_id for Digital Marketing
$stmt = $conn->prepare("SELECT price FROM Courses WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($course_price);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = 6878768782;
    $amount = 100 * 100;

    $course_id = 2; // Assuming "Digital Marketing" has course_id = 2

    // Check if the user exists with the provided name and email
    $user_check_query = "SELECT user_id FROM Users WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($user_check_query);
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        // If user exists, proceed with enrollment
        $enroll_query = "INSERT INTO enrollments (student_id, course_id, status) VALUES (?, ?, 'pending')";
        $stmt = $conn->prepare($enroll_query);
        $stmt->bind_param("ii", $user_id, $course_id);
        if ($stmt->execute()) {
            // Successfully enrolled, redirect to payment.php
            $enrollment_id = $stmt->insert_id;
            $reference_id = "payment-" . $enrollment_id;

            $response = generatepaymentlink($name, $amount, $email, $reference_id, $phone);
            if (isset($response['short_url'])) {
                // Update payment ID in the database
                // print_r($response);
                $payment_id = $response['id'];
                $update_payment_query = "UPDATE enrollments SET payment_id = ? WHERE enrollment_id = ?";
                $stmt = $conn->prepare($update_payment_query);
                $stmt->bind_param("si", $payment_id, $enrollment_id);
                $stmt->execute();
               

                 echo "<script>
                         alert('Enrollment successful. Please complete the payment to start the course.');
                     window.location.href='" . $response['short_url'] . "';
                       </script>";
            } else {
                echo "<script>alert('Error generating payment link.');</script>";
            }
        } else {
            echo "<script>alert('Error during enrollment.');</script>";
        }
        $stmt->close();
    } else {
        // If user does not exist, show message and redirect to register page
        echo "<script>
                alert('You are not registered. Please log in first.');
                window.location.href='register.php';
              </script>";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">* Name</label>
        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your Name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">* Email</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your Email" required>
    </div>
    <div class="mb-3">
        <label for="course" class="form-label">* Course Name</label>
        <input type="text" class="form-control" id="courses" name="courses" value="python" readonly>
    </div>
    <div class="mb-3">
        <label for="price" class="form-label">* Price (₹)</label>
        <input type="text" class="form-control" id="price" name="price" value="<?php echo $course_price; ?>" readonly> <!-- Default price -->
    </div>
    <button type="submit" class="btn btn-custom w-100">Pay Now</button>
</form>
</body>
</html>
