-- Create the database
CREATE DATABASE IF NOT EXISTS attendance_system;

-- Use the database
USE attendance_system;

-- Create the employees table
CREATE TABLE IF NOT EXISTS employees (
    employee_id VARCHAR(20) PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    department VARCHAR(100) NOT NULL
);

-- Create the attendance table
CREATE TABLE IF NOT EXISTS attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_id VARCHAR(20) NOT NULL,
    scan_time DATETIME NOT NULL,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);