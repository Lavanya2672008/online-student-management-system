<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {

    header("Location: ../login.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container">

        <h1>Admin Dashboard</h1>

        <p>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </p>

        <hr>

        <h2>Management</h2>

        <ul>

            <li>
                <a href="students.php">
                    Students
                </a>
            </li>

            <li>
                <a href="attendance.php">
                    Attendance
                </a>
            </li>

            <li>
                <a href="marks.php">
                    Marks
                </a>
            </li>

            <li>
                <a href="leaves.php">
                    Leave Requests
                </a>
            </li>

        </ul>

        <br>

        <a href="../logout.php">
            Logout
        </a>

    </div>

</body>

</html>
