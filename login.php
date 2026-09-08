<?php

session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    if (empty($username) || empty($password)) {

        $error = "Please enter username and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password, role 
             FROM users 
             WHERE username = ?"
        );

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                if ($user["role"] == "admin") {

                    header("Location: admin/dashboard.php");

                } else {

                    header("Location: student/dashboard.php");

                }

                exit();

            } else {

                $error = "Invalid username or password.";

            }

        } else {

            $error = "Invalid username or password.";

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

    <title>Login - Student Details System</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

    <div class="container">

        <h1>Student Details System</h1>

        <h2>Login</h2>

        <?php if (!empty($error)): ?>

            <p style="color: red;">
                <?php echo htmlspecialchars($error); ?>
            </p>

        <?php endif; ?>

        <form method="POST" action="">

            <div>
                <label>Username</label>
                <br>

                <input
                    type="text"
                    name="username"
                    required
                >

            </div>

            <br>

            <div>

                <label>Password</label>
                <br>

                <input
                    type="password"
                    name="password"
                    required
                >

            </div>

            <br>

            <button type="submit">
                Login
            </button>

        </form>

        <br>

        <a href="index.php">
            Back to Home
        </a>

    </div>

</body>

</html>
