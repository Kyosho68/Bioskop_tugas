<?php
session_start();
header('Content-Type: application/json');
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["error" => "Method tidak valid"]);
    exit();
}

// Cek login
if (!isset($_SESSION['id_user'])) {
    echo json_encode(["error" => "Silakan login terlebih dahulu"]);
    exit();
}

$id_user = $_SESSION['id_user'];
$id_penayangan = isset($_POST['id_penayangan']) ? intval($_POST['id_penayangan']) : 0;
$kursi = isset($_POST['kursi']) ? $_POST['kursi'] : [];
$total_harga = isset($_POST['total_harga']) ? floatval($_POST['total_harga']) : 0;

if (empty($kursi) || $total_harga <= 0) {
    echo json_encode(["error" => "Data tidak lengkap"]);
    exit();
}

// Generate kode tiket unik
function generateKodeTiket($conn) {
    do {
        $timestamp = strtoupper(base_convert(time(), 10, 36));
        $random = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 5));
        $kode = "TIX-{$timestamp}-{$random}";
        
        $check = $conn->prepare("SELECT kode_tiket FROM pemesanan WHERE kode_tiket = ?");
        $check->bind_param("s", $kode);
        $check->execute();
        $result = $check->get_result();
    } while ($result->num_rows > 0);
    
    return $kode;
}

$kode_tiket = generateKodeTiket($conn);
$kursi_json = json_encode($kursi);

// Insert pemesanan
$sql = "INSERT INTO pemesanan (kode_tiket, id_user, id_penayangan, kursi, total_harga, status_pembayaran) 
        VALUES (?, ?, ?, ?, ?, 'berhasil')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("siisd", $kode_tiket, $id_user, $id_penayangan, $kursi_json, $total_harga);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "kode_tiket" => $kode_tiket,
        "message" => "Pemesanan berhasil!"
    ]);
} else {
    echo json_encode(["error" => "Gagal menyimpan pemesanan"]);
}

$stmt->close();
$conn->close();
?>