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

// Get counts
$total_staff = 0;
$today_attendance = 0;
$pending_requests = 0;

// Total staff count
$staff_query = "SELECT COUNT(*) as total FROM employees";
$staff_result = $conn->query($staff_query);
if ($staff_result) {
    $row = $staff_result->fetch_assoc();
    $total_staff = $row['total'];
}

// Today's attendance count
$today = date("Y-m-d");
$attendance_query = "SELECT COUNT(DISTINCT employee_id) as total FROM attendance WHERE DATE(scan_time) = '$today'";
$attendance_result = $conn->query($attendance_query);
if ($attendance_result) {
    $row = $attendance_result->fetch_assoc();
    $today_attendance = $row['total'];
}

// Pending requests count (with error handling)
try {
    // First check if table exists
    $table_check = $conn->query("SELECT 1 FROM requests LIMIT 1");
    
    if ($table_check !== false) {
        $pending_query = "SELECT COUNT(*) as total FROM requests WHERE status = 'pending'";
        $pending_result = $conn->query($pending_query);
        if ($pending_result) {
            $row = $pending_result->fetch_assoc();
            $pending_requests = $row['total'];
        }
    }
} catch (mysqli_sql_exception $e) {
    // Table doesn't exist - we'll just show 0 pending requests
    $pending_requests = 0;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .stat-card {
            transition: transform 0.3s;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
        }
        .staff-table {
            margin-top: 30px;
        }
        .nav-buttons {
            gap: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Staff Attendance System</a>
            <div class="d-flex align-items-center nav-buttons">
                <span class="text-light me-3">Welcome, <?php echo $_SESSION['username'] ?? 'Admin'; ?></span>
                <a href="add_staff.php" class="btn btn-sm btn-outline-light">Add Staff</a>
                <a href="logout.php" class="btn btn-sm btn-outline-light">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <h1 class="text-center mb-4">Admin Dashboard</h1>
        
        <div class="row">
            <!-- Total Staff -->
            <div class="col-md-4 mb-4">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Staff</h5>
                        <div class="stat-number text-primary"><?php echo $total_staff; ?></div>
                        <button class="btn btn-sm btn-outline-primary mt-2" data-bs-toggle="modal" data-bs-target="#staffModal">
                            View All Staff
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Today's Attendance -->
            <div class="col-md-4 mb-4">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Today's Attendance</h5>
                        <div class="stat-number text-success"><?php echo $today_attendance; ?></div>
                        <a href="view_attendance.php?filter=today" class="btn btn-sm btn-outline-success mt-2">
                            View Report
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Pending Requests -->
            <div class="col-md-4 mb-4">
                <div class="card stat-card">
                    <div class="card-body text-center">
                        <h5 class="card-title">Pending Requests</h5>
                        <div class="stat-number text-warning"><?php echo $pending_requests; ?></div>
                        <a href="view_requests.php" class="btn btn-sm btn-outline-warning mt-2">
                            Manage Requests
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Modal -->
    <div class="modal fade" id="staffModal" tabindex="-1" aria-labelledby="staffModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staffModalLabel">All Staff Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Employee ID</th>
                                    <th>Name</th>
                                    <th>Department</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $conn = new mysqli($servername, $username, $password, $dbname);
                                $staff_list_query = "SELECT * FROM employees ORDER BY name ASC";
                                $staff_list_result = $conn->query($staff_list_query);
                                
                                if ($staff_list_result->num_rows > 0) {
                                    while($row = $staff_list_result->fetch_assoc()) {
                                        echo "<tr>
                                                <td>{$row['employee_id']}</td>
                                                <td>{$row['name']}</td>
                                                <td>{$row['department']}</td>
                                                <td>
                                                    <a href='edit_staff.php?id={$row['employee_id']}' class='btn btn-sm btn-outline-primary'>Edit</a>
                                                    <a href='delete_staff.php?id={$row['employee_id']}' class='btn btn-sm btn-outline-danger'>Delete</a>
                                                </td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4'>No staff members found</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>