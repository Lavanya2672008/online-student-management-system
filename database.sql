CREATE DATABASE IF NOT EXISTS student_details;

USE student_details;


-- ==========================================
-- USERS
-- ==========================================

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,

    username VARCHAR(100) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    role ENUM('admin', 'faculty', 'student') NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- STUDENTS
-- ==========================================

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id VARCHAR(50) NOT NULL UNIQUE,

    name VARCHAR(150) NOT NULL,

    email VARCHAR(150) UNIQUE,

    phone VARCHAR(20),

    course VARCHAR(100),

    year INT,

    section VARCHAR(20),

    date_of_birth DATE,

    address TEXT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- FACULTY
-- ==========================================

CREATE TABLE faculty (
    id INT AUTO_INCREMENT PRIMARY KEY,

    faculty_id VARCHAR(50) NOT NULL UNIQUE,

    name VARCHAR(150) NOT NULL,

    email VARCHAR(150) UNIQUE,

    phone VARCHAR(20),

    department VARCHAR(100),

    designation VARCHAR(100),

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- ==========================================
-- SUBJECTS
-- ==========================================

CREATE TABLE subjects (
    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_code VARCHAR(50) NOT NULL UNIQUE,

    subject_name VARCHAR(150) NOT NULL,

    department VARCHAR(100),

    semester INT,

    faculty_id INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (faculty_id)
        REFERENCES faculty(id)
        ON DELETE SET NULL
);


-- ==========================================
-- ATTENDANCE
-- ==========================================

CREATE TABLE attendance (
    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    subject_id INT NOT NULL,

    total_classes INT DEFAULT 0,

    attended_classes INT DEFAULT 0,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE
);


-- ==========================================
-- MARKS
-- ==========================================

CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    subject_id INT NOT NULL,

    test_name VARCHAR(100) NOT NULL,

    marks_obtained DECIMAL(5,2) DEFAULT 0,

    total_marks DECIMAL(5,2) DEFAULT 100,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE
);


-- ==========================================
-- ASSIGNMENTS
-- ==========================================

CREATE TABLE assignments (
    id INT AUTO_INCREMENT PRIMARY KEY,

    subject_id INT NOT NULL,

    title VARCHAR(200) NOT NULL,

    description TEXT,

    due_date DATE,

    total_marks DECIMAL(5,2) DEFAULT 100,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (subject_id)
        REFERENCES subjects(id)
        ON DELETE CASCADE
);


-- ==========================================
-- ASSIGNMENT SUBMISSIONS
-- ==========================================

CREATE TABLE assignment_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,

    assignment_id INT NOT NULL,

    student_id INT NOT NULL,

    marks_obtained DECIMAL(5,2),

    submission_date DATE,

    status ENUM('Submitted', 'Pending', 'Late')
        DEFAULT 'Pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (assignment_id)
        REFERENCES assignments(id)
        ON DELETE CASCADE,

    FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE
);


-- ==========================================
-- LEAVES
-- ==========================================

CREATE TABLE leaves (
    id INT AUTO_INCREMENT PRIMARY KEY,

    student_id INT NOT NULL,

    from_date DATE NOT NULL,

    to_date DATE NOT NULL,

    reason TEXT,

    status ENUM('Pending', 'Approved', 'Rejected')
        DEFAULT 'Pending',

    approved_by INT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (student_id)
        REFERENCES students(id)
        ON DELETE CASCADE,

    FOREIGN KEY (approved_by)
        REFERENCES users(id)
        ON DELETE SET NULL
);
