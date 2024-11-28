<?php
session_start();
include('config.php'); // Assuming you have a separate file for DB connection

// Enable error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Capture the form data
$email = $_POST['Email'];  // Using email as the login credential
$password = $_POST['Password'];  // User's password

// Validate input fields
if (empty($email) || empty($password)) {
    $_SESSION['error'] = "Please enter both email and password!";
    // Log error to console (optional)
    echo "<script>console.log('Error: Please enter both email and password!');</script>";
    header("Location: login.php");
    exit();
}

// Hash the password before comparing (you could use password_hash for better security)
//$passwordHash = md5($password); // md5 for example, but using password_hash is more secure

// Query to check the credentials and get the email from tbl_registration
$sql = "
    SELECT l.user_id, r.email 
    FROM tbl_login l
    JOIN tbl_registration r ON l.user_id = r.user_id
    WHERE l.email = ? AND l.password = ?";  // Checking the email in tbl_login as the login field

$stmt = $con->prepare($sql);

// Check if the query was prepared successfully
if (!$stmt) {
    echo "<script>console.log('SQL Error: Could not prepare the SQL statement.');</script>";
    echo "<script>console.log('MySQL Error: " . mysqli_error($con) . "');</script>";
    exit();
}

// Bind parameters to the query
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

// Check if user exists
if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['email'] = $user['email'];  // Store email in session instead of username

    // Log successful login
    echo "<script>console.log('Login successful for user: " . $user['email'] . "');</script>";

    header("Location: index.php"); // Redirect to the checkout page after successful login
    exit();
} else {
    $_SESSION['error'] = "Invalid email or password!";
    // Log failed login attempt
    echo "<script>console.log('Error: Invalid email or password!');</script>";

    // Show alert and redirect back to login
    echo "<script>alert('The password is not matching');</script>";
    
    exit();
}
?>
