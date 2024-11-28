<link rel="stylesheet" href="css/css/movie_about_styles.css">
<?php
include('header.php');

$movie_id = $_GET['id'] ?? 0;
$movie_name=$_GET['title'] ?? 'venom';
$tmdb_api_key = '7d6d71ee4e29b78914b65797e1bcc63c';
$tmdb_url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$tmdb_api_key&language=en-US&append_to_response=videos,credits,production_companies";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $tmdb_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$tmdb_response = curl_exec($ch);
curl_close($ch);

$movie = json_decode($tmdb_response, true);

// Extract data
$overview = $movie['overview'] ?? 'No overview available';
$poster_path = "https://image.tmdb.org/t/p/w500" . ($movie['poster_path'] ?? '');
$release_date = $movie['release_date'] ?? 'Unknown';
$title = $movie['title'] ?? 'Unknown';
$rating = $movie['vote_average'] ?? 'N/A';
$cast = array_slice($movie['credits']['cast'], 0, 5); // Limit cast to top 5
$trailer_key = $movie['videos']['results'][0]['key'] ?? null; // Get trailer key from TMDB API
$trailer_url = $trailer_key ? "https://www.youtube.com/embed/$trailer_key?autoplay=1" : null;
$backdropPath = $movie['backdrop_path'] ?? '';
$genres = $movie['genres'] ?? []; // Correct key is 'genres', not 'geners'|
$original_languages = $movie['spoken_languages'] ?? [];
$release_date = $movie['release_date'] ?? 'Unknown';
$runtime = $movie['runtime'] ?? 'Unknown';  // Duration in minutes
$adult = $movie['adult'] ?? false; // Check for adult rating

// Extract genre names into a list
$genreNames = [];
foreach ($genres as $genre) {
    $genreNames[] = $genre['name'];
}
$languageNames = [];
foreach ($original_languages as $language) {
    $languageNames[] = $language['name'];
}
$languageList = implode(', ', $languageNames);

// Format adult rating (true/false)
$adultRating = $adult ? 'Rated R' : 'Not Rated';

// Format the release date
$formattedReleaseDate = $release_date != 'Unknown' ? date('F j, Y', strtotime($release_date)) : 'Unknown';

// Format runtime (convert minutes to hours and minutes)
$formattedRuntime = $runtime != 'Unknown' ? floor($runtime / 60) . 'h ' . ($runtime % 60) . 'm' : 'Unknown';




// Get crew details (director, producer, etc.)
$crew = $movie['credits']['crew'] ?? [];
$director = '';
$producer = '';
foreach ($crew as $member) {
    if ($member['job'] == 'Director') {
        $director = $member;
    }
    if ($member['job'] == 'Producer') {
        $producer = $member;
    }
}
?>

<link rel="stylesheet" href="css/css/movie_about_styles.css">

<div class="container">
    <div class="top movie-image" style="background-image: url('https://image.tmdb.org/t/p/original<?= htmlspecialchars($backdropPath) ?>');">
        <div class="left-top">
            <img src="<?php echo htmlspecialchars($poster_path); ?>" alt="Movie Poster" class="image_small"/>
            <?php if ($trailer_url): ?>
                <!-- <p>Trailer URL: <?php echo htmlspecialchars($trailer_url); ?></p> -->
                <div class="play-button" onclick="openModal();">
                    <span>▶</span>
                </div>
            <?php else: ?>
                <p>No trailer available</p>
            <?php endif; ?>

        </div>
        <div class="right-top">
            <h3 class="title"><?= htmlspecialchars($title); ?></h3>
            <div class="interest">
                <h3><span class="star-icon" style="color:red ">★</span> <?php echo $movie['vote_average']; ?>/10 </h3>
                <h3 class="sector">( <?php echo number_format($movie['vote_count']); ?> are interested )</h3>
                <!-- <h3><span class="star-icon red">★</span> <?php echo $movie['vote_average']; ?>/10 <br><?php echo number_format($movie['vote_count']); ?> are interested <br> Are you interested in watching this movie?</h3> -->
                <button>I'm intrested</button>
            </div>
            <div>
                <p class="cat">2D,IMAX 2D</p>
                <p class="cat"><?php echo htmlspecialchars($languageList); ?></p>
            </div>
            <div>
                <p class="categories"> <?php echo htmlspecialchars($formattedReleaseDate); ?></p>
                <p class="categories-1"> <?php echo htmlspecialchars($formattedRuntime); ?></p>

                <p class="categories"><?php echo implode(', ', $genreNames); ?></p> 
                <p class="categories-2">U/A</p>
                
            </div>
        </div>
    </div>

    <!-- <div class="button-container">
        <?php if ($trailer_url): ?>                            
            <a href="<?php echo $trailer_url; ?>" target="_blank" class="watch_but">Watch Trailer</a>
        <?php endif; ?>
    </div> -->

    
    
    <!-- Movie Description Section -->
    <div class="description">
        <h3 class="title">About The Movie</h3>
        <p><?php echo htmlspecialchars($overview); ?></p>
        <p><strong>Original Language:</strong> <?php echo htmlspecialchars($languageList); ?></p>
        <p><strong>Release Date:</strong> <?php echo htmlspecialchars($formattedReleaseDate); ?></p>
        <p><strong>Duration:</strong> <?php echo htmlspecialchars($formattedRuntime); ?></p>
        <p><strong>Adult Rating:</strong> <?php echo htmlspecialchars($adultRating); ?></p>
    </div>

    <!-- Cast Section -->
    <div class="right">
        <h4>Cast</h4>
        <ul>
            <?php foreach ($cast as $member): ?>
                <li>
                    <?php 
                        // Use fallback image if actor image is not available
                        $actor_image = $member['profile_path'] ? "https://image.tmdb.org/t/p/w500" . $member['profile_path'] : 'avatar.svg';
                    ?>
                    <img src="<?php echo htmlspecialchars($actor_image); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="actor-photo" />
                    <?php echo htmlspecialchars($member['name']); ?> as <?php echo htmlspecialchars($member['character']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Crew Section (Director and Producer) -->
    <div class="crew">
        <h4>Crew</h4>
        <ul>
            <?php if ($director): ?>
                <li>
                    <h5>Director:</h5>
                    <img src="<?php echo htmlspecialchars($director['profile_path'] ? 'https://image.tmdb.org/t/p/w500' . $director['profile_path'] : 'avatar.svg'); ?>" alt="Director" class="crew-photo"/>
                    <span><?php echo htmlspecialchars($director['name']); ?></span>
                </li>
            <?php endif; ?>
            <?php if ($producer): ?>
                <li>
                    <h5>Producer:</h5>
                    <img src="<?php echo htmlspecialchars($producer['profile_path'] ? 'https://image.tmdb.org/t/p/w500' . $producer['profile_path'] : 'avatar.svg'); ?>" alt="Producer" class="crew-photo"/>
                    <span><?php echo htmlspecialchars($producer['name']); ?></span>
                </li>
            <?php endif; ?>
        </ul>
        
        <!-- Form for booking -->
        <form action="update_session_data.php" method="POST">
            <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>"/>
            <input type="hidden" nmae="movie_name" value="<?php echo htmlspecialchars($movie_name); ?>" />
            <input type="hidden" name="show_id" value="<?php echo htmlspecialchars($_GET['show_id'] ?? ''); ?>"/>
            <input type="hidden" name="theater_id" value="<?php echo htmlspecialchars($_GET['theater_id'] ?? ''); ?>"/>
            <input type="hidden" name="screen_id" value="<?php echo htmlspecialchars($_GET['screen_id'] ?? ''); ?>"/>
            <button type="submit" class="booking-button">Book Tickets</button>  
        </form>
    </div>
</div>

<!-- Modal to display trailer -->
<div id="videoModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal();">&times;</span>
        <iframe id="videoPlayer" width="100%" height="400" frameborder="0" allowfullscreen></iframe>
    </div>
</div>

<?php include('footer.php'); ?>

<script>
    // Open the modal with the YouTube trailer
    function openModal() {
        var trailerUrl = "<?php echo $trailer_url; ?>";
        var videoId = trailerUrl.split('v=')[1];
        var iframeSrc = "https://www.youtube.com/embed/" + videoId + "?autoplay=1";
        document.getElementById('videoPlayer').src = iframeSrc;
        document.getElementById('videoModal').style.display = "block";
    }

    // Close the modal
    function closeModal() {
        document.getElementById('videoPlayer').src = ""; // Stop video
        document.getElementById('videoModal').style.display = "none";
    }
</script>
