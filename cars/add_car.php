<?php
require_once 'config.php';
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Új autó hozzáadása</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: #615c5cff;
      font-family: Arial, sans-serif;
    }
    .form-container {
      max-width: 500px;
      margin: 60px auto;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      padding: 30px;
    }
    .form-container h2 {
      margin-bottom: 20px;
      color: #2c2c2c;
      text-align: center;
    }
    .form-container label {
      display: block;
      margin-top: 15px;
    }
    .form-container input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 5px;
    }
    .form-container button {
      margin-top: 20px;
      width: 100%;
      padding: 12px;
      background: #3a8dde;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1em;
      cursor: pointer;
    }
    .form-container button:hover {
      background: #0056b3;
    }
    .back-link {
      display: block;
      margin-top: 15px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="form-container">
    <h2>Új autó hozzáadása</h2>
    <form method="post" action="create_car.php">
      <label>Márka</label>
      <input type="text" name="brand" required>

      <label>Típus</label>
      <input type="text" name="model" required>

      <label>Ár (€)</label>
      <input type="number" name="price" required>

      <button type="submit">Hozzáadás</button>
    </form>
    <a class="back-link" href="dashboard.php">Vissza</a>
  </div>
</body>
</html>
