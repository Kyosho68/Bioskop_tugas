<?php
include 'db.php';

if (isset($_POST['buat'])) {
    $nama = $_POST['nama'];
    $img = $_POST['link_img'];
    $harga = $_POST['harga'];
    $sinopsis = $_POST['sinopsis'];

    $sql = "INSERT INTO film (judul ,sinopsis, harga,  poster) VALUES ('$nama', '$sinopsis', '$harga', '$img')";
    echo json_encode(["status" => "success"]);

    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Berhasil membuat film!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";

    } else {
        echo "<script>alert('Gagal membuat film!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
        echo "Error: " . $conn->error;
    }
}



// UPDATE / EDIT PROMO
if (isset($_POST['edit'])) {
    $nama = $_POST['film_n'];      
    $img = $_POST['link_n'];
    $sinopsis = $_POST['sinopsis_n'];
    $harga = $_POST['harga_n'];
    $id = $_POST['film_id'];

    $sql = "UPDATE film SET poster = '$img' , judul = '$nama', sinopsis = '$sinopsis', harga = '$harga' WHERE id = $id";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil edit film!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal edit film!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    }
    exit();
}



// DELETE PROMO
if (isset($_POST['hapus'])) {
    $id = $_POST['film_id'];       

    $sql = "DELETE FROM film WHERE id = '$id'";
    $stmt = $conn->prepare($sql);

    if ($stmt->execute()) {
        echo "<script>alert('Berhasil menghapus film!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    } else {
        echo "<script>alert('Gagal menghapus film!'); 
              window.location.href='../XHTML/admin_menu.xml';</script>";
        exit();
    }
    
}



header('Content-Type: application/json');

$sql = "SELECT * FROM film";
$result = $conn->query($sql);

$film_data = [];
while($row = $result->fetch_assoc()) {
    $film_data[] = [
        "id" => $row['id'],    
        "judul" => $row['judul'],
        "sinopsis" => $row['sinopsis'],
        "poster" => $row['poster'],
        "harga" => $row['harga']
    ];
}

echo json_encode($film_data);



?>