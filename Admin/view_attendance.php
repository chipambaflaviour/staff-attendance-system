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

$filter = $_GET['filter'] ?? 'today';
$title = "Today's Attendance Report";

if ($filter === 'today') {
    $today = date("Y-m-d");
    $query = "SELECT a.*, e.name, e.department 
              FROM attendance a 
              JOIN employees e ON a.employee_id = e.employee_id 
              WHERE DATE(a.scan_time) = '$today'
              ORDER BY a.scan_time DESC";
} else {
    $query = "SELECT a.*, e.name, e.department 
              FROM attendance a 
              JOIN employees e ON a.employee_id = e.employee_id 
              ORDER BY a.scan_time DESC";
}

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="dashboard.php">Staff System</a>
        <div class="d-flex align-items-center">
            <span class="text-light me-3">Welcome, <?php echo $_SESSION['username'] ?? 'Admin'; ?></span>
            <a href="dashboard.php" class="btn btn-sm btn-outline-light me-2">Back to Dashboard</a>
            <a href="add_staff.php" class="btn btn-sm btn-outline-light me-2">Add Staff</a>
            <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
        </div>
    </div>
</nav>

    <div class="container">
        <h1 class="text-center mb-4"><?php echo $title; ?></h1>
        
        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Employee ID</th>
                                <th>Name</th>
                                <th>Department</th>
                                <th>Scan Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo "<tr>
                                            <td>{$row['employee_id']}</td>
                                            <td>{$row['name']}</td>
                                            <td>{$row['department']}</td>
                                            <td>{$row['scan_time']}</td>
                                          </tr>";
                                }
                            } else {
                                echo "<tr><td colspan='4'>No attendance records found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>