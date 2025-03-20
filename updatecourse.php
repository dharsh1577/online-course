<?php
include '../db.php'; // Ensure the correct path to your database connection file

// Check if course ID is provided
if (!isset($_GET['id'])) {
    echo "<script>alert('No course ID provided!'); window.location.href = 'dashboard.php';</script>";
    exit;
}

$course_id = intval($_GET['id']);

// Fetch course data
$sql = "SELECT title, description, price, duration_hours FROM courses WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<script>alert('Course not found!'); window.location.href = 'dashboard.php';</script>";
    exit;
}

$course = $result->fetch_assoc();

// Handle course update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $duration_hours = $_POST['duration_hours'];

    $sql = "UPDATE courses SET title = ?, description = ?, price = ?, duration_hours = ? WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdii", $title, $description, $price, $duration_hours, $course_id);

    if ($stmt->execute()) {
        echo "<script>alert('Course updated successfully!'); window.location.href = 'dashboard.php';</script>";
    } else {
        echo "<script>alert('Error updating course: " . $stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Course</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Edit Course</h1>
        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Course Title</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($course['title']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($course['description']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" step="0.01" class="form-control" id="price" name="price" value="<?php echo $course['price']; ?>" required>
            </div>
            <div class="mb-3">
                <label for="duration_hours" class="form-label">Duration (Hours)</label>
                <input type="number" class="form-control" id="duration_hours" name="duration_hours" value="<?php echo $course['duration_hours']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
