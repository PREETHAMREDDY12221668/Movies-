<?php
include('config.php');
session_start();
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
<header>
    <nav class="navbar">
        <div class="logo"><a href="index.php">Movie +</a></div>
        <ul class="nav-links">
            <li><a href="index.php">Home</a></li>
            <li><a href="movies_events.php">Now Showing</a></li>
            <!-- <li><a href="booking.php">Cinemas</a></li> -->
            <li>
                <?php
                if (isset($_SESSION['user'])) {
                    $stmt = $con->prepare("SELECT * FROM tbl_registration WHERE user_id = ?");
                    $stmt->bind_param("i", $_SESSION['user']);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                    $stmt->close();
                ?>
                    <a href="profile.php"><?php echo htmlspecialchars($user['name']); ?></a>
                    <a href="logout.php">Logout</a>
                <?php } else { ?>
                    <a href="login.php">Login</a>
                <?php } ?>
            </li>
        </ul>
        <div class="profile-icon">
            <img src="user-icon.png" alt="User Profile Icon">
        </div>
    </nav>
</header>

<div class="clear"></div>
<div class="block">
	<div class="wrap">
		<form action="process_search.php" id="reservation-form" method="post" onsubmit="myFunction()">
	       <fieldset>
				<div class="field" >
					<input type="text" placeholder="Search Movies Here..." style="height:27px;width:500px"  required id="search111" name="search">
					<input type="submit" value="Search" style="height:28px;padding-top:4px" id="button111">
				</div>       	
			</fieldset>
		</form>
	<div class="clear"></div>
</div>
</div>
<script>
function myFunction() {
    if ($('#search111').val() == "") {
        alert("Please enter movie name...");
        return false;
    } else {
        return true;
    }
}

</script>