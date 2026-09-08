<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = trim($_POST["student_id"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);
    $year = intval($_POST["year"]);
    $section = trim($_POST["section"]);
    $date_of_birth = $_POST["date_of_birth"];
    $address = trim($_POST["address"]);

    if (empty($student_id) || empty($name)) {

        $error = "Student ID and Name are required.";

    } else {

        $stmt = $conn->prepare(
            "INSERT INTO students
            (student_id, name, email, phone, course, year, section, date_of_birth, address)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssssis ss",
            $student_id,
            $name,
            $email,
            $phone,
            $course,
            $year,
            $section,
            $date_of_birth,
            $address
        );

        if ($stmt->execute()) {

            $message = "Student added successfully.";

        } else {

            $error = "Unable to add student. Student ID may already exist.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Student</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="container">

    <h1>Add Student</h1>

    <p>
        <a href="dashboard.php">Dashboard</a>
        |
        <a href="students.php">Students</a>
        |
        <a href="../logout.php">Logout</a>
    </p>

    <hr>

    <?php if (!empty($message)): ?>

        <p style="color: green;">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>

    <?php if (!empty($error)): ?>

        <p style="color: red;">
            <?php echo htmlspecialchars($error); ?>
        </p>

    <?php endif; ?>

    <form method="POST">

        <p>
            <label>Student ID</label><br>

            <input
                type="text"
                name="student_id"
                required
            >
        </p>

        <p>
            <label>Full Name</label><br>

            <input
                type="text"
                name="name"
                required
            >
        </p>

        <p>
            <label>Email</label><br>

            <input
                type="email"
                name="email"
            >
        </p>

        <p>
            <label>Phone</label><br>

            <input
                type="text"
                name="phone"
            >
        </p>

        <p>
            <label>Course</label><br>

            <input
                type="text"
                name="course"
            >
        </p>

        <p>
            <label>Year</label><br>

            <input
                type="number"
                name="year"
                min="1"
                max="6"
            >
        </p>

        <p>
            <label>Section</label><br>

            <input
                type="text"
                name="section"
            >
        </p>

        <p>
            <label>Date of Birth</label><br>

            <input
                type="date"
                name="date_of_birth"
            >
        </p>

        <p>
            <label>Address</label><br>

            <textarea
                name="address"
                rows="4"
            ></textarea>
        </p>

        <button type="submit">
            Add Student
        </button>

    </form>

</div>

</body>

</html>
