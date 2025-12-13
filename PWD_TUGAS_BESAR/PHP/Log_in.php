<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['nama'];
    $password = $_POST['pass1'];

    $sql = "SELECT * FROM user WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['foto_profil'] = $row['foto_profil'];

            if($row['role'] == 'admin'){
                echo "<script>alert('Login berhasil! Selamat datang Admin'); 
                      window.location.href='../XHTML/admin_menu.xml';</script>";
            } else {
                echo "<script>alert('Login berhasil!'); 
                      window.location.href='../XHTML/home_screen.xml';</script>";
            }
        } else {
            echo "<script>alert('Password salah!'); 
                  window.location.href='../XHTML/log_in.xml';</script>";
        }
    } else {
        echo "<script>alert('Username/Email tidak ditemukan!'); 
              window.location.href='../XHTML/log_in.xml';</script>";
    }
    $stmt->close();
}
$conn->close();
?>