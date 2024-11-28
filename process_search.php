<?php 
// Fetch genres from TMDb API
$api_key = "7d6d71ee4e29b78914b65797e1bcc63c"; // Replace with your TMDb API key
$genre_api_url = "https://api.themoviedb.org/3/genre/movie/list?api_key=$api_key";

// Fetch genre data
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $genre_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$genre_response = curl_exec($ch);
curl_close($ch);

// Decode genres
$genres = json_decode($genre_response, true);
$genre_list = [];
if (!empty($genres['genres'])) {
    foreach ($genres['genres'] as $genre) {
        $genre_list[$genre['id']] = $genre['name'];
    }
}

// Extract POST data
extract($_POST);

if (!empty($search)) {
    // Check if search matches a genre
    $genre_id = array_search(strtolower($search), array_map('strtolower', $genre_list));

    // Prepare API URL based on genre or search query
    if ($genre_id) {
        // Search movies by genre
        $api_url = "https://api.themoviedb.org/3/discover/movie?api_key=$api_key&with_genres=$genre_id";
    } else {
        // Default: Search movies by title
        $api_url = "https://api.themoviedb.org/3/search/movie?api_key=$api_key&query=" . urlencode($search);
    }

    // Fetch movie data
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response
    $movies = json_decode($response, true);
}
?>

<div class="content">
    <div class="wrap">
        <div class="content-top">
            <h3>Movies</h3>
            <?php
            // Display movies
            if (!empty($movies['results'])) {
                foreach ($movies['results'] as $movie) {
                    // Extract movie details
                    $movie_id = $movie['id'];
                    $movie_name = $movie['title'] ?? "Unknown Title";
                    $movie_image = !empty($movie['poster_path']) ? "https://image.tmdb.org/t/p/w500" . $movie['poster_path'] : "placeholder.jpg"; // Fallback for missing image
                    $movie_cast = "N/A"; // Cast data requires another API call

                    // Display each movie
                    ?>
                    <div class="col_1_of_4 span_1_of_4">
                        <div class="imageRow">
                            <div class="single">
                                <a href="about.php?id=<?php echo $movie_id; ?>" rel="lightbox">
                                    <img src="<?php echo $movie_image; ?>" alt="<?php echo $movie_name; ?>" />
                                </a>
                            </div>
                            <div class="movie-text">
                                <h4 class="h-text">
                                    <a href="about.php?id=<?php echo $movie_id; ?>">
                                        <?php echo $movie_name; ?>
                                    </a>
                                </h4>
                                Cast: <span class="color2"><?php echo $movie_cast; ?></span><br>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                // No movies found
                echo "<p>No movies found for your search query.</p>";
            }
            ?>
        </div>
        <div class="clear"></div>      
    </div>
</div>
