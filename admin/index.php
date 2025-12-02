session_start();
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin - Dashboard</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="container">
  <div class="header">
    <h2>Admin Dashboard</h2>
    <div><a href="../index.php">Beranda</a> | <a href="../logout.php">Logout</a></div>
  </div>

  <p>Menu admin: <a class="btn" href="movies.php">Kelola Film</a> <a class="btn" href="schedules.php">Kelola Jadwal</a></p>
</div>
</body>
</html>