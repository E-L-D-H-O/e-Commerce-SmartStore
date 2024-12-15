<?php
session_start();
require "../db/dbinit.php";

$error = ""; // Initialize error message

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Please enter both email and password!";
    } else {
        // Check user in the database
        $stmt = $conn->prepare("SELECT id, role, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($user_id, $role, $hashed_password);
            $stmt->fetch();

            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'admin') {
                    header('Location: ../admin/adminHome.php');
                } else {
                    header('Location: products.php');
                }
                exit;
            } else {
                $error = "Invalid email or password!";
            }
        } else {
            $error = "No account found with this email!";
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
    <title>Login</title>
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
                    <li class="nav-item">
                        <a class="nav-link active" href="./signUp.php">signUp</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Login Container -->
    <div class="container login-container mt-5">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-6 d-none d-md-flex login-sidebar">
                <div>
                    <h1>Welcome Back!</h1>
                    <p>Login to your account and continue your shopping journey.</p>
                </div>
            </div>

            <!-- Login Form -->
            <div class="col-md-6 col-sm-12 form-container">
                <h2 class="text-center mb-4">Login</h2>
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email"
                            required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password"
                            placeholder="Enter your password" required>
                    </div>
                    <button type="submit" class="btn btnSignUp w-100">Login</button>
                </form>

                <div class="text-center mt-3">
                    <span>Don't have an account? <a href="signUp.php">Sign up here</a></span>
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