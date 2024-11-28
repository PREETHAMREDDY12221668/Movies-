<?php
include('config.php');

date_default_timezone_set('Asia/Kolkata');
?>

<!DOCTYPE HTML>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="css/header-style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>
<header class="header">
    <nav class="navbar">
        <div class="logo"><a href="index.php">Movie +</a></div>
        <ul class="nav-links">
            <li><a href="index.php">[Home]</a></li>
            <li><a href="movies_events.php">[Stream]</a></li>
            <li><a href="booking.php">[Events]</a></li>
        </ul>
        
        
        <?php include('searchbar.php'); ?>
<!-- Dropdown Menu -->
        <div class="dropdown">
            <div class="profile-icon">
                <img src="images/avatar.svg" alt="User Profile Icon">
                <p>Account</p>
            </div>
            <div class="dropdown-menu">
                <?php
                if (isset($_SESSION['user'])) {
                    $stmt = $con->prepare("SELECT * FROM tbl_registration WHERE user_id = ?");
                    $stmt->bind_param("i", $_SESSION['user']);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                    $stmt->close();
                ?>
                    <a href="profile.php"><?php echo htmlspecialchars($user['name']); ?></a>
                    <a href="logout.php">[Logout]</a>
                <?php } else { ?>
                    <a href="login.php">[Login]</a>
                <?php } ?>
            </div>
        </div>
        
    </nav>
</header>
</body>
</html>
