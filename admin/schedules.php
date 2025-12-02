session_start();
require '../config/database.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit;
}

// proses delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM schedules WHERE id = ?")->execute([$id]);
    header('Location: schedules.php');
    exit;
}

// ambil movies untuk select
$movies = $pdo->query("SELECT id,title FROM movies ORDER BY title")->fetchAll();
$schedules = $pdo->query("SELECT s.*, m.title FROM schedules s JOIN movies m ON m.id = s.movie_id ORDER BY show_date, show_time")->fetchAll();
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Kelola Jadwal</title><link rel="stylesheet" href="../assets/css/style.css"></head>
<body>
<div class="container">
  <div class="header"><h2>Kelola Jadwal</h2><div><a href="index.php">Admin</a> | <a href="movies.php">Kelola Film</a></div></div>

  <h3>Tambah Jadwal</h3>
  <form action="schedules_process.php" method="post">
    <input type="hidden" name="action" value="create">
    <div class="form-group"><label>Film</label>
      <select class="input" name="movie_id" required>
        <?php foreach($movies as $m): ?><option value="<?= $m['id'] ?>"><?=htmlspecialchars($m['title'])?></option><?php endforeach; ?>
      </select>
    </div>
    <div class="form-group"><label>Studio</label><input class="input" name="studio" required></div>
    <div class="form-group"><label>Tanggal</label><input class="input" type="date" name="show_date" required></div>
    <div class="form-group"><label>Waktu</label><input class="input" type="time" name="show_time" required></div>
    <div class="form-group"><label>Jumlah Kursi</label><input class="input" type="number" name="seats_total" value="40" required></div>
    <button class="btn" type="submit">Tambah Jadwal</button>
  </form>

  <h3>Daftar Jadwal</h3>
  <table class="table">
    <thead><tr><th>Film</th><th>Studio</th><th>Tanggal</th><th>Waktu</th><th>Kursi</th><th>Aksi</th></tr></thead>
    <tbody>
    <?php foreach($schedules as $s): ?>
      <tr>
        <td><?=htmlspecialchars($s['title'])?></td>
        <td><?=htmlspecialchars($s['studio'])?></td>
        <td><?=htmlspecialchars($s['show_date'])?></td>
        <td><?=htmlspecialchars(substr($s['show_time'],0,5))?></td>
        <td><?=htmlspecialchars($s['seats_total'])?></td>
        <td><a class="btn" href="schedules.php?delete=<?= $s['id'] ?>" onclick="return confirm('Hapus jadwal?')">Hapus</a></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>