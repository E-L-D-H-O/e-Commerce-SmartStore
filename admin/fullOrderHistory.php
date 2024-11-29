<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - View Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="adminHome.html">Manage Products</a></li>
                    <li class="nav-item"><a class="nav-link active" href="#">View Orders</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Order History Section -->
    <div class="container my-5">
        <h2 class="mb-4 text-center">Order History</h2>
        <table id="orderTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User Name</th>
                    <th>Email</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <!-- Sample Data -->
                <tr>
                    <td>#1001</td>
                    <td>John Doe</td>
                    <td>john.doe@example.com</td>
                    <td>2024-11-20</td>
                    <td>$199.99</td>
                    <td>Credit Card</td>
                    <td><span class="badge bg-success">Completed</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#viewOrderModal">View</button>
                    </td>
                </tr>
                <tr>
                    <td>#1002</td>
                    <td>Jane Smith</td>
                    <td>jane.smith@example.com</td>
                    <td>2024-11-19</td>
                    <td>$499.99</td>
                    <td>PayPal</td>
                    <td><span class="badge bg-warning">Pending</span></td>
                    <td>
                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                            data-bs-target="#viewOrderModal">View</button>
                    </td>
                </tr>
                <!-- Add more rows dynamically -->
            </tbody>
        </table>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h6>User Details</h6>
                    <ul>
                        <li><strong>Name:</strong> John Doe</li>
                        <li><strong>Email:</strong> john.doe@example.com</li>
                        <li><strong>Address:</strong> 123 Main St, Springfield</li>
                    </ul>
                    <hr>
                    <h6>Order Items</h6>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>iPhone 14</td>
                                <td>1</td>
                                <td>$999</td>
                                <td>$999</td>
                            </tr>
                            <tr>
                                <td>AirPods Pro</td>
                                <td>2</td>
                                <td>$249</td>
                                <td>$498</td>
                            </tr>
                        </tbody>
                    </table>
                    <hr>
                    <h6>Order Summary</h6>
                    <p><strong>Total Amount:</strong> $1,497</p>
                    <p><strong>Payment Method:</strong> Credit Card</p>
                    <p><strong>Status:</strong> Completed</p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#orderTable').DataTable();
        });
    </script>
</body>

</html>