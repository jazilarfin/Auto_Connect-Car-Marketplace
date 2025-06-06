<?php

		// Start PHP session
		// Check if a session is not already active
		if (session_status() == PHP_SESSION_NONE) {
  		session_start();
		}





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

// Get form data
$make = $_POST['make'] ?? '';
$model = $_POST['model'] ?? '';
$year = $_POST['year'] ?? '';
$mileage = $_POST['mileage'] ?? '';
$price = $_POST['price'] ?? '';
$city = $_POST['city'] ?? '';
$description = $_POST['description'] ?? '';
$bodytype = $_POST['bodytype'] ?? '';
$variant = $_POST['variant'] ?? '';

// Retrieve logged-in user's seller ID
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT s.SellerID FROM seller s JOIN users u ON s.UserID = u.UserID WHERE u.Username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
// $sellerId = $row['SellerID'];
// Check if $row is not null before accessing $row['SellerID']
if ($row !== null && isset($row['SellerID'])) {
    $SellerID = $row['SellerID'];

}







// Check if image file is uploaded
if(isset($_FILES["imageUpload"]) && $_FILES["imageUpload"]["error"] === UPLOAD_ERR_OK) {
    // Specify the directory where you want to save the image
    $target_directory = "uploads/";

    // Get the file name
    $file_name = basename($_FILES["imageUpload"]["name"]);

    // Specify the file path
    $target_file = $target_directory . $file_name;

    // Move the uploaded file to the specified directory
    if (move_uploaded_file($_FILES["imageUpload"]["tmp_name"], $target_file)) {
        // echo "The file ". htmlspecialchars( basename( $_FILES["imageUpload"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
} else {
    // Use default image if no image uploaded
    $target_file = "uploads/CarDefault.png";
}

// Insert data into database
$sql = "INSERT INTO cars (Make, Model, Year, Mileage, Price, `City`, Description, BodyType, Photo, Variant, SellerID)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssssssi", $make, $model, $year, $mileage, $price, $city, $description, $bodytype, $target_file, $variant, $SellerID);

// Check for errors during SQL query execution
if ($stmt->execute()) {
    // echo "New record created successfully";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

// Close statement and connection
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selling Confirmation</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <div class="alert alert-success" role="alert">
            Vehicle registered successfully. Redirecting to the list page...
        </div>
    </div>

    <script>
        // Redirect to list.php after 3 seconds
        setTimeout(function() {
            window.location.href = "list.php";
        }, 3000);
    </script>
</body>
</html>
