<link rel="stylesheet" href="css/booking.css" >
<link rel="stylesheet" href=css/style.css>
<script>
 let currentDayIndex = 0;
    const numberOfDays = 4;

    function updateDateBar(direction) {
        const dateBar = document.querySelector('.date-cont-small');
        dateBar.innerHTML = ''; // Clear current dates

        // Update the current day index
        currentDayIndex += direction * numberOfDays;

        // Prevent going below day 0
        if (currentDayIndex < 0) {
            currentDayIndex = 0;
        }

        // Generate new dates
        for (let i = 0; i < numberOfDays; i++) {
            const date = new Date();
            date.setDate(date.getDate() + currentDayIndex + i);

            // Format date (e.g., "Mon, Nov 27")
            const formattedDate = date.toLocaleDateString('en-US', {
                weekday: 'short',
                month: 'short',
                day: 'numeric',
            });

            // Create date element
            const dateDiv = document.createElement('div');
            dateDiv.className = 'date';
            if (i === 0) dateDiv.classList.add('active');
            dateDiv.textContent = formattedDate;

            dateBar.appendChild(dateDiv);
        }
    }

    // Initialize the date bar with the first set of dates
    document.addEventListener('DOMContentLoaded', () => {
        updateDateBar(0);
    });

function toggleDropdown(dropdownId) {
            const dropdown = document.getElementById(dropdownId);
            dropdown.classList.toggle("show");
        }

        // Close the dropdown if the user clicks outside of it
        window.onclick = function(event) {
            if (!event.target.matches('.dropbtn')) {
                const dropdowns = document.getElementsByClassName("dropdown-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    dropdowns[i].classList.remove('show');
                }
            }
        }
</script>

<?php
session_start();


$currentDayIndex = 0; // Highlight today by default
$dates = [];

// Generate a range of dates (e.g., today + 7 days for a week)
$number_of_days = 4; // Number of days to show in the calendar
for ($i = 0; $i < $number_of_days; $i++) {
    $date = strtotime("+$i days");
    $dates[] = date('D, M j', $date); // Format: e.g., "Mon, Nov 27"
}


include('header.php');

// Check if required session variables are set
if (!isset($_SESSION['movie'])) {
    echo "Error: Required session data is missing.";
    exit;
}

// Fetch the movie name from the database
$movie_id = $_SESSION['movie'] ?? null; // Get the movie ID from session
$movie_name_query = "
    SELECT name
    FROM Movies 
    WHERE movie_id = '" . mysqli_real_escape_string($con, $movie_id) . "'
";
$movie_name_result = mysqli_query($con, $movie_name_query);

// Fetch the movie name
$movie_name = "Unknown Movie";
if ($movie_name_result && mysqli_num_rows($movie_name_result) > 0) {
    $movie_data = mysqli_fetch_assoc($movie_name_result);
    $movie_name = $movie_data['name']; // Retrieve the movie name
}

// Query the `Shows` table to fetch all relevant shows sorted by theater name
$movie_condition = "movie_id = '" . mysqli_real_escape_string($con, $movie_id) . "'";
$query = "
    SELECT 
        Shows.*, 
        Theaters.name AS theater_name, 
        Theaters.location AS theater_location
    FROM 
        Shows
    INNER JOIN 
        Theaters 
    ON 
        Shows.theater_id = Theaters.theater_id
    WHERE 
        $movie_condition
    ORDER BY 
        theater_name ASC
";

$result = mysqli_query($con, $query);

// Check if any shows are found
if (mysqli_num_rows($result) === 0) {
    echo "<p>No shows found matching the provided movie criteria.</p>";
    include('footer.php');
    exit;
}
?>
<div class="content">
    <h3><?php echo htmlspecialchars($movie_name); ?></h3>
    <h2>U/A </h2>
    <div class="date-bar">
   
        <div class="date-cont-left">
        <button class="arrow" onclick="updateDateBar(-1)">&#9664;</button>
            <div class="date-cont-small">
            <?php foreach ($dates as $index => $date): ?>
                <div class="date <?php echo $index == $currentDayIndex ? 'active' : ''; ?>">
                    <?php echo $date; ?>
                </div>
            <?php endforeach; ?>
            </div>
            <button class="arrow" onclick="updateDateBar(1)">&#9654;</button>
        </div>
        
         <div class="date-cont-right">
    <div class="dropdown">
        <button onclick="toggleDropdown('dropdown2D')" class="dropbtn">2D</button>
        <div id="dropdown2D" class="dropdown-content">
            <a href="#">Option 1</a>
            <a href="#">Option 2</a>
            <a href="#">Option 3</a>
        </div>
    </div>

    <div class="dropdown">
        <button onclick="toggleDropdown('dropdownHindi')" class="dropbtn">Hindi</button>
        <div id="dropdownHindi" class="dropdown-content">
            <a href="#">Option A</a>
            <a href="#">Option B</a>
            <a href="#">Option C</a>
        </div>
    </div>

    <!-- Additional Filters -->
    <div class="filter">
        <select>
            <option>Filter Sub Regions</option>
            <option>Region 1</option>
            <option>Region 2</option>
        </select>
    </div>
    <div class="filter">
        <select>
            <option>Filter Price Range</option>
            <option>\$10 - $20</option>
            <option>\$20 - $30</option>
        </select>
    </div>
    <div class="search-icon">
        <button>🔍</button>
    </div>
        </div>
    </div>

    <div class="show-list">
        <?php 
        $theaters = []; // Array to group shows by theater

        // Group shows by theater name and location
        while ($show = mysqli_fetch_array($result)) {
            $theater_key = $show['theater_name'] . " - " . $show['theater_location'];
            if (!isset($theaters[$theater_key])) {
                $theaters[$theater_key] = [];
            }
            $theaters[$theater_key][] = [
                'show_id' => $show['show_id'], 
                'start_time' => $show['start_time'],
                'end_time' => $show['end_time'],
                'ticket_price' => $show['ticket_price']
            ];
        }

        // Display the grouped data
        foreach ($theaters as $theater_name => $shows): ?>
            <div class="show-card">
                <div class="show-details">
                    <h4><?php echo htmlspecialchars($theater_name); ?></h4>
                    <div class="info">
                        <span class="non-cancellable">Non-cancellable</span>
                    </div>
                </div>
                <div class="show-timings" >
                        <?php foreach ($shows as $show): ?>
                        <a href="seat_selection.php?show_id=<?php echo $show['show_id']; ?>" class="timing">
                            <?php echo date('h:i A', strtotime($show['start_time'])); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
        <form action="update_session_data.php" method="POST">
            <input type="hidden" name="theater_name" value="<?php echo htmlspecialchars($_GET['theater_name'] ?? ''); ?>"/>
            <button type="submit" style="display: none;">Submit</button> <!-- Hidden submit button -->
        </form>
    </div>
</div>
<script>
    function submitTheaterForm(theaterName) {
    // Set the value of the hidden input field to the clicked theater name
    const form = document.querySelector('form[action="update_session_data.php"]');
    form.querySelector('input[name="theater_name"]').value = theaterName;

    // Submit the form
    form.submit();
}

</script>
<?php include('footer.php'); ?>

