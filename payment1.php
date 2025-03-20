<?php
require_once '../db.php'; // Database connection

date_default_timezone_set('Asia/Kolkata'); // Set timezone
$timestamp = date("Y-m-d H:i:s"); // Current timestamp

// Razorpay Credentials (Replace with actual keys in production)
$key_id = "rzp_test_RcJak5fcO3zfR9";
$key_secret = "ul8MqhYAbfpeikySEmq7vr6B";

// Get user details from the form
$name = $_POST['name'];
$email = $_POST['email'];
$course_id = $_POST['course'];  // Course ID selected from the form

// Fetch course price
$course_query = "SELECT price FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($course_query);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$stmt->bind_result($price);
$stmt->fetch();
$stmt->close();

if ($price) {
    $amount = $price * 100; // Convert to paise
    $reference_id = "ENR" . time(); // Unique reference ID
    $expire_by = time() + (60 * 60 * 24); // Expire in 24 hours

    // Check if user exists in the database
    $user_check_query = "SELECT user_id FROM Users WHERE username = ? AND email = ?";
    $stmt = $conn->prepare($user_check_query);
    $stmt->bind_param("ss", $name, $email);
    $stmt->execute();
    $stmt->bind_result($user_id);
    $stmt->fetch();
    $stmt->close();

    if ($user_id) {
        // Generate payment link
        $response = generatepaymentlink($name, $amount, $email, $reference_id, "");

        if (isset($response['short_url'])) {
            $payment_id = $response['id'];
            $short_url = $response['short_url'];

            // Insert enrollment details into the database
            $stmt = $conn->prepare("INSERT INTO Enrollments (user_id, course_id, amount, status, enrollment_date, payment_id, reference_id) VALUES (?, ?, ?, 'Pending', ?, ?, ?)");
            $stmt->bind_param("iidsss", $user_id, $course_id, $price, $timestamp, $payment_id, $reference_id);
            $stmt->execute();
            $stmt->close();

            // Redirect user to payment page
            echo "<script>
                    window.location.href='$short_url';
                  </script>";
        } else {
            echo "<script>alert('Error generating payment link.');</script>";
        }
    } else {
        echo "<script>
                alert('You are not registered. Please log in first.');
                window.location.href='login.php';
              </script>";
    }

} else {
    echo "<script>alert('Course not found. Please try again.');</script>";
}

$conn->close();

// Function to generate payment link
function generatepaymentlink($name, $amount, $email, $reference_id, $phone) {
    global $key_id, $key_secret;
    
    $url = "https://api.razorpay.com/v1/payment_links";
    $data = [
        "amount" => $amount,
        "currency" => "INR",
        "reference_id" => $reference_id,
        "description" => "Payment for Course",
        "expire_by" => time() + (60 * 60 * 24),
        "customer" => [
            "name" => $name,
            "email" => $email,
            "contact" => $phone
        ],
        "notify" => [
            "sms" => true,
            "email" => true
        ],
        "callback_url" => "http://localhost/dharsh/user/callback.php", // Replace with your actual callback URL
        "callback_method" => "get"
    ];

    $json_data = json_encode($data);
    $auth = base64_encode("$key_id:$key_secret");

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . $auth
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['short_url'])) {
            return $response_data;
        } else {
            echo "Error: Could not generate payment link.";
        }
    }
    curl_close($ch);
}
?>
