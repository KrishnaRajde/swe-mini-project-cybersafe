<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

mysqli_report(MYSQLI_REPORT_OFF);

$host = "127.0.0.1";
$user = "root";
$dbname = "cybersafe";

$tries = [[3306, ""], [3307, ""], [8889, "root"], [3306, "root"]];
$conn = false;
foreach ($tries as $try) {
    $c = @mysqli_connect($host, $user, $try[1], "", $try[0]);
    if (!$c) continue;
    if (!$conn) $conn = $c;
    if (mysqli_select_db($c, $dbname)) {
        $conn = $c;
        break;
    }
}

if (!$conn) {
    die("Database connection failed. Please start MySQL from the XAMPP Control Panel and refresh this page.");
}

mysqli_set_charset($conn, "utf8mb4");
mysqli_query($conn, "CREATE DATABASE IF NOT EXISTS $dbname");
mysqli_select_db($conn, $dbname);

mysqli_query($conn, "CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    quiz_score INT DEFAULT 0,
    photo VARCHAR(255) NULL,
    learn_completed TINYINT(1) NOT NULL DEFAULT 0,
    analyzer_completed TINYINT(1) NOT NULL DEFAULT 0,
    generator_completed TINYINT(1) NOT NULL DEFAULT 0,
    phishing_completed TINYINT(1) NOT NULL DEFAULT 0,
    quiz_completed TINYINT(1) NOT NULL DEFAULT 0
)");

$columns = [];
$result = mysqli_query($conn, "SHOW COLUMNS FROM users");
while ($row = mysqli_fetch_assoc($result)) {
    $columns[] = $row['Field'];
}
$needed = [
    "quiz_score" => "INT DEFAULT 0",
    "photo" => "VARCHAR(255) NULL",
    "learn_completed" => "TINYINT(1) NOT NULL DEFAULT 0",
    "analyzer_completed" => "TINYINT(1) NOT NULL DEFAULT 0",
    "generator_completed" => "TINYINT(1) NOT NULL DEFAULT 0",
    "phishing_completed" => "TINYINT(1) NOT NULL DEFAULT 0",
    "quiz_completed" => "TINYINT(1) NOT NULL DEFAULT 0"
];
foreach ($needed as $col => $type) {
    if (!in_array($col, $columns)) {
        mysqli_query($conn, "ALTER TABLE users ADD $col $type");
    }
}

function check_login() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?msg=login_required");
        exit;
    }
}
?>
