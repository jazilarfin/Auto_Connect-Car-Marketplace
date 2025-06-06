<?php
// Start session (if not already started)
session_start();

// Database connection
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


// Place this code after your database connection setup

// Place this at the beginning of the file after opening the PHP tag and setting up database connection

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_user') {
    $userId = $_POST['user_id'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $firstName = $_POST['firstname'];
    $lastName = $_POST['lastname'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Prepare the SQL statement to prevent SQL injection
    $sqlUpdate = "UPDATE Users SET Email = ?, Phone = ?, FirstName = ?, LastName = ?, Address = ?, Password = ? WHERE UserID = ?";
    $stmt = $conn->prepare($sqlUpdate);

    // Bind parameters
    $stmt->bind_param("ssssssi", $email, $phone, $firstName, $lastName, $address, $password, $userId);

    // Execute the statement
    if ($stmt->execute()) {
        echo "<p>User updated successfully.</p>";
    } else {
        echo "<p>Error updating user: " . $stmt->error . "</p>";
    }
    $stmt->close();
    exit; // Stop further execution to avoid loading the rest of the page
}

$username = $_SESSION['username'];
$sql = "SELECT UserID,Username,Password,Role,FirstName,LastName,Email,Phone,Address FROM users WHERE Username = '$username'";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            color: #333;
        }

        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .button {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }

        .button-red {
            background-color: red;
        }

        .navbar-brand {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Vehicle Sales & Purchase</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <a href="index.php" class="btn btn-primary ml-2">Logout </a>
                <!-- Link to Register Page -->

            </div>
        </div>
    </nav>




    <h1>Profile Management Panel</h1>

    <!-- User Management Section -->
    <h2>User Management</h2>
    <table>
        <thead>
            <tr>
                <th>User ID</th>
                <th>Username</th>
                <th>Password</th>
                <th>Role</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                // Output data of each row
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['UserID']}</td>
                    <td>{$row['Username']}</td>
                    
                    <td><input type='text' value='{$row['Password']}' class='form-control' id='password{$row['UserID']}' disabled></td>

                    <td>{$row['Role']}</td>
                    <td><input type='text' value='{$row['FirstName']}' class='form-control' id='firstname{$row['UserID']}' disabled></td>
                    <td><input type='text' value='{$row['LastName']}' class='form-control' id='lastname{$row['UserID']}' disabled></td>
                    <td><input type='email' value='{$row['Email']}' class='form-control' id='email{$row['UserID']}' disabled></td>
                    <td><input type='text' value='{$row['Phone']}' class='form-control' id='phone{$row['UserID']}' disabled></td>
                    <td><input type='text' value='{$row['Address']}' class='form-control' id='address{$row['UserID']}' disabled></td>
                    <td>
                        <button id='editButton{$row['UserID']}' class='button' onclick='enableEdit({$row['UserID']})'>Edit</button>
                        <button id='saveButton{$row['UserID']}' class='button button-red' onclick='submitUpdate({$row['UserID']})' style='display:none;'>Save</button>
                        
                    </td>
                </tr>";
                }
            } else {
                echo "<tr><td colspan='10'>No users found</td></tr>";
            }
            ?>
           <!-- <td><input type='text' value='{$row['Username']}' class='form-control' id='username{$row['UserID']}' disabled></td> -->


        </tbody>
    </table>
    <!-- <button class='button' onclick='updateUser({$row['UserID']})'>Update</button> -->

    

    <script>
        function enableEdit(userId) {
            //document.getElementById('username' + userId).disabled = false;
            document.getElementById('email' + userId).disabled = false;
            document.getElementById('phone' + userId).disabled = false;
            document.getElementById('firstname' + userId).disabled = false; // Enable first name field
            document.getElementById('lastname' + userId).disabled = false; // Enable last name field
            document.getElementById('address' + userId).disabled = false; // Enable address field
            document.getElementById('password' + userId).disabled = false; // Enable password field
            document.getElementById('editButton' + userId).style.display = 'none';
            document.getElementById('saveButton' + userId).style.display = 'inline-block';
        }
        function submitUpdate(userId) {
            var email = document.getElementById('email' + userId).value;
            var phone = document.getElementById('phone' + userId).value;
            var firstname = document.getElementById('firstname' + userId).value;
            var lastname = document.getElementById('lastname' + userId).value;
            var address = document.getElementById('address' + userId).value;
            var password = document.getElementById('password' + userId).value;

            fetch('admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update_user&user_id=' + userId + '&email=' + encodeURIComponent(email) + '&phone=' + encodeURIComponent(phone) + '&firstname=' + encodeURIComponent(firstname) + '&lastname=' + encodeURIComponent(lastname) + '&address=' + encodeURIComponent(address) + '&password=' + encodeURIComponent(password)
            }).then(response => response.text())
                .then(html => {
                    // handle response
                    alert("Update Successful");
                    location.reload(); // Reload the page to see the changes
                });
        }

    </script>

    



</body>

</html>