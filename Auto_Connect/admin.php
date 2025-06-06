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

//gpt
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

//gpt end

// Place this at the beginning of the file after opening the PHP tag and setting up database connection
// if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_car') {
//     $carId = $_POST['car_id'];
//     $make = $_POST['make'];
//     $model = $_POST['model'];
//     $price = $_POST['price'];
//     $seller = $_POST['seller'];
//     $city = $_POST['city'];

//     // Prepare the SQL statement to prevent SQL injection
//     $sqlUpdate = "UPDATE cars SET Make = ?, Model = ?, Price = ?, SellerID = ?, City = ? WHERE CarID = ?";
//     $stmt = $conn->prepare($sqlUpdate);
//     $stmt->bind_param("sssssi", $make, $model, $price, $seller, $city, $carId);
//     if ($stmt->execute()) {
//         echo "<p>Car updated successfully.</p>";
//     } else {
//         echo "<p>Error updating car: " . $stmt->error . "</p>";
//     }
//     $stmt->close();
//     exit; // Stop further execution to avoid loading the rest of the page
// }


// gpt
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'update_car') {
    $carId = $_POST['car_id'];
    $make = $_POST['make'];
    $model = $_POST['model'];
    $price = $_POST['price'];
    $city = $_POST['city'];

    // Prepare the SQL statement to prevent SQL injection
    $sqlUpdate = "UPDATE cars SET Make = ?, Model = ?, Price = ?, City = ? WHERE CarID = ?";
    $stmt = $conn->prepare($sqlUpdate);
    $stmt->bind_param("ssssi", $make, $model, $price, $city, $carId);
    if ($stmt->execute()) {
        echo "<p>Car updated successfully.</p>";
    } else {
        echo "<p>Error updating car: " . $stmt->error . "</p>";
    }
    $stmt->close();
    exit; // Stop further execution to avoid loading the rest of the page
}


//gpt ends





// Handling the POST request for deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user_id'])) {
    $deleteUserId = $_POST['delete_user_id'];
    $sqlDelete = "DELETE FROM Users WHERE UserID = ?"; // Ensure the table and column name are correct
    $stmt = $conn->prepare($sqlDelete);
    if ($stmt) {
        $stmt->bind_param("i", $deleteUserId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "<p>User deleted successfully.</p>"; // Feedback message
        } else {
            echo "<p>Error deleting user: user may not exist or query failed.</p>";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_car_id'])) {
    $deleteCarId = $_POST['delete_car_id'];
    $sqlDelete = "DELETE FROM cars WHERE CarID = ?"; // Ensure the table and column name are correct
    $stmt = $conn->prepare($sqlDelete);
    if ($stmt) {
        $stmt->bind_param("i", $deleteCarId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo "<p>Car deleted successfully.</p>"; // Feedback message
        } else {
            echo "<p>Error deleting Car: user may not exist or query failed.</p>";
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
}

// Query to fetch users
$sql = "SELECT UserID,Username,Password,Role,FirstName,LastName,Email,Phone,Address FROM users WHERE role !='admin'"; // Adjust the table name and column names based on your schema
$result = $conn->query($sql);

// Query to fetch listings
//$sqlListings = "SELECT CarID,Make,Model,Price,SellerID,CityID,Available  FROM cars"; // Adjust the table name and column names based on your schema
$sqlListings = "SELECT CarID,Make,Model,Price,SellerID,City FROM cars "; // Adjust the table name and column names based on your schema
$resultListings = $conn->query($sqlListings);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
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
                <a href="profile.php" class="btn btn-primary ml-2">Profile </a> <!-- Link to Register Page -->

            </div>
        </div>
    </nav>




    <h1>Admin Panel</h1>

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
            //<td><input type='password' class='form-control' id='password{$row['UserID']}' disabled></td>
            
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
                        <form method='post' action='' style='display:inline-block'>
                            <input type='hidden' name='delete_user_id' value='{$row['UserID']}'>
                            <button type='submit' class='button button-red'>Delete</button>
                        </form>
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

    <!-- Listing Management Section -->
    <h2>Car Management</h2>
    <table>
        <thead>
            <tr>
                <th>Car ID</th>
                <th>Make</th>
                <th>Model</th>
                <th>Price</th>
                <th>SellerID</th>
                <th>City Name</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($resultListings->num_rows > 0) {
                // Output data of each row
                while ($row = $resultListings->fetch_assoc()) {
                    echo "<tr>
                    <td>{$row['CarID']}</td>
                    <td><input type='text' value='{$row['Make']}' class='form-control' id='make{$row['CarID']}' disabled></td>
                    <td><input type='text' value='{$row['Model']}' class='form-control' id='model{$row['CarID']}' disabled></td>
                    <td><input type='text' value='{$row['Price']}' class='form-control' id='price{$row['CarID']}' disabled></td>
                    <td>{$row['SellerID']}</td>
                    <td><input type='text' value='{$row['City']}' class='form-control' id='city{$row['CarID']}' disabled></td>
                    
                    <td>
                        <button id='editButton{$row['CarID']}' class='button' onclick='enableEditCar({$row['CarID']})'>Edit</button>
                        <button id='saveButton{$row['CarID']}' class='button button-red' onclick='submitUpdateCar({$row['CarID']})' style='display:none;'>Save</button>
                        <form method='post' action='' style='display:inline-block'>
                            <input type='hidden' name='delete_car_id' value='{$row['CarID']}'>
                            <button type='submit' class='button button-red'>Delete</button>
                        </form>
                    </td>
                </tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No listings found</td></tr>";
            }
            // <td>{$row['Available']}</td>
            // <td><input type='text' value='{$row['SellerID']}' class='form-control' id='seller{$row['CarID']}' disabled></td>
            //
            ?>
        </tbody>


    </table>



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


    <!-- <script>
        function enableEditCar(carId) {
            document.getElementById('make' + carId).disabled = false;
            document.getElementById('model' + carId).disabled = false;
            document.getElementById('price' + carId).disabled = false;
            document.getElementById('seller' + carId).disabled = false;
            document.getElementById('city' + carId).disabled = false;
            document.getElementById('editButton' + carId).style.display = 'none';
            document.getElementById('saveButton' + carId).style.display = 'inline-block';
        }

        function submitUpdateCar(carId) {
            var make = document.getElementById('make' + carId).value;
            var model = document.getElementById('model' + carId).value;
            var price = document.getElementById('price' + carId).value;
            var seller = document.getElementById('seller' + carId).value;
            var city = document.getElementById('city' + carId).value;

            fetch('admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update_car&car_id=' + carId + '&make=' + encodeURIComponent(make) + '&model=' + encodeURIComponent(model) + '&price=' + encodeURIComponent(price) + '&seller=' + encodeURIComponent(seller) + '&city=' + encodeURIComponent(city)
            }).then(response => response.text())
                .then(html => {
                    // handle response
                    alert("Update Successful");
                    location.reload(); // Reload the page to see the changes
                });
        }
    </script> -->
    <!-- gpt -->

    <script>
        function enableEditCar(carId) {
            document.getElementById('make' + carId).disabled = false;
            document.getElementById('model' + carId).disabled = false;
            document.getElementById('price' + carId).disabled = false;
            document.getElementById('city' + carId).disabled = false;
            document.getElementById('editButton' + carId).style.display = 'none';
            document.getElementById('saveButton' + carId).style.display = 'inline-block';
        }

        function submitUpdateCar(carId) {
            var make = document.getElementById('make' + carId).value;
            var model = document.getElementById('model' + carId).value;
            var price = document.getElementById('price' + carId).value;
            var city = document.getElementById('city' + carId).value;

            fetch('admin.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'action=update_car&car_id=' + carId + '&make=' + encodeURIComponent(make) + '&model=' + encodeURIComponent(model) + '&price=' + encodeURIComponent(price) + '&city=' + encodeURIComponent(city)
            }).then(response => response.text())
                .then(html => {
                    // handle response
                    alert("Update Successful");
                    location.reload(); // Reload the page to see the changes
                });
        }
    </script>



    <!-- gpt end -->
</body>

</html>