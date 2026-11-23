<?php
require_once '../includes/auth.php';
requireRole('teacher');
$pageTitle = 'Manage Classes';
require '../includes/header.php';

// Soft delete / deactivate
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("UPDATE classes SET is_active = 0 WHERE id = ?")->execute([$id]);
    header('Location: classes.php');
    exit;
}

$classes = $pdo->query("SELECT * FROM classes ORDER BY created_at DESC")->fetchAll();
?>

<div class="page-header">
    <h1>Manage Classes</h1>
    <a href="class-form.php" class="btn btn-primary">+ Add New Class</a>
</div>

<table class="table">
    <thead>
        <tr>
            <th>Thumbnail</th>
            <th>Title</th>
            <th>Fee</th>
            <th>Duration</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($classes as $c): ?>
            <tr>
                <td>
                    <?php if (!empty($c['thumbnail'])): ?>
                        <img
                            src="<?= BASE_URL ?>/assets/uploads/class-thumbnails/<?= rawurlencode(basename($c['thumbnail'])) ?>"
                            alt="<?= htmlspecialchars($c['title']) ?> thumbnail"
                            class="teacher-class-thumbnail"
                        >
                    <?php else: ?>
                        <span class="text-muted">No image</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($c['title']) ?></td>
                <td>₹<?= number_format($c['fee'], 2) ?></td>
                <td><?= $c['duration_days'] ?> days</td>
                <td><?= $c['is_active'] ? '<span class="badge badge-success">Active</span>' : '<span class="badge">Inactive</span>' ?></td>
                <td>
                    <a href="class-form.php?id=<?= $c['id'] ?>" class="btn btn-sm">Edit</a>
                    <?php if ($c['is_active']): ?>
                        <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Deactivate this class?')">Deactivate</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php require '../includes/footer.php'; ?>
