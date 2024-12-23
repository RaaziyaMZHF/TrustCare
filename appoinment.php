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

// Variables to hold form data
$doctor = $service = $appointment_date = $patient_name = $phone_number = "";
$doctorErr = $serviceErr = $dateErr = $nameErr = $phoneErr = $successMsg = $errorMsg = "";

// Fetch doctors from the database
$doctorQuery = "SELECT id, name FROM doctors"; // Adjust table/column names as needed
$doctorResult = $conn->query($doctorQuery);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate Doctor
    if (empty($_POST["doctor"])) {
        $doctorErr = "Doctor selection is required.";
    } else {
        $doctor = $_POST["doctor"];
    }

    // Validate Service
    if (empty($_POST["service"])) {
        $serviceErr = "Service selection is required.";
    } else {
        $service = $_POST["service"];
    }

    // Validate Appointment Date
    if (empty($_POST["appointment_date"])) {
        $dateErr = "Appointment date is required.";
    } else {
        $appointment_date = $_POST["appointment_date"];
    }

    // Validate Patient Name
    if (empty($_POST["patient_name"])) {
        $nameErr = "Patient name is required.";
    } else {
        $patient_name = $_POST["patient_name"];
    }

    // Validate Phone Number
    if (empty($_POST["phone_number"])) {
        $phoneErr = "Phone number is required.";
    } else {
        $phone_number = $_POST["phone_number"];
        // Check if phone number is valid (simple check)
        if (!preg_match("/^[0-9]{10}$/", $phone_number)) {
            $phoneErr = "Phone number must be 10 digits.";
        }
    }

    // If there are no errors, insert data into the database
    if (empty($doctorErr) && empty($serviceErr) && empty($dateErr) && empty($nameErr) && empty($phoneErr)) {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO appointments (doctor, service, appointment_date, patient_name, phone_number) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $doctor, $service, $appointment_date, $patient_name, $phone_number);

        // Execute the query
        if ($stmt->execute()) {
            $successMsg = "Appointment booked successfully!";
        } else {
            $errorMsg = "Error: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment</title>
    <link rel="stylesheet" href="appointment.css">
    <link rel="stylesheet" href="css/style.css">
    

</head>
<body>
<header class="header">
        <a href="#" class="logo"> <img src="./images/logo.png" alt=""> <strong>Trust</strong>care </a>
        
        <nav class="navbar">
            <a href="index.html">home</a>
            <a href="./pages/About.html">about</a>
            <a href="./pages/Services.html">services</a>
            <a href="./doctor.php">doctors</a>
            <a href="#appoinment">appointment</a>
            <a href="signup.php">review</a>
            <a href="./pages/blogs.html">blogs</a>
        </nav>
    
        <div id="menu-btn" class="fas fa-bars"></div>
    </header>

<!-- Appointment Form -->
<form action="appoinment.php" method="POST">
    <h2>Book an Appointment</h2>

    <!-- Doctor Dropdown -->
    <label for="doctor">Select Doctor</label>
    <select name="doctor" id="doctor" required>
        <option value="">Select a Doctor</option>
        <?php
        // Loop through the results to populate the dropdown
        if ($doctorResult->num_rows > 0) {
            while ($row = $doctorResult->fetch_assoc()) {
                echo "<option value='" . $row['id'] . "'>" . $row['name'] . "</option>";
            }
        } else {
            echo "<option value=''>No doctors available</option>";
        }
        ?>
    </select>
    <span class="error-message"><?php echo $doctorErr; ?></span>

    <!-- Service Dropdown -->
    <label for="service">Select Service</label>
    <select name="service" id="service" required>
        <option value="">Select a Service</option>
        <option value="Consultation" <?php echo ($service == "Consultation") ? "selected" : ""; ?>>Consultation</option>
        <option value="Follow-up" <?php echo ($service == "Follow-up") ? "selected" : ""; ?>>Follow-up</option>
        <option value="Emergency" <?php echo ($service == "Emergency") ? "selected" : ""; ?>>Emergency</option>
    </select>
    <span class="error-message"><?php echo $serviceErr; ?></span>

    <!-- Appointment Date -->
    <label for="appointment_date">Appointment Date</label>
    <input type="date" id="appointment_date" name="appointment_date" value="<?php echo $appointment_date; ?>" required>
    <span class="error-message"><?php echo $dateErr; ?></span>

    <!-- Patient Name -->
    <label for="patient_name">Patient Name</label>
    <input type="text" id="patient_name" name="patient_name" value="<?php echo $patient_name; ?>" required>
    <span class="error-message"><?php echo $nameErr; ?></span>

    <!-- Phone Number -->
    <label for="phone_number">Phone Number</label>
    <input type="text" id="phone_number" name="phone_number" value="<?php echo $phone_number; ?>" required>
    <span class="error-message"><?php echo $phoneErr; ?></span>

    <!-- Submit Button -->
    <button type="submit">Submit Appointment</button>
</form>

<!-- Popup Messages -->
<div id="popupSuccess" class="popup">
    <div class="message"><?php echo $successMsg; ?></div>
    <button class="close-btn" onclick="closePopup('popupSuccess')">Close</button>
</div>

<div id="popupError" class="popup">
    <div class="message"><?php echo $errorMsg; ?></div>
    <button class="close-btn" onclick="closePopup('popupError')">Close</button>
</div>

<!-- JavaScript to handle popup display -->
<script>
    // Show the success or error popup if there's a message
    <?php if ($successMsg): ?>
        document.getElementById('popupSuccess').classList.add('show');
    <?php elseif ($errorMsg): ?>
        document.getElementById('popupError').classList.add('show');
    <?php endif; ?>

    // Close the popup
    function closePopup(popupId) {
        document.getElementById(popupId).classList.remove('show');
    }
 
        const menuButton = document.getElementById('menu-btn');
    const navbar = document.querySelector('.navbar');

    menuButton.addEventListener('click', () => {
        navbar.classList.toggle('active');
    });
</script>

</body>
</html>
