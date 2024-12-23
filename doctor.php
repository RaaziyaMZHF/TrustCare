<?php
// Database connection
$servername = "localhost";  // Replace with your database server
$username = "root";         // Replace with your database username
$password = "";             // Replace with your database password
$dbname = "trustcare";      // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables for filter and search
$specialtyFilter = isset($_POST['specialty']) ? $_POST['specialty'] : '';
$searchQuery = isset($_POST['search']) ? $_POST['search'] : '';

// Build query with filter and search
$query = "SELECT * FROM doctors WHERE 1=1";

if ($specialtyFilter) {
    $query .= " AND department LIKE '%" . $conn->real_escape_string($specialtyFilter) . "%'";
}

if ($searchQuery) {
    $query .= " AND name LIKE '%" . $conn->real_escape_string($searchQuery) . "%'";
}

// Execute the query
$result = $conn->query($query);

// Get all specialties for the filter
$specialtiesQuery = "SELECT DISTINCT department FROM doctors";
$specialtiesResult = $conn->query($specialtiesQuery);

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctors</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .containerdoc {
            width: 80%;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            margin-top: 200px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .filter, .search {
            margin-bottom: 20px;
            text-align: center;
        }
        .filter select, .search input {
            padding: 10px;
            margin: 5px;
            font-size: 16px;
        }
        .search input {
            width: 200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
            text-align: left;
        }
        th, td {
            padding: 12px;
        }
        th {
            background-color: #4CAF50;
            color: white;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .no-records {
            text-align: center;
            color: red;
        }
        /* Search Button Styling */
.search button {
    padding: 10px 20px;
    font-size: 16px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.search button:hover {
    background-color: #45a049;  /* Darker shade on hover */
}

.search button:focus {
    outline: none;  /* Remove outline when clicked */
}

    </style>
</head>
<body>
<header class="header">

<a href="#" class="logo"> <img src="./images/logo.png" alt=""> <strong>Trust</strong>care </a>

<nav class="navbar">
    <a href="./index.html">home</a>
    <a href="./pages/About.html">about</a>
    <a href="./pages/services.html">services</a>
    <a href="./doctors.php">doctors</a>
    <a href="./appoinment.php">appointment</a>
    <a href="./signup.php">review</a>
    <a href="./pages/blogs.html">blogs</a>
</nav>

<div id="menu-btn" class="fas fa-bars"></div>

</header>

<div class="containerdoc">
    <h2>Doctors List</h2>

    <!-- Search Bar -->
    <div class="search">
        <form method="POST" action="doctor.php">
            <input type="text" name="search" placeholder="Search by Name" value="<?php echo htmlspecialchars($searchQuery); ?>" />
            <button type="submit">Search</button>
        </form>
    </div>

    <!-- Filter Form (by Specialty) -->
    <div class="filter">
        <form method="POST" action="doctor.php">
            <select name="specialty" onchange="this.form.submit()">
                <option value="">Select Specialty</option>
                <?php while ($row = $specialtiesResult->fetch_assoc()): ?>
                    <option value="<?php echo $row['department']; ?>" <?php echo ($specialtyFilter == $row['department']) ? "selected" : ""; ?>>
                        <?php echo $row['department']; ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>
    </div>

    <!-- Doctor Table -->
    <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialty</th> <!-- Display Department (Specialty) -->
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo $row['department']; ?></td> <!-- Department (Specialty) -->
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="no-records">No doctors found matching your search or filter.</p>
    <?php endif; ?>
</div>
<script>
        const menuButton = document.getElementById('menu-btn');
    const navbar = document.querySelector('.navbar');

    menuButton.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });
    </script>

</body>
</html>
