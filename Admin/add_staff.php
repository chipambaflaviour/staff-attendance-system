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

// Initialize messages
$success_message = "";
$error_message = "";

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $employee_id = trim($_POST['employee_id']);
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);

    // Validate inputs
    if (empty($employee_id) || empty($name) || empty($department)) {
        $error_message = "All fields are required!";
    } else {
        // Check if employee exists
        $check_sql = "SELECT * FROM employees WHERE employee_id = ?";
        $stmt = $conn->prepare($check_sql);
        $stmt->bind_param("s", $employee_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error_message = "Employee ID '$employee_id' already exists!";
        } else {
            // Insert new staff
            $insert_sql = "INSERT INTO employees (employee_id, name, department) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $stmt->bind_param("sss", $employee_id, $name, $department);

            if ($stmt->execute()) {
                $success_message = "Staff member '$name' added successfully!";
                // Clear form
                $_POST = array();
            } else {
                $error_message = "Error: " . $conn->error;
            }
        }
        $stmt->close();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .card {
            margin-top: 20px;
        }
    </style>
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

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Add New Staff</h2>
                        
                        <?php if ($success_message): ?>
                            <div class="alert alert-success"><?php echo $success_message; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error_message): ?>
                            <div class="alert alert-danger"><?php echo $error_message; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Employee ID</label>
                                <input type="text" name="employee_id" class="form-control" 
                                       value="<?php echo $_POST['employee_id'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?php echo $_POST['name'] ?? ''; ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" 
                                       value="<?php echo $_POST['department'] ?? ''; ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Add Staff</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>