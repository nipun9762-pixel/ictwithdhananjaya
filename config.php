<?php
session_start();

$host = 'db.fr-roub1.bengt.wasmernet.com';
$db = 'ictwithdhananjaya';
$port = '20184';
$user = 'user_a8c0666b';
$pass = 'pw_On8YnOQCRi1KTmf6NsP8rFZdvP2y79Za'; // change this if your MySQL has a password

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

define('BASE_URL', '');
define('WHATSAPP_NUMBER', '94770441914');
