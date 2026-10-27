<?php
include "includes/db.php";
include "includes/modules.php";

header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["ok" => false, "error" => "POST required"]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["ok" => false, "error" => "Login required"]);
    exit;
}

if (!module_csrf_valid($_POST['csrf'] ?? null)) {
    http_response_code(403);
    echo json_encode(["ok" => false, "error" => "Invalid request token"]);
    exit;
}

$module = $_POST['module'] ?? "";
$allowed = ["analyzer", "generator"];

if (!in_array($module, $allowed, true)) {
    http_response_code(400);
    echo json_encode(["ok" => false, "error" => "Unknown module"]);
    exit;
}

$ok = mark_module_complete($conn, (int) $_SESSION['user_id'], $module);
echo json_encode(["ok" => $ok]);
?>
