<?php
session_start();
require '../db/dbinit.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    switch ($action) {
        case 'add_product':
            $name = filter_var($_POST['name']);
            $brand_id = filter_var($_POST['brand_id'], FILTER_SANITIZE_NUMBER_INT);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $stock = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);
            $image = $_FILES['image']['name'];

            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($image);
            if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                $sql = "INSERT INTO products (name, brand_id, price, stock, image) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sidis', $name, $brand_id, $price, $stock, $image);
                if ($stmt->execute()) {
                    $message = "Product added successfully.";
                } else {
                    $message = "Error adding product: " . $stmt->error;
                }
            } else {
                $message = "Failed to upload image.";
            }
            break;

        case 'add_brand':
            $name = filter_var($_POST['name']);
            $sql = "INSERT INTO brands (name) VALUES (?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('s', $name);
            if ($stmt->execute()) {
                $message = "Brand added successfully.";
            } else {
                $message = "Error adding brand: " . $stmt->error;
            }
            break;

        case 'edit_product':
            $id = $_POST['id'];
            $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
            $brand_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            $stock = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);

            if (!empty($_FILES['image']['name'])) {
                $image = $_FILES['image']['name'];
                $target_dir = "../uploads/";
                $target_file = $target_dir . basename($image);
                if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
                    $sql = "UPDATE products SET name = ?, brand_id = ?, price = ?, stock = ?, image = ? WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param('sidisi', $name, $brand_id, $price, $stock, $image, $id);
                } else {
                    $message = "Failed to upload image.";
                }
            } else {
                $sql = "UPDATE products SET name = ?, brand_id = ?, price = ?, stock = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param('sidii', $name, $brand_id, $price, $stock, $id);
            }

            if ($stmt->execute()) {
                $message = "Product updated successfully.";
            } else {
                $message = "Error updating product: " . $stmt->error;
            }
            break;


        case 'delete_product':
            $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
            $sql = "DELETE FROM products WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $id);
            if ($stmt->execute()) {
                $message = "Product deleted successfully.";
            } else {
                $message = "Error deleting product: " . $stmt->error;
            }
            break;
    }
}

// Fetch products and brands for display
$products = $conn->query("SELECT p.id, p.name, b.name AS brand, p.image, p.price, p.stock FROM products p JOIN brands b ON p.brand_id = b.id");
$brands = $conn->query("SELECT * FROM brands");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/dataTables.bootstrap5.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Admin Panel</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active " href="adminHome.php">Manage Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="fullOrderHistory.php">View Orders</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h2 class="mb-4 text-center">Manage Products</h2>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Add New Product</button>
            <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#addBrandModal">Add New Brand</button>
        </div>

        <table id="productTable" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Name</th>
                    <th>Brand</th>
                    <th>Image</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $products->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['brand']) ?></td>
                        <td><img src="../uploads/<?= htmlspecialchars($row['image']) ?>" alt="Product Image" width="100"></td>
                        <td>$<?= number_format($row['price'], 2) ?></td>
                        <td><?= $row['stock'] ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#editProductModal"
                                data-id="<?= $row['id'] ?>"
                                data-name="<?= htmlspecialchars($row['name']) ?>"
                                data-brand-id="<?= $row['id'] ?>"
                                data-price="<?= $row['price'] ?>"
                                data-stock="<?= $row['stock'] ?>"
                                data-image="../uploads/<?= htmlspecialchars($row['image']) ?>">Edit</button>

                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="delete_product">
                                <input type="hidden" name="id" value="<?= $row['id'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this product?');">Delete</button>
                            </form>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="action" value="add_product">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="productBrand" class="form-label">Brand</label>
                            <select class="form-select" id="productBrand" name="brand_id" required>
                                <?php while ($brand = $brands->fetch_assoc()): ?>
                                    <option value="<?= $brand['id'] ?>"><?= htmlspecialchars($brand['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="productPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="productPrice" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="productStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="productStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="productImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="productImage" name="image" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Product Modal -->
    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit_product">
                    <input type="hidden" name="id" id="editProductId">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="editProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductBrand" class="form-label">Brand</label>
                            <select class="form-select" id="editProductBrand" name="brand_id" required disabled>
                                <?php while ($brand = $brands->fetch_assoc()): ?>
                                    <option value="<?= $brand['brand_id'] ?>" <?= (isset($brandId) && $brandId == $brand['brand_id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="editProductPrice" class="form-label">Price</label>
                            <input type="number" class="form-control" id="editProductPrice" name="price" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductStock" class="form-label">Stock</label>
                            <input type="number" class="form-control" id="editProductStock" name="stock" required>
                        </div>
                        <div class="mb-3">
                            <label for="editProductImage" class="form-label">Product Image</label>
                            <input type="file" class="form-control" id="editProductImage" name="image" accept="image/*">
                            <img id="currentImage" src="" alt="Current Image" class="mt-2" width="100">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Add Brand Modal -->
    <div class="modal fade" id="addBrandModal" tabindex="-1" aria-labelledby="addBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addBrandModalLabel">Add New Brand</h5>
                    <button type="button" the="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="action" value="add_brand">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="brandName" class="form-label">Brand Name</label>
                            <input type="text" class="form-control" id="brandName" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Brand</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable();
        });

        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                document.location = '?action=delete_product&id=' + id;
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            var editModal = document.getElementById('editProductModal');
            editModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var price = button.getAttribute('data-price');
                var stock = button.getAttribute('data-stock');

                var modal = this;
                modal.querySelector('#editProductId').value = id;
                modal.querySelector('#editProductName').value = name;
                modal.querySelector('#editProductPrice').value = price;
                modal.querySelector('#editProductStock').value = stock;
                // Assume the brand and image are managed separately and do not reset here
            });
        });
    </script>

</body>

</html>