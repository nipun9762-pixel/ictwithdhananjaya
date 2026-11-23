<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $phone    = trim($_POST['phone'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (!$name || !$email || !$password) {
        $error = 'All required fields must be filled.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email address.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error = 'Password must be at least 6 characters.';
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = 'Email already registered.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (name, email, password, phone, role) VALUES (?, ?, ?, ?, 'student')");
            $stmt->execute([$name, $email, $hash, $phone]);
            $success = 'Registration successful! You can now login.';
        }
    }
}

$pageTitle = 'Register';
require 'includes/header.php';
?>

<div class="auth-box">
    <h2>Student Registration</h2>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" class="form">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Phone</label>
            <input type="text" name="phone" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Password *</label>
            <input type="password" name="password" required>
        </div>
        <div class="form-group">
            <label>Confirm Password *</label>
            <input type="password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Register</button>
    </form>
    <p class="text-center">Already have an account? <a href="<?= BASE_URL ?>/login.php">Login</a></p>
</div>

<?php require 'includes/footer.php'; ?>
