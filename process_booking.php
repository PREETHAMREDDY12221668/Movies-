<?php include('header.php');
if(!isset($_SESSION['user'])) {
    header('location:login.php');
}
?>
<link rel="stylesheet" href="validation/dist/css/bootstrapValidator.css"/>
<script type="text/javascript" src="validation/dist/js/bootstrapValidator.js"></script>

<?php
include('form.php');
$frm = new formBuilder;      
?>

<div class="content">
    <div class="wrap">
        <div class="content-top">
            <h3>Payment</h3>
            
            <!-- Display selected movie and show details -->
            <div class="col-md-8 col-md-offset-2" style="margin-bottom:50px">
                <h4><strong>Movie:</strong> <?php echo $_SESSION['movie_name']; ?></h4>
                <h4><strong>Theater:</strong> <?php echo $_SESSION['theater_name']; ?></h4>
                <h4><strong>Screen:</strong> <?php echo $_SESSION['screen_name']; ?></h4>
                <h4><strong>Show Time:</strong> <?php echo $_SESSION['show_time']; ?></h4>
                <h4><strong>Date:</strong> <?php echo $_SESSION['show_date']; ?></h4>
                <h4><strong>Total Amount:</strong> Rs <?php echo $_SESSION['amount']; ?></h4>
            </div>
            
            <!-- Payment Form -->
            <form action="process_payment.php" method="post" id="form1">
                <div class="col-md-4 col-md-offset-4" style="margin-bottom:50px">
                    <div class="form-group">
                        <label class="control-label">Name on Card</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Card Number</label>
                        <input type="text" class="form-control" name="number" required title="Enter 16 digit card number">
                    </div>
                    <div class="form-group">
                        <label class="control-label">Expiration Date</label>
                        <input type="date" class="form-control" name="date" required>
                    </div>
                    <div class="form-group">
                        <label class="control-label">CVV</label>
                        <input type="text" class="form-control" name="cvv" required>
                    </div>
                    <div class="form-group">
                        <button class="btn btn-success">Make Payment</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include('footer.php');?>

<script>
$(document).ready(function() {
    $('#form1').bootstrapValidator({
        fields: {
            name: {
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'The Name is required and can\'t be empty'
                    },
                    regexp: {
                        regexp: /^[a-zA-Z ]+$/,
                        message: 'The Name can only consist of alphabets'
                    }
                }
            },
            number: {
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'The Card Number is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 16,
                        max: 16,
                        message: 'The Card Number must be 16 characters long'
                    },
                    regexp: {
                        regexp: /^[0-9 ]+$/,
                        message: 'Enter a valid Card Number'
                    }
                }
            },
            date: {
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'The Expiry Date is required and can\'t be empty'
                    }
                }
            },
            cvv: {
                verbose: false,
                validators: {
                    notEmpty: {
                        message: 'The CVV is required and can\'t be empty'
                    },
                    stringLength: {
                        min: 3,
                        max: 3,
                        message: 'The CVV must be 3 characters long'
                    },
                    regexp: {
                        regexp: /^[0-9 ]+$/,
                        message: 'Enter a valid CVV'
                    }
                }
            }
        }
    });
});
</script>

<?php
// Store booking details in session before redirecting to the payment page
session_start();
include('config.php');

// Assuming that these session variables were set earlier in the booking process
$_SESSION['movie_id'] = $movie_id;  // Set movie_id based on selected movie
$_SESSION['movie_name'] = $movie_name;  // Set movie name
$_SESSION['screen'] = $screen_id;  // Set the selected screen
$_SESSION['seats'] = $seats;  // Set number of seats
$_SESSION['amount'] = $amount;  // Set total amount
$_SESSION['date'] = $date;  // Set selected date for the show

header('location:bank.php');
?>
