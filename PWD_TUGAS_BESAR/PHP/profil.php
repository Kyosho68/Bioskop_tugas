<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    echo json_encode(["error" => "Tidak ada session"]);
    exit();
}

$id_user = $_SESSION['id_user'];
$sql = "SELECT id_user, username, email, foto_profil FROM user WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["error" => "User tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>