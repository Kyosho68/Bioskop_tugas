session_start();
require '../config/database.php';
if (!isset($_SESSION['user_id']) || empty($_SESSION['is_admin'])) {
    header('Location: ../login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $movie_id = (int)($_POST['movie_id'] ?? 0);
        $studio = trim($_POST['studio'] ?? '');
        $show_date = $_POST['show_date'] ?? '';
        $show_time = $_POST['show_time'] ?? '';
        $seats_total = (int)($_POST['seats_total'] ?? 40);

        $stmt = $pdo->prepare("INSERT INTO schedules (movie_id, studio, show_date, show_time, seats_total, seats_booked) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$movie_id, $studio, $show_date, $show_time, $seats_total, json_encode([])]);
        header('Location: schedules.php');
        exit;
    }
}
header('Location: schedules.php');
exit;