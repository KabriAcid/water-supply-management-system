<?php require __DIR__ . '/components/main-header.php'; ?>

<body>
    <?php require __DIR__ . '/components/navbar.php'; ?>
    <div class="container-fluid p-0" style="background: #f8fbff;">
        <!-- Hero Section -->
        <section class="hero-section d-flex align-items-center justify-content-center" id="main-container">
            <div class="container text-center">
                <h1 style="font-size: 4rem;" class="gradient-text">Water Supply Management System</h1>
                <p class="lead mb-4 fw-bold" style="color:rgb(0, 32, 54);">Streamline your water supply operations with our comprehensive and user-friendly system.</p>
                <a href="#how-it-works" style="background-color: #00c6fb;" class="btn gradient-btn">Get Started</a>
            </div>
        </section>

        <!-- How It Works -->
        <section id="how-it-works" class="py-5" style="background: #eaf6fb;">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold">How It Works</h2>
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4 text-center">
                        <img src="assets/img/register.jpg" alt="Register" class="mb-3" style="height:80px;">
                        <h5>Register</h5>
                        <p>Create your free account to get started.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="assets/img/order.jpg" alt="Place Order" class="mb-3" style="height:80px;">
                        <h5>Place Order</h5>
                        <p>Order water tanks easily from your dashboard.</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <img src="assets/img/delivery.jpg" alt="Get Delivery" class="mb-3" style="height:80px;">
                        <h5>Get Delivery</h5>
                        <p>We deliver water to your doorstep, fast and reliable.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features -->
        <section class="py-5" style="background: #fff;">
            <div class="container">
                <h2 class="text-center mb-5 fw-bold">Features</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="assets/img/secure.png" class="card-img-top" alt="Secure">
                            <div class="card-body text-center">
                                <h5 class="card-title">Simple & Secure</h5>
                                <p class="card-text">Healthy and pure for the benefit of your health.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="assets/img/history.jpg" class="card-img-top" alt="Order History">
                            <div class="card-body text-center">
                                <h5 class="card-title">Order History</h5>
                                <p class="card-text">Track your orders and view your order history anytime.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 shadow-sm border-0">
                            <img src="assets/img/support.jpg" class="card-img-top" alt="Support">
                            <div class="card-body text-center">
                                <h5 class="card-title">24/7 Support</h5>
                                <p class="card-text">Our team is always ready to help you with your water needs, anytime.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- About Us -->
        <section class="py-5" style="background: #eaf6fb;">
            <div class="container">
                <h2 class="text-center mb-4 fw-bold">About Us</h2>
                <div class="row justify-content-center">
                    <div class="col-md-8 text-center">
                        <img src="assets/img/logo.png" alt="About Us" class="mb-3" style="height:200px;width:200px;">
                        <p>
                            We are dedicated to providing reliable and efficient water supply solutions for homes and businesses. Our platform makes it easy to order and manage your water needs online.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact -->
        <section class="py-5" style="background: #fff;">
            <div class="container">
                <h2 class="text-center mb-4 fw-bold">Contact</h2>
                <div class="row justify-content-center">
                    <div class="col-md-6 text-center">
                        <img src="assets/img/contact.png" alt="Contact" class="mb-3" style="height:60px;">
                        <p>Email: <a href="mailto:support@aqualink.com">support@aqualink.com</a></p>
                        <p>Phone: <a href="tel:+2347037943396">070 3794 3396</a></p>
                        <p class="text-xs text-secondary">Copyright @Dreamerscode</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php require __DIR__ . '/components/footer.php'; ?>
</body>

</html>