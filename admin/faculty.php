<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

$search = trim($_GET["search"] ?? "");

if ($search !== "") {

    $stmt = $conn->prepare("
        SELECT id, faculty_id, name, email, phone, department, designation
        FROM faculty
        WHERE faculty_id LIKE ?
           OR name LIKE ?
           OR email LIKE ?
           OR department LIKE ?
           OR designation LIKE ?
        ORDER BY id DESC
    ");

    $searchTerm = "%" . $search . "%";

    $stmt->bind_param(
        "sssss",
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm,
        $searchTerm
    );

    $stmt->execute();
    $facultyResult = $stmt->get_result();

} else {

    $facultyResult = $conn->query("
        SELECT id, faculty_id, name, email, phone, department, designation
        FROM faculty
        ORDER BY id DESC
    ");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Faculty Management | SmartStudent</title>

    <style>

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f7fb;
            color: #1f2937;
        }

        .topbar {
            height: 70px;
            background: #111827;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 35px;
        }

        .brand {
            font-size: 22px;
            font-weight: bold;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 18px;
        }

        .logout {
            background: #ef4444;
            color: white;
            text-decoration: none;
            padding: 9px 15px;
            border-radius: 7px;
            font-size: 14px;
        }

        .container {
            max-width: 1400px;
            margin: 35px auto;
            padding: 0 25px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 25px;
            gap: 20px;
        }

        .page-header h1 {
            font-size: 28px;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #6b7280;
        }

        .add-btn {
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            font-weight: bold;
            white-space: nowrap;
        }

        .search-card {
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }

        .search-form {
            display: flex;
            gap: 10px;
        }

        .search-input {
            flex: 1;
            padding: 12px 14px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            outline: none;
        }

        .search-input:focus {
            border-color: #2563eb;
        }

        .search-btn {
            border: none;
            background: #111827;
            color: white;
            padding: 12px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: bold;
        }

        .clear-btn {
            background: #e5e7eb;
            color: #374151;
            text-decoration: none;
            padding: 12px 18px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
        }

        .table-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .table-header {
            padding: 20px 22px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header h2 {
            font-size: 18px;
        }

        .count {
            color: #6b7280;
            font-size: 14px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        th {
            background: #f9fafb;
            color: #374151;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
            text-align: left;
            padding: 15px 18px;
            border-bottom: 1px solid #e5e7eb;
        }

        td {
            padding: 16px 18px;
            border-bottom: 1px solid #f0f0f0;
            font-size: 14px;
        }

        tr:hover {
            background: #f9fafb;
        }

        .faculty-name {
            font-weight: bold;
            color: #111827;
        }

        .faculty-id {
            font-weight: bold;
            color: #2563eb;
        }

        .department {
            display: inline-block;
            background: #eff6ff;
            color: #1d4ed8;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .designation {
            color: #4b5563;
        }

        .actions {
            display: flex;
            gap: 8px;
        }

        .edit-btn,
        .delete-btn {
            text-decoration: none;
            padding: 7px 11px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: bold;
        }

        .edit-btn {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .delete-btn {
            background: #fee2e2;
            color: #dc2626;
        }

        .empty {
            text-align: center;
            padding: 60px 20px;
            color: #6b7280;
        }

        .empty-icon {
            font-size: 42px;
            margin-bottom: 12px;
        }

        @media (max-width: 700px) {

            .topbar {
                padding: 0 18px;
            }

            .topbar-right span {
                display: none;
            }

            .container {
                margin: 25px auto;
                padding: 0 15px;
            }

            .page-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .add-btn {
                width: 100%;
                text-align: center;
            }

            .search-form {
                flex-direction: column;
            }

            .search-btn,
            .clear-btn {
                width: 100%;
                justify-content: center;
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

        <div>
            <h1>Faculty Management</h1>

            <p>
                Manage faculty members, departments and designations.
            </p>
        </div>

        <a href="add_faculty.php" class="add-btn">
            + Add Faculty
        </a>

    </div>


    <div class="search-card">

        <form method="GET" class="search-form">

            <input
                type="text"
                name="search"
                class="search-input"
                placeholder="Search by faculty ID, name, email, department or designation..."
                value="<?php echo htmlspecialchars($search); ?>"
            >

            <button type="submit" class="search-btn">
                Search
            </button>

            <?php if ($search !== ""): ?>

                <a href="faculty.php" class="clear-btn">
                    Clear
                </a>

            <?php endif; ?>

        </form>

    </div>


    <div class="table-card">

        <div class="table-header">

            <h2>Faculty Directory</h2>

            <span class="count">
                <?php echo $facultyResult->num_rows; ?> faculty member(s)
            </span>

        </div>


        <?php if ($facultyResult->num_rows > 0): ?>

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>Faculty ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Actions</th>

                        </tr>

                    </thead>

                    <tbody>

                    <?php while ($faculty = $facultyResult->fetch_assoc()): ?>

                        <tr>

                            <td>
                                <span class="faculty-id">
                                    <?php echo htmlspecialchars($faculty["faculty_id"]); ?>
                                </span>
                            </td>

                            <td>
                                <span class="faculty-name">
                                    <?php echo htmlspecialchars($faculty["name"]); ?>
                                </span>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($faculty["email"]); ?>
                            </td>

                            <td>
                                <?php echo htmlspecialchars($faculty["phone"] ?: "—"); ?>
                            </td>

                            <td>
                                <span class="department">
                                    <?php echo htmlspecialchars($faculty["department"]); ?>
                                </span>
                            </td>

                            <td>
                                <span class="designation">
                                    <?php echo htmlspecialchars($faculty["designation"]); ?>
                                </span>
                            </td>

                            <td>

                                <div class="actions">

                                    <a
                                        href="edit_faculty.php?id=<?php echo $faculty["id"]; ?>"
                                        class="edit-btn"
                                    >
                                        Edit
                                    </a>

                                    <a
                                        href="delete_faculty.php?id=<?php echo $faculty["id"]; ?>"
                                        class="delete-btn"
                                        onclick="return confirm('Are you sure you want to delete this faculty member?');"
                                    >
                                        Delete
                                    </a>

                                </div>

                            </td>

                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty">

                <div class="empty-icon">
                    👨‍🏫
                </div>

                <h3>No Faculty Found</h3>

                <p>
                    <?php
                    if ($search !== "") {
                        echo "No faculty members match your search.";
                    } else {
                        echo "No faculty members have been added yet.";
                    }
                    ?>
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

</body>

</html>
