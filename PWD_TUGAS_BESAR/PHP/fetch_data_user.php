<?php
include 'db.php';
session_start();



// UPDATE / EDIT PROMO
if (isset($_POST['edit'])) {

    $nama = $_POST['username'];      
    $email = $_POST['email'];
    $id = $_SESSION['id_user'];

    $sql = "UPDATE user SET username = '$nama', email = '$email' WHERE id_user = $id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil edit promo!'); 
              window.location.href='../XHTML/profil.xml';</script>";
        exit();
    } else {
        echo "<script>alert('Berhasil edit promo!'); 
              window.location.href='../XHTML/profil.xml';</script>";
        exit();
    }
    exit();
}



header('Content-Type: application/json');

if (isset($_SESSION['id_user'])) {

    $user_id = $_SESSION['id_user'];

    // hanya ambil user yang sedang login
    $sql = "SELECT * FROM user WHERE id_user = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

} else {

    // kalau tidak ada session, kosongkan atau bebas mau apa
    echo json_encode([]);
    exit();
}

$user_data = [];
while($row = $result->fetch_assoc()) {
    $user_data[] = [
        "id" => $row['id_user'],
        "username" => $row['username'],
        "email" => $row['email'],
        "pass" => $row['password'],
        "foto" => $row['foto_profil']
    ];
}

echo json_encode($user_data);;



?>