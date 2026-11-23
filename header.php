<?php
require_once __DIR__ . '/auth.php';
$user = currentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Tuition LMS' ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<header class="modern-navbar">
    <div class="container nav-container">
        <!-- Logo -->
        <a href="<?= BASE_URL ?>/" class="nav-logo">
            <img src="<?= BASE_URL ?>/assets/images/ICT.png" alt="logo" class="logo-png"
        </a>

        <!-- Menu Links -->
        <nav class="nav-menu">
            <a href="<?= BASE_URL ?>/" class="nav-link active">Home</a>
            <a href="<?= BASE_URL ?>/student/classes.php" class="nav-link">Courses</a>
            <a href="#" class="nav-link">About</a>
            <a href="#" class="nav-link">Blogs</a>
            <a href="#" class="nav-link">Contact</a>
        </nav>

        <!-- Right Side -->
        <div class="nav-right">
            <!-- Search -->
            <div class="nav-search">
                <input type="text" placeholder="Search...">
            </div>

            <?php if ($user): ?>
                <!-- Logged in -->
                <span class="nav-user">Hi, <?= htmlspecialchars($user['name']) ?></span>
                
                <?php if ($user['role'] === 'teacher'): ?>
                    <a href="<?= BASE_URL ?>/teacher/dashboard.php" class="btn-login">Teacher Panel</a>
                <?php else: ?>
                    <a href="<?= BASE_URL ?>/student/dashboard.php" class="btn-login">Dashboard</a>
                <?php endif; ?>
                
                <a href="<?= BASE_URL ?>/logout.php" class="btn-signup">Logout</a>
            <?php else: ?>
                <!-- Not logged in -->
                <a href="<?= BASE_URL ?>/login.php" class="btn-login">Log in</a>
                <a href="<?= BASE_URL ?>/register.php" class="btn-signup">Sign up →</a>
            <?php endif; ?>
        </div>
    </div>
</header>

<main class="container">