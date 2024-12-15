<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
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
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">Order History</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Order History Section -->
    <div class="container mt-5">
        <h2 class="text-center mb-4">Your Order History</h2>

        <?php
        session_start();
        require '../db/dbinit.php';

        // Check if the user is logged in
        if (!isset($_SESSION['user_id'])) {
            header("Location: login.php");
            exit;
        }

        $userId = $_SESSION['user_id'];

        // Fetch orders for the logged-in user
        $orderQuery = "SELECT * FROM orders WHERE user_id = ? ORDER BY order_date DESC";
        $stmt = $conn->prepare($orderQuery);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $ordersResult = $stmt->get_result();

        if ($ordersResult->num_rows > 0) {
            while ($order = $ordersResult->fetch_assoc()) {
                $orderId = $order['id'];
                $totalAmount = $order['total_amount'];
                $createdAt = $order['order_date'];

                // Fetch order items
                $itemQuery = "SELECT oi.*, p.name AS product_name FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?";
                $itemStmt = $conn->prepare($itemQuery);
                $itemStmt->bind_param("i", $orderId);
                $itemStmt->execute();
                $itemsResult = $itemStmt->get_result();

                echo "<div class='order-card mb-4'>
                        <div class='order-header d-flex justify-content-between align-items-center'>
                            <div>
                                <h6>Order #$orderId</h6>
                                <span class='small'>Placed on: $createdAt</span>
                            </div>
                        </div>
                        <div class='order-details d-flex justify-content-between align-items-center'>
                            <div>
                                <p class='mb-1'><strong>Total:</strong> $$totalAmount</p>
                                <p class='mb-0'><strong>Items:</strong> " . $itemsResult->num_rows . "</p>
                            </div>
                            <button class='btn btn-outline-primary view-details-btn' type='button' data-bs-toggle='collapse' data-bs-target='#orderDetails$orderId' aria-expanded='false' aria-controls='orderDetails$orderId'>
                                View Details
                            </button>
                        </div>
                        <div class='collapse collapse-content' id='orderDetails$orderId'>
                            <ul>";

                while ($item = $itemsResult->fetch_assoc()) {
                    $productName = $item['product_name'];
                    $quantity = $item['quantity'];
                    $price = $item['price'];

                    echo "<li><strong>$productName:</strong> $$price x $quantity</li>";
                }

                echo "</ul>
                        </div>
                    </div>";

                $itemStmt->close();
            }
        } else {
            echo "<p class='text-center'>You have no orders yet.</p>";
        }

        $stmt->close();
        ?>

    </div>

    <!-- Footer -->
    <footer class="fixed-bottom bg-dark text-light text-center py-3 mt-5">
        <p class="mb-0">&copy; 2024 smartsphere. All Rights Reserved.</p>
    </footer>
</body>

</html>