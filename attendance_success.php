<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "attendance_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$employee_id = $_GET['id'];

// Fetch employee details
$stmt = $conn->prepare("SELECT name, department FROM employees WHERE employee_id = ?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $employee = $result->fetch_assoc();
    
    // Record attendance
    $stmt = $conn->prepare("INSERT INTO attendance (employee_id, scan_time) VALUES (?, NOW())");
    $stmt->bind_param("s", $employee_id);
    $stmt->execute();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Attendance Recorded</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow text-center">
            <div class="card-body">
                <h1 class="text-success">✓ Attendance Recorded</h1>
                <div class="mt-4">
                    <p><strong>Employee ID:</strong> <?php echo $employee_id; ?></p>
                    <p><strong>Name:</strong> <?php echo $employee['name']; ?></p>
                    <p><strong>Department:</strong> <?php echo $employee['department']; ?></p>
                    <p class="text-muted"><?php echo date("Y-m-d H:i:s"); ?></p>
                </div>
                <a href="index.html" class="btn btn-primary mt-3">Scan Again</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php
} else {
    header("Location: error.html"); // Redirect if employee not found
}
$conn->close();
?>