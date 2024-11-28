<?php include('header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="css/style.css">
	<style>
		section#bg {
			position: relative;
		}

		section#bg video {
			width: 100%;
			height: 83vh;
			object-fit: cover;
			object-position: center;
			position: absolute;
			top: 0;
			left: 0;
		}

		section#bg h1 {
			position: absolute;
			width: 100%;
			top: 0;
			left: 0;
			background-color: #000;
			color: #FFFFFF;
			font-size: 250px;
			text-align: center;
			line-height: 83vh;
			mix-blend-mode: darken;
			margin: 0;
			text-transform: uppercase;
		}

		section#bg h2 {
			position: absolute;
			width: 100%;
			
			color: #949494;
			font-size: 50px;
			text-align: center;
		}
		section#bg h3{
			position: absolute;
			text-align: center;
			color: #949494;
			font-size: 20px;
			
			padding: 250px;
			font-size: 15px;
			font-weight: 100;
		}
		section#bg h4 {
			position: absolute;
			text-align: center;
			color: #949494;
			font-size: 20px;
			
			
		}

		.bookNow {
			position: absolute;
			width: 200px;
			height: 50px;
			background-color: #FFFFFF;
			color: #0F0F0F;
			border-radius: 50px;
			text-align: center;
			font-size: 18px;
			line-height: 50px;
			cursor: pointer;
			border: none;
			bottom: 50px; /* Align it below the text */
			left: 44%;
			top: 60vh;
			
		}
		.bookNow:hover{
			border: 1px solid #040404;
			
		}
		.container{
			display: flex;
			flex-direction: column;
			align-items: center;
			gap: 50px;
		}

		
	</style>
</head>
<body>
	<section id="bg">
		<video autoplay loop muted>
			<source src="https://videos.pexels.com/video-files/5512607/5512607-hd_1920_1080_25fps.mp4" type="video/mp4">
		</video>
		
		<div class="container">
		<h2>.Your Ticket to Cinematic Adventures.</h2>
		<h1>Cinematic</h1>
		<h3>Discover the ultimate platform for booking movie tickets and live event passes effortlessly. Our website combines convenience and excitement, ensuring you never miss out on the entertainment you love!</h3>
		<h4>Book, Watch, Enjoy!</h4>
		<button class="bookNow">Book Now!</button>
		</div>
	</section>
</body>
</html>
