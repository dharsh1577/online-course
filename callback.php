<?php
require_once '../db.php';

$data = $_GET;

if (isset($data['razorpay_payment_link_status']) && $data['razorpay_payment_link_status'] == 'paid') {
    $payment_id = $data['razorpay_payment_link_id'];

    // Update the enrollments table
    $stmt = $conn->prepare("UPDATE enrollments SET status = 'completed' WHERE payment_id = ?");
    $stmt->bind_param("s", $payment_id);

    if ($stmt->execute()) {
        echo 
        "<script>
        alert('Payment status updated successfully.');
        window.location.href='thankyou.php';
      </script>";
       
    } else {
        echo "Error updating payment status: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Payment failed or was not completed.";
}

file_put_contents('razorpay_callback_log.txt', print_r($data, true), FILE_APPEND);
?>
