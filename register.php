<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root"; // Change to your database username
$password = ""; // Change to your database password
$database = "students_db"; // Change to your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before storing it

    // Prepare and bind statement to insert new user into the database
    $stmt = $conn->prepare("INSERT INTO students (username, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $username, $password);

    // Execute the statement
    if ($stmt->execute()) {
        // Registration successful, set session variables and redirect to dashboard
        $_SESSION['username'] = $username;
        header("Location: dashboard.php");
        exit();
    } else {
        // Registration failed
        echo "Registration failed. Please try again.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
