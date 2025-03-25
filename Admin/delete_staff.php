<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_system";

$conn = new mysqli($servername, $username, $password, $dbname);

$employee_id = $_GET['id'] ?? '';

if (!empty($employee_id)) {
    // First delete attendance records to maintain referential integrity
    $conn->query("DELETE FROM attendance WHERE employee_id = '$employee_id'");
    
    // Then delete the staff member
    $stmt = $conn->prepare("DELETE FROM employees WHERE employee_id = ?");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();
?>