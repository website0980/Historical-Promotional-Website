<?php
// Admin Login Page
require_once 'config.php';

$error = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Verify credentials
    if ($username === ADMIN_USERNAME && $password === ADMIN_PASSWORD) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_username'] = $username;
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Invalid username or password';
    }
}

// Redirect if already logged in
if (isLoggedIn()) {
    header('Location: dashboard.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Tagum City</title>
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body class="login-body">
    <div class="login-container">
        <div class="login-box">
            <div class="login-header">
<img src="../assets/images/City of Tagum Logo.png" alt="Tagum City" class="logo-img logo-img-small">
                <h1>Tagum Admin</h1>
                <p>Destination Management System</p>
            </div>

            <form method="POST" class="login-form">
                <?php if ($error): ?>
                    <div class="error-message">
                        <span>⚠️</span>
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        required 
                        autofocus
                        placeholder="Enter your username"
                        class="form-control"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required 
                        placeholder="Enter your password"
                        class="form-control"
                    >
                </div>

                <button type="submit" class="login-btn">Login</button>
            </form>

            <div class="login-footer">
                <p><strong>Demo Credentials:</strong></p>
                <p>Username: <code>admin</code></p>
                <p>Password: <code>tagum2026</code></p>
            </div>

            <div class="back-to-site">
                <a href="../index.php">← Back to Website</a>
            </div>
        </div>
    </div>
</body>
</html>
