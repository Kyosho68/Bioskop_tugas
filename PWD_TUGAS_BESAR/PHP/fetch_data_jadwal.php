<?php
include 'db.php';



if (isset($_POST['buat'])) {
    $film = $_POST['film_id_h2'];
    $jadwal = $_POST['jadwal'];
    $jumlah = $_POST['jumlah_kursi'];

    $sql = "INSERT INTO jadwal_tayang (id_film, jadwal_penayangan, jumlah_kursi_tersisah) VALUES ('$film', '$jadwal', '$jumlah')";
    echo json_encode(["status" => "success"]);

    if ($conn->query($sql) === TRUE) {
       echo "<script>alert('Berhasil membuat promo!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();

    } else {
        echo "<script>alert('Gagal membuat promo!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
        echo "Error: " . $conn->error;
    }
    $conn->close();
}



// UPDATE / EDIT PROMO
if (isset($_POST['edit'])) {
    $id = $_POST['id_tayang'];
    $jadwal = $_POST['jadwal_n'];
    $jumlah = $_POST['jumlah_kursi_n'];


    $sql = "UPDATE jadwal_tayang SET jadwal_penayangan = '$jadwal', jumlah_kursi_tersisah = '$jumlah' WHERE id_penayangan = $id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil edit promo!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal edit promo!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    }
    exit();
}



// DELETE PROMO
if (isset($_POST['hapus'])) {
    $id = $_POST['id_tayang'];       // nama promo

    $sql = "DELETE FROM jadwal_tayang WHERE id_penayangan = $id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil menghapus promo!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus promo!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    }
    exit();
}


header("Content-Type: application/json");
$data = [];

// Jika ingin filter berdasarkan id film
if (isset($_GET['id_film'])) {

    $filmId = intval($_GET['id_film']);

    $sql = "SELECT * FROM jadwal_tayang WHERE id_film = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $filmId);
    $stmt->execute();
    $result = $stmt->get_result();

} else {
    // Ambil semua jadwal
    $result = $conn->query("SELECT * FROM jadwal_tayang");
}

while ($row = $result->fetch_assoc()) {
    $data[] = [
        "id"                 => $row['id_penayangan'],
        "id_film"            => $row['id_film'],
        "jadwal_p"  => $row['jadwal_penayangan'],
        "jumlah"             => $row['jumlah_kursi_tersisah']
    ];
}

echo json_encode($data);
?>

