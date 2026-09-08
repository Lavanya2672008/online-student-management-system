<?php
session_start();

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "admin") {
    header("Location: ../login.php");
    exit;
}

require_once "../config/database.php";

// Validate ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: students.php");
    exit;
}

$id = (int) $_GET["id"];

// Check whether student exists
$check = $conn->prepare("SELECT id FROM students WHERE id = ?");
$check->bind_param("i", $id);
$check->execute();

$result = $check->get_result();

if ($result->num_rows === 0) {
    $check->close();
    header("Location: students.php?error=notfound");
    exit;
}

$check->close();

// Delete student
$stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $stmt->close();

    header("Location: students.php?deleted=1");
    exit;
}

$stmt->close();

header("Location: students.php?error=delete");
exit;
?>
