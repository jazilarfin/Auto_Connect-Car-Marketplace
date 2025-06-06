<?php
// Replace with your actual database credentials
$server = "localhost";
$username = "root";
$password = "";
$database = "project_phase1";

$error = ""; // Initialize error variable
$success = ""; // Initialize success variable

try {
    // Establish connection
    $conn = new mysqli($server, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Prepare and bind parameters for users table
        $stmt_users = $conn->prepare("INSERT INTO Users (Username, Password, Role, FirstName, LastName, Email, Phone, Address) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_users->bind_param("ssssssss", $username, $password, $role, $firstName, $lastName, $email, $phone, $address);

        // Prepare and bind parameters for seller table
        $stmt_seller = $conn->prepare("INSERT INTO Seller (UserID) VALUES (?)");
        $stmt_seller->bind_param("i", $userID);

        // Prepare and bind parameters for buyers table
        $stmt_buyers = $conn->prepare("INSERT INTO Buyers (UserID) VALUES (?)");
        $stmt_buyers->bind_param("i", $userID);

        // Set parameters and execute for users table
        $username = $_POST["username"];
        $password = $_POST["password"];
        $role = $_POST["role"];
        $firstName = $_POST["firstName"];
        $lastName = $_POST["lastName"];
        $email = $_POST["email"];
        $phone = $_POST["phone"];
        $address = $_POST["address"];

        if ($stmt_users->execute()) {
            // Get the user ID of the inserted user
            $userID = $conn->insert_id;

            // Insert the user ID into the Seller table if the user role is 'seller'
            if ($role === 'seller') {
                $stmt_seller->execute();
            }

            // Insert the user ID into the Buyers table if the user role is 'buyer'
            if ($role === 'buyer') {
                $stmt_buyers->execute();
            }

            // Registration successful
            $success = "Registration successful!";
        } else {
            throw new Exception("Error: " . $conn->error);
        }

        $stmt_users->close();
        $stmt_seller->close();
        $stmt_buyers->close();
    }

    $conn->close();
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage(); // Set error message
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Head content -->
    <style>
        .error-message, .success-message {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .success-message {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
    </style>
</head>
<body>
    <!-- Body content -->
    <?php if (!empty($error)) : ?>
    <div class="error-message">
        <?php echo $error; ?>
    </div>
    <?php endif; ?>

    <?php if (!empty($success)) : ?>
    <div class="success-message">
        <?php echo $success; ?>
    </div>
    <?php endif; ?>

    <!-- JavaScript redirect -->
    <script>
        <?php if (!empty($error)) : ?>
        // If error exists, delay redirect after displaying error message
        setTimeout(function() {
            window.location.href = "register.html";
        }, 3000);
        <?php elseif (!empty($success)) : ?>
        // If success message exists, delay redirect after displaying success message
        setTimeout(function() {
            window.location.href = "index.php";
        }, 3000);
        <?php else : ?>
        // If no error or success, redirect immediately
        window.location.href = "index.php";
        <?php endif; ?>
    </script>
</body>
</html>