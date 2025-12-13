<?php
header('Content-Type: application/json');
include 'db.php';

$kode_tiket = isset($_GET['kode']) ? $_GET['kode'] : '';

if (empty($kode_tiket)) {
    echo json_encode(["error" => "Kode tiket tidak valid"]);
    exit();
}

$sql = "SELECT p.*, u.username, u.email, 
        j.jadwal_penayangan, f.judul, f.poster, f.sinopsis, f.harga
        FROM pemesanan p
        JOIN user u ON p.id_user = u.id_user
        JOIN jadwal_tayang j ON p.id_penayangan = j.id_penayangan
        JOIN film f ON j.id_film = f.id
        WHERE p.kode_tiket = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $kode_tiket);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $data['kursi'] = json_decode($data['kursi'], true);
    echo json_encode($data);
} else {
    echo json_encode(["error" => "Tiket tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>