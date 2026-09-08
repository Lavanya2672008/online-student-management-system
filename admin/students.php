<?php

session_start();

require_once "../config/database.php";

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit();
}

$sql = "SELECT * FROM students ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Students - Admin</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

<div class="container">

    <h1>Students</h1>

    <p>
        Welcome,
        <?php echo htmlspecialchars($_SESSION["username"]); ?>
    </p>

    <hr>

    <p>
        <a href="dashboard.php">Dashboard</a>
        |
        <a href="add_student.php">Add Student</a>
        |
        <a href="../logout.php">Logout</a>
    </p>

    <br>

    <?php if ($result && $result->num_rows > 0): ?>

        <table border="1" cellpadding="10" cellspacing="0">

            <thead>

                <tr>

                    <th>Student ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Section</th>

                </tr>

            </thead>

            <tbody>

                <?php while ($student = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo htmlspecialchars($student["student_id"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($student["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($student["email"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($student["phone"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($student["course"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($student["year"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($student["section"]); ?>
                        </td>

                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    <?php else: ?>

        <p>No students found.</p>

    <?php endif; ?>

</div>

</body>

</html>
