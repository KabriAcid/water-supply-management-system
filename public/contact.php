<?php
require_once __DIR__ . '/../components/main-header.php';

$name = $email = $message = '';
$success = $error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (!$name || !$email || !$message) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Here you would normally send an email or store the message
        // For demo, just show a success message
        $success = "Thank you for contacting us, $name! We'll get back to you soon.";
        $name = $email = $message = '';
    }
}
?>

<body>
    <?php require_once __DIR__ . '/../components/navbar.php'; ?>
    <div class="container py-5" style="max-width: 600px;">
        <h2 class="fw-bold mb-4 text-center">Contact Us</h2>
        <?php if ($success): ?>
            <div class="alert alert-success text-center"><?= htmlspecialchars($success) ?></div>
        <?php elseif ($error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <form method="POST" class="shadow p-4 rounded bg-white">
            <div class="mb-3">
                <label for="name" class="form-label fw-semibold">Your Name</label>
                <input type="text" placeholder="Full Name" class="form-control" id="name" name="name" required value="<?= htmlspecialchars($name) ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Your Email</label>
                <input type="email" placeholder="Email Address" class="form-control" id="email" name="email" required value="<?= htmlspecialchars($email) ?>">
            </div>
            <div class="mb-3">
                <label for="message" class="form-label fw-semibold">Message</label>
                <textarea class="form-control" id="message" placeholder="Your message goes here..." name="message" rows="5" required><?= htmlspecialchars($message) ?></textarea>
            </div>
            <button type="submit" class="btn gradient-btn w-100">Send Message</button>
        </form>
        <div class="text-center mt-4">
            <p class="mb-1"><strong>Email:</strong> <a href="mailto:support@watersupply.com">support@watersupply.com</a></p>
            <p class="mb-0"><strong>Phone:</strong> <a href="tel:+2347037943396">+123 456 7890</a></p>
        </div>
    </div>
    <?php require_once __DIR__ . '/../components/footer.php'; ?>
</body>

</html>