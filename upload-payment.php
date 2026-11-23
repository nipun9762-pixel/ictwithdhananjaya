<?php
require_once '../includes/auth.php';
requireRole('student');

$class_id = (int)($_GET['class_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ? AND is_active = 1");
$stmt->execute([$class_id]);
$class = $stmt->fetch();

if (!$class) {
    die('Class not found.');
}

// Already has access?
if (hasClassAccess($_SESSION['user_id'], $class_id)) {
    header('Location: class-view.php?id=' . $class_id);
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_FILES['slip']) || $_FILES['slip']['error'] !== UPLOAD_ERR_OK) {
        $error = 'Please select a valid payment slip.';
    } else {
        $file = $_FILES['slip'];
        $allowed = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        if (!in_array($file['type'], $allowed)) {
            $error = 'Only JPG, PNG or PDF allowed.';
        } elseif ($file['size'] > $maxSize) {
            $error = 'File too large (max 5MB).';
        } else {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = 'pay_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
            $dest = '../assets/uploads/payments/' . $filename;

            if (!is_dir('../assets/uploads/payments')) {
                mkdir('../assets/uploads/payments', 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $stmt = $pdo->prepare("
                    INSERT INTO enrollments (student_id, class_id, payment_slip, amount, status)
                    VALUES (?, ?, ?, ?, 'pending')
                ");
                $stmt->execute([$_SESSION['user_id'], $class_id, $filename, $class['fee']]);
                $success = 'Payment slip uploaded successfully! Waiting for teacher approval.';
            } else {
                $error = 'Failed to upload file.';
            }
        }
    }
}

$pageTitle = 'Upload Payment';
require '../includes/header.php';
?>

<div class="auth-box">
    <h2>Upload Payment Slip</h2>
    <p><strong>Class:</strong> <?= htmlspecialchars($class['title']) ?></p>
    <p><strong>Fee:</strong> ₹<?= number_format($class['fee'], 2) ?></p>

    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <?php if (!$success): ?>
    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>Payment Slip (JPG/PNG/PDF) *</label>
            <input type="file" name="slip" accept=".jpg,.jpeg,.png,.pdf" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Upload Slip</button>
    </form>
    <?php endif; ?>

    <p class="text-center"><a href="classes.php">← Back to Classes</a></p>
</div>

<?php require '../includes/footer.php'; ?>
