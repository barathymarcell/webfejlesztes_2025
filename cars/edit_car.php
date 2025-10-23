<?php
require_once 'config.php';
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();
if (!$car) {
    header('Location: dashboard.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE cars SET brand=?, model=?, price=? WHERE id=?");
    $stmt->execute([$_POST['brand'], $_POST['model'], $_POST['price'], $id]);
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Autó szerkesztése</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: #615c5cff;
      font-family: Arial, sans-serif;
    }
    .container {
      width: 500px;
      margin: 60px auto;
    }
    h2 {
      margin-bottom: 20px;
      text-align: center;
    }
    .back-link {
      display: block;
      margin-top: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
<div class="container">
  <h2>Autó szerkesztése</h2>
  <form method="post">
    <label>Márka</label>
    <input type="text" name="brand" value="<?=htmlspecialchars($car['brand'])?>" required>
    <label>Típus</label>
    <input type="text" name="model" value="<?=htmlspecialchars($car['model'])?>" required>
    <label>Ár (€)</label>
    <input type="number" step="0.01" name="price" value="<?=htmlspecialchars($car['price'])?>" required>
    <button type="submit">Mentés</button>
  </form>
  <p><a href="dashboard.php" class="back-link">Vissza</a></p>
</div>
</body>
</html>
