<?php
session_start();
include('db.php'); // Include your database connection here

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];
    $image = $_FILES['image']['name'];
    $target_dir = "upload/";

    // Validate the form inputs
    if (empty($name) || empty($review) || empty($rating)) {
        $error_message = "Please fill in all fields.";
    } elseif ($_FILES['image']['error'] === 4) {
        // No image uploaded
        $image = null;
    } elseif ($_FILES['image']['error'] === 0) {
        // File upload
        $target_file = $target_dir . basename($image);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file type (only images allowed)
        if (in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            $error_message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        }
    }

    // Insert into the database if everything is correct
    if (!$error_message) {
        $sql = "INSERT INTO reviews (name, review, rating, image) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssis", $name, $review, $rating, $image);
        
        if ($stmt->execute()) {
            $success_message = "Your review has been submitted successfully!";
        } else {
            $error_message = "There was an error submitting your review.";
        }

        $stmt->close();
    }

    // if (!is_dir($target_dir)) {
    //     echo "Error: The target directory does not exist.";
    // } elseif (!is_writable($target_dir)) {
    //     echo "Error: The target directory is not writable.";
    // } else {
    //     if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
    //         echo "File uploaded successfully.";
    //     } else {
    //         echo "Failed to upload file.";
    //     }
    // }
    
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Submit Review</title>
    <link rel="stylesheet" href="style.css">
    <script>
        // Show success or error message in popup
        function showModal(type) {
            var modal = document.getElementById(type + 'Modal');
            modal.style.display = 'block';
        }

        // Close the modal
        function closeModal(type) {
            var modal = document.getElementById(type + 'Modal');
            modal.style.display = 'none';
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
    <a href="logout.php" class="logout-btn">Logout</a>
</nav>

<div id="menu-btn" class="fas fa-bars"></div>

</header>
    <!-- Logout Button -->


    <div class="containeradd">
        <h2>Submit Your Review</h2>

        <!-- Review Form -->
        <form method="POST" enctype="multipart/form-data">
            <label for="name">Name:</label><br>
            <input type="text" name="name" id="name" required><br>

            <label for="review">Review:</label><br>
            <textarea name="review" id="review" required></textarea><br>

            <label for="rating">Rating:</label><br>
            <select name="rating" id="rating" required>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
            </select><br>

            <label for="image">Upload Image:</label><br>
            <input type="file" name="image" id="image"><br><br>

            <button type="submit">Submit Review</button>
        </form>
    </div>



    <!-- Success Modal -->
    <?php if ($success_message): ?>
    <div id="successModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('success')">&times;</span>
            <h2>Success</h2>
            <p><?php echo $success_message; ?></p>
            <button onclick="closeModal('success')">OK</button>
        </div>
    </div>
    <script>
        showModal('success'); // Show success modal
    </script>
    <?php endif; ?>

    <!-- Error Modal -->
    <?php if ($error_message): ?>
    <div id="errorModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('error')">&times;</span>
            <h2>Error</h2>
            <p><?php echo $error_message; ?></p>
            <button onclick="closeModal('error')">OK</button>
        </div>
    </div>
    <script>
        showModal('error'); // Show error modal
    </script>
    <?php endif; ?>

    <!-- Modal Styles -->
    <style>
        .containeradd {
    background-color: #fff;
    padding: 30px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    width: 600px;
    max-width: 100%;
    margin-top: 300px;
}
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
