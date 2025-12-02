session_start();
require '../config/database.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit;
}

$action = $_REQUEST['action'] ?? '';
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    $poster_path = null;
    if (!empty($_FILES['poster']['name'])) {
        $f = $_FILES['poster'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $newName = 'poster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dst = __DIR__ . '/../uploads/posters/' . $newName;
        move_uploaded_file($f['tmp_name'], $dst);
        $poster_path = 'uploads/posters/' . $newName;
    }

    $stmt = $pdo->prepare("INSERT INTO movies (title, genre, duration, description, poster) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$title,$genre,$duration,$description,$poster_path]);
    header('Location: movies.php');
    exit;
}

if ($action === 'edit' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
    $stmt->execute([$id]);
    $movie = $stmt->fetch();
    if (!$movie) { header('Location: movies.php'); exit; }
    ?>
    <!doctype html>
    <html><head><meta charset="utf-8"><title>Edit Film</title><link rel="stylesheet" href="../assets/css/style.css"></head><body>
    <div class="container">
      <div class="header"><h2>Edit Film</h2><div><a href="movies.php">Kembali</a></div></div>
      <form action="movies_process.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?= $movie['id'] ?>">
        <div class="form-group"><label>Judul</label><input class="input" name="title" value="<?=htmlspecialchars($movie['title'])?>"></div>
        <div class="form-group"><label>Genre</label><input class="input" name="genre" value="<?=htmlspecialchars($movie['genre'])?>"></div>
        <div class="form-group"><label>Durasi</label><input class="input" name="duration" type="number" value="<?=htmlspecialchars($movie['duration'])?>"></div>
        <div class="form-group"><label>Deskripsi</label><textarea class="input" name="description"><?=htmlspecialchars($movie['description'])?></textarea></div>
        <div class="form-group"><label>Ganti Poster</label><input class="input" type="file" name="poster" accept="image/*"></div>
        <button class="btn" type="submit">Simpan</button>
      </form>
    </div>
    </body></html>
    <?php
    exit;
}

if ($action === 'update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $title = trim($_POST['title'] ?? '');
    $genre = trim($_POST['genre'] ?? '');
    $duration = (int)($_POST['duration'] ?? 0);
    $description = trim($_POST['description'] ?? '');

    if (!empty($_FILES['poster']['name'])) {
        $f = $_FILES['poster'];
        $ext = pathinfo($f['name'], PATHINFO_EXTENSION);
        $newName = 'poster_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $dst = __DIR__ . '/../uploads/posters/' . $newName;
        move_uploaded_file($f['tmp_name'], $dst);
        $poster_path = 'uploads/posters/' . $newName;
    }

    $sql = "UPDATE movies SET title = ?, genre = ?, duration = ?, description = ?" . (isset($poster_path) ? ", poster = ?" : "") . " WHERE id = ?";
    $params = isset($poster_path) ? [$title,$genre,$duration,$description,$poster_path,$id] : [$title,$genre,$duration,$description,$id];
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    header('Location: movies.php');
    exit;
}

header('Location: movies.php');
exit;