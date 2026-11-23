<?php
require_once 'includes/auth.php';

if (isLoggedIn()) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role']    = $user['role'];
        $_SESSION['name']    = $user['name'];

        if ($user['role'] === 'teacher') {
            header('Location: ' . BASE_URL . '/teacher/dashboard.php');
        } else {
            header('Location: ' . BASE_URL . '/student/dashboard.php');
        }
        exit;
    } else {
        $error = 'Invalid email or password.';
    }
}

$pageTitle = 'Login';
require 'includes/header.php';
?>

<div class="auth-box">
    <h2>Login</h2>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>

    <form method="POST" class="form">
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button>
    </form>
    <p class="text-center">New student? <a href="<?= BASE_URL ?>/register.php">Register here</a></p>
</div>

<?php require 'includes/footer.php'; ?>
