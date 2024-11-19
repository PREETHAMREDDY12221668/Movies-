<?php
include('header.php'); 
require_once('TMDbClient.php');

$apiKey = '7d6d71ee4e29b78914b65797e1bcc63c';
$tmdbClient = new TMDbClient($apiKey);

// Fetch popular, now-playing movies, and genres
$popularMovies = $tmdbClient->getPopularMovies();
$nowPlayingMovies = $tmdbClient->getNowPlayingMoviesInIndia();
$genres = $tmdbClient->getGenres();

$genreMapping = [];
foreach ($genres as $genre) {
    $genreMapping[$genre['id']] = $genre['name'];
}
?>

<link rel="stylesheet" href="movie_events_styles.css"> 

<div class="content">
    <div class="wrap">
        <!-- Popular Movies Section -->
        <div class="content-top">
            <h3>Recommended Movies</h3>
            <a href="all_movies.php" class="see-all-link">See All ›</a>
            <div class="movie-row">
                <?php
                if ($popularMovies && isset($popularMovies['results'])) {
                    foreach ($popularMovies['results'] as $movie) {
                        $movieGenres = array_map(function($genreId) use ($genreMapping) {
                            return $genreMapping[$genreId] ?? 'Unknown';
                        }, $movie['genre_ids']);
                        ?>
                        <div class="movie-card col_1_of_4">
                            <div class="movie-container">
                                <span class="promoted-badge">PROMOTED</span>
                                <div class="imageRow">
                                    <div class="single">
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?>" />
                                        </a>
                                    </div>
                                </div>
                                <div class="rating-container">
                                    <span class="star-icon">★</span> <?php echo $movie['vote_average']; ?>/10
                                    <span class="votes"><?php echo number_format($movie['vote_count']); ?> Votes</span>
                                </div>
                                <div class="movie-text">
                                    <h4>
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <?php echo $movie['title']; ?>
                                        </a>
                                    </h4>
                                    <div class="genre">
                                        <?php echo implode(', ', $movieGenres); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Unable to load popular movies.</p>";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>

        <!-- Now-Playing Movies Section -->
        <div class="content-top">
            <h3>Now Playing in India</h3>
            <a href="now_playing.php" class="see-all-link">See All ›</a>
            <div class="movie-row">
                <?php
                if ($nowPlayingMovies && isset($nowPlayingMovies['results'])) {
                    foreach ($nowPlayingMovies['results'] as $movie) {
                        $movieGenres = array_map(function($genreId) use ($genreMapping) {
                            return $genreMapping[$genreId] ?? 'Unknown';
                        }, $movie['genre_ids']);
                        ?>
                        <div class="movie-card col_1_of_4">
                            <div class="movie-container">
                                <div class="imageRow">
                                    <div class="single">
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?>" />
                                        </a>
                                    </div>
                                </div>
                                <div class="rating-container">
                                    <span class="star-icon">★</span> <?php echo $movie['vote_average']; ?>/10
                                    <span class="votes"><?php echo number_format($movie['vote_count']); ?> Votes</span>
                                </div>
                                <div class="movie-text">
                                    <h4>
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <?php echo $movie['title']; ?>
                                        </a>
                                    </h4>
                                    <div class="genre">
                                        <?php echo implode(', ', $movieGenres); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Unable to load now-playing movies.</p>";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
<?php
include('header.php');
require_once('TMDbClient.php');

$apiKey = '7d6d71ee4e29b78914b65797e1bcc63c';
$tmdbClient = new TMDbClient($apiKey);

// Fetch popular, now-playing movies, and genres
$popularMovies = $tmdbClient->getPopularMovies();
$nowPlayingMovies = $tmdbClient->getNowPlayingMoviesInIndia();
$genres = $tmdbClient->getGenres();

$genreMapping = [];
if ($genres) {
    foreach ($genres as $genre) {
        $genreMapping[$genre['id']] = $genre['name'];
    }
}
?>

<link rel="stylesheet" href="movie_events_styles.css"> 

<div class="content">
    <div class="wrap">
        <!-- Popular Movies Section -->
        <div class="content-top">
            <h3>Recommended Movies</h3>
            <a href="all_movies.php" class="see-all-link">See All ›</a>
            <div class="movie-row">
                <?php
                if (!empty($popularMovies['results'])) {
                    foreach ($popularMovies['results'] as $movie) {
                        $movieGenres = array_map(function($genreId) use ($genreMapping) {
                            return $genreMapping[$genreId] ?? 'Unknown';
                        }, $movie['genre_ids']);
                        ?>
                        <div class="movie-card col_1_of_4">
                            <div class="movie-container">
                                <span class="promoted-badge">PROMOTED</span>
                                <div class="imageRow">
                                    <div class="single">
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?>" />
                                        </a>
                                    </div>
                                </div>
                                <div class="rating-container">
                                    <span class="star-icon">★</span> <?php echo $movie['vote_average']; ?>/10
                                    <span class="votes"><?php echo number_format($movie['vote_count']); ?> Votes</span>
                                </div>
                                <div class="movie-text">
                                    <h4>
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <?php echo $movie['title']; ?>
                                        </a>
                                    </h4>
                                    <div class="genre">
                                        <?php echo implode(', ', $movieGenres); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Unable to load popular movies.</p>";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>

        <!-- Now-Playing Movies Section -->
        <div class="content-top">
            <h3>Now Playing in India</h3>
            <a href="now_playing.php" class="see-all-link">See All ›</a>
            <div class="movie-row">
                <?php
                if (!empty($nowPlayingMovies['results'])) {
                    foreach ($nowPlayingMovies['results'] as $movie) {
                        $movieGenres = array_map(function($genreId) use ($genreMapping) {
                            return $genreMapping[$genreId] ?? 'Unknown';
                        }, $movie['genre_ids']);
                        ?>
                        <div class="movie-card col_1_of_4">
                            <div class="movie-container">
                                <div class="imageRow">
                                    <div class="single">
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <img src="https://image.tmdb.org/t/p/w500<?php echo $movie['poster_path']; ?>" alt="<?php echo $movie['title']; ?>" />
                                        </a>
                                    </div>
                                </div>
                                <div class="rating-container">
                                    <span class="star-icon">★</span> <?php echo $movie['vote_average']; ?>/10
                                    <span class="votes"><?php echo number_format($movie['vote_count']); ?> Votes</span>
                                </div>
                                <div class="movie-text">
                                    <h4>
                                        <a href="about2.php?id=<?php echo $movie['id']; ?>">
                                            <?php echo $movie['title']; ?>
                                        </a>
                                    </h4>
                                    <div class="genre">
                                        <?php echo implode(', ', $movieGenres); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo "<p>Unable to load now-playing movies.</p>";
                }
                ?>
            </div>
            <div class="clear"></div>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>
