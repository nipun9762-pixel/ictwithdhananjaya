<?php
require_once '../includes/auth.php';
requireRole('teacher');

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $action = $_GET['action'];

    if ($action === 'approve') {
        $stmt = $pdo->prepare("SELECT e.*, c.duration_days FROM enrollments e JOIN classes c ON e.class_id = c.id WHERE e.id = ?");
        $stmt->execute([$id]);
        $en = $stmt->fetch();

        if ($en && $en['status'] === 'pending') {
            $start = date('Y-m-d');
            $end = date('Y-m-d', strtotime("+{$en['duration_days']} days"));
            $pdo->prepare("
                UPDATE enrollments 
                SET status='approved', start_date=?, end_date=?, approved_by=?, approved_at=NOW()
                WHERE id=?
            ")->execute([$start, $end, $_SESSION['user_id'], $id]);
        }
    } elseif ($action === 'reject') {
        $pdo->prepare("UPDATE enrollments SET status='rejected' WHERE id=?")->execute([$id]);
    }
    header('Location: payments.php');
    exit;
}

$pending = $pdo->query("
    SELECT e.*, u.name as student_name, u.email, c.title as class_title
    FROM enrollments e
    JOIN users u ON e.student_id = u.id
    JOIN classes c ON e.class_id = c.id
    WHERE e.status = 'pending'
    ORDER BY e.created_at DESC
")->fetchAll();

$pageTitle = 'Payment Approvals';
require '../includes/header.php';
?>

<h1>Pending Payment Approvals</h1>

<?php if (empty($pending)): ?>
    <p>No pending payments.</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Student</th>
                <th>Class</th>
                <th>Amount</th>
                <th>Slip</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($pending as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['student_name']) ?><br><small><?= htmlspecialchars($p['email']) ?></small></td>
                    <td><?= htmlspecialchars($p['class_title']) ?></td>
                    <td>₹<?= number_format($p['amount'], 2) ?></td>
                    <td>
                        <?php if ($p['payment_slip']): ?>
                            <a href="/assets/uploads/payments/<?= htmlspecialchars($p['payment_slip']) ?>" target="_blank">View Slip</a>
                        <?php endif; ?>
                    </td>
                    <td><?= date('d M Y', strtotime($p['created_at'])) ?></td>
                    <td>
                        <a href="?action=approve&id=<?= $p['id'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Approve this payment?')">Approve</a>
                        <a href="?action=reject&id=<?= $p['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Reject this payment?')">Reject</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require '../includes/footer.php'; ?>
