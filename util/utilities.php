<?php
// Reusable function to get user info by id
function get_user_info($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
