<?php

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {

        $message = "Username and password are required.";

    } elseif (strlen($password) < 8) {

        $message = "Password must contain at least 8 characters.";

    } else {

        $hashed_password = password_hash(
            $password,
            PASSWORD_DEFAULT
        );

        $role = "admin";

        $stmt = $conn->prepare(
            "INSERT INTO users (username, password, role)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param(
            "sss",
            $username,
            $hashed_password,
            $role
        );

        if ($stmt->execute()) {

            $message = "Admin account created successfully.";

        } else {

            $message = "Unable to create account. Username may already exist.";

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Create Admin | SmartStudent</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;

            min-height: 100vh;

            display: flex;

            align-items: center;
            justify-content: center;

            font-family: Arial, sans-serif;

            background: #f8fafc;
        }

        .container {
            width: 100%;
            max-width: 420px;

            padding: 20px;
        }

        .card {
            background: white;

            padding: 35px;

            border-radius: 15px;

            border: 1px solid #e2e8f0;

            box-shadow:
                0 20px 50px rgba(15, 23, 42, .08);
        }

        h1 {
            margin-bottom: 8px;

            text-align: center;
        }

        .subtitle {
            text-align: center;

            color: #64748b;

            margin-bottom: 30px;
        }

        label {
            display: block;

            margin-bottom: 7px;

            font-weight: 600;

            font-size: 14px;
        }

        input {
            width: 100%;

            padding: 13px;

            margin-bottom: 20px;

            border: 1px solid #cbd5e1;

            border-radius: 8px;

            font-size: 15px;
        }

        button {
            width: 100%;

            padding: 13px;

            border: none;

            border-radius: 8px;

            background: #2563eb;

            color: white;

            font-size: 15px;

            font-weight: 600;

            cursor: pointer;
        }

        button:hover {
            background: #1d4ed8;
        }

        .message {
            margin-bottom: 20px;

            padding: 12px;

            background: #eff6ff;

            color: #1d4ed8;

            border-radius: 8px;

            font-size: 14px;

            text-align: center;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card">

        <h1>🎓 SmartStudent</h1>

        <p class="subtitle">
            Create Administrator Account
        </p>


        <?php if (!empty($message)): ?>

            <div class="message">

                <?php echo htmlspecialchars($message); ?>

            </div>

        <?php endif; ?>


        <form method="POST">

            <label>
                Admin Username
            </label>

            <input
                type="text"
                name="username"
                placeholder="Enter admin username"
                required
            >


            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Minimum 8 characters"
                required
            >


            <button type="submit">
                Create Admin Account
            </button>

        </form>

    </div>

</div>

</body>

</html>
