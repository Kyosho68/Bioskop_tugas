<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nama'];
    $password = $_POST['pass1'];
    $confirm = $_POST['pass2'];
    $email = $_POST['email'];

    if (empty($username) || empty($email)) {
        echo "<script>alert('Username dan Email harus diisi!'); window.history.back();</script>";
        exit();
    }

    if (empty($password) || empty($confirm)) {
        echo "<script>alert('Password harus diisi!'); window.history.back();</script>";
        exit();
    }

    if ($password != $confirm) {
        echo "<script>alert('Password tidak cocok!'); window.history.back();</script>";
        exit();
    }

    // Cek apakah username atau email sudah ada
    $check = "SELECT * FROM user WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($check);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo "<script>alert('Username atau Email sudah terdaftar!'); window.history.back();</script>";
        exit();
    }

    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO user (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password_hash);

    if ($stmt->execute()) {
        echo "<script>alert('Akun berhasil dibuat! Silakan login.'); 
              window.location.href='../XHTML/log_in.xml';</script>";
    } else {
        echo "<script>alert('Gagal membuat akun!'); window.history.back();</script>";
    }
    $stmt->close();
}
$conn->close();
?>