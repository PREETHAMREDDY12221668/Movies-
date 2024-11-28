<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/login.css">
  <title>Login</title>
</head>
<body>
  <div class="login-content">
    <div class="login-wrap">
      <div class="login-content-top">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
              <?php
              // Error handling
              if (isset($_SESSION['error'])) {
                  echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                  
                  // Log the error message to console using JavaScript
                  echo "<script>console.log('Error: " . $_SESSION['error'] . "');</script>";
                  
                  unset($_SESSION['error']); // Clear the error message after displaying
              }
              ?>
              <p class="login-box-msg">Sign in to start your session</p>
             
              <!-- Login Form (login.php) -->
              <form action="process_login.php" method="post">
                  <div class="form-group">
                      <label for="email">Email</label>
                      <input type="email" name="Email" class="form-control" required placeholder="Enter your email" autocomplete="username">
                  </div>
                  <div class="form-group">
                      <label for="password">Password</label>
                      <input type="password" name="Password" class="form-control" required placeholder="Enter your password" autocomplete="current-password">
                  </div>
                  <button type="submit" class="btn btn-primary">Login</button>
              </form>

              <!-- Register Link -->
              <div class="text-center mt-3">
                  <p>Don't have an account? <a href="registration.php">Register here</a></p>
              </div>
              <h3></h3>
            </div>
          </div>
        </div>
      </div>
      <!-- <div class="clear"></div> -->
    </div>
  </div>

<?php include('footer.php'); ?>

<!-- JavaScript to log form submission and other details -->
<script>
   function logFormSubmission(event) {
  // Log to check if function is triggered
  console.log("logFormSubmission triggered");

  event.preventDefault(); // Prevent the default form submission

  var email = document.querySelector('input[name="Email"]').value;
  var password = document.querySelector('input[name="Password"]').value;
  
  console.log('Form submitted:');
  console.log('Email: ' + email);
  console.log('Password: ' + password);

  if (email && password) {
    console.log('Form is valid. Submitting now.');
    document.querySelector('form').submit(); // Submit the form after logging
  } else {
    console.log('Form validation failed.');
  }
}
</script>

</body>
</html>
