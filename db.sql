/* Users Management */
CREATE TABLE Users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student' NOT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active' NOT NULL
);

INSERT INTO Users (username, email, password, role, status) 
VALUES 
('admin', 'admin@example.com', 'dharsh07', 'admin', 'active'),
('dharsh', 'dharsh@gmail.com', '1234567', 'student', 'active'),
('Aappu', 'Aapu@gmail.com', '12345', 'student', 'active'),
('banu', 'banu@gmail.com', '12345', 'student', 'active');

/* Course Management */
CREATE TABLE Courses (
    course_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    lessons INT NOT NULL,
    image_url VARCHAR(255),
    video_url VARCHAR(255), -- Column to store a single video URL
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);


INSERT INTO Courses (title, description, price, lessons, image_url, video_url) VALUES 
('3D with Blender',' Process of creating, modeling, animating, and rendering three-dimensional content using Blender, a powerful and free open-source 3D software. Blender is widely used by artists, designers, and developers for various creative projects','1500','12','https://i.pinimg.com/736x/1a/c3/79/1ac37977d2f559e8aeca64011e22aec3.jpg', NULL),
('Digital Marketing',' Use of online channels and technologies to promote products, services, or brands. It includes various strategies such as search engine optimization (SEO), social media marketing, email marketing, content marketing, and paid advertising.','1500','12','https://i.pinimg.com/736x/d3/b6/fa/d3b6fa75258432f7c8f07bc38a7d67fc.jpg', NULL),
('Machine Learning',' Branch of artificial intelligence (AI) that focuses on developing algorithms and statistical models that enable computers to perform tasks without explicit programming.','$1500','12','https://i.pinimg.com/736x/72/c3/a1/72c3a11ef2d82e70dfde5f0f1301ae64.jpg', NULL),
('Full stack developer',' Practice of working with both the front-end and back-end components of a web application. A Full Stack Developer has the ability to develop and manage both the client-side (front-end) and the server-side (back-end) of a web application. ','1500','12','https://i.pinimg.com/736x/f5/db/5d/f5db5d12261703c9902547e56515fcae.jpg', NULL);

/* Course Videos */
CREATE TABLE course_videos (
    video_id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    video_url VARCHAR(255) NOT NULL,
    FOREIGN KEY (course_id) REFERENCES Courses(course_id) ON DELETE CASCADE
);


INSERT INTO Course_Videos (course_id, video_url) VALUES
(1, 'https://www.youtube.com/embed/nIoXOplUvAw?si=f58vzXkzf-ti5xmj'),
(1, 'https://www.youtube.com/embed/Rqhtw7dg6Wk?si=48oJ72V_6JL1NyZo'),
(2, 'https://www.youtube.com/embed/nIoXOplUvAw?si=f58vzXkzf-ti5xmj'),
(2, 'https://www.youtube.com/embed/nIoXOplUvAw?si=f58vzXkzf-ti5xmj'),
(3, 'https://www.youtube.com/embed/nIoXOplUvAw?si=f58vzXkzf-ti5xmj'),
(3, 'https://www.youtube.com/embed/nIoXOplUvAw?si=f58vzXkzf-ti5xmj'),
(4, 'https://www.youtube.com/embed/nIoXOplUvAw?si=f58vzXkzf-ti5xmj'),
(4, 'https://www.youtube.com/embed/Rqhtw7dg6Wk?si=48oJ72V_6JL1NyZo');

/* Upcoming Courses Table */
CREATE TABLE Upcoming (
    upcoming_id INT AUTO_INCREMENT PRIMARY KEY,
    course_name VARCHAR(255) NOT NULL,
    course_image VARCHAR(255) NOT NULL
);

INSERT INTO Upcoming (course_name, course_image) VALUES
('Artificial Intelligence', 'https://i.pinimg.com/474x/13/6e/75/136e7543801d0facf2155d39c9667b39.jpg'),
('Data Science with Python', 'https://i.pinimg.com/236x/6e/e9/18/6ee918d6bad713109da451d783c45126.jpg'),
('Web Development Bootcamp', 'https://i.pinimg.com/236x/d0/01/7c/d0017c91cd96e091e83882b3f63925fa.jpg'),
('Mobile app development', 'https://i.pinimg.com/236x/0a/eb/ac/0aebaca476b29dc1e6554a70fc29dfda.jpg');


CREATE TABLE Enrollments (
    enrollment_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL, -- Stores the payment amount
    enrollment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Completed', 'Dropped') NOT NULL,
    payment_id VARCHAR(255), -- Column for Razorpay payment ID
    reference_id VARCHAR(255), -- Column for reference ID
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (course_id) REFERENCES Courses(course_id)
);



INSERT INTO Enrollments (user_id,  course_id, status) VALUES
 ('1',  '1', 'Completed'),
 ('2',  '2', 'Active'),
 ('3', '3', 'Dropped');

/* Feedback Management */
CREATE TABLE Feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    course_id INT NOT NULL,
    feedback_text TEXT NOT NULL,
    rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5), -- Ensures rating is between 1 and 5
    feedback_date DATE NOT NULL DEFAULT CURRENT_DATE, -- Stores the date when feedback was posted
    reply_message TEXT, -- Added to store the reply message
    FOREIGN KEY (user_id) REFERENCES Users(user_id),
    FOREIGN KEY (course_id) REFERENCES Courses(course_id)
);

INSERT INTO `feedback`(`user_id`, `course_id`, `feedback_text`, `rating`) VALUES
('1', '1', 'This course was an amazing journey into Full Stack Development. The concepts were explained in a clear and concise manner, and the projects helped solidify my understanding. I especially enjoyed the section on backend development, as it was something I had no prior experience with. Highly recommend this course for anyone looking to learn Full Stack Development!', '5'),
('2', '2', 'The Digital Marketing course provided in-depth insights into the world of online marketing. The mentor explained concepts like SEO, social media strategies, and email marketing with real-world examples. The assignments were challenging but rewarding, and I feel much more confident applying these skills in a professional setting.', '4'),
('3', '3', 'The Machine Learning course is definitely a good starting point for anyone interested in AI. The curriculum covered topics like supervised learning, unsupervised learning, and neural networks. However, I felt that some sections, especially the math-heavy ones, could have used more detailed explanations. Overall, it was a worthwhile experience.', '4'),
('4', '4', 'The Full Stack Development course exceeded my expectations! The content was well-structured, and the mentor’s delivery made even the complex topics easy to grasp. I particularly liked how the course focused on practical, real-world applications. The capstone project at the end of the course tied everything together beautifully.', '5'),
('3', '1', 'While the course content was good, I found the pacing to be a bit uneven. Some topics were covered very quickly, while others were repeated several times. The projects were helpful in understanding the material, but I feel there could have been more variety. Overall, it’s a good course, but there’s room for improvement.', '3'),
('2', '2', 'This Digital Marketing course was fantastic. It not only taught me the basics but also introduced me to advanced concepts like analytics and paid advertising strategies. The mentor shared valuable insights from their own professional experience, which made the course even more engaging. I can already see how this knowledge will help me grow my career.', '5');



/* Enquiries */
CREATE TABLE Enquiries (
    enquiry_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    number VARCHAR(15) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    reply_message TEXT -- Added to store the reply message
);

INSERT INTO `enquiries`( `name`, `email`, `number`, `message`) VALUES
 ('dharsh','dharsh@gmail.com','7373998498','plz help me....!');

