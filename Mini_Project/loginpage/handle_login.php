<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection variables
    $server = "localhost";
    $username = "root";
    $password = "";
    $database = "loginpage";

    // Create a Database connection
    $con = mysqli_connect($server, $username, $password, $database);

    // Check for connection success
    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // Collect POST variables
    $email = $_POST['Email'];
    $password = $_POST['Password'];

    // Prepare the SQL query to check for the user
    $sql = "SELECT * FROM healthinfo WHERE email=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a user is found
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Password is correct, redirect to the main project
            header("Location: http://127.0.0.1:5000/"); // Change to the correct path
            exit();
        } else {
            // Invalid password
            echo "<script>alert('Invalid email or password. Please try again.');</script>";
            echo "<script>window.location.href = 'login.html';</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Invalid email or password. Please try again.');</script>";
        echo "<script>window.location.href = 'login.html';</script>";
    }

    // Close the prepared statement and Database Connection
    $stmt->close();
    $con->close();
}
?>
