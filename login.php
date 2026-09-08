<?php
session_start();

require_once "config/database.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (empty($username) || empty($password)) {

        $error = "Please enter both username and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, username, password, role
             FROM users
             WHERE username = ?
             LIMIT 1"
        );

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows === 1) {

            $user = $result->fetch_assoc();

            if (password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                switch ($user["role"]) {

                    case "admin":
                        header("Location: admin/dashboard.php");
                        break;

                    case "faculty":
                        header("Location: faculty/dashboard.php");
                        break;

                    case "student":
                        header("Location: student/dashboard.php");
                        break;

                    default:
                        session_destroy();

                        $error = "Invalid user role.";
                        break;
                }

                exit;

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

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login | SmartStudent</title>

    <link rel="stylesheet" href="css/style.css">

    <style>

        body {
            min-height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            background:
                radial-gradient(
                    circle at 10% 10%,
                    #dbeafe,
                    transparent 30%
                ),
                radial-gradient(
                    circle at 90% 90%,
                    #e0f2fe,
                    transparent 30%
                ),
                #f8fafc;
        }

        .login-container {
            width: 100%;
            max-width: 440px;

            padding: 20px;
        }

        .login-card {
            background: white;

            border: 1px solid #e2e8f0;

            border-radius: 18px;

            padding: 40px;

            box-shadow:
                0 25px 60px rgba(15, 23, 42, 0.10);
        }

        .login-logo {
            text-align: center;

            margin-bottom: 30px;
        }

        .login-logo .icon {
            font-size: 42px;

            margin-bottom: 10px;
        }

        .login-logo h1 {
            font-size: 25px;

            margin-bottom: 5px;
        }

        .login-logo p {
            color: #64748b;

            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;

            margin-bottom: 7px;

            font-size: 14px;

            font-weight: 600;

            color: #334155;
        }

        .form-group input {
            width: 100%;

            padding: 13px 14px;

            border: 1px solid #cbd5e1;

            border-radius: 8px;

            outline: none;

            font-size: 15px;

            transition: 0.2s;
        }

        .form-group input:focus {
            border-color: #2563eb;

            box-shadow:
                0 0 0 3px rgba(37, 99, 235, 0.10);
        }

        .login-submit {
            width: 100%;

            border: none;

            cursor: pointer;

            padding: 14px;

            background: #2563eb;

            color: white;

            border-radius: 8px;

            font-size: 15px;

            font-weight: 600;

            transition: 0.3s;
        }

        .login-submit:hover {
            background: #1d4ed8;

            transform: translateY(-1px);
        }

        .error-message {
            padding: 12px 14px;

            margin-bottom: 20px;

            background: #fef2f2;

            color: #b91c1c;

            border: 1px solid #fecaca;

            border-radius: 8px;

            font-size: 14px;
        }

        .back-home {
            text-align: center;

            margin-top: 22px;
        }

        .back-home a {
            color: #2563eb;

            font-size: 14px;

            font-weight: 600;
        }

        .security-note {
            margin-top: 25px;

            padding-top: 20px;

            border-top: 1px solid #e2e8f0;

            text-align: center;

            color: #94a3b8;

            font-size: 12px;
        }

    </style>

</head>


<body>

<div class="login-container">

    <div class="login-card">

        <div class="login-logo">

            <div class="icon">🎓</div>

            <h1>SmartStudent</h1>

            <p>
                Academic Management Portal
            </p>

        </div>


        <?php if (!empty($error)): ?>

            <div class="error-message">

                <?php echo htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <form method="POST" action="">

            <div class="form-group">

                <label for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter your username"
                    autocomplete="username"
                    required
                >

            </div>


            <div class="form-group">

                <label for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <button
                type="submit"
                class="login-submit"
            >
                Login to Portal →
            </button>

        </form>


        <div class="back-home">

            <a href="index.php">
                ← Back to Home
            </a>

        </div>


        <div class="security-note">

            🔒 Secure role-based authentication

        </div>

    </div>

</div>

</body>

</html>
