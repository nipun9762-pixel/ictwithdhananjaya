<?php
require_once '../includes/auth.php';
requireRole('student');
$pageTitle = 'Student Dashboard';
require '../includes/header.php';

$user = currentUser();

// Active enrollments (already approved)
$stmt = $pdo->prepare("
    SELECT e.*, c.title, c.fee, c.thumbnail
    FROM enrollments e
    JOIN classes c ON e.class_id = c.id
    WHERE e.student_id = ? AND e.status = 'approved' AND e.end_date >= CURDATE()
    ORDER BY e.end_date ASC
");
$stmt->execute([$_SESSION['user_id']]);
$active = $stmt->fetchAll();

// Pending payments
$stmt = $pdo->prepare("
    SELECT e.*, c.title 
    FROM enrollments e
    JOIN classes c ON e.class_id = c.id
    WHERE e.student_id = ? AND e.status = 'pending'
");
$stmt->execute([$_SESSION['user_id']]);
$pending = $stmt->fetchAll();

// All available classes (so student can see new ones immediately)
$stmt = $pdo->query("SELECT * FROM classes WHERE is_active = 1 ORDER BY created_at DESC");
$allClasses = $stmt->fetchAll();

foreach ($allClasses as &$c) {
    $c['has_access'] = hasClassAccess($_SESSION['user_id'], $c['id']);
    
    $check = $pdo->prepare("SELECT id FROM enrollments WHERE student_id = ? AND class_id = ? AND status = 'pending'");
    $check->execute([$_SESSION['user_id'], $c['id']]);
    $c['is_pending'] = $check->fetch() ? true : false;
}
unset($c);
?>

<h1>Welcome, <?= htmlspecialchars($user['name']) ?></h1>

<div class="dashboard-grid">
    <div class="card">
        <h3>Active Classes</h3>
        <p class="big-number"><?= count($active) ?></p>
    </div>
    <div class="card">
        <h3>Pending Payments</h3>
        <p class="big-number"><?= count($pending) ?></p>
    </div>
    <div class="card">
        <h3>Available Classes</h3>
        <p class="big-number"><?= count($allClasses) ?></p>
    </div>
</div>

<?php if ($active): ?>
    <h2 style="margin-top: 30px;">Your Active Classes (You can enter)</h2>
    <div class="class-grid">
        <?php foreach ($active as $a): ?>
            <div class="class-card">
                <?php if (!empty($a['thumbnail'])): ?>
                    <img
                        src="<?= BASE_URL ?>/assets/uploads/class-thumbnails/<?= rawurlencode(basename($a['thumbnail'])) ?>"
                        alt="<?= htmlspecialchars($a['title']) ?> thumbnail"
                        class="class-card-thumbnail"
                    >
                <?php endif; ?>
                <h3><?= htmlspecialchars($a['title']) ?></h3>
                <p>Valid till: <strong><?= $a['end_date'] ?></strong></p>
                <a href="class-view.php?id=<?= $a['class_id'] ?>" class="btn btn-primary">Enter Class</a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($pending): ?>
    <h2 style="margin-top: 30px;">Pending Approvals</h2>
    <ul class="list">
        <?php foreach ($pending as $p): ?>
            <li><?= htmlspecialchars($p['title']) ?> – Waiting for teacher approval</li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<!-- Always show all available classes so new ones appear immediately -->
<h2 style="margin-top: 40px;">All Available Classes</h2>
<p style="margin-bottom: 15px; color: #555;">New classes added by teacher will appear here. Upload payment slip to get access.</p>

<?php if (empty($allClasses)): ?>
    <div class="card" style="text-align:left;">
        <p>No classes available yet. Please wait for the teacher to add classes.</p>
    </div>
<?php else: ?>
    <div class="class-grid">
        <?php foreach ($allClasses as $c): ?>
            <div class="class-card">
                <?php if (!empty($c['thumbnail'])): ?>
                    <img
                        src="<?= BASE_URL ?>/assets/uploads/class-thumbnails/<?= rawurlencode(basename($c['thumbnail'])) ?>"
                        alt="<?= htmlspecialchars($c['title']) ?> thumbnail"
                        class="class-card-thumbnail"
                    >
                <?php endif; ?>
                <h3><?= htmlspecialchars($c['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars(substr($c['description'] ?? '', 0, 100))) ?><?= strlen($c['description'] ?? '') > 100 ? '...' : '' ?></p>
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
<?php endif; ?>

<?php require '../includes/footer.php'; ?>
