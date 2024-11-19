<link rel="stylesheet" href="about_style.css">
<?php
include('header.php');

$movie_id = $_GET['id'] ?? 0;
$tmdb_api_key = '7d6d71ee4e29b78914b65797e1bcc63c';
$tmdb_url = "https://api.themoviedb.org/3/movie/$movie_id?api_key=$tmdb_api_key&language=en-US&append_to_response=videos,credits";

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
$trailer = $movie['videos']['results'][0]['key'] ?? null;
$trailer_url = $trailer ? "https://www.youtube.com/watch?v=$trailer" : null;
?>

<link rel="stylesheet" href="movie_about_styles.css">

<div class="content">
    <div class="wrap">
        <div class="content-top">
            <div class="section group">
                <div class="about span_1_of_2">
                    <h3><?php echo htmlspecialchars($title); ?></h3>
                    <div class="about-top">
                        <div class="grid images_3_of_2">
                            <img src="<?php echo htmlspecialchars($poster_path); ?>" alt="Movie Poster"/>
                        </div>
                        <div class="desc span_3_of_2">
                            <p><strong>Release Date:</strong> <?php echo date('d-M-Y', strtotime($release_date)); ?></p>
                            <p><strong>Rating:</strong> <?php echo htmlspecialchars($rating); ?>/10</p>
                            <p><?php echo htmlspecialchars($overview); ?></p>

                            <?php if ($trailer_url): ?>
                                <a href="<?php echo $trailer_url; ?>" target="_blank" class="watch_but">Watch Trailer</a>
                            <?php endif; ?>

                            <div class="cast">
                                <h4>Cast</h4>
                                <ul>
                                    <?php foreach ($cast as $member): ?>
                                        <li><?php echo htmlspecialchars($member['name']); ?> as <?php echo htmlspecialchars($member['character']); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
                <?php include('movie_sidebar.php'); ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
