<?php
session_start();

// Function to set role and redirect
if (isset($_POST['role'])) {
    $_SESSION['role'] = $_POST['role'];
    header("Location: dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Corporate Learning Portal</title>
    <style>
        /* Reset */
        body, h1, p, ul, li, input, button {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background: #01274e;
            padding: 1rem 0;
            color: white;
            text-align: center;
        }

        .nav-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 2rem;
        }

        .logo {
            width: 150px;
            height: 60px;
            background: url('images/logo.png') no-repeat center center;
            background-size: contain;
        }

        .nav-links {
            list-style: none;
            display: flex;
        }

        .nav-links li {
            margin-left: 1.5rem;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
        }

        .key ul {
            list-style: none;
            padding-left: 20px;
        }
        .key ul li {
            margin: 10px 0;
            padding-left: 20px;
            position: relative;
        }
        .key ul li::before {
            content: "•";
            position: absolute;
            left: 0;
            color: #004080;
            font-size: 20px;
        }

        .slider-container {
            position: relative;
            overflow: hidden;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .slide {
            min-width: 100%;
            overflow: hidden;
        }

        .slide img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .slider-controls {
            position: absolute;
            top: 50%;
            width: 100%;
            display: flex;
            justify-content: space-between;
            transform: translateY(-50%);
            z-index: 2;
        }

        .container {
            display: flex;
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .main-content {
            flex: 2;
            padding: 20px;
        }

        .main-content img {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .sidebar {
            flex: 1;
            background-color: #f4f4f4;
            padding: 20px;
            border-left: 2px solid #ccc;
        }

        .sidebar h2 {
            margin-bottom: 10px;
        }

        .sidebar p {
            margin-bottom: 20px;
        }

        .sidebar button {
            display: block;
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            background-color: #e0e0e0;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .sidebar button:hover {
            background-color: #d6d6d6;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 16px;
        }

        .form button {
            width: 100%;
            padding: 10px;
            background: #ff7300;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .form button:hover {
            background: #e85b00;
        }

        footer {
            background: #01274e; /* Footer background color */
            color: white;
            text-align: center;
            padding: 1rem;
            width: 100vw; /* Full width of the viewport */
            position: relative; /* Ensure no overlap */
            left: 0; /* Align with the left edge */
        }
    </style>
</head>
<body>
    <header>
        <div class="nav-container">
        <a href="#" class="logo"></a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#help">Help & Support</a></li>
                    <li><a href="dashboard.php">Dashboard</a></li>
                    <li><a href="#courses">Courses</a></li>
                    <li><a href="#mytraining">My Training</a></li>
                    <li><a href="#resources">Resources</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <!-- Main Content Area -->
        <div class="main-content">
            <div class="slider-container">
                <div class="slider">
                    <div class="slide"><img src="images/slide-01.png" alt="Slide 1"></div>
                    <div class="slide"><img src="images/slide-02.png" alt="Slide 2"></div>
                    <div class="slide"><img src="images/slide-03.png" alt="Slide 3"></div>
                </div>
            </div>
        <br>
        <h1>Welcome to the ComplyPro Portal</h1>
        <br>
        <p>ComplyPro provides comprehensive compliance solutions tailored for the power, water, sewerage, and gas industries. Through this platform, you will gain the knowledge and skills essential for maintaining industry compliance and ensuring safety.</p>
        <br>
        <p><strong>ComplyPro’s online training courses</strong> are designed for organisations seeking centralised, flexible compliance management. The training includes interactive components, such as quizzes, to enhance understanding and track progress effectively.</p>
        <br>
        <h2>Key Features:</h2>
        <div class="key">
        <ul>
            <li><strong>Centralised Compliance Management</strong>: Track certifications, licences, and training for all your staff in one unified system.</li>
            <li><strong>Customisable Workflows</strong>: Adapt compliance processes to meet the unique requirements of your organisation and industry.</li>
            <li><strong>Real-Time Tracking</strong>: Monitor activities instantly, ensuring critical compliance tasks are never overlooked.</li>
            <li><strong>Automated Reporting</strong>: Generate audit-ready reports automatically, saving time and reducing errors in manual data entry.</li>
            <li><strong>Mobile Access</strong>: Stay up to date with compliance information from anywhere, with real-time mobile access for field updates.</li>
        </ul>
        </div>
        <br>
        <p>Additional online courses and tools are also available through ComplyPro’s extensive platform.</p>
        </div>

        <!-- Sidebar -->
        <div class="sidebar">
            <h2>Hi Venjie,</h2>
            <p>Welcome back.<br>You last logged in on:<br><strong>Wednesday, 27 Nov 2024 at 1:46 PM.</strong></p>
            <p>Please select your role to proceed to the dashboard:</p>
        <form method="post" action="">
            <select id="role-select" name="role" required>
                <option value="" disabled selected>Select your role</option>
                <option value="Administrator">Administrator</option>
                <option value="Coordinator">Coordinator</option>
                <option value="User">User</option>
            </select>
            <button class="form" type="submit">Proceed</button>
        </form>
        </div>
    </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const slides = document.querySelectorAll('.slide');
            const slider = document.querySelector('.slider');
            let currentIndex = 0;

            const updateSlider = () => {
                slider.style.transform = `translateX(-${currentIndex * 100}%)`;
            };

            setInterval(nextSlide, 5000); // Auto-slide every 5 seconds
        });
    </script>
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2024 ComplyPro. All Rights Reserved.</p>
    </footer>

</body>
</html>