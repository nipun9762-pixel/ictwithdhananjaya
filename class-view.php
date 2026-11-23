<?php
require_once '../includes/auth.php';
requireRole('student');

$class_id = (int)($_GET['id'] ?? 0);

if (!hasClassAccess($_SESSION['user_id'], $class_id)) {
    die('<div style="padding:40px;text-align:center;"><h2>Access Denied</h2><p>You do not have an approved enrollment for this class.</p><a href="classes.php">Go back</a></div>');
}

$stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->execute([$class_id]);
$class = $stmt->fetch();

$stmt = $pdo->prepare("SELECT * FROM class_content WHERE class_id = ? ORDER BY created_at DESC");
$stmt->execute([$class_id]);
$contents = $stmt->fetchAll();

$live = $recordings = $notes = $notices = [];
foreach ($contents as $item) {
    if ($item['type'] === 'live_link') $live[] = $item;
    elseif ($item['type'] === 'recording') $recordings[] = $item;
    elseif ($item['type'] === 'note') $notes[] = $item;
    elseif ($item['type'] === 'notice') $notices[] = $item;
}

$pageTitle = $class['title'];
require '../includes/header.php';
?>

<div class="class-header">
    <h1><?= htmlspecialchars($class['title']) ?></h1>
    <p><?= nl2br(htmlspecialchars($class['description'] ?? '')) ?></p>
</div>

<div class="tabs">
    <button class="tab-btn active" data-tab="live">Live Class</button>
    <button class="tab-btn" data-tab="recordings">Recordings</button>
    <button class="tab-btn" data-tab="notes">Notes / Tutes</button>
    <button class="tab-btn" data-tab="notices">Notices</button>
</div>

<div class="tab-content active" id="live">
    <?php if ($live): ?>
        <?php foreach ($live as $l): ?>
            <div class="content-item">
                <h3><?= htmlspecialchars($l['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($l['description'] ?? '')) ?></p>
                <a href="<?= htmlspecialchars($l['link']) ?>" target="_blank" class="btn btn-primary">Join Live Class</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No live class link available yet.</p>
    <?php endif; ?>
</div>

<div class="tab-content" id="recordings">
    <?php if ($recordings): ?>
        <?php foreach ($recordings as $r): ?>
            <div class="content-item">
                <h3><?= htmlspecialchars($r['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($r['description'] ?? '')) ?></p>
                <?php if ($r['file_path']): ?>
                    <a href="<?= BASE_URL ?>/assets/uploads/recordings/<?= htmlspecialchars($r['file_path']) ?>" target="_blank" class="btn btn-outline">Watch / Download</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recordings uploaded yet.</p>
    <?php endif; ?>
</div>

<div class="tab-content" id="notes">
    <?php if ($notes): ?>
        <?php foreach ($notes as $n): ?>
            <div class="content-item">
                <h3><?= htmlspecialchars($n['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($n['description'] ?? '')) ?></p>
                <?php if ($n['file_path']): ?>
                    <a href="<?= BASE_URL ?>/assets/uploads/notes/<?= htmlspecialchars($n['file_path']) ?>" target="_blank" class="btn btn-outline">Download Note</a>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notes available yet.</p>
    <?php endif; ?>
</div>

<div class="tab-content" id="notices">
    <?php if ($notices): ?>
        <?php foreach ($notices as $n): ?>
            <div class="content-item">
                <h3><?= htmlspecialchars($n['title']) ?></h3>
                <p><?= nl2br(htmlspecialchars($n['description'] ?? '')) ?></p>
                <small><?= $n['created_at'] ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No notices yet.</p>
    <?php endif; ?>
</div>

<?php require '../includes/footer.php'; ?>
