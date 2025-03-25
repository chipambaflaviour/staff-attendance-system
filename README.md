Staff Attendance Tracking System
📌 Staff Attendance Tracking System
A QR Code-Based Attendance System for Lusaka South College, developed using PHP, MySQL, JavaScript, and Bootstrap. This system allows staff to scan their IDs to mark attendance, and admin users can manage attendance records through a web-based dashboard.
📜 Features
✅ QR Code Scanning – Staff members scan their IDs to log attendance.
 ✅ Real-Time Attendance Logging – Attendance is stored in a MySQL database.
 ✅ Admin Dashboard – Admin users can view, manage, and export attendance records.
 ✅ User Authentication – Secure admin login with hashed passwords.
 ✅ Dynamic Reports – View attendance history with staff details (ID, name, position, department).
 ✅ Remote Access – The system can be accessed from mobile devices over a local network.
🛠 Installation Instructions
1️⃣ Requirements
WampServer/XAMPP (for PHP & MySQL)


Web Browser (Chrome, Firefox, Edge)


Code Editor (VS Code, Sublime Text, Notepad++)


2️⃣ Clone the Repository
Run the following command in your terminal:
bash
CopyEdit
git clone https://github.com/your-username/staff-attendance-system.git

Replace your-username with your GitHub username.
3️⃣ Setup the Database
Open phpMyAdmin (http://localhost/phpmyadmin/).


Create a new database:

 sql
CopyEdit
CREATE DATABASE staff_attendance;


Import the provided SQL file (staff_attendance.sql).


Update db_connect.php with your database credentials.


📌 Usage
🔹 Staff Attendance (Scanning QR Code)
Open the system in your browser:

 arduino
CopyEdit
http://localhost/staff_attendance_system/


Scan your QR code using the built-in scanner.


The system records attendance with a timestamp.


🔹 Admin Access (Manage Attendance)
Login as admin:
username: admin
Password:admin123
 arduino
CopyEdit
http://localhost/staff_attendance_system/admin_login.php


View attendance records, generate reports, and export data.


🚀 Future Enhancements
🔹 Export reports to Excel or PDF.
 🔹 Mobile-responsive UI for better accessibility.
 🔹 Admin controls to manage staff records.
 🔹 Auto-email notifications for attendance tracking.
📜 License
This project is open-source under the MIT License. Feel free to contribute! 🎉
🤝 Contributing
Want to improve this project? Fork it, make your changes, and submit a pull request!
🔗 GitHub Repository: Your Repo Link
💡 Need help? Feel free to open an issue or contact me

