<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seat Selection</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        table {
            margin: 0 auto;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 5px;
        }

        .seat-layout th {
            font-weight: bold;
            text-align: center;

        }

        .seatR {
            font-weight: bold;
            color: grey;
            
        }

        .seatI {
            width: 25px;
            height: 25px;
            border: 1px solid #ccc;
            cursor: pointer;
            text-align: center;
            vertical-align: middle;
            font-size: 10px;

        }

        .seatI.booked {
            background-color: grey;
            color: white;
            pointer-events: none;
        }

        .seatI.available {
            color: #1ea83c;
            border: 1px solid #1ea83c;
            cursor: pointer;
        }

        .seatI.selected {
            background-color: #1ea83c;
            color: white;
        }


        button {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <?php
    include('config.php');
    session_start();

    // Access individual session variables
    $movie_id = $_SESSION['movie'] ?? null;
    $theatre_name=$_SESSION['theater_name']?? null ;
    $movie_name_query = "
    SELECT name
    FROM Movies 
    WHERE movie_id = '" . mysqli_real_escape_string($con, $movie_id) . "'";
    $movie_name_result = mysqli_query($con, $movie_name_query);

    // Fetch the movie name
    $movie_name = "Unknown Movie";
    if ($movie_name_result && mysqli_num_rows($movie_name_result) > 0) {
        $movie_data = mysqli_fetch_assoc($movie_name_result);
        $movie_name = $movie_data['name']; // Retrieve the movie name
    }
    // Example usage
    echo "Movie ID: $movie_id<br>";
    echo "theater_name : $theatre_name <br>";
    ?>
    <div class="showtime-bar">
    <h3><?php echo htmlspecialchars($movie_name); ?></h3>
        <?php
        // Define the showtimes
        $showtimes = ["12:15 PM", "6:15 PM", "9:45 PM"];

        // Generate buttons for each showtime
        foreach ($showtimes as $time) {
            // Assume 6:15 PM is the current selected showtime
            
            $isSelected = ($time == "6:15 PM") ? true : false;
            $class = $isSelected ? "primary" : "secondary";
            
            echo "<button class='showtime-button $class'>$time</button>";
        }
        ?>
    </div>
    
    <h1>Select Your Seats</h1>
    <table id="seat-layout" class="seat-layout"></table>
    <button onclick="bookSeats()">Book Seats</button>
    <?php
        include('config.php');
        

        // Get show_id from URL parameter
        $show_id = isset($_GET['show_id']) ? $_GET['show_id'] : null;  // Retrieve from URL if available
        $ticket_price = 0;  // Default ticket price

        if ($show_id) {
            // Fetch the ticket price for the selected show
            $price_query = "SELECT ticket_price FROM Shows WHERE show_id = '" . mysqli_real_escape_string($con, $show_id) . "'";
            $price_result = mysqli_query($con, $price_query);

            if ($price_result && mysqli_num_rows($price_result) > 0) {
                $price_data = mysqli_fetch_assoc($price_result);
                $ticket_price = $price_data['ticket_price'];  // Retrieve the ticket price
            }
        }

        // Now $ticket_price holds the value from the database
        ?>
        <h3>Ticket Price: ₹<?php echo htmlspecialchars($ticket_price); ?></h3>

    <script>
        let showId = getShowId();

        function getShowId() {
            const urlParams = new URLSearchParams(window.location.search);
            const urlShowId = urlParams.get('show_id');
            
            if (urlShowId) {
                sessionStorage.setItem('show_id', urlShowId); // Save to session storage
                return urlShowId;
            }

            const storedShowId = sessionStorage.getItem('show_id');
            if (storedShowId) {
                return storedShowId;
            }

            alert('Show ID not found. Please provide a valid show_id in the URL or session.');
            return null;
        }

        if (!showId) {
            throw new Error("Show ID is missing.");
        }

        let selectedSeats = [];

        fetch(`get_seats.php?show_id=${showId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Fetched seat data:', data); // Log the response

                if (!data || !data.rows || !data.columns || !data.seats) {
                    alert("Error fetching seat layout.");
                    return;
                }

                const layout = document.getElementById('seat-layout');
                layout.innerHTML = ''; // Clear previous layout

                const bookedSeats = new Set();
                Object.keys(data.seats).forEach(seatRange => {
                    if (data.seats[seatRange] === 'booked') {
                        // Split the range into individual seats and add to the bookedSeats set
                        seatRange.split(',').forEach(seat => {
                            bookedSeats.add(seat.trim());
                        });
                    }
                });
                console.log('Parsed booked seats:', Array.from(bookedSeats));

                for (let row = data.rows; row >= 1; row--) {
                    const tr = document.createElement('tr');

                    const rowLabelCell = document.createElement('td');
                    rowLabelCell.classList.add('seatR');
                    rowLabelCell.textContent = String.fromCharCode(64 + row); // Row label
                    tr.appendChild(rowLabelCell);

                    for (let col = 1; col <= data.columns; col++) {
                        const seatNumber = `${String.fromCharCode(64 + row)}${col}`;
                        const status = bookedSeats.has(seatNumber) ? 'booked' : 'available';
                        console.log(`Seat ${seatNumber}: ${status}`); // Log each seat's status

                        const seatCell = document.createElement('td');
                        const seat = document.createElement('div');
                        seat.className = `seatI ${status}`;
                        seat.textContent = col; // Column number
                        seat.onclick = () => toggleSeat(seat, seatNumber, status);

                        seatCell.appendChild(seat);
                        tr.appendChild(seatCell);
                    }

                    layout.appendChild(tr);
                }
            })
            .catch(err => {
                console.error('Error fetching seat data:', err);
            });

        function toggleSeat(seat, seatNumber, status) {
            if (status === 'available') {
                if (selectedSeats.includes(seatNumber)) {
                    selectedSeats = selectedSeats.filter(s => s !== seatNumber);
                    seat.classList.remove('selected');
                } else {
                    selectedSeats.push(seatNumber);
                    seat.classList.add('selected');
                }
            }
        }

    // Update the way seat numbers are displayed in the layout (e.g., "A1", "B2", etc.)
        fetch(`get_seats.php?show_id=${showId}`)
            .then(response => response.json())
            .then(data => {
                if (!data || !data.rows || !data.columns || !data.seats) {
                    alert("Error fetching seat layout.");
                    return;
                }

                const layout = document.getElementById('seat-layout');
                layout.innerHTML = ''; // Clear previous layout

                for (let row = data.rows; row >= 1; row--) {
                    const tr = document.createElement('tr');

                    // First <td>: Row label
                    const rowLabelCell = document.createElement('td');
                    const rowLabel = document.createElement('div');
                    rowLabel.classList.add('seatR');
                    rowLabel.textContent = String.fromCharCode(64 + row); // Convert row number to letter
                    rowLabelCell.appendChild(rowLabel);
                    tr.appendChild(rowLabelCell);

                    // Second <td>: Container for seat divs
                    const seatContainerCell = document.createElement('td');
                    const seatContainer = document.createElement('div');
                    seatContainer.style.display = 'flex';
                    seatContainer.style.justifyContent = 'space-between'; // Distribute halves
                    seatContainer.style.gap = '35px'; // Additional gap between halves

                    // Create two halves for seats
                    const firstHalf = document.createElement('div');
                    firstHalf.style.display = 'flex';
                    firstHalf.style.justifyContent='left'
                    firstHalf.style.gap = '5px'; // Gap between seats in the first half

                    const secondHalf = document.createElement('div');
                    secondHalf.style.display = 'flex';
                    secondHalf.style.justifyContent='right'
                    secondHalf.style.gap = '5px'; // Gap between seats in the second half

                    for (let col = 1; col <= data.columns; col++) {
                        const seatIndex = (row - 1) * data.columns + col;
                        if (seatIndex > data.totalSeats) break; // Stop if we exceed total seats

                        // Create seat number in the format "A1", "A2", etc.
                        const seatNumber = `${String.fromCharCode(64 + row)}${col}`;
                        const status = data.seats[seatNumber] || 'available';

                        const seat = document.createElement('div');
                        seat.className = `seatI ${status}`;
                        seat.textContent = `${col}`; // Display column number
                        seat.onclick = () => toggleSeat(seat, seatNumber, status);

                        // Add seat to the appropriate half
                        if (col <= Math.ceil(data.columns / 2)) {
                            firstHalf.appendChild(seat);
                        } else {
                            secondHalf.appendChild(seat);
                        }
                    }

                    // Append halves to the seat container
                    seatContainer.appendChild(firstHalf);
                    if (data.columns > 8) seatContainer.appendChild(secondHalf); // Only append second half if columns > 8

                    seatContainerCell.appendChild(seatContainer);
                    tr.appendChild(seatContainerCell);

                    layout.appendChild(tr);
                }
            }).catch(err => {
                console.error('Error fetching seat data:', err);
            });


        // Update the bookSeats function to send seat data in letter-number format
        function bookSeats() {
                alert('The function is called');
                
                // Calculate the total amount: number of selected seats * ticket price
                const totalAmount = selectedSeats.length * <?php echo $ticket_price; ?>;
                
                console.log('Data being sent:', JSON.stringify({ show_id: showId, seats: selectedSeats, total_amount: totalAmount }));
                
                fetch('book_seat.php', {
                    method: 'POST', 
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ show_id: showId, seats: selectedSeats, total_amount: totalAmount })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Seats booked successfully!');
                    } else {
                        alert('Failed to book seats.');
                    }
                    location.reload();
                });
            }


    </script>
</body>
</html>
