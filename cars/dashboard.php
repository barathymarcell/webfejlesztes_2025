<?php
require_once 'config.php';
if (!isset($_SESSION['role'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rating'], $_POST['car_id'])) {
    $rating = (int)$_POST['rating'];
    $car_id = (int)$_POST['car_id'];
    $user_id = $_SESSION['user_id'];

    if ($rating >= 1 && $rating <= 5) {
        $stmt = $pdo->prepare("INSERT INTO ratings (car_id, user_id, rating) VALUES (?, ?, ?)
                               ON DUPLICATE KEY UPDATE rating = VALUES(rating)");
        $stmt->execute([$car_id, $user_id, $rating]);
    }
    header("Location: dashboard.php");
    exit;
}

$stmt = $pdo->query("
    SELECT c.*,
           (SELECT ROUND(AVG(r.rating),1) FROM ratings r WHERE r.car_id = c.id) AS avg_rating,
           (SELECT rating FROM ratings r WHERE r.car_id = c.id AND r.user_id = " . intval($_SESSION['user_id']) . ") AS user_rating
    FROM cars c
    ORDER BY id DESC
");
$cars = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Autók</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      background: #615c5cff;
      font-family: Arial, sans-serif;
    }
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
    }
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }
    .header .buttons {
      display: flex;
      gap: 10px;
    }
    .header a.button {
      display: inline-block;
      padding: 10px 16px;
      border-radius: 6px;
      background: #3a8dde;
      color: #fff;
      text-decoration: none;
      font-size: 0.95em;
    }
    .header a.button:hover {
      background: #0056b3;
    }
    .card-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 20px;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      border: 1px solid black;
      padding: 20px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: transform 0.2s;
      min-height: 260px;
    }
    .card:hover { transform: translateY(-2px); }
    .card .info { flex-grow: 1; }
    .card h3 { margin: 0 0 10px 0; font-size: 1.2em; }
    .card p { margin: 5px 0; font-size: 1em; color: #333; }
    .actions { margin-top: auto; display: flex; flex-wrap: wrap; gap: 8px; }
    .actions a {
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 6px;
      background: #3a8dde;
      color: #fff;
      font-size: 0.9em;
    }
    .actions a.delete { background: #dc3545; }
    .actions a.delete_reservation { background: #615c5cff; }
.stars {
  display: flex;
  font-size: 2em;
  cursor: pointer;
}

.star {
  color: #ccc;
    font-size: 2em;
}

.star.active {
  color: gold;
    font-size: 2em;
}
.staradmin {
  color: gold;
    font-size: 2em;
}
  </style>
</head>
<body>
<div class="container">
  <div class="header">
    <h2>Üdv, <?=htmlspecialchars($_SESSION['username'])?></h2>
    <div class="buttons">
      <?php if ($_SESSION['role'] === 'admin'): ?>
        <a href="add_car.php" class="button">Autó hozzáadása</a>
      <?php endif; ?>
      <a class="button" href="logout.php">Kijelentkezés</a>
    </div>
  </div>

  <h3>Elérhető autók</h3>
  <div class="card-grid">
    <?php foreach($cars as $c): ?>
      <div class="card">
        <div class="info">
          <h3><?= htmlspecialchars($c['brand']) ?></h3>
          <p><strong>Típus:</strong> <?= htmlspecialchars($c['model']) ?></p>
          <p><strong>Ár:</strong> <?= number_format($c['price'], 2, ',', ' ') ?> €</p>

          <?php if (!empty($c['reserved_by'])): ?>
              <p><strong>Foglalva:</strong>
                <?php
                $stmtUser = $pdo->prepare("SELECT username FROM accounts WHERE id = ?");
                $stmtUser->execute([$c['reserved_by']]);
                $user = $stmtUser->fetch();
                echo htmlspecialchars($user['username']);
                ?>
              </p>
          <?php endif; ?>

          <?php if ($_SESSION['role'] === 'user'): ?>
            <form method="post">
              <input type="hidden" name="car_id" value="<?=$c['id']?>">
              <p><strong class="staradmin">&#9733;</strong> <?= $c['avg_rating'] ? $c['avg_rating'] : "Nincs értékelés" ?></p>
              <div class="stars">
                <?php for ($i=1; $i<=5; $i++): ?>
                  <button type="submit" name="rating" value="<?=$i?>" style="background:none;border:none;padding:0;">
                    <span class="star <?=($c['user_rating'] >= $i ? 'active' : '')?>">&#9733;</span>
                  </button>
                <?php endfor; ?>
              </div>
            </form>
          <?php elseif ($_SESSION['role'] === 'admin'): ?>
            <p><strong class="staradmin">&#9733;</strong> <?= $c['avg_rating'] ? $c['avg_rating'] : "Nincs értékelés" ?></p>
          <?php endif; ?>
        </div>

        <div class="actions">
          <?php if (!empty($c['reserved_by'])): ?>
              <?php if ($_SESSION['role'] === 'admin'): ?>
                <a class="delete_reservation" href="unreserve_car.php?id=<?= $c['id'] ?>" onclick="return confirm('Biztos felszabadítod a foglalást?');">Foglalás törlése</a>
              <?php elseif ($c['reserved_by'] == $_SESSION['user_id']): ?>
                <a class="delete" href="unreserve_car.php?id=<?= $c['id'] ?>" onclick="return confirm('Biztos törlöd a foglalásod?');">Foglalás törlése</a>
              <?php endif; ?>
          <?php else: ?>
              <?php if ($_SESSION['role'] === 'user'): ?>
                <a class="button" href="reserve_car.php?id=<?= $c['id'] ?>">🚗 Foglalás</a>
              <?php endif; ?>
          <?php endif; ?>

          <?php if ($_SESSION['role']==='admin'): ?>
            <a href="edit_car.php?id=<?= $c['id'] ?>">✏️ Szerkesztés</a>
            <a class="delete" href="delete_car.php?id=<?= $c['id'] ?>" onclick="return confirm('Biztos törölni akarod?');">🗑️ Törlés</a>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
</body>
</html>
