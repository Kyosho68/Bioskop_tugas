<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    echo json_encode(["error" => "Silakan login"]);
    exit();
}

$id_user = $_SESSION['id_user'];
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';

if (empty($username) || empty($email)) {
    echo json_encode(["error" => "Data tidak lengkap"]);
    exit();
}

// Cek duplikat username/email
$check = "SELECT id_user FROM user WHERE (username = ? OR email = ?) AND id_user != ?";
$stmt = $conn->prepare($check);
$stmt->bind_param("ssi", $username, $email, $id_user);
$stmt->execute();
if ($stmt->get_result()->num_rows > 0) {
    echo json_encode(["error" => "Username atau email sudah digunakan"]);
    exit();
}

$sql = "UPDATE user SET username = ?, email = ? WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssi", $username, $email, $id_user);

if ($stmt->execute()) {
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    echo json_encode(["success" => true, "message" => "Profil berhasil diupdate"]);
} else {
    echo json_encode(["error" => "Gagal update profil"]);
}

$stmt->close();
$conn->close();
?>