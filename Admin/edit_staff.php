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
$success = $error = '';

// Fetch current data
$stmt = $conn->prepare("SELECT * FROM employees WHERE employee_id = ?");
$stmt->bind_param("s", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$employee = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_id = trim($_POST['employee_id']);
    $name = trim($_POST['name']);
    $department = trim($_POST['department']);

    if (empty($new_id) || empty($name) || empty($department)) {
        $error = "All fields are required!";
    } else {
        $update_stmt = $conn->prepare("UPDATE employees SET employee_id=?, name=?, department=? WHERE employee_id=?");
        $update_stmt->bind_param("ssss", $new_id, $name, $department, $employee_id);
        
        if ($update_stmt->execute()) {
            $success = "Staff updated successfully!";
            $employee_id = $new_id; // Update displayed ID
            // Refresh employee data
            $stmt->execute();
            $result = $stmt->get_result();
            $employee = $result->fetch_assoc();
        } else {
            $error = "Error updating staff: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Staff</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="dashboard.php">Staff System</a>
            <a href="dashboard.php" class="btn btn-sm btn-outline-light">Back to Dashboard</a>
        </div>
    </nav>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center mb-4">Edit Staff Member</h2>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php endif; ?>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label class="form-label">Employee ID</label>
                                <input type="text" name="employee_id" class="form-control" 
                                       value="<?= htmlspecialchars($employee['employee_id'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" 
                                       value="<?= htmlspecialchars($employee['name'] ?? '') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Department</label>
                                <input type="text" name="department" class="form-control" 
                                       value="<?= htmlspecialchars($employee['department'] ?? '') ?>" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Update Staff</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>