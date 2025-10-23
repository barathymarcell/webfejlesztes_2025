<?php
require_once 'config.php';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $pass1 = $_POST['password'];
    $pass2 = $_POST['password2'];

    if ($pass1 !== $pass2) {
        $err = "A jelszavak nem egyeznek!";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM accounts WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $err = "Ez a felhasználónév már létezik!";
        } else {
            $hash = password_hash($pass1, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO accounts (username, password, role) VALUES (?, ?, 'user')");
            $stmt->execute([$username, $hash]);
            header("Location: index.php?regok=1");
            exit;
        }
    }
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Regisztráció</title>
  <link rel="stylesheet" href="style.css">
  <style>
    .container { width: 500px; margin: 60px auto; }
    h2 { margin-bottom: 20px; text-align: center; }
  </style>
</head>
<body>
<div class="container">
  <h2>Regisztráció</h2>
  <?php if ($err) echo "<div class='err'>".htmlspecialchars($err)."</div>"; ?>
  <form method="post">
    <label>Felhasználónév</label>
    <input type="text" name="username" required>

    <label>Jelszó</label>
    <input type="password" name="password" required>

    <label>Jelszó újra</label>
    <input type="password" name="password2" required>

    <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
      <label>
        <input type="checkbox" name="is_admin"> Admin felhasználó
      </label>
    <?php endif; ?>

    <button type="submit">Regisztrálok</button>
  </form>
  <p>Már van fiókod? <a href="index.php">Bejelentkezés</a></p>
</div>
</body>
</html>
