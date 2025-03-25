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

// Create requests table if not exists
$create_table = "CREATE TABLE IF NOT EXISTS requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(20) NOT NULL,
    request_type VARCHAR(50) NOT NULL,
    description TEXT,
    status VARCHAR(20) DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
)";

$conn->query($create_table);

$query = "SELECT r.*, e.name, e.department 
          FROM requests r
          JOIN employees e ON r.employee_id = e.employee_id
          WHERE r.status = 'pending'
          ORDER BY r.created_at DESC";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Requests</title>
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
        <h1 class="text-center mb-4">Pending Requests</h1>
        
        <div class="card shadow">
            <div class="card-body">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Department</th>
                                    <th>Request Type</th>
                                    <th>Description</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['name']; ?> (<?php echo $row['employee_id']; ?>)</td>
                                        <td><?php echo $row['department']; ?></td>
                                        <td><?php echo ucfirst($row['request_type']); ?></td>
                                        <td><?php echo $row['description']; ?></td>
                                        <td><?php echo $row['created_at']; ?></td>
                                        <td>
                                            <a href="approve_request.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-success">Approve</a>
                                            <a href="reject_request.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger">Reject</a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        No pending requests found.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>