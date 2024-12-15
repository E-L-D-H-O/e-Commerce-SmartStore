<?php
session_start();
require '../db/dbinit.php';

// Initialize cart if not already set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}


// Check if a product ID is provided
if (isset($_GET['id'])) {
    $productId = intval($_GET['id']);

    // Fetch product details from the database
    $query = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $product = $result->fetch_assoc();

        // Check if the product is already in the cart
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId]['quantity'] += 1; // Increment quantity
        } else {
            // Add new product to the cart
            $_SESSION['cart'][$productId] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => 1
            ];
        }

        $_SESSION['message'] = "Product added to cart successfully!";
    } else {
        $_SESSION['message'] = "Product not found.";
    }

    $stmt->close();
    $conn->close();
    header("Location: products.php");
    exit;
}

// Handle item removal
if (isset($_GET['remove'])) {
    $id = $_GET['remove'];
    unset($_SESSION['cart'][$id]);
}

// Handle quantity update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_quantity'])) {
    $id = $_POST['product_id'];
    $quantity = max(1, intval($_POST['quantity'])); // Ensure quantity is at least 1
    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]['quantity'] = $quantity;
    }
}

// Calculate cart totals
$subtotal = 0;
foreach ($_SESSION['cart'] as $item) {
    $subtotal += $item['price'] * $item['quantity'];
}
$total = $subtotal; // Add shipping or other fees if necessary
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart</title>
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
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="products.php">Products</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="orderHistory.php">Order History</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Cart Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Cart</h2>
        <div class="row">
            <div class="col-md-8">
                <?php if (!empty($_SESSION['cart'])) { ?>
                    <?php foreach ($_SESSION['cart'] as $id => $item) { ?>
                        <div class="row mb-4 align-items-center border-bottom pb-2">
                            <div class="col-3">
                                <img src="../uploads/<?= htmlspecialchars($item['image']) ?>" alt="Product Image" class="cart-item img-fluid">
                            </div>
                            <div class="col-5">
                                <h5><?= htmlspecialchars($item['name']) ?></h5>
                                <p>$<?= htmlspecialchars($item['price']) ?></p>
                            </div>
                            <div class="col-2">
                                <form method="POST" action="">
                                    <input type="hidden" name="product_id" value="<?= $id ?>">
                                    <input type="number" name="quantity" class="form-control" value="<?= $item['quantity'] ?>" min="1">
                                    <button type="submit" name="update_quantity" class="btn btn-outline-secondary btn-sm mt-2">Update</button>
                                </form>
                            </div>
                            <div class="col-2 text-end">
                                <a href="?remove=<?= $id ?>" class="btn btn-outline-danger btn-sm">Remove</a>
                            </div>
                        </div>
                    <?php } ?>
                <?php } else { ?>
                    <p class="text-center">Your cart is empty!</p>
                <?php } ?>
            </div>

            <!-- Summary Section -->
            <div class="col-md-4">
                <div class="cart-summary">
                    <h5>Cart Summary</h5>
                    <p>Subtotal: $<?= $subtotal ?></p>
                    <p>Shipping: Free</p>
                    <hr>
                    <p><strong>Total: $<?= $total ?></strong></p>
                    <a href="checkout.php" class="btn btn-success w-100">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    </div>
</body>
<!-- Footer -->
<footer class="fixed-bottom bg-dark text-light text-center py-3 mt-5">
    <p class="mb-0">&copy; 2024 smartsphere. All Rights Reserved.</p>
</footer>

</html>