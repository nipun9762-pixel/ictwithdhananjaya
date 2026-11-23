CREATE DATABASE IF NOT EXISTS tuition_lms;
USE tuition_lms;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'teacher') NOT NULL DEFAULT 'student',
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE classes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    description TEXT,
    fee DECIMAL(10,2) NOT NULL,
    duration_days INT DEFAULT 30,
    thumbnail VARCHAR(255) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE enrollments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    class_id INT NOT NULL,
    payment_slip VARCHAR(255),
    amount DECIMAL(10,2),
    status ENUM('pending', 'approved', 'rejected', 'expired') DEFAULT 'pending',
    start_date DATE NULL,
    end_date DATE NULL,
    approved_by INT NULL,
    approved_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL
);

CREATE TABLE class_content (
    id INT AUTO_INCREMENT PRIMARY KEY,
    class_id INT NOT NULL,
    type ENUM('recording', 'note', 'notice', 'live_link') NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    file_path VARCHAR(255),
    link VARCHAR(500),
    uploaded_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Default Teacher Account
-- Email: teacher@tuition.com
-- Password: teacher123
INSERT INTO users (name, email, password, role) VALUES 
('Tuition Sir', 'teacher@tuition.com', '$2y$10$cYRGb0zJRDqD3p0pcWxKN./5MOWMnH54KpmVDgGnDXCzYA83SxnE2', 'teacher');
