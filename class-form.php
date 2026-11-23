<?php
require_once '../includes/auth.php';
requireRole('teacher');

$id = (int)($_GET['id'] ?? 0);
$class = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->execute([$id]);
    $class = $stmt->fetch();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $fee = (float)($_POST['fee'] ?? 0);
    $duration = (int)($_POST['duration_days'] ?? 30);
    $thumbnail = $class['thumbnail'] ?? null;
    $uploadedThumbnail = null;

    if (!$title || $fee <= 0) {
        $error = 'Title and valid fee are required.';
    } else {
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            $file = $_FILES['thumbnail'];
            $maxSize = 5 * 1024 * 1024;
            $mimeExtensions = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
            ];

            if ($file['error'] !== UPLOAD_ERR_OK) {
                $error = 'The thumbnail upload failed. Please try again.';
            } elseif ($file['size'] > $maxSize) {
                $error = 'Thumbnail must be no larger than 5MB.';
            } else {
                $imageInfo = getimagesize($file['tmp_name']);
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);

                if (!$imageInfo || !isset($mimeExtensions[$mimeType])) {
                    $error = 'Please upload a valid JPG, PNG, or WebP image.';
                } else {
                    $uploadDirectory = __DIR__ . '/../assets/uploads/class-thumbnails';
                    if (!is_dir($uploadDirectory) && !mkdir($uploadDirectory, 0755, true) && !is_dir($uploadDirectory)) {
                        $error = 'Could not create the thumbnail upload directory.';
                    } else {
                        $uploadedThumbnail = bin2hex(random_bytes(16)) . '.' . $mimeExtensions[$mimeType];
                        $destination = $uploadDirectory . '/' . $uploadedThumbnail;
                        if (!move_uploaded_file($file['tmp_name'], $destination)) {
                            $error = 'Failed to save the thumbnail image.';
                        } else {
                            $thumbnail = $uploadedThumbnail;
                        }
                    }
                }
            }
        }

        if (!$error) {
            if ($id && $class) {
                $stmt = $pdo->prepare("UPDATE classes SET title=?, description=?, fee=?, duration_days=?, thumbnail=? WHERE id=?");
                $stmt->execute([$title, $description, $fee, $duration, $thumbnail, $id]);
                $success = 'Class updated successfully.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO classes (title, description, fee, duration_days, thumbnail, is_active, created_by) VALUES (?, ?, ?, ?, ?, 1, ?)");
                $stmt->execute([$title, $description, $fee, $duration, $thumbnail, $_SESSION['user_id']]);
                $success = 'Class created successfully.';
                $id = $pdo->lastInsertId();
            }

            $stmt = $pdo->prepare("SELECT * FROM classes WHERE id = ?");
            $stmt->execute([$id]);
            $class = $stmt->fetch();
        } elseif ($uploadedThumbnail) {
            unlink(__DIR__ . '/../assets/uploads/class-thumbnails/' . $uploadedThumbnail);
        }
    }
}

$pageTitle = $id ? 'Edit Class' : 'Add Class';
require '../includes/header.php';
?>

<div class="auth-box" style="max-width:600px;">
    <h2><?= $id ? 'Edit Class' : 'Add New Class' ?></h2>
    <?php if ($error): ?><div class="alert alert-error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= htmlspecialchars($success) ?></div><?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="form">
        <div class="form-group">
            <label>Title *</label>
            <input type="text" name="title" required value="<?= htmlspecialchars($class['title'] ?? '') ?>">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?= htmlspecialchars($class['description'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
            <label>Fee (₹) *</label>
            <input type="number" name="fee" step="0.01" required value="<?= $class['fee'] ?? '' ?>">
        </div>
        <div class="form-group">
            <label>Duration (days)</label>
            <input type="number" name="duration_days" value="<?= $class['duration_days'] ?? 30 ?>">
        </div>
        <div class="form-group">
            <label for="thumbnail">Class card thumbnail (JPG, PNG, or WebP; max 5MB)</label>
            <input type="file" id="thumbnail" name="thumbnail" accept="image/jpeg,image/png,image/webp">
            <?php if (!empty($class['thumbnail'])): ?>
                <p>Current thumbnail:</p>
                <img
                    src="<?= BASE_URL ?>/assets/uploads/class-thumbnails/<?= rawurlencode(basename($class['thumbnail'])) ?>"
                    alt="Current class thumbnail"
                    class="class-thumbnail-preview"
                >
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary btn-block"><?= $id ? 'Update' : 'Create' ?> Class</button>
    </form>
    <p class="text-center"><a href="classes.php">← Back to Classes</a></p>
</div>

<?php require '../includes/footer.php'; ?>
