<?php
include 'db.php';



if (isset($_POST['buat'])) {
    $nama = $_POST['nama'];
    $img = $_POST['link_img'];

    $sql = "INSERT INTO promo (nama_promo, img_link_promo) VALUES ('$nama', '$img')";
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
    $nama = $_POST['promo_n'];      
    $img = $_POST['link'];      
    $id = $_POST['promo_id'];

    $sql = "UPDATE promo SET img_link_promo = '$img' , nama_promo = '$nama' wHERE id_promo = $id";
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
    $id = $_POST['promo_id'];       // nama promo

    $sql = "DELETE FROM promo WHERE id_promo = '$id'";
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


header('Content-Type: application/json');

$sql = "SELECT * FROM promo";
$result = $conn->query($sql);

$promoImages = [];
while($row = $result->fetch_assoc()) {
    $promoImages[] = [
        "id" => $row['id_promo'],  
        "nama" => $row['nama_promo'],
        "link" => $row['img_link_promo']
    ];
}


echo json_encode($promoImages);
?>

