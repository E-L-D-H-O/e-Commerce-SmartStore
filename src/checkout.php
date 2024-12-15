<?php
session_start();
require '../db/dbinit.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Ensure the cart is not empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    $_SESSION['message'] = "Your cart is empty!";
    header("Location: cart.php");
    exit;
}

$userId = $_SESSION['user_id'];
$totalAmount = 0;

// Calculate the total amount
foreach ($_SESSION['cart'] as $item) {
    $totalAmount += $item['price'] * $item['quantity'];
}

// Insert the order into the database
$orderQuery = "INSERT INTO orders (user_id, total_amount) VALUES (?, ?)";
$stmt = $conn->prepare($orderQuery);
$stmt->bind_param("id", $userId, $totalAmount);
$stmt->execute();
$orderId = $stmt->insert_id; // Get the inserted order ID
$stmt->close();

// Insert order items
$orderItemQuery = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($orderItemQuery);

foreach ($_SESSION['cart'] as $item) {
    $stmt->bind_param("iiid", $orderId, $item['id'], $item['quantity'], $item['price']);
    $stmt->execute();
}
$stmt->close();

// Clear the cart
unset($_SESSION['cart']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script>
        // Redirect to the products page after 5 seconds
        setTimeout(function() {
            window.location.href = "products.php";
        }, 5000);
    </script>
</head>

<body>
    <div class="container text-center mt-5">
        <h1 class="display-4">Thank You for Your Order!</h1>
        <p class="lead">Your order has been placed successfully.</p>
        <p>You will be redirected to the products page in a few seconds...</p>
        <a href="products.php" class="btn btn-primary mt-3">Go to Products Now</a>
    </div>
</body>

</html>