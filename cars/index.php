<?php
require_once 'config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, username, password, role FROM accounts WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $err = "Hibás felhasználónév vagy jelszó!";
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Bejelentkezés</title>
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
  </style>
</head>
<body>
<div class="container">
  <h2>Bejelentkezés</h2>
  <?php if (isset($_GET['regok'])) echo "<div style='color:green'>Sikeres regisztráció!</div>"; ?>
  <?php if ($err) echo "<div class='err'>".htmlspecialchars($err)."</div>"; ?>
  <form method="post">
    <label>Felhasználónév</label>
    <input type="text" name="username" required>
    <label>Jelszó</label>
    <input type="password" name="password" required>
    <button type="submit">Belépés</button>
  </form>
  <p>Nincs fiókod? <a href="register.php">Regisztráció</a></p>
</div>
</body>
</html>
