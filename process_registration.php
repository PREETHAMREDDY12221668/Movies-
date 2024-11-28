<?php
session_start();
include('config.php');
extract($_POST);

// Sanitize inputs to prevent SQL injection
$name = mysqli_real_escape_string($con, $name);
$email = mysqli_real_escape_string($con, $email);
$phone = mysqli_real_escape_string($con, $phone);
$age = mysqli_real_escape_string($con, $age);
$gender = mysqli_real_escape_string($con, $gender);  // Assuming you have a gender input field
$password = mysqli_real_escape_string($con, $password);

// Insert data into tbl_registration (make sure the column names match your table structure)
$query1 = "INSERT INTO tbl_registration (name, email, phone, age, gender) 
           VALUES ('$name', '$email', '$phone', '$age', '$gender')";

// Execute the query and check for errors
if (mysqli_query($con, $query1)) {
    // Get the inserted user id
    $id = mysqli_insert_id($con);

    // Insert data into tbl_login (email will be saved as the username)
    $query2 = "INSERT INTO tbl_login (user_id, username, email, password, role) 
               VALUES ('$id', '$email', '$email', '$password', '2')";  // Assuming '2' is the default user role

    if (mysqli_query($con, $query2)) {
        // Store the user id in the session and redirect
        $_SESSION['user'] = $id;
        header('Location: index.php');
        exit();
    } else {
        // Handle login insert error
        $_SESSION['error'] = "Error inserting into tbl_login: " . mysqli_error($con);
        header('Location: registration.php');
        exit();
    }
} else {
    // Handle registration insert error
    $_SESSION['error'] = "Error inserting into tbl_registration: " . mysqli_error($con);
    header('Location: registration.php');
    exit();
}
?>
