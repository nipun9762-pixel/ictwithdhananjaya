<?php
require_once '../includes/auth.php';
requireRole('teacher');

$classes = $pdo->query("SELECT id, title FROM classes WHERE is_active = 1 ORDER BY title")->fetchAll();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $class_id = (int)($_POST['class_id'] ?? 0);
    $type = $_POST['type'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $link = trim($_POST['link'] ?? '');

    if (!$class_id || !$type || !$title) {
        $error = 'Class, Type and Title are required.';
    } else {
        $file_path = null;

        if (in_array($type, ['recording', 'note']) && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES['file'];
            $folder = $type === 'recording' ? 'recordings' : 'notes';
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = $type . '_' . time() . '_' . rand(1000,9999) . '.' . $ext;
            $dest = '../assets/uploads/' . $folder . '/' . $filename;

            if (!is_dir(dirname($dest))) {
                mkdir(dirname($dest), 0777, true);
            }

            if (move_uploaded_file($file['tmp_name'], $dest)) {
                $file_path = $filename;
            } else {
                $error = 'File upload failed.';
            }
        }

        if (!$error) {
            $stmt = $pdo->prepare("
                INSERT INTO class_content (class_id, type, title, description, file_path, link, uploaded_by)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$class_id, $type, $title, $description, $file_path, $link ?: null, $_SESSION['user_id']]);
            $success = 'Content uploaded successfully!';
        }
    }
}

$pageTitle = 'Upload Content';
require '../includes/header.php';
?>

<div class="auth-box" style="max-width:650px;">
    <h2>Upload Content for Class</h2>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>Select Class *</label>
            <select name="class_id" required>
                <option value="">-- Choose Class --</option>
                <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label>Content Type *</label>
            <select name="type" id="contentType" required>
                <option value="live_link">Live Class Link</option>
                <option value="recording">Recording</option>
                <option value="note">Note / Tute</option>
                <option value="notice">Notice</option>
            </select>
        </div>
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" required>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="3"></textarea>
        </div>
        <div class="form-group" id="linkField">
            <label>Live Class Link (Zoom/Meet etc.)</label>
            <input type="url" name="link" placeholder="https://...">
        </div>
        <div class="form-group" id="fileField" style="display:none;">
            <label>Upload File</label>
            <input type="file" name="file">
        </div>
        <button type="submit" class="btn btn-primary btn-block">Upload Content</button>
    </form>
</div>

<script>
document.getElementById('contentType').addEventListener('change', function() {
    const type = this.value;

    // Show link field for live_link, recording, and note
    if (type === 'live_link' || type === 'recording' || type === 'note') {
        document.getElementById('linkField').style.display = 'block';
    } else {
        document.getElementById('linkField').style.display = 'none';
    }

    // Show file upload only for recording and note
    if (type === 'recording' || type === 'note') {
        document.getElementById('fileField').style.display = 'block';
    } else {
        document.getElementById('fileField').style.display = 'none';
    }
});
</script>

<?php require '../includes/footer.php'; ?>
