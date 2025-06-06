<?php
// Check if a session is already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Replace with your actual database credentials
$server = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "project_phase1";

// Establish connection
$conn = new mysqli($server, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve user information
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT UserID, Role FROM users WHERE Username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$userId = $row['UserID'];
$role = $row['Role'];

// Fetch respective ID based on role
if ($role === 'buyer') {
    $stmt = $conn->prepare("SELECT BuyerID FROM buyers WHERE UserID=?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row ? $row[array_key_first($row)] : "N/A"; // $id shows the buyerid use it to built buyer site
    $stmt->close();
    // Redirect to Buyer -> buyer.php
    header("Location:buyer.php");
    exit();



} elseif ($role === 'seller') {
    /* $stmt = $conn->prepare("SELECT SellerID FROM seller WHERE UserID=?"); 
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $id = $row ? $row[array_key_first($row)] : "N/A"; 
    $stmt->close(); */

    // Redirect to Seller -> list.php
    header("Location:list.php");
    exit();

} elseif ($role === 'admin') {
    $userId; // use this to built the admin site
    // Redirect to Admin -> admin.php
    header("Location:admin.php");
    exit();


}


// Close statement and connection

$conn->close();
?>