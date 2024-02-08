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
    $password = $_POST['password'];

    // Prepare and bind statement
    $stmt = $conn->prepare("SELECT id, username, password FROM students WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verify password
    if ($user && password_verify($password, $user['password'])) {
        // Password is correct, set session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        // Redirect to student dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        // Incorrect username or password
        echo "Incorrect username or password. <a href='login.html'>Try again</a>.";
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
