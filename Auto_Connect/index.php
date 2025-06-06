<?php

session_start();

// Replace with your actual database credentials
$server = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$database = "project_phase1";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve username and password from the formn
    $usernameInput = $_POST["username"];
    $passwordInput = $_POST["password"];

    // Establish connection
    $conn = new mysqli($server, $username, $password, $database);

    // Check connection
    if ($conn->connect_error) {
        $error = "Connection failed: " . $conn->connect_error;
    } else {
        // Prepare SQL statement to retrieve user information
        $stmt = $conn->prepare("SELECT * FROM Users WHERE Username=? AND Password=?");
        $stmt->bind_param("ss", $usernameInput, $passwordInput);

        // Execute SQL statement
        $stmt->execute();

        // Get result
        $result = $stmt->get_result();

        // Check if any row is returned
        if ($result->num_rows > 0) {
            // Successful login
            // $error = "Login successful!";
            $row = $result->fetch_assoc();
            $_SESSION['username'] = $row['Username'];

            // Successful login, redirect to list.php
            header("Location:search.php");
            exit();
        } else {
            // Invalid credentials
            $error = "Invalid username or password";
        }

        // Close statement and connection
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

    <!-- this code was done before by jazil  -->
    <!-- Main Content -->
    <!-- <div class="container mt-4">
        <h1>Login</h1>
        <?php if (!empty($error)) : ?>
        <div class="alert alert-<?php echo ($error === 'Login successful!') ? 'success' : 'danger'; ?>" role="alert">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>



        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div> -->
            <!-- jazil code uptill this  -->

      <!-- code injected by Benzene        -->
      <!-- navigation bar    -->
      
 <!-- Navigation -->
 <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="#">Vehicle Sales & Purchase</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="list.php">Listings</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sell.html">Sell Your Vehicle</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Login</a>
                </li>
            </ul> -->
            <span style = " text-shadow: 2px 2px 5px red;font-family: "Brush Script MT", Times, serif;">Dont have id </span>	&#128546;  <span>No worries you can register &#128512; </span>

            <a href="register.html" class="btn btn-primary ml-2">Register </a> <!-- Link to Register Page -->






        </div>
    </div>
</nav>


      <!-- main content  -->


      <div class="container mt-4">
        <h1>Login</h1>
        <?php if (!empty($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $error; ?>
        </div>
        <?php endif; ?>

        <form action="" method="POST"> <!-- removed action attribute -->
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
    </div>

        <!-- Benzene code up till this  -->
    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
