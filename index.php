<?php include('header.php'); ?>

<?php
require_once 'config.php'; // Include your database connection script


// Fetch movie advertisements from the database
$query = "SELECT * FROM movie_ads"; // Adjust table/column names as needed
$result = $con->query($query);

// Store the data in an array
$ads = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $ads[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie +</title>
    <link rel="stylesheet" href="mainstyles.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="background-gradient">
    <div class="slider">
        <?php if (!empty($ads)): ?>
            <?php foreach ($ads as $ad): ?>
                <div class="slide movie-section">
                    <div class="movie-image" style="background-image:linear-gradient(90deg, rgb(26, 26, 26) 24.97%, rgb(26, 26, 26) 38.3%, rgba(26, 26, 26, 0.04) 97.47%, rgb(26, 26, 26) 100%), url('<?= htmlspecialchars($ad['image_path']) ?>'); background-reapet:no-reapet">
                        <div class="gradient-overlay"></div>
                        <div class="movie-info">
                            <div class="tags">
                                <span class="tag"><?= htmlspecialchars($ad['genre']) ?></span>
                                <span class="rating"><?= htmlspecialchars($ad['rating']) ?>/10</span>
                            </div>
                            <h1 class="movie-title"><?= htmlspecialchars($ad['title']) ?></h1>
                            <p class="movie-description"><?= htmlspecialchars($ad['description']) ?></p>
                            <p class="movie-duration"><?= htmlspecialchars($ad['duration']) ?> min</p>
                            <a href="#" class="btn-buy-tickets">Buy Tickets</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="slide movie-section">
                <div class="movie-image" style="background-image: url('default-ad.jpg');">
                    <div class="gradient-overlay"></div>
                    <div class="movie-info">
                        <h1 class="movie-title">No Advertisements Available</h1>
                        <p class="movie-description">Please check back later for updates.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Navigation buttons -->
    <div class="navigation">
        <button onclick="moveSlide(-1)">&#10094;</button>
        <button onclick="moveSlide(1)">&#10095;</button>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        let currentIndex = 0;
        const slides = document.querySelectorAll('.slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.transform = `translateX(${(i - index) * 100}%)`;
            });
        }

        function moveSlide(direction) {
            currentIndex = (currentIndex + direction + totalSlides) % totalSlides;
            showSlide(currentIndex);
        }

        // Autoplay
        let autoplay = setInterval(() => moveSlide(1), 5000);

        document.querySelector('.slider').addEventListener('mouseenter', () => {
            clearInterval(autoplay);
        });

        document.querySelector('.slider').addEventListener('mouseleave', () => {
            autoplay = setInterval(() => moveSlide(1), 5000);
        });

        showSlide(currentIndex);
    });
</script>
<?php include('movies_events.php') ?>
</body>
</html>
