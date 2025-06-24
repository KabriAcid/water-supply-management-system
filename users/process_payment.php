<?php
session_start();
require_once __DIR__ . '/../config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$quantity = intval($_POST['quantity'] ?? 0);
$delivery_address = trim($_POST['delivery_address'] ?? '');
$delivery_date = $_POST['delivery_date'] ?? '';
$payment_reference = $_POST['payment_reference'] ?? '';
$amount = floatval($_POST['amount'] ?? 0);

if ($quantity < 1000 || !$delivery_address || !$payment_reference || $amount <= 0) {
    // Show error and stop
    echo "<script>alert('Invalid order/payment details.');window.location.href='place_order.php';</script>";
    exit;
}

// Insert order
$stmt = $pdo->prepare("INSERT INTO orders (user_id, quantity, delivery_address, delivery_date, status, created_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
$result = $stmt->execute([
    $user_id,
    $quantity,
    $delivery_address,
    $delivery_date ? $delivery_date : null
]);
if ($result) {
    $order_id = $pdo->lastInsertId();
    // Insert transaction
    $stmt2 = $pdo->prepare("INSERT INTO transactions (user_id, order_id, amount, status, payment_reference, payment_method) VALUES (?, ?, ?, 'successful', ?, 'Flutterwave')");
    $stmt2->execute([
        $user_id,
        $order_id,
        $amount,
        $payment_reference
    ]);
    echo "<script>window.location.href='user-dashboard.php';</script>";
    exit;
} else {
    echo "<script>alert('Failed to place order. Please try again.');window.location.href='place_order.php';</script>";
    exit;
}
