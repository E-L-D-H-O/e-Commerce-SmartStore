<?php
session_start();
require "../db/dbinit.php";

$error = "";
$input_errors = [];
$first_name = $last_name = $email = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $first_name = trim($_POST['firstName']);
    $last_name = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Input validation
    if (empty($first_name)) {
        $input_errors['firstName'] = "First name is required!";
    }
    if (empty($last_name)) {
        $input_errors['lastName'] = "Last name is required!";
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $input_errors['email'] = "Valid email is required!";
    }
    if (empty($password)) {
        $input_errors['password'] = "Password is required!";
    } elseif (strlen($password) < 8) {
        $input_errors['password'] = "Password must be at least 8 characters long!";
    }
    if ($password !== $confirm_password) {
        $input_errors['confirm_password'] = "Passwords do not match!";
    }

    // Proceed if no input errors
    if (empty($input_errors)) {
        // Check for duplicate email
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $input_errors['email'] = "An account with this email already exists!";
        } else {
            // Insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed_password);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $conn->insert_id;
                header('Location: login.php');
                exit;
            } else {
                $error = "Error: " . $stmt->error;
            }
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
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
                    <li class="nav-item">
                        <a class="nav-link active" href="./login.php">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Signup Container -->
    <div class="container signup-container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-6 d-none d-md-flex signup-sidebar">
                <div>
                    <h1>Join Us Today!</h1>
                    <p>Create an account and start your shopping journey with us.</p>
                </div>
            </div>

            <!-- Signup Form -->
            <div class="col-md-6 col-sm-12 form-container">
                <h2 class="text-center mb-4">Signup</h2>

                <!-- Display General Errors -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="firstName" class="form-label">First Name</label>
                        <input type="text" class="form-control <?= isset($input_errors['firstName']) ? 'is-invalid' : '' ?>"
                            id="firstName" name="firstName"
                            value="<?= htmlspecialchars($first_name); ?>" placeholder="Enter your first name" required>
                        <div class="invalid-feedback"><?= $input_errors['firstName'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="lastName" class="form-label">Last Name</label>
                        <input type="text" class="form-control <?= isset($input_errors['lastName']) ? 'is-invalid' : '' ?>"
                            id="lastName" name="lastName"
                            value="<?= htmlspecialchars($last_name); ?>" placeholder="Enter your last name" required>
                        <div class="invalid-feedback"><?= $input_errors['lastName'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control <?= isset($input_errors['email']) ? 'is-invalid' : '' ?>"
                            id="email" name="email"
                            value="<?= htmlspecialchars($email); ?>" placeholder="Enter your email address" required>
                        <div class="invalid-feedback"><?= $input_errors['email'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control <?= isset($input_errors['password']) ? 'is-invalid' : '' ?>"
                            id="password" name="password" placeholder="Create a password" required>
                        <div class="invalid-feedback"><?= $input_errors['password'] ?? ''; ?></div>
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm Password</label>
                        <input type="password" class="form-control <?= isset($input_errors['confirm_password']) ? 'is-invalid' : '' ?>"
                            id="confirm_password" name="confirm_password" placeholder="Confirm password" required>
                        <div class="invalid-feedback"><?= $input_errors['confirm_password'] ?? ''; ?></div>
                    </div>
                    <button type="submit" class="btn btnSignUp w-100">Signup</button>
                </form>

                <div class="text-center mt-3">
                    <span>Already have an account? <a href="login.php">Login here</a></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="fixed-bottom text-light text-center py-3 mt-5">
        <p class="mb-0">&copy; 2024 smartsphere. All Rights Reserved.</p>
    </footer>
</body>

</html>