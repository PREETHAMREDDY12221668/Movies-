<?php
// Database connection
$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "db_movie";
$con = mysqli_connect($host, $user, $pass, $db);

if (!$con) {
    die(json_encode(['error' => 'Connection failed: ' . mysqli_connect_error()]));
}

// Validate and sanitize input
$show_id = filter_input(INPUT_GET, 'show_id', FILTER_VALIDATE_INT);
if (!$show_id) {
    die(json_encode(['error' => 'Invalid show_id']));
}

// Fetch the total seats for the screen associated with the show
$sql = "SELECT screens.total_seats 
        FROM shows 
        JOIN screens ON shows.screen_id = screens.screen_id 
        WHERE shows.show_id = ?";
$stmt = $con->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Query preparation failed: ' . $con->error]));
}
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $screen = $result->fetch_assoc();
    $total_seats = $screen['total_seats'];
} else {
    die(json_encode(['error' => 'Show not found or no seats available']));
}

// Calculate rows and columns dynamically
$columns = ceil(sqrt($total_seats));
$rows = ceil($total_seats / $columns);

// Fetch booked seats for the show
$sql = "SELECT seat_number FROM bookings WHERE show_id = ?";
$stmt = $con->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Query preparation failed: ' . $con->error]));
}
$stmt->bind_param("i", $show_id);
$stmt->execute();
$result = $stmt->get_result();

$seats = [];
while ($row = $result->fetch_assoc()) {
    $seat_number = $row['seat_number'];
    if ($seat_number) {
        $seats[$seat_number] = 'booked'; // Mark booked seats
    }
}

// Prepare the response
$response = [
    'rows' => $rows,
    'columns' => $columns,
    'totalSeats' => $total_seats,
    'seats' => $seats
];

// Send response as JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
