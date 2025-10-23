<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $carId = (int)$_GET['id'];
    $userId = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    $stmt = $pdo->prepare("SELECT reserved_by FROM cars WHERE id = ?");
    $stmt->execute([$carId]);
    $car = $stmt->fetch();

    if ($car) {
        if ($role === 'admin' || $car['reserved_by'] == $userId) {
            $stmt = $pdo->prepare("UPDATE cars SET reserved_by = NULL WHERE id = ?");
            $stmt->execute([$carId]);
        }
    }
}

header("Location: dashboard.php");
exit;
