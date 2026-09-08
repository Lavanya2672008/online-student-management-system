<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {

    header("Location: ../login.php");

    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Dashboard</title>

    <link rel="stylesheet" href="../css/style.css">

</head>

<body>

    <div class="container">

        <h1>Student Dashboard</h1>

        <p>
            Welcome,
            <?php echo htmlspecialchars($_SESSION["username"]); ?>
        </p>

        <hr>

        <h2>My Details</h2>

        <ul>

            <li>
                <a href="profile.php">
                    My Profile
                </a>
            </li>

            <li>
                <a href="attendance.php">
                    My Attendance
                </a>
            </li>

            <li>
                <a href="marks.php">
                    My Marks
                </a>
            </li>

            <li>
                <a href="leaves.php">
                    My Leaves
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
