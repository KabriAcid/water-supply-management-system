<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../components/header.php';

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


$error = '';
$success = '';

// Fetch price per litre from settings
$price_per_litre = 1.00; // default
$stmt = $pdo->query("SELECT price_per_litre FROM settings LIMIT 1");
if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $price_per_litre = $row['price_per_litre'];
}

// Get user info for payment
$user_id = $_SESSION['user_id'];
$user = get_user_info($pdo, $user_id);

// Only handle order creation here if redirected from payment processor
if (isset($_GET['success']) && $_GET['success'] === '1') {
    $success = "Order placed and payment successful!";
} elseif (isset($_GET['cancel']) && $_GET['cancel'] === '1') {
    $error = "Payment was cancelled.";
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
            <form id="order-form" method="POST" autocomplete="off" action="process_payment.php">
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
                <input type="hidden" name="payment_reference" id="payment_reference">
                <input type="hidden" name="amount" id="amount">
                <button type="submit"
                    class="btn gradient-btn w-100"
                    id="pay-btn"
                    data-price="<?= htmlspecialchars($price_per_litre) ?>">
                    Place Order & Pay
                </button>
            </form>
        </div>
    </div>

    <!-- Flutterwave JS -->
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        document.getElementById('order-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const quantity = parseInt(document.getElementById('quantity').value);
            const pricePerLitre = parseFloat(document.getElementById('pay-btn').getAttribute('data-price'));
            const amount = quantity * pricePerLitre;
            const deliveryAddress = document.getElementById('delivery_address').value.trim();

            if (!quantity || quantity < 1000 || !deliveryAddress) {
                this.submit(); // Let PHP handle validation errors
                return;
            }

            // Start Flutterwave payment
            FlutterwaveCheckout({
                public_key: "<?= env('FLUTTERWAVE_PUBLIC_KEY') ?>",
                tx_ref: "WSS-" + Date.now(),
                amount: amount,
                currency: "NGN",
                payment_options: "card,banktransfer",
                customer: {
                    email: "<?= htmlspecialchars($user['email'] ?? 'user@example.com') ?>",
                    name: "<?= htmlspecialchars($user['name'] ?? 'User') ?>"
                },
                customizations: {
                    title: "Water Supply Payment",
                    description: "Payment for water order",
                    logo: "/water-supply-system/assets/img/logo.png"
                },
                callback: function(response) {
                    if (response.status === "successful") {
                        // Send data to process_payment.php via POST
                        const form = document.getElementById('order-form');
                        document.getElementById('payment_reference').value = response.transaction_id || response.tx_ref;
                        document.getElementById('amount').value = amount;
                        form.action = "process_payment.php";
                        form.submit();
                    } else {
                        alert("Payment was not successful. Please try again.");
                    }
                },
                onclose: function() {
                    window.location.href = "user-dashboard.php";
                }
            });
        });
    </script>
</body>

</html>