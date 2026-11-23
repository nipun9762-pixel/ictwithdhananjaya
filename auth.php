<?php
require_once __DIR__ . '/config.php';

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ' . BASE_URL . '/login.php');
        exit;
    }
}

function requireRole($role) {
    requireLogin();
    if ($_SESSION['role'] !== $role) {
        header('Location: ' . BASE_URL . '/');
        exit;
    }
}

function currentUser() {
    global $pdo;
    if (!isLoggedIn()) return null;
    $stmt = $pdo->prepare("SELECT id, name, email, role, phone FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}

function hasClassAccess($student_id, $class_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT id FROM enrollments 
        WHERE student_id = ? AND class_id = ? 
        AND status = 'approved' AND end_date >= CURDATE()
    ");
    $stmt->execute([$student_id, $class_id]);
    return $stmt->fetch() ? true : false;
}
