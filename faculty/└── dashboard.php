<?php

session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "faculty") {

    header("Location: ../login.php");

    exit;
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

    <title>Faculty Dashboard | SmartStudent</title>

    <link
        rel="stylesheet"
        href="../css/style.css"
    >

    <style>

        .dashboard-page {
            min-height: 100vh;

            background: #f8fafc;
        }

        .dashboard-nav {
            height: 70px;

            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 0 6%;

            background: white;

            border-bottom: 1px solid #e2e8f0;
        }

        .dashboard-logo {
            font-size: 20px;

            font-weight: 700;
        }

        .dashboard-user {
            display: flex;
            align-items: center;

            gap: 15px;

            font-size: 14px;
        }

        .logout {
            padding: 8px 15px;

            border-radius: 7px;

            background: #fee2e2;

            color: #b91c1c;

            font-weight: 600;
        }

        .dashboard-content {
            padding: 50px 6%;
        }

        .welcome h1 {
            font-size: 32px;

            margin-bottom: 8px;
        }

        .welcome p {
            color: #64748b;

            margin-bottom: 35px;
        }

        .dashboard-grid {
            display: grid;

            grid-template-columns:
                repeat(3, 1fr);

            gap: 20px;
        }

        .dashboard-card {
            background: white;

            padding: 28px;

            border-radius: 12px;

            border: 1px solid #e2e8f0;

            transition: 0.3s;
        }

        .dashboard-card:hover {
            transform: translateY(-3px);

            box-shadow:
                0 10px 30px rgba(15,23,42,.07);
        }

        .dashboard-card-icon {
            font-size: 28px;

            margin-bottom: 15px;
        }

        .dashboard-card h3 {
            margin-bottom: 7px;
        }

        .dashboard-card p {
            color: #64748b;

            font-size: 14px;
        }

        @media (max-width: 700px) {

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .dashboard-content {
                padding: 35px 5%;
            }

        }

    </style>

</head>


<body>

<div class="dashboard-page">


    <header class="dashboard-nav">

        <div class="dashboard-logo">
            🎓 SmartStudent
        </div>


        <div class="dashboard-user">

            <span>
                👨‍🏫
                <?php echo htmlspecialchars($_SESSION["username"]); ?>
            </span>

            <a
                href="../logout.php"
                class="logout"
            >
                Logout
            </a>

        </div>

    </header>


    <main class="dashboard-content">

        <div class="welcome">

            <h1>
                Faculty Dashboard
            </h1>

            <p>
                Manage students, attendance, marks and assignments.
            </p>

        </div>


        <div class="dashboard-grid">


            <div class="dashboard-card">

                <div class="dashboard-card-icon">
                    👨‍🎓
                </div>

                <h3>
                    Students
                </h3>

                <p>
                    View and manage students assigned to your subjects.
                </p>

            </div>


            <div class="dashboard-card">

                <div class="dashboard-card-icon">
                    📊
                </div>

                <h3>
                    Attendance
                </h3>

                <p>
                    Record and monitor student attendance.
                </p>

            </div>


            <div class="dashboard-card">

                <div class="dashboard-card-icon">
                    📝
                </div>

                <h3>
                    Marks
                </h3>

                <p>
                    Enter and manage test and examination marks.
                </p>

            </div>


            <div class="dashboard-card">

                <div class="dashboard-card-icon">
                    📚
                </div>

                <h3>
                    Assignments
                </h3>

                <p>
                    Create assignments and evaluate submissions.
                </p>

            </div>


            <div class="dashboard-card">

                <div class="dashboard-card-icon">
                    📈
                </div>

                <h3>
                    Performance
                </h3>

                <p>
                    Analyze student academic performance.
                </p>

            </div>


            <div class="dashboard-card">

                <div class="dashboard-card-icon">
                    📅
                </div>

                <h3>
                    Leave Requests
                </h3>

                <p>
                    Review and manage student leave applications.
                </p>

            </div>


        </div>

    </main>

</div>

</body>

</html>
