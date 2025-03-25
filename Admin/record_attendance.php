<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root"; // Replace with your MySQL username
$password = ""; // Replace with your MySQL password
$dbname = "attendance_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]));
}

// Get the scanned employee ID from the request
$data = json_decode(file_get_contents('php://input'), true);
$employee_id = $data['employee_id'];

// Validate employee ID
$stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Employee exists, record attendance
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, scan_time) VALUES (?, NOW())");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();

    echo json_encode(["status" => "success", "message" => "Attendance recorded"]);
} else {
    // Employee not found
    echo json_encode(["status" => "error", "message" => "Employee not found"]);
}

$stmt->close();
$conn->close();
?>