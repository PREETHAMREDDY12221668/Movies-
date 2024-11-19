<?php
include('header.php'); // Ensure header.php includes necessary HTML head elements and links to jQuery if needed
require_once 'TMDbClient.php';

$tmdb = new TMDbClient('7d6d71ee4e29b78914b65797e1bcc63c'); // Replace with your actual TMDb API key

// Fetch "Now Playing" movies in India
$moviesResponse = $tmdb->getNowPlayingMoviesInIndia(); // Use the proper method

// Check if movies were fetched successfully
if ($moviesResponse && !empty($moviesResponse['results'])) {
    $movies = $moviesResponse['results'];
} else {
    $movies = []; // Empty array if no movies fetched
}

// Fetch genre list and create a mapping
$genresResponse = $tmdb->request('genre/movie/list', []); // Adjust parameters if needed
$genresList = $genresResponse['genres'] ?? [];
$genreMap = [];
foreach ($genresList as $genre) {
    $genreMap[$genre['id']] = $genre['name'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie +</title>
    <link rel="stylesheet" href="mainstyles.css"> <!-- Ensure this is the path to your CSS file -->
    <style>
        /* Include the revised CSS here or keep it in mainstyles.css */
    </style>
</head>
<body>

<div class="background-gradient">
    <div class="slider">
        <?php if (!empty($movies)): ?>
            <?php foreach ($movies as $movie): ?>
                <?php
                    $movieDetails = $tmdb->getMovieDetails($movie['id']); // Fetch detailed info
                    if ($movieDetails) {
                        $movie = array_merge($movie, $movieDetails);
                    }
                    // Map genre IDs to names
                    $movieGenres = [];
                    if (isset($movie['genre_ids']) && is_array($movie['genre_ids'])) {
                        foreach ($movie['genre_ids'] as $genreId) {
                            $movieGenres[] = htmlspecialchars($genreMap[$genreId] ?? 'Unknown');
                        }
                    }
                ?>
                <section class="slide movie-section">
                    <?php
                        $backdropPath = $movie['backdrop_path'] ?? 'joker.jpg';
                    ?>
                    <div class="movie-image" style="background-image: url('https://image.tmdb.org/t/p/original<?= htmlspecialchars($backdropPath) ?>');">
                        <div class="gradient-overlay"></div>
                        <div class="movie-info">
                            <div class="tags">
                                <?php foreach ($movieGenres as $genre): ?>
                                    <span class="tag"><?= $genre ?></span>
                                <?php endforeach; ?>
                                <span class="rating"><?= htmlspecialchars($movie['vote_average'] ?? 'N/A') ?>/10</span>
                            </div>
                            <h1 class="movie-title"><?= htmlspecialchars($movie['title'] ?? 'No Title') ?></h1>
                            <p class="movie-description"><?= htmlspecialchars($movie['overview'] ?? 'No Description Available.') ?></p>
                            <p class="movie-duration"><?= htmlspecialchars($movie['runtime'] ?? 'N/A') ?> min</p>
                            <a href="#" class="btn-buy-tickets">Buy Tickets</a>
                        </div>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <section class="slide movie-section">
                <div class="movie-image" style="background-image: url('joker.jpg');">
                    <div class="gradient-overlay"></div>
                    <div class="movie-info">
                        <div class="tags">
                            <span class="tag">Drama</span>
                            <span class="rating">4.5/10</span>
                        </div>
                        <h1 class="movie-title">Joker</h1>
                        <p class="movie-description">A beggar's life takes an unexpected turn when a misadventure upends his daily routine...</p>
                        <p class="movie-duration">122 min</p>
                        <a href="#" class="btn-buy-tickets">Buy Tickets</a>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </div>

    <!-- Navigation buttons -->
    <div class="navigation">
        <button onclick="moveSlide(-1)">&#10094;</button>
        <button onclick="moveSlide(1)">&#10095;</button>
    </div>
</div>


<?php include('footer.php'); ?>
<?php include('searchbar.php'); ?>

<script>
    // JavaScript for slider functionality with Autoplay
    document.addEventListener('DOMContentLoaded', function() {
        let currentIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;
        const intervalTime = 5000; // 5 seconds
        let slideInterval;

        window.moveSlide = function(direction) {
            currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
            showSlide(currentIndex);
            resetInterval();
        };

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.transform = `translateX(${(i - index) * 100}%)`;
            });
        }

        function startAutoplay() {
            slideInterval = setInterval(() => {
                moveSlide(2);
            }, intervalTime);
        }

        function resetInterval() {
            clearInterval(slideInterval);
            startAutoplay();
        }

        // Lazy load images
        function lazyLoadImages() {
            slides.forEach(slide => {
                const movieImage = slide.querySelector('.movie-image');
                const backgroundImage = movieImage.style.backgroundImage;

                // Check if the slide is in view
                const observer = new IntersectionObserver(entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            // Load the actual image
                            movieImage.style.backgroundImage = backgroundImage;
                            observer.unobserve(slide); // Stop observing after loading
                        }
                    });
                });

                observer.observe(slide);
            });
        }

        // Initialize the slider
        showSlide(currentIndex);
        startAutoplay();
        lazyLoadImages(); // Call lazy loading

        // Pause on hover
        document.querySelector('.slider').addEventListener('mouseenter', () => {
            clearInterval(slideInterval);
        });

        document.querySelector('.slider').addEventListener('mouseleave', () => {
            startAutoplay();
        });
    });
</script>

</body>
</html>
