<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/footer.css"
</head>
<body>
<div class="footer-content">
    <div class="footer-wrap">
        <!-- About Us Section -->
        <div class="col_1_of_4">
            <h3 class="h3">About Us</h3>
            <p class="p">
                Welcome to MovieZone, your go-to platform for booking movie tickets online. 
                Experience the best shows and get exclusive offers with ease and convenience.
            </p>
            <p class="p">
                Our mission is to make movie booking seamless and enjoyable for everyone. 
                Join us for the latest in entertainment!
            </p>
        </div>

        <!-- Quick Links Section -->
        <div class="col_1_of_4">
            <h3 class="h3">Quick Links</h3>
            <ul class="footer-links">
                <li><a href="index.php">Home</a></li>
                <li><a href="movies_events.php">Movies</a></li>
                <li><a href="offers.php">Offers</a></li>
                <li><a href="contact_us.php">Contact Us</a></li>
                <li><a href="login.php">Login</a></li>
            </ul>
        </div>

        <!-- Customer Support Section -->
        <div class="col_1_of_4">
            <h3 class="h3">Customer Support</h3>
            <ul class="footer-links">
                <li><a href="faq.php">FAQs</a></li>
                <li><a href="refund_policy.php">Refund Policy</a></li>
                <li><a href="terms_conditions.php">Terms & Conditions</a></li>
                <li><a href="privacy_policy.php">Privacy Policy</a></li>
                <li><a href="support.php">Help Center</a></li>
            </ul>
        </div>

        <!-- Newsletter Signup and Social Media -->
        <div class="col_1_of_4">
            <h3 class="h3">Stay Connected</h3>
            <p class="p">Subscribe to our newsletter to get the latest updates on movies, offers, and events:</p>
            <form id="newsletter-form">
                <input type="email" id="newsletter-input" placeholder="Enter your email" required>
                <button id="newsletter-button" type="submit">Subscribe</button>
            </form>
            <div class="social">
                <a href="#"><img src="images/facebook.svg" alt="Facebook"></a>
                <a href="#"><img src="images/x.svg" alt="Twitter"></a>
                <a href="#"><img src="images/instagram.svg" alt="Instagram"></a>
                <a href="#"><img src="images/youtube.svg" alt="YouTube"></a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <p class="p">&copy; 2024 Movie+. All Rights Reserved. Designed by Movie+ Team.</p>
    </div>
</div>

</body>
</html>

<style>
.content {
	padding-bottom:0px !important;
}
#form111 {
                
                margin:50px auto;
}
#search111{
                padding:8px 15px;
                background-color:#fff;
                border:0px solid #dbdbdb;
}
#button111 {
                position:relative;
                padding:6px 15px;
                left:-8px;
                border:2px solid #207cca;
                background-color:#207cca;
                color:#fafafa;
}
#button111:hover  {
                background-color:#fafafa;
                color:#207cca;
}

</style>

<script src="js/auto-complete.js"></script>
 <link rel="stylesheet" href="css/auto-complete.css">
    <script>
        var demo1 = new autoComplete({
            selector: '#search111',
            minChars: 1,
            source: function(term, suggest){
                term = term.toLowerCase();
                <?php
						$qry2=mysqli_query($con,"select * from tbl_movie");
						?>
               var string="";
                <?php $string="";
                while($ss=mysqli_fetch_array($qry2))
                {
                
                $string .="'".strtoupper($ss['movie_name'])."'".",";
                
                
              
                }
                ?>
               
              var choices=[<?php echo $string;?>];
                var suggestions = [];
                for (i=0;i<choices.length;i++)
                    if (~choices[i].toLowerCase().indexOf(term)) suggestions.push(choices[i]);
                suggest(suggestions);
            }
        });
    </script>