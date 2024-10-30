<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$dbname = "car_rental";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$car_id = $_POST['car_id'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// Check if the car is available for the given date range
$sql = "SELECT * FROM rentals WHERE car_id = ? AND ((start_date BETWEEN ? AND ?) OR (end_date BETWEEN ? AND ?))";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $car_id, $start_date, $end_date, $start_date, $end_date);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(array("success" => false, "message" => "Car is not available for the selected dates"));
} else {
    // Insert the rental record
    $sql = "INSERT INTO rentals (car_id, start_date, end_date) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $car_id, $start_date, $end_date);
    
    if ($stmt->execute()) {
        // Update the car's availability
        $sql = "UPDATE cars SET available = 0 WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $car_id);
        $stmt->execute();
        
        echo json_encode(array("success" => true));
    } else {
        echo json_encode(array("success" => false, "message" => "Error: " . $stmt->error));
    }
}

$conn->close();
?>