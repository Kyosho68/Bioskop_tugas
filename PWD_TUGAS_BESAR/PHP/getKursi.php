<?php
header('Content-Type: application/json');
include 'db.php';

$id_penayangan = isset($_GET['id_penayangan']) ? intval($_GET['id_penayangan']) : 0;

if ($id_penayangan == 0) {
    echo json_encode(["error" => "ID penayangan tidak valid"]);
    exit();
}

// Ambil semua kursi yang sudah dipesan untuk jadwal ini
$sql = "SELECT kursi FROM pemesanan WHERE id_penayangan = ? AND status_pembayaran = 'berhasil'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_penayangan);
$stmt->execute();
$result = $stmt->get_result();

$kursi_dipesan = [];
while ($row = $result->fetch_assoc()) {
    $kursi_array = json_decode($row['kursi'], true);
    if (is_array($kursi_array)) {
        $kursi_dipesan = array_merge($kursi_dipesan, $kursi_array);
    }
}

// Generate semua kursi (8 baris x 10 kolom)
$rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
$cols = 10;
$semua_kursi = [];

foreach ($rows as $row) {
    for ($i = 1; $i <= $cols; $i++) {
        $nomor = $row . $i;
        $semua_kursi[] = [
            'nomor' => $nomor,
            'status' => in_array($nomor, $kursi_dipesan) ? 'dipesan' : 'tersedia'
        ];
    }
}

echo json_encode($semua_kursi);
$conn->close();
?>