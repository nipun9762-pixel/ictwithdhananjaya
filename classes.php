<?php
require_once '../includes/auth.php';
requireRole('student');
$pageTitle = 'All Classes';
require '../includes/header.php';

$stmt = $pdo->query("SELECT * FROM classes WHERE is_active = 1 ORDER BY created_at DESC");
$classes = $stmt->fetchAll();

foreach ($classes as &$c) {
    $c['has_access'] = hasClassAccess($_SESSION['user_id'], $c['id']);
    
    // Check if already pending
    $check = $pdo->prepare("SELECT id FROM enrollments WHERE student_id = ? AND class_id = ? AND status = 'pending'");
    $check->execute([$_SESSION['user_id'], $c['id']]);
    $c['is_pending'] = $check->fetch() ? true : false;
}
unset($c);
?>

<h1>Available Classes</h1>

<div class="class-grid">
    <?php foreach ($classes as $c): ?>
        <div class="class-card">
            <?php if (!empty($c['thumbnail'])): ?>
                <img
                    src="<?= BASE_URL ?>/assets/uploads/class-thumbnails/<?= rawurlencode(basename($c['thumbnail'])) ?>"
                    alt="<?= htmlspecialchars($c['title']) ?> thumbnail"
                    class="class-card-thumbnail"
                >
            <?php endif; ?>
            <h3><?= htmlspecialchars($c['title']) ?></h3>
            <p><?= nl2br(htmlspecialchars(substr($c['description'] ?? '', 0, 120))) ?><?= strlen($c['description'] ?? '') > 120 ? '...' : '' ?></p>
            <p class="fee">Fee: ₹<?= number_format($c['fee'], 2) ?> / <?= $c['duration_days'] ?> days</p>

            <?php if ($c['has_access']): ?>
                <a href="class-view.php?id=<?= $c['id'] ?>" class="btn btn-primary">Enter Class</a>
            <?php elseif ($c['is_pending']): ?>
                <span class="badge badge-warning">Payment Pending Approval</span>
            <?php else: ?>
                <a href="upload-payment.php?class_id=<?= $c['id'] ?>" class="btn btn-outline">Upload Payment Slip</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>

<?php require '../includes/footer.php'; ?>
