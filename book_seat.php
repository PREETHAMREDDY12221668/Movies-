<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = "127.0.0.1";
$user = "root";
$pass = "";
$db = "db_movie";
$con = mysqli_connect($host, $user, $pass, $db);

if ($con->connect_error) {
    die(json_encode(['success' => false, 'message' => "Database connection failed: " . $con->connect_error]));
}

// Debug: Log received data
$data = json_decode(file_get_contents("php://input"), true);
error_log("Data received: " . print_r($data, true));

file_put_contents('log.txt', "Received data: " . print_r($data, true), FILE_APPEND);

$show_id = $data['show_id'] ?? null;
$seats = $data['seats'] ?? [];
$total_amount = $data['total_amount'] ?? 0;

if (!$show_id || empty($seats) || !$total_amount) {
    echo json_encode(['success' => false, 'message' => 'Invalid input data.']);
    exit;
}

if (!is_array($seats)) {
    echo json_encode(['success' => false, 'message' => 'Invalid seat data format.']);
    exit;
}

// Convert the seat numbers array to a string for checking in the database
$seat_numbers = implode(",", $seats);

// Check if the requested seats are already booked
$seat_numbers_check = "'" . implode("','", $seats) . "'"; // Prepare for SQL query

$sql_check = "SELECT seat_number FROM bookings WHERE show_id = ? AND seat_number IN ($seat_numbers_check)";
$result = $con->prepare($sql_check);
if (!$result) {
    echo json_encode(['success' => false, 'message' => 'Failed to prepare check query.']);
    exit;
}
$result->bind_param("i", $show_id);
$result->execute();
$result->store_result();

if ($result->num_rows > 0) {
    // If there are any results, some seats are already booked
    echo json_encode(['success' => false, 'message' => 'One or more selected seats are already booked.']);
    $result->close();
    $con->close();
    exit;
}

// Proceed with booking if no conflicts found
$con->begin_transaction();

try {
    $booking_date = date('Y-m-d H:i:s');
    $number_of_seats = count($seats);

    $sql = "INSERT INTO bookings (show_id, booking_date, number_of_seats, total_amount, seat_number) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    if (!$stmt) {
        throw new Exception("Failed to prepare query: " . $con->error);
    }
    $stmt->bind_param("isids", $show_id, $booking_date, $number_of_seats, $total_amount, $seat_numbers);
    $stmt->execute();

    if ($stmt->affected_rows === 0) {
        throw new Exception("Failed to insert booking record.");
    }

    $con->commit();
    echo json_encode(['success' => true, 'message' => 'Booking successful!']);
} catch (Exception $e) {
    $con->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} finally {
    if (isset($stmt)) {
        $stmt->close();
    }
    $con->close();
}
?>
