<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>List of Cars for Buyers</title>
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
                    session_start();
                    if (isset($_SESSION['username'])) {
                        echo '<li class="nav-item">';
                        echo '<span class="nav-link" style="color: blue;">Welcome, (' . $_SESSION['username'] . ')</span>';
                        echo '</li>';
                    }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link active" href="buyer.php">Listings</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link" href="sell.html">Sell Your Vehicle</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.html">Contact Us</a> -->
                    </li>
                    <?php
                    if (isset($_SESSION['username'])) {
                        echo '<li class="nav-item">';
                        echo '<a href="index.php" class="btn btn-primary ml-2">Logout</a>';
                        echo '<a href="profile.php" class="btn btn-primary ml-2">Profile </a>';
                        echo '</li>';
                    } else {
                        echo '<li class="nav-item">';
                        echo '<a class="nav-link" href="index.php">Login</a>';
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4 card-container">
        <h1 class="mb-4">List of Cars for Buyers</h1>
        <div class="row mb-4">
            <div class="col">
                <form method="GET">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search  cars by a key word ..."
                            name="search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

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

            //dafault vali  Query to fetch all car data
            $sql = "SELECT c.City,c.CarID, c.Make, c.Model, c.Year, c.Mileage, c.Price, c.Description, c.SellerID, c.BodyType, c.Photo, c.Variant, u.FirstName,u.LastName,u.Username, u.Phone, u.Email FROM cars c JOIN seller s ON c.SellerID = s.SellerID JOIN users u ON s.UserID = u.UserID";


            // Check if search query is present
            if (isset($_GET['search']) && !empty($_GET['search'])) {
                $search = $_GET['search'];
                // Add search conditions to SQL query
                $sql .= " WHERE c.Make LIKE '%$search%' OR c.Model LIKE '%$search%' OR c.Variant LIKE '%$search%' OR c.Year LIKE '%$search%' OR c.Mileage LIKE '%$search%' OR c.Price LIKE '%$search%' OR c.BodyType LIKE '%$search%' OR c.City LIKE '%search%'";
            }


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
                    echo '<a href="#" class="btn btn-primary view-details-btn" data-toggle="modal" data-target="#carDetailsModal' . $row["CarID"] . '">Details</a>';
                    echo '<a href="#" class="btn btn-secondary ml-2 contact-details-btn" data-toggle="modal" data-target="#contactDetailsModal' . $row["CarID"] . '">Contact</a>';
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
                    echo '<p><strong>Discription:</strong> ' . $row["Description"] . '</p>';
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

                    // Modal for contact details
                    echo '<div class="modal fade" id="contactDetailsModal' . $row["CarID"] . '" tabindex="-1" role="dialog" aria-labelledby="contactDetailsModalLabel' . $row["CarID"] . '" aria-hidden="true">';
                    echo '<div class="modal-dialog" role="document">';
                    echo '<div class="modal-content">';
                    echo '<div class="modal-header">';
                    echo '<h5 class="modal-title" id="contactDetailsModalLabel' . $row["CarID"] . '">Contact Details</h5>';
                    echo '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
                    echo '<span aria-hidden="true">&times;</span>';
                    echo '</button>';
                    echo '</div>';
                    echo '<div class="modal-body">';
                    echo '<p><strong>Seller Name:</strong> ' . $row["FirstName"] . $row["LastName"] . '</p>';
                    echo '<p><strong>Email:</strong> ' . $row["Email"] . '</p>';
                    echo '<p><strong>Phone:</strong> ' . $row["Phone"] . '</p>';
                    // Additional contact details can be added here
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
</body>

</html>