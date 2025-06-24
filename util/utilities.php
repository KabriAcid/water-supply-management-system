<?php
// Helper to get env variable
function env($key, $default = null)
{
    if (isset($_ENV[$key])) return $_ENV[$key];
    if (function_exists('getenv')) {
        $val = getenv($key);
        if ($val !== false) return $val;
    }
    return $default;
}

// Reusable function to get user info by id
function get_user_info($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
