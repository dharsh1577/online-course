<?php
require_once '../db.php'; // Ensure the correct path to your database connection file

// Check if a course ID is provided
if (isset($_GET['course_id'])) {
    $course_id = intval($_GET['course_id']);
} else {
    header("Location: courses.php");
    exit;
}

// Function to fetch course details by ID
function fetchCourseById($conn, $course_id) {
    $sql = "SELECT course_id, title FROM courses WHERE course_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

// Function to extract video ID from YouTube URL
function extractYouTubeVideoId($url) {
    if (preg_match('/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|(?:.*?[\?&]v=))([^"&?\/\s]{11}))/i', $url, $matches)) {
        return $matches[1];
    }
    return false;
}

// Handle video URL upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $video_url = $_POST['video_url'] ?? null;

    if ($video_url) {
        // Extract video ID and create embed URL
        $video_id = extractYouTubeVideoId($video_url);
        if ($video_id) {
            $embed_url = "https://www.youtube.com/embed/" . $video_id;

            // Insert URL into database
            $sql = "INSERT INTO course_videos (course_id, video_url) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $course_id, $embed_url);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Invalid YouTube URL.";
        }
    }
}

// Fetch course details
$course = fetchCourseById($conn, $course_id);

// Fetch uploaded videos for the course
$sql = "SELECT video_url FROM course_videos WHERE course_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $course_id);
$stmt->execute();
$videos = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Videos for <?php echo $course['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-4">
        <h3>Upload Videos for <?php echo $course['title']; ?></h3>
        
        <!-- Video Upload Form -->
        <form action="upload.php?course_id=<?php echo $course_id; ?>" method="POST">
            <div class="mb-3">
                <label for="video_url" class="form-label">YouTube Video URL</label>
                <input type="url" class="form-control" name="video_url" placeholder="Enter YouTube video URL" required />
            </div>
            
            <button type="submit" class="btn btn-primary">Upload Video</button>
        </form>

        <hr>

        <h4>Uploaded Videos</h4>
        <?php if ($videos->num_rows > 0): ?>
            <div class="row">
                <?php $counter = 0; ?>
                <?php while ($video = $videos->fetch_assoc()): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <iframe width="100%" height="315" src="<?php echo $video['video_url']; ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                    <?php if (++$counter % 2 == 0) echo '</div><div class="row">'; ?>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>No videos uploaded yet.</p>
        <?php endif; ?>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
