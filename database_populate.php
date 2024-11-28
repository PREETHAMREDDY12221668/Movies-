<?php
include('config.php');
$api_key = '7d6d71ee4e29b78914b65797e1bcc63c';
$api_url = "https://api.themoviedb.org/3/movie/popular?api_key=$api_key";

// Fetch movie data from TMDB API
$response = file_get_contents($api_url);

if ($response === FALSE) {
    die("Error fetching data from TMDB API.");
}

$movies = json_decode($response, true);

// Insert movie data into the database
if (isset($movies['results'])) {
    foreach ($movies['results'] as $movie) {
        // Extract required fields
        $movie_id = $movie['id'];
        $name = mysqli_real_escape_string($con, $movie['title']); // Sanitize input
        $description = mysqli_real_escape_string($con, $movie['overview']); // Sanitize input
        $release_date = $movie['release_date'];
        $image_url = "https://image.tmdb.org/t/p/w500" . $movie['poster_path'];
        $trailer_url = "https://www.youtube.com/watch?v=" . $movie['id']; // Placeholder, you can use a separate API to fetch the real trailer
       
        // Fetch cast details
        $cast_url = "https://api.themoviedb.org/3/movie/$movie_id/casts?api_key=$api_key";
        $cast_response = file_get_contents($cast_url);
        $cast_data = json_decode($cast_response, true);
        $cast = '';
        if (isset($cast_data['cast'])) {
            foreach ($cast_data['cast'] as $actor) {
                $cast .= mysqli_real_escape_string($con, $actor['name']) . ', ';
            }
            $cast = rtrim($cast, ', '); // Remove trailing comma
        }

        // Fetch crew details
        $crew_url = "https://api.themoviedb.org/3/movie/$movie_id/credits?api_key=$api_key";
        $crew_response = file_get_contents($crew_url);
        $crew_data = json_decode($crew_response, true);
        $crew = '';
        if (isset($crew_data['crew'])) {
            foreach ($crew_data['crew'] as $member) {
                if ($member['job'] == 'Director' || $member['job'] == 'Producer') {
                    $crew .= mysqli_real_escape_string($con, $member['name']) . ' (' . $member['job'] . '), ';
                }
            }
            $crew = rtrim($crew, ', '); // Remove trailing comma
        }

        // SQL query to insert data into the Movies table
        $query = "INSERT INTO Movies (movie_id, name, description, release_date, image_url, trailer_url, cast, crew)
                  VALUES ('$movie_id', '$name', '$description', '$release_date', '$image_url', '$trailer_url', '$cast', '$crew')";

        if (mysqli_query($con, $query)) {
            echo "Movie '$name' added successfully.<br>";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($con) . "<br>";
        }
    }
} else {
    echo "No movies found in the API response.";
}

?>
