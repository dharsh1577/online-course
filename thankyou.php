<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'Arial', sans-serif;
            overflow: hidden; /* Hide the scrollbars */
        }

        /* Full-screen background video */
        .background-video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover; /* Ensures the video covers the screen */
            z-index: -1; /* Ensures the video stays behind the content */
        }

        .thank-you-container {
            text-align: center;
            color: black;
            padding: 30px 50px;
            border-radius: 15px; /* Rounded corners */
            max-width: 700px; /* Max width for content */
            position: relative;
            z-index: 1; /* Ensures the content stays above the video */
        }

        .thank-you-container h1 {
            font-size: 3.5em;
            font-weight: bold;
            margin-bottom: 20px;
            letter-spacing: 1px; /* Slightly increased letter spacing */
            text-transform: uppercase; /* Make the heading more dramatic */
            color: #007bff; /* Set the text color */
            text-shadow: 0 0 6px #000, 0 0 10px #007bff, 0 0 15px #007bff, 0 0 20px #007bff; /* Black and blue neon glow */
            animation: neon-glow 1.5s ease-in-out infinite alternate; /* Apply glowing animation */
        }

        @keyframes neon-glow {
            0% {
                text-shadow: 0 0 5px #000, 0 0 10px #007bff, 0 0 15px #007bff;
            }
            100% {
                text-shadow: 0 0 10px #000, 0 0 20px #007bff, 0 0 30px #007bff;
            }
        }

        .thank-you-container p {
            font-size: 1.5em;
            margin-bottom: 30px;
            color: white; 
            line-height: 1.6; /* Improved readability with line spacing */
        }
        .button {
    padding: 18px 35px;
    font-size: 1.3em;
    background: linear-gradient(145deg, #0069d9, #0056b3); /* Gradient background */
    color: white;
    border: none;
    border-radius: 50px; /* More rounded button */
    cursor: pointer;
    text-decoration: none;
    transition: all 0.3s ease; /* Smooth transition for all properties */
    display: inline-block;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); /* Subtle shadow effect */
}

.button:hover {
    background: linear-gradient(145deg, #0056b3, #003d7a); /* Darker gradient for hover */
    transform: translateY(-4px) scale(1.05); /* Button lifts and slightly enlarges */
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3); /* Stronger shadow on hover */
}

.button:active {
    transform: translateY(2px) scale(1); /* Button presses down and resets scale */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2); /* Light shadow when pressed */
}

        @media (max-width: 768px) {
            .thank-you-container {
                padding: 20px 30px;
            }

            .thank-you-container h1 {
                font-size: 2.5em; /* Responsive font size for smaller screens */
            }

            .thank-you-container p {
                font-size: 1.2em; /* Responsive font size for smaller screens */
            }

            .button {
                font-size: 1.1em; /* Slightly smaller button on mobile */
            }
        }
    </style>
</head>
<body>
    <!-- Background Video -->
    <video class="background-video" autoplay loop muted>
        <source src="thank.mp4" type="video/mp4">
        Your browser does not support the video tag.
    </video>

    <div class="thank-you-container">
        <h1>Thank You for Your Registration!</h1>
        <p>We are excited to have you on board. Your journey towards mastering your course begins now!</p>
        <a href="mycourse.php" class="button">Go to My Courses</a>
    </div>
</body>
</html>
