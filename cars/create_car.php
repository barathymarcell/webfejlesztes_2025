<?php
require_once 'config.php';
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO cars (brand, model, price) VALUES (?, ?, ?)");
    $stmt->execute([$_POST['brand'], $_POST['model'], $_POST['price']]);
}
header('Location: dashboard.php');
exit;
