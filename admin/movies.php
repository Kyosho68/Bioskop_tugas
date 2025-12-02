session_start();
require '../config/database.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit;
}

// proses delete jika ada
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM movies WHERE id = ?")->execute([$id]);
    header('Location: movies.php');
    exit;
}

// ambil data film
$stmt = $pdo->query("SELECT * FROM movies ORDER BY created_at DESC");
$movies = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Kelola Film</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
  <div class="header">
    <h2>Kelola Film</h2>
    <div><a href="index.php">Admin</a> | <a href="../index.php">Lihat Situs</a> | <a href="../logout.php">Logout</a></div>
  </div>

  <h3>Tambah Film</h3>
  <form action="movies_process.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="action" value="create">
    <div class="form-group"><label>Judul</label><input class="input" name="title" required></div>
    <div class="form-group"><label>Genre</label><input class="input" name="genre"></div>
    <div class="form-group"><label>Durasi (menit)</label><input class="input" name="duration" type="number"></div>
    <div class="form-group"><label>Deskripsi</label><textarea class="input" name="description"></textarea></div>
    <div class="form-group"><label>Poster</label><input class="input" type="file" name="poster" accept="image/*"></div>
    <button class="btn" type="submit">Tambah</button>
  </form>

  <h3>Daftar Film</h3>
  <table class="table">
    <thead><tr><th>Poster</th><th>Judul</th><th>Genre</th><th>Durasi</th><th>Aksi</th></tr></thead>
    <tbody>
      <?php foreach($movies as $m): ?>
      <tr>
        <td><img src="<?= $m['poster'] ? '../' . htmlspecialchars($m['poster']) : 'https://via.placeholder.com/60x80' ?>" style="width:60px;height:80px;object-fit:cover;"></td>
        <td><?=htmlspecialchars($m['title'])?></td>
        <td><?=htmlspecialchars($m['genre'])?></td>
        <td><?=htmlspecialchars($m['duration'])?> mnt</td>
        <td>
          <a class="btn" href="movies_process.php?action=edit&id=<?= $m['id'] ?>">Edit</a>
          <a class="btn" href="movies.php?delete=<?= $m['id'] ?>" onclick="return confirm('Hapus film?')">Hapus</a>
        </td>
      </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
</body>
</html>