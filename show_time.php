<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Listings</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #F2F2F2;
        }

        .container {
            width: 100%;
            
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Movie Title Styling */
        .movie-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        /* Date Section Styling */
        .date-section {
            font-size: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }

        .date {
            padding: 5px 10px;
            background-color: #F84464;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: #FFFEFE;
            max-width: 50px;
            text-align: center;
            height: 60px;
        }

        .date:hover {
            background-color: #e0e0e0;
        }

        /* Theater Section Styling */
        .theater-section {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            max-width: 1000px;
            margin-left: 200px;
        }

        .theater-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .theater-header h2 {
            font-size: 18px;
            color: #333;
            margin: 0;
        }

        .screen-name {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        .theater-amenities {
            display: flex;
            gap: 10px;
            font-size: 12px;
            color: #777;
        }

        .theater-amenities span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Showtimes Section */
        .showtimes {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .showtime {
            background-color: #e6f7e6;
            color: #009900;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #b3e6b3;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .showtime:hover {
            background-color: #c3e6c3;
        }

        /* Cancellation Notice */
        .cancellation {
            font-size: 12px;
            color: #f90;
            margin-top: 5px;
        }

        /* Divider */
        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }

    </style>
</head>
<body>

<div class="container">
    <?php
    include('header.php');
    // Database connection parameters
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "db_movie";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch movie details along with screen and booking information
    $sql = "
        SELECT 
            tbl_movie.movie_name,
            tbl_screens.screen_name,
            tbl_screens.seats AS screen_seats,
            tbl_screens.charge AS ticket_price,
            tbl_bookings.no_seats AS booked_seats,
            tbl_bookings.date AS booking_date,
            tbl_movie.image,
            tbl_movie.release_date
        FROM tbl_movie
        LEFT JOIN tbl_screens ON tbl_movie.t_id = tbl_screens.t_id
        LEFT JOIN tbl_bookings ON tbl_screens.screen_id = tbl_bookings.screen_id
        WHERE tbl_movie.status = 0
        ORDER BY tbl_movie.release_date DESC
    ";

    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
        $currentMovie = null;

        // Output movie and related information
        while ($row = $result->fetch_assoc()) {
            if ($currentMovie !== $row["movie_name"]) {
                // Display new movie title and dates
                $currentMovie = $row["movie_name"];
                echo "<div class='movie-title'>" . htmlspecialchars($currentMovie) . "</div>";

                // Example of date section
               
                // Generate real-time date section with the next 6 days
                echo "<div class='date-section'>";
                for ($i = 0; $i < 6; $i++) {
                    $date = new DateTime();
                    $date->modify("+$i day");
                    echo "<div class='date'>" . $date->format("D d M") . "</div>";
                }
                echo "</div>";
               
                
            }

            // Theater section
            echo "<div class='theater-section'>";
            
            // Theater Header
            echo "<div class='theater-header'>";
            echo "<h2>" . htmlspecialchars($row["screen_name"]) . "</h2>";
            echo "<div class='theater-amenities'>";
            echo "<span>📱 M-Ticket</span>";
            echo "<span>🍿 Food & Beverage</span>";
            echo "</div>";
            echo "</div>";

            // Screen information
            echo "<div class='screen-name'>Screen: " . htmlspecialchars($row["screen_name"]) . "</div>";
            
            // Showtimes
            echo "<div class='showtimes'>";
            echo "<div class='showtime'>11:10 AM</div>";
            echo "<div class='showtime'>04:25 PM</div>";
            echo "<div class='showtime'>10:20 PM</div>";
            echo "</div>";
            
            // Cancellation Notice
            echo "<div class='cancellation'>Cancellation Available</div>";

            echo "</div><hr>";
        }
    } else {
        echo "<p>No theaters found.</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Listings</title>
    <style>
        /* General Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #F2F2F2;
        }

        .container {
            width: 100%;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Movie Title Styling */
        .movie-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }

        /* Date Section Styling */
        .date-section {
            font-size: 12px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
        }

        .date {
            padding: 5px 10px;
            background-color: #F84464;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            color: #FFFEFE;
            max-width: 50px;
            text-align: center;
            height: 60px;
        }

        .date:hover {
            background-color: #e0e0e0;
        }

        /* Theater Section Styling */
        .theater-section {
            border-bottom: 1px solid #ddd;
            padding: 20px 0;
            max-width: 1000px;
            margin-left: 200px;
        }

        .theater-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .theater-header h2 {
            font-size: 18px;
            color: #333;
            margin: 0;
        }

        .screen-name {
            font-size: 14px;
            color: #555;
            margin-top: 5px;
        }

        .theater-amenities {
            display: flex;
            gap: 10px;
            font-size: 12px;
            color: #777;
        }

        .theater-amenities span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Showtimes Section */
        .showtimes {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 10px;
        }

        .showtime {
            background-color: #e6f7e6;
            color: #009900;
            padding: 8px 12px;
            border-radius: 5px;
            font-size: 14px;
            text-align: center;
            border: 1px solid #b3e6b3;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .showtime:hover {
            background-color: #c3e6c3;
        }

        /* Cancellation Notice */
        .cancellation {
            font-size: 12px;
            color: #f90;
            margin-top: 5px;
        }

        /* Divider */
        hr {
            border: none;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }

    </style>
</head>
<body>

<div class="container">
    <?php
    include('header.php');
    
    // Movie ID from the URL
    $movie_id = $_GET['id'];

    // TMDB API URL to fetch movie details
    $tmdb_api_key = '7d6d71ee4e29b78914b65797e1bcc63c'; // Replace with your TMDB API Key
    $movie_url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$tmdb_api_key&language=en-US";

    // Get movie details from TMDB API
    $movie_data = file_get_contents($movie_url);
    $movie_details = json_decode($movie_data, true);

    if ($movie_details && isset($movie_details['title'])) {
        $movie_name = $movie_details['title'];
        $movie_image = 'https://image.tmdb.org/t/p/w500' . $movie_details['poster_path']; // Poster image URL
        echo "<div class='movie-title'>" . htmlspecialchars($movie_name) . "</div>";
        echo "<img src='" . $movie_image . "' alt='Movie Poster' style='width: 200px; margin-bottom: 20px;'>";

        // Example of date section for the next 6 days
        echo "<div class='date-section'>";
        for ($i = 0; $i < 6; $i++) {
            $date = new DateTime();
            $date->modify("+$i day");
            echo "<div class='date'>" . $date->format("D d M") . "</div>";
        }
        echo "</div>";
    }

    // Database connection parameters
    $servername = "127.0.0.1";
    $username = "root";
    $password = "";
    $dbname = "db_movie";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch theater details along with showtimes for the movie
    $sql = "
    SELECT 
        tbl_screens.screen_name,
        tbl_screens.seats AS screen_seats,
        tbl_screens.charge AS ticket_price,
        tbl_bookings.no_seats AS booked_seats,
        tbl_bookings.date AS booking_date,
        tbl_shows.start_date
    FROM tbl_screens
    LEFT JOIN tbl_bookings ON tbl_screens.screen_id = tbl_bookings.screen_id
    LEFT JOIN tbl_shows ON tbl_screens.screen_id = tbl_shows.s_id
   
    ORDER BY tbl_screens.screen_id
    ";

    $result = $conn->query($sql);
  
    if ($result->num_rows > 0) {
        // Output theater and related information with showtimes
        while ($row = $result->fetch_assoc()) {
            // Theater section
            echo "<div class='theater-section'>";
            
            // Theater Header
            echo "<div class='theater-header'>";
            echo "<h2>" . htmlspecialchars($row["screen_name"]) . "</h2>";
            echo "<div class='theater-amenities'>";
            echo "<span>📱 M-Ticket</span>";
            echo "<span>🍿 Food & Beverage</span>";
            echo "</div>";
            echo "</div>";

            // Screen information
            echo "<div class='screen-name'>Screen: " . htmlspecialchars($row["screen_name"]) . "</div>";
            
            // Showtimes (fetched from tbl_shows)
            echo "<div class='showtimes'>";
            $showtimes = explode(',', $row["showtime"]); // Assuming showtimes are stored as a comma-separated string
            foreach ($showtimes as $time) {
                echo "<div class='showtime'>" . htmlspecialchars($time) . "</div>";
            }
            echo "</div>";
            
            // Cancellation Notice
            echo "<div class='cancellation'>Cancellation Available</div>";

            echo "</div><hr>";
        }
    } else {
        echo "<p>No theaters found.</p>";
    }

    // Close the connection
    $conn->close();
    ?>
</div>

</body>
</html>