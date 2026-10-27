<?php

$CYBERSAFE_MODULES = [
    "learn"     => ["column" => "learn_completed",     "name" => "Safety Tips",        "page" => "learn.php"],
    "analyzer"  => ["column" => "analyzer_completed",  "name" => "Password Analyzer",  "page" => "password_analyzer.php"],
    "generator" => ["column" => "generator_completed", "name" => "Password Generator", "page" => "password_generator.php"],
    "phishing"  => ["column" => "phishing_completed",  "name" => "Phishing Detector",  "page" => "phishing_detector.php"],
    "quiz"      => ["column" => "quiz_completed",      "name" => "Cyber Security Quiz", "page" => "quiz.php"],
];

function get_module_progress($conn, $user_id) {
    global $CYBERSAFE_MODULES;

    $stmt = mysqli_prepare($conn, "SELECT learn_completed, analyzer_completed, generator_completed, phishing_completed, quiz_completed FROM users WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $row = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

    $modules = [];
    $done = 0;
    foreach ($CYBERSAFE_MODULES as $key => $m) {
        $is_done = $row && (int) $row[$m['column']] === 1;
        if ($is_done) {
            $done++;
        }
        $modules[$key] = ["name" => $m['name'], "page" => $m['page'], "done" => $is_done];
    }

    $total = count($CYBERSAFE_MODULES);
    return ["modules" => $modules, "done" => $done, "total" => $total, "all_done" => $done === $total];
}

function mark_module_complete($conn, $user_id, $module) {
    global $CYBERSAFE_MODULES;

    if (!isset($CYBERSAFE_MODULES[$module])) {
        return false;
    }

    $column = $CYBERSAFE_MODULES[$module]['column'];
    $stmt = mysqli_prepare($conn, "UPDATE users SET $column = 1 WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    return mysqli_stmt_execute($stmt);
}

function module_csrf_token() {
    if (empty($_SESSION['module_csrf'])) {
        $_SESSION['module_csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['module_csrf'];
}

function module_csrf_valid($token) {
    return is_string($token) && !empty($_SESSION['module_csrf']) && hash_equals($_SESSION['module_csrf'], $token);
}
?>
