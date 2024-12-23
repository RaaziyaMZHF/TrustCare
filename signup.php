<?php
include('db.php');

$signup_success = false;  // Flag to check if signup is successful
$error_message = '';  // To hold any error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];

    // Check if username or email already exists
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $signup_success = false;
        $error_message = "Email already exists.";
    } else {
        // Insert new user into the database
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        
        if ($conn->query($sql) === TRUE) {
            $signup_success = true;
        } else {
            $signup_success = false;
            $error_message = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Signup</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Display the modal if signup is successful or if there's an error
        function showModal(type) {
            var modal = document.getElementById('signupModal');
            var errorModal = document.getElementById('errorModal');

            if (type === 'success') {
                modal.style.display = 'block';
            } else {
                errorModal.style.display = 'block';
            }
        }

        // Close the modal
        function closeModal(type) {
            var modal = document.getElementById('signupModal');
            var errorModal = document.getElementById('errorModal');

            if (type === 'success') {
                modal.style.display = 'none';
                window.location.href = 'login.php'; // Redirect to login page
            } else {
                errorModal.style.display = 'none';
            }
        }
    </script>
</head>
<link rel="stylesheet" href="css/style.css">
<body>

<header class="header">

<a href="#" class="logo"> <img src="./images/logo.png" alt=""> <strong>Trust</strong>care </a>

<nav class="navbar">
    <a href="./index.html">home</a>
    <a href="./pages/About.html" target="_self">about</a>
    <a href="./pages/services.html">services</a>
    <a href="doctor.php">doctors</a>
    <a href="appoinment.php">appointment</a>
    <a href="#review">review</a>
    <a href="./pages/blogs.html">blogs</a>
</nav>

<div id="menu-btn" class="fas fa-bars"></div>

</header>
    <div class="container">
        <form method="POST">
            <h2>Signup</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="email" name="email" placeholder="Email" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Sign Up</button>
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>

    <!-- Modal for Signup Success -->
    <?php if ($signup_success): ?>
    <div id="signupModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('success')">&times;</span>
            <h2>Signup Successful!</h2>
            <p>You have successfully signed up. Please log in to continue.</p>
            <button onclick="closeModal('success')">OK</button>
        </div>
    </div>
    <script>
        showModal('success'); // Show the modal on successful signup
    </script>
    <?php endif; ?>

    <!-- Modal for Signup Error (e.g., username or email already exists) -->
    <?php if (!$signup_success && $error_message): ?>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('error')">&times;</span>
            <h2>Error</h2>
            <p><?php echo $error_message; ?></p>
            <button onclick="closeModal('error')">OK</button>
        </div>
    </div>
    <script>
        showModal('error'); // Show the modal if there is an error
    </script>
    <?php endif; ?>

    <!-- Modal Styles -->
    <style>
        .modal {
            display: none; /* Hidden by default */
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4); /* Black with transparency */
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            text-align: center;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 20px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        button {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #45a049;
        }
    </style>
</body>
</html>
