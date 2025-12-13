<?php
include 'db.php'; // your DB connection

$id = $_GET['id'];

$sql = "SELECT * FROM film WHERE id = '$id'";
$result = $conn->query($sql);
$film = $result->fetch_assoc();

$sql = "SELECT * FROM jadwal_tayang WHERE id_film = '$id'";
$result = $conn->query($sql);
$jadwal = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    
    <link rel="stylesheet" href="../frontend_Bioskop/assets/css/style.css" />
    <title><?= $film['judul'] ?></title>
    <header>
    <h1>Bioskop Modern</h1>
    </header>
    <style>
        img { width: 250px; border-radius: 10px; }
    </style>
</head>
<body>
<div class="container">
    <div class="film-detail">
        
        <img src="<?= $film['poster'] ?>" class="poster">

        <div class="info">
            <h1><?= $film['judul'] ?></h1>
            
            <p><strong>Harga:</strong> Rp <?= $film['harga'] ?></p>

            <p><strong>Sinopsis:</strong></p>
            <p><?= $film['sinopsis'] ?></p>
        </div>

    </div>

    <div class="jadwal-wrapper">
        <div class="info">
            <h1>Pilih Jadwal </h1>
            <select name="jadwal" id="jadwal">
                <?php
                $sql = "SELECT * FROM jadwal_tayang WHERE id_film = '$id'";
                $result = $conn->query($sql);
                while ($jadwal = $result->fetch_assoc()) {
                    echo "<option value='" . $jadwal['id'] . "'>" . $jadwal['jadwal_penayangan'] . "</option>";
                }
                ?>
            </select>
        </div>

    </div>
</div>

</body>
</html>