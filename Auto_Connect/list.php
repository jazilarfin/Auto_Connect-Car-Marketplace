<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Cars</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #343a40;
        }

        .card-container {
            max-width: 1200px;
            margin: 0 auto;
        }

        .card {
            border: none;
            transition: transform 0.3s;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .card-img-top {
            max-height: 200px;
            object-fit: contain;
            width: 100%;
        }

        .card-title {
            font-size: 1.25rem;
        }

        .card-text {
            font-size: 0.875rem;
        }

        .hidden {
            display: none;
        }

        /* CSS for smooth transition */
        .fade-out {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
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
                <ul class="navbar-nav ml-auto">


                    <?php
                    // Start PHP session
                    // Check if a session is not already active
                    if (session_status() == PHP_SESSION_NONE) {
                        session_start();
                    }


                    // Check if user is logged in
                    if (isset($_SESSION['username'])) {
                        // Display username
                        echo '<li class="nav-item">';
                        echo '<span class="nav-link" style="color: blue;">Welcome, (' . $_SESSION['username'] . ')</span>';


                        echo '</li>';
                    } else {
                        // If user is not logged in, display login link
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="index.php">Login</a>';
                        echo '</li>';
                    }
                    ?>




                    <li class="nav-item">
                        <a class="nav-link active" href="list.php">Listings</a>
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



                </ul>
                <a href="index.php" class="btn btn-primary ml-2">Logout </a> <!-- Link to Register Page -->
                <a href="profile.php" class="btn btn-primary ml-2">Profile </a> <!-- Link to Register Page -->
            </div>
        </div>
    </nav>








    <!-- Main Content -->

    <div class="container mt-4 card-container">
        <h1 class="mb-4">List of Cars</h1>

        <div class="row">
            <?php
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
                $sellerId = $row['SellerID'];
            }



            // Query to fetch car data including photo paths
            $sql = "SELECT CarID, Make, Model, Year, Mileage, Price, `City`, Description, BodyType, Photo, Variant FROM cars WHERE SellerID = $sellerId";
            $result = $conn->query($sql);

            // Check if there are any cars
            if ($result->num_rows > 0) {
                // Output data of each car
                while ($row = $result->fetch_assoc()) {
                    echo '<div class="col-lg-3 col-md-4 col-sm-6 mb-4">';
                    echo '<div class="card">';
                    // Display image fetched from the database
                    echo '<img src="' . $row["Photo"] . '" class="card-img-top" alt="Car">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . $row["Make"] . ' ' . $row["Model"] . '</h5>';
                    echo '<p class="card-text">Year: ' . $row["Year"] . '<br>';
                    echo 'Mileage: ' . $row["Mileage"] . '<br>';
                    echo 'Price: ' . $row["Price"] . '</p>';
                    echo '<button class="btn btn-primary view-details-btn" data-toggle="modal" data-target="#carDetailsModal' . $row["CarID"] . '">View Details</button>';
                    //delete button
                    echo '<span class="ml-4">'; // Add spacing between the buttons
                    echo '<button class="btn btn-danger delete-btn" data-carid="' . $row["CarID"] . '">Delete</button>';
                    echo '</span>'; // Close the span tag
            
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    // Modal for car details
                    echo '<div class="modal fade" id="carDetailsModal' . $row["CarID"] . '" tabindex="-1" role="dialog" aria-labelledby="carDetailsModalLabel" aria-hidden="true">';
                    echo '<div class="modal-dialog" role="document">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h5 class="modal-title" id="carDetailsModalLabel">' . $row["Make"] . ' ' . $row["Model"] . ' Details</h5>';
                    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<p><strong>Year:</strong> ' . $row["Year"] . '</p>';
                    echo '<p><strong>Mileage:</strong> ' . $row["Mileage"] . '</p>';
                    echo '<p><strong>Price:</strong> ' . $row["Price"] . '</p>';
                    echo '<p><strong>City:</strong> ' . $row["City"] . '</p>';
                    echo '<p><strong>Description:</strong> ' . $row["Description"] . '</p>';
                    echo '<p><strong>Body Type:</strong> ' . $row["BodyType"] . '</p>';
                    echo '<p><strong>Variant:</strong> ' . $row["Variant"] . '</p>';
                    // Additional details can be added here
                    echo '</div>';
                    echo '<div class="modal-footer">';
                    echo '<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<div class="col">';
                echo '<p>No cars found</p>';
                echo '</div>';
            }
            $conn->close();
            ?>
        </div>
    </div>

    <!-- Bootstrap JS and jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.delete-btn').click(function () {
                // Get the car ID from the data attribute
                var carId = $(this).data('carid');

                // Prompt a confirmation dialog before deleting
                var confirmation = confirm("Are you sure you want to delete this car?");

                // If user confirms deletion
                if (confirmation) {
                    // Get the parent element (card) of the delete button
                    var card = $(this).closest('.card');

                    // Apply fade-out transition to the card
                    card.addClass('fade-out');

                    // Send an AJAX request to the server using fetch()
                    fetch('delete_car.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: 'carId=' + encodeURIComponent(carId),
                    })
                        .then(response => response.text())
                        .then(data => {
                            // Handle the response from the server
                            console.log(data); // Output the response to the console for checking
                            // Remove the card from the DOM after the transition
                            card.fadeOut(500, function () {
                                $(this).remove();
                            });
                        })
                        .catch(error => console.error('Error:', error));
                }
            });
        });
    </script>


</body>

</html>