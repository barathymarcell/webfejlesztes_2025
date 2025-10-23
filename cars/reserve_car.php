<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $carId = (int)$_GET['id'];

    $stmt = $pdo->prepare("SELECT reserved_by FROM cars WHERE id = ?");
    $stmt->execute([$carId]);
    $car = $stmt->fetch();

    if ($car && $car['reserved_by'] === null) {
        $stmt = $pdo->prepare("UPDATE cars SET reserved_by = ? WHERE id = ?");
        $stmt->execute([$_SESSION['user_id'], $carId]);
    }
}

header("Location: dashboard.php");
exit;
