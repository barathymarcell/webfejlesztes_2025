<?php
require_once 'config.php';
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);
}
header('Location: dashboard.php');
exit;
