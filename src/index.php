<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">SmartSphere</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.html">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.html">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="orderHistory.html">Order History</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.html">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1 class="display-4 fw-bold">Welcome to SmartSphere</h1>
            <p class="lead">Find the latest and greatest products tailored to your needs. Start shopping now!</p>
            <a href="products.html" class="btn btn-primary btn-lg mt-3">Shop Now</a>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Shop by Categories</h2>
        <div class="row categories">
            <div class="col-md-4">
                <div class="card">
                    <img src="https://source.unsplash.com/400x300/?smartphone" class="card-img-top" alt="Smartphones">
                    <div class="card-body text-center">
                        <h5 class="card-title">Smartphones</h5>
                        <a href="products.html" class="btn btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://source.unsplash.com/400x300/?laptop" class="card-img-top" alt="Laptops">
                    <div class="card-body text-center">
                        <h5 class="card-title">Laptops</h5>
                        <a href="products.html" class="btn btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="https://source.unsplash.com/400x300/?accessories" class="card-img-top" alt="Accessories">
                    <div class="card-body text-center">
                        <h5 class="card-title">Accessories</h5>
                        <a href="products.html" class="btn btn-outline-primary">Shop Now</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Call-to-Action Section -->
    <div class="container mt-5">
        <div class="cta-section">
            <h2 class="mb-3">Sign Up and Get Exclusive Discounts!</h2>
            <p>Join our community and stay updated on the latest offers and products.</p>
            <a href="signUp.php" class="btn btn-light btn-lg">Sign Up Now</a>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light text-center py-3 mt-5">
        <p class="mb-0">&copy; 2024 smartsphere. All Rights Reserved.</p>
    </footer>
</body>

</html>