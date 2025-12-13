<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if (!isset($_SESSION['id_user'])) {
    echo json_encode(["error" => "Silakan login"]);
    exit();
}

if (!isset($_FILES['foto'])) {
    echo json_encode(["error" => "Tidak ada file"]);
    exit();
}

$id_user = $_SESSION['id_user'];
$file = $_FILES['foto'];
$upload_dir = '../frontend_Bioskop/uploads/';

// Buat folder jika belum ada
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Validasi file
$allowed = ['jpg', 'jpeg', 'png', 'gif'];
$filename = $file['name'];
$ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

if (!in_array($ext, $allowed)) {
    echo json_encode(["error" => "Format file tidak valid"]);
    exit();
}

if ($file['size'] > 2 * 1024 * 1024) { // 2MB
    echo json_encode(["error" => "File terlalu besar (max 2MB)"]);
    exit();
}

// Generate nama file unik
$new_filename = "user_{$id_user}_" . time() . "." . $ext;
$upload_path = $upload_dir . $new_filename;

if (move_uploaded_file($file['tmp_name'], $upload_path)) {
    // Update database
    $sql = "UPDATE user SET foto_profil = ? WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_filename, $id_user);
    
    if ($stmt->execute()) {
        $_SESSION['foto_profil'] = $new_filename;
        echo json_encode([
            "success" => true,
            "foto_profil" => $new_filename,
            "message" => "Foto berhasil diupload"
        ]);
    } else {
        echo json_encode(["error" => "Gagal update database"]);
    }
    $stmt->close();
} else {
    echo json_encode(["error" => "Gagal upload file"]);
}

$conn->close();
?>