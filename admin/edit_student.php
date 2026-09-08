<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$student = null;
$error = "";
$success = "";

// Validate student ID from URL
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: students.php");
    exit;
}

$id = (int) $_GET["id"];

// Fetch student details
$stmt = $conn->prepare("
    SELECT id, student_id, name, email, phone, course, year, section,
           date_of_birth, address
    FROM students
    WHERE id = ?
");

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$student = $result->fetch_assoc();

$stmt->close();

if (!$student) {
    header("Location: students.php");
    exit;
}

// Update student
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $student_id = trim($_POST["student_id"]);
    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $course = trim($_POST["course"]);
    $year = (int) $_POST["year"];
    $section = trim($_POST["section"]);
    $date_of_birth = trim($_POST["date_of_birth"]);
    $address = trim($_POST["address"]);

    if (
        empty($student_id) ||
        empty($name) ||
        empty($email) ||
        empty($course) ||
        empty($section)
    ) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {

        // Check duplicate student ID
        $check = $conn->prepare("
            SELECT id
            FROM students
            WHERE student_id = ? AND id != ?
        ");

        $check->bind_param("si", $student_id, $id);
        $check->execute();

        $duplicate = $check->get_result()->num_rows > 0;
        $check->close();

        if ($duplicate) {
            $error = "Student ID already exists.";
        } else {

            $stmt = $conn->prepare("
                UPDATE students
                SET
                    student_id = ?,
                    name = ?,
                    email = ?,
                    phone = ?,
                    course = ?,
                    year = ?,
                    section = ?,
                    date_of_birth = ?,
                    address = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "sssssisssi",
                $student_id,
                $name,
                $email,
                $phone,
                $course,
                $year,
                $section,
                $date_of_birth,
                $address,
                $id
            );

            if ($stmt->execute()) {
                $stmt->close();

                header("Location: students.php?updated=1");
                exit;
            } else {
                $error = "Unable to update student details.";
            }

            $stmt->close();
        }
    }

    // Keep entered values if validation fails
    $student["student_id"] = $student_id;
    $student["name"] = $name;
    $student["email"] = $email;
    $student["phone"] = $phone;
    $student["course"] = $course;
    $student["year"] = $year;
    $student["section"] = $section;
    $student["date_of_birth"] = $date_of_birth;
    $student["address"] = $address;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Student | SmartStudent</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .topbar {
            background: #111827;
            color: white;
            padding: 18px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .topbar a {
            color: white;
            text-decoration: none;
        }

        .logout {
            background: #ef4444;
            padding: 9px 15px;
            border-radius: 7px;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
        }

        .page-header h1 {
            font-size: 28px;
        }

        .back-btn {
            text-decoration: none;
            color: #2563eb;
            font-weight: bold;
        }

        .card {
            background: white;
            border-radius: 14px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .full {
            grid-column: span 2;
        }

        label {
            margin-bottom: 8px;
            font-weight: 600;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: #2563eb;
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .required {
            color: #ef4444;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 13px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .buttons {
            margin-top: 25px;
            display: flex;
            gap: 12px;
        }

        .btn {
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #e5e7eb;
            color: #374151;
        }

        @media (max-width: 700px) {
            .topbar {
                padding: 15px 20px;
            }

            .topbar-right {
                gap: 10px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full {
                grid-column: span 1;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }

            .card {
                padding: 20px;
            }
        }
    </style>
</head>

<body>

<div class="topbar">

    <div class="brand">
        SmartStudent
    </div>

    <div class="topbar-right">
        <span>
            Admin: <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </span>

        <a href="../logout.php" class="logout">
            Logout
        </a>
    </div>

</div>

<div class="container">

    <div class="page-header">

        <h1>Edit Student</h1>

        <a href="students.php" class="back-btn">
            ← Back to Students
        </a>

    </div>

    <div class="card">

        <?php if (!empty($error)): ?>
            <div class="error">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

            <div class="form-grid">

                <div class="form-group">
                    <label>
                        Student ID <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="student_id"
                        value="<?php echo htmlspecialchars($student["student_id"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>
                        Full Name <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="<?php echo htmlspecialchars($student["name"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>
                        Email <span class="required">*</span>
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="<?php echo htmlspecialchars($student["email"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Phone</label>

                    <input
                        type="text"
                        name="phone"
                        value="<?php echo htmlspecialchars($student["phone"]); ?>"
                    >
                </div>

                <div class="form-group">
                    <label>
                        Course <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="course"
                        value="<?php echo htmlspecialchars($student["course"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Year</label>

                    <select name="year">

                        <option value="1" <?php echo ($student["year"] == 1) ? "selected" : ""; ?>>
                            1st Year
                        </option>

                        <option value="2" <?php echo ($student["year"] == 2) ? "selected" : ""; ?>>
                            2nd Year
                        </option>

                        <option value="3" <?php echo ($student["year"] == 3) ? "selected" : ""; ?>>
                            3rd Year
                        </option>

                        <option value="4" <?php echo ($student["year"] == 4) ? "selected" : ""; ?>>
                            4th Year
                        </option>

                    </select>
                </div>

                <div class="form-group">
                    <label>
                        Section <span class="required">*</span>
                    </label>

                    <input
                        type="text"
                        name="section"
                        value="<?php echo htmlspecialchars($student["section"]); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label>Date of Birth</label>

                    <input
                        type="date"
                        name="date_of_birth"
                        value="<?php echo htmlspecialchars($student["date_of_birth"]); ?>"
                    >
                </div>

                <div class="form-group full">
                    <label>Address</label>

                    <textarea name="address"><?php echo htmlspecialchars($student["address"]); ?></textarea>
                </div>

            </div>

            <div class="buttons">

                <button type="submit" class="btn btn-primary">
                    Update Student
                </button>

                <a href="students.php" class="btn btn-secondary">
                    Cancel
                </a>

            </div>

        </form>

    </div>

</div>

</body>
</html>
