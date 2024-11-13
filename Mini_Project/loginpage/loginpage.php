<?php
// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Set Connection Variables
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
    $name = $_POST['Name'];
    $email = $_POST['Email'];
    $password = $_POST['Password'];
    $dob = $_POST['DOB'];
    $gender = $_POST['gender'];
    $weight = $_POST['Weight'];
    $bp = isset($_POST['bp']) ? $_POST['bp'] : 'not specified';
    $bpLevel = isset($_POST['bpLevel']) ? $_POST['bpLevel'] : ''; // Get BP level if provided
    $diabetes = isset($_POST['diabetes']) ? $_POST['diabetes'] : 'not specified';
    $diabetesDetails = isset($_POST['diabetesDetails']) ? $_POST['diabetesDetails'] : ''; // Get diabetes details if provided

    // If blood pressure is abnormal, update the value
    if ($bp === 'no' && !empty($bpLevel)) {
        $bp = $bpLevel; // Update the bp variable with the provided value
    }

    // If diabetes is present, update the value
    if ($diabetes === 'yes' && !empty($diabetesDetails)) {
        $diabetes = $diabetesDetails; // Update the diabetes variable with the provided details
    }

    // Check if the email already exists
    $checkEmail = "SELECT * FROM healthinfo WHERE email=?";
    $stmt = $con->prepare($checkEmail);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Email already exists. Please log in.');</script>";
    } else {
        // Hash the password before storing it
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query
        $sql = "INSERT INTO healthinfo (name, email, password, dob, gender, weight, blood_pressure, diabetes, submission_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, current_timestamp())";

        $stmt = $con->prepare($sql);
        // Bind parameters
        $stmt->bind_param("ssssssss", $name, $email, $hashedPassword, $dob, $gender, $weight, $bp, $diabetes);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>alert('Successfully registered! Please log in.');</script>";
            header("Location: login.html"); // Redirect to login page
            exit();
        } else {
            echo "ERROR: " . $stmt->error;
        }
    }

    // Close the prepared statement and Database Connection
    $stmt->close();
    $con->close();
}
?>
