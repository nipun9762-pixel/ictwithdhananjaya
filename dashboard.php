<?php
require_once '../includes/auth.php';
requireRole('teacher');
$pageTitle = 'Teacher Dashboard';
require '../includes/header.php';

$totalClasses = $pdo->query("SELECT COUNT(*) FROM classes WHERE is_active = 1")->fetchColumn();
$pendingPayments = $pdo->query("SELECT COUNT(*) FROM enrollments WHERE status = 'pending'")->fetchColumn();
$activeStudents = $pdo->query("SELECT COUNT(DISTINCT student_id) FROM enrollments WHERE status = 'approved' AND end_date >= CURDATE()")->fetchColumn();
?>

<h1>Teacher Dashboard</h1>

<div class="dashboard-grid">
    <div class="card">
        <h3>Active Classes</h3>
        <p class="big-number"><?= $totalClasses ?></p>
        <a href="classes.php" class="btn btn-sm">Manage</a>
    </div>
    <div class="card">
        <h3>Pending Payments</h3>
        <p class="big-number"><?= $pendingPayments ?></p>
        <a href="payments.php" class="btn btn-sm">Review</a>
    </div>
    <div class="card">
        <h3>Active Students</h3>
        <p class="big-number"><?= $activeStudents ?></p>
    </div>
</div>

<div class="quick-actions">
    <a href="class-form.php" class="btn btn-primary">+ Add New Class</a>
    <a href="upload-content.php" class="btn btn-outline">Upload Content</a>
    <a href="payments.php" class="btn btn-outline">Approve Payments</a>
</div>

<?php require '../includes/footer.php'; ?>
