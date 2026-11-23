<?php
session_start();

$host = 'localhost';
$db   = 'tuition_lms';
$user = 'root';
$pass = '';          // change this if your MySQL has a password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// IMPORTANT: Change this according to your folder name
// If project is in htdocs/tuition-lms then keep as below
// If you put it directly in htdocs root, change to ''
define('BASE_URL', '/tuition-lms');
