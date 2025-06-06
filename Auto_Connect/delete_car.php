<?php

// file_put_contents('debug.log', print_r($_POST, true));

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project_phase1";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the car ID is provided in the POST request
if (isset($_POST['carId'])) {
    // Sanitize the input to prevent SQL injection
    $carId = $conn->real_escape_string($_POST['carId']);

    // SQL query to delete the car with the given ID
    $sql = "DELETE FROM cars WHERE CarID = $carId";

    // Execute the query
    if ($conn->query($sql) === TRUE) {
        // Send a success response
        echo "Car deleted successfully";
    } else {
        // Send an error response
        echo "Error deleting car: " . $conn->error;
    }
} else {
    // Send an error response if the car ID is not provided
    echo "Car ID not provided";
}

// Close the database connection
$conn->close();
?>
