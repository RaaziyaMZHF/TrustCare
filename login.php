<?php
session_start();
include('db.php'); // Make sure your DB connection is correct

$login_success = false;
$error_message = ''; // To hold any error message

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Check if username exists in the database
    $sql = "SELECT * FROM users WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User exists, now check the password
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            // Successful login, create a session
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id']; // Store user ID if needed

            // Redirect to the dashboard or home page
            header('Location: add_review.php');
            exit();
        } else {
            $error_message = "Incorrect password.";
        }
    } else {
        $error_message = "Username does not exist.";
    }

    $stmt->close(); // Close the prepared statement
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Display the modal if there's an error
        function showModal() {
            var errorModal = document.getElementById('errorModal');
            errorModal.style.display = 'block';
        }

        // Close the modal
        function closeModal() {
            var errorModal = document.getElementById('errorModal');
            errorModal.style.display = 'none';
        }
    </script>
</head>
<link rel="stylesheet" href="css/style.css">
<body>
<header class="header">

<a href="#" class="logo"> <img src="./images/logo.png" alt=""> <strong>Trust</strong>care </a>

<nav class="navbar">
    <a href="#home">home</a>
    <a href="./pages/About.html" target="_self">about</a>
    <a href="./pages/services.html">services</a>
    <a href="#doctors">doctors</a>
    <a href="#appointment">appointment</a>
    <a href="#review">review</a>
    <a href="./pages/blogs.html">blogs</a>
</nav>

<div id="menu-btn" class="fas fa-bars"></div>

</header>
    <div class="container">
        <form method="POST">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required><br>
            <input type="password" name="password" placeholder="Password" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Don't have an account? <a href="signup.php">Signup</a></p>
    </div>

    <!-- Modal for Error (incorrect username or password) -->
    <?php if ($error_message): ?>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Error</h2>
            <p><?php echo $error_message; ?></p>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>
    <script>
        showModal(); // Show the modal if there is an error
    </script>
    <?php endif; ?>

    <!-- Modal Styles -->
    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4); 
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
