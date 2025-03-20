<?php
session_start();
include '../db.php'; // Make sure to include your database connection file

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course_name = $_POST['course_name'];
    $course_image = $_POST['course_image']; 

    $stmt = $conn->prepare("INSERT INTO Upcoming (course_name, course_image) VALUES (?, ?)");
    $stmt->bind_param("ss", $course_name,$course_image);

    // Execute the statement
    if ($stmt->execute()) {
        echo "New course created successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
    /* Centering Container */
    .centered-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        background-color: #f4f4f4;
        padding: 20px;
    }

    /* Form Styling */
    .form-container {
        background-color: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.4);
        width: 100%;
        max-width: 500px;
        position: relative; /* Ensures no unwanted shifting */
    }

    /* Improve Input Styling */
    .form-group label {
        font-weight: bold;
        color: #333;
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #ccc;
        padding: 10px;
        font-size: 16px;
        transition: all 0.3s ease-in-out;
    }

    .form-control:focus {
        border-color: #007bff;
        box-shadow: 0 0 8px rgba(0, 123, 255, 0.3);
        outline: none;
    }

    /* Button Styling */
    .btn-primary {
        background-color: #007bff;
        border: none;
        padding: 12px;
        font-size: 18px;
        width: 100%;
        border-radius: 8px;
        transition: background 0.3s;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    /* Fix for Small Screens */
    @media (max-width: 768px) {
        .form-container {
            padding: 20px;
        }
    }
</style>

</head>
<body>
    <div class="centered-container">
        <div class="form-container col-lg-8 col-md-10 col-sm-12">
 
            <form action="newup.php" method="post">
                <div class="form-group">
                    <label for="title">course_name:</label>
                    <input type="text" class="form-control" id="course_name" name="course_name" required>
                </div>
                
                <div class="form-group">
                    <label for="course_image">course_image:</label>
                    <textarea class="form-control" id="course_image" name="course_image" required></textarea>
                </div>
               
                
                
                <button type="submit" class="btn btn-primary">Create Course</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
