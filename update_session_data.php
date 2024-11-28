<?php
session_start(); // Start the session to access session variables

// Check if the form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Validate and sanitize input data before storing in session
    if (isset($_POST['movie_id'], $_POST['show_id'], $_POST['theater_id'], $_POST['screen_id'])) {
        $_SESSION['movie'] = $_POST['movie_id'];
        $_SESSION['movie_name'] = $_POST['movie_name'] ?? null;
        $_SESSION['show'] = $_POST['show_id'];
        $_SESSION['theater'] = $_POST['theater_id'];
        $_SESSION['theater_name'] = $_POST['theater_name'];
        $_SESSION['screen'] = $_POST['screen_id'];

        // Redirect to the booking page (or any other page)
        header('Location: booking.php');
        exit;
    } else {
        // If the data is incomplete, handle the error here
        echo "Error: Missing data.";
    }
}
?>
