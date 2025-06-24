<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}
require_once __DIR__ . '/../config/database.php';
require __DIR__ . '/../components/header.php';

$error = '';
$success = '';

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quantity = intval($_POST['quantity'] ?? 0);
    $delivery_address = htmlspecialchars(trim($_POST['delivery_address'] ?? ''));
    $delivery_date = $_POST['delivery_date'] ?? '';
    
    // if quantity is less than 1000 litres
    if ($quantity < 1000) {
        $error = "Minimum order quantity is 1000 litres.";
    }

    // if quantity is not a number 
    if (!is_numeric($quantity)) {
        $error = "Quantity must be a valid number.";
    }


    if ($quantity <= 0 || !$delivery_address) {
        $error = "All fields are required and quantity must be greater than 0.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, quantity, delivery_address, delivery_date, status, created_at) VALUES (?, ?, ?, ?, 'Pending', NOW())");
        $result = $stmt->execute([
            $_SESSION['user_id'],
            $quantity,
            $delivery_address,
            $delivery_date ? $delivery_date : null
        ]);
        if ($result) {
            $success = "Order placed successfully!";
        } else {
            $error = "Failed to place order. Please try again.";
        }
    }
}
?>

<body>
    <?php require_once __DIR__ . '/../components/sidebar.php'; ?>
    <div class="flex-grow-1" style="margin-left: var(--sidebar-width); min-width: 0; padding: 15px;">
        <?php require_once __DIR__ . '/../components/dashboard-navbar.php'; ?>
        <div class="container py-5 mt-5" style="max-width: 600px;">
            <h2 class="fw-bold mb-4">Place New Water Order</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger text-center fw-bold"><?= htmlspecialchars($error) ?></div>
            <?php elseif ($success): ?>
                <div class="alert alert-success text-center fw-bold"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            <form method="POST" autocomplete="off">
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity (Litres)</label>
                    <input type="number" class="form-control" placeholder="e.g 2000" id="quantity" name="quantity" min="1" required inputmode="numeric">
                </div>
                <div class="mb-3">
                    <label for="delivery_address" class="form-label">Delivery Address</label>
                    <textarea class="form-control" placeholder="e.g No. 9 Sardauna Estate Katsina" id="delivery_address" name="delivery_address" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="delivery_date" class="form-label">Preferred Delivery Date (optional)</label>
                    <input type="date" class="form-control" id="delivery_date" name="delivery_date">
                </div>
                <button type="submit" class="btn btn-primary w-100">Place Order</button>
            </form>
        </div>
    </div>
</body>

</html>