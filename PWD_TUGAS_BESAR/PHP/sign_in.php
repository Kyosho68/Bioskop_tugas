<?php
include 'db.php';


$username = $_POST['nama'];
$password = $_POST['pass1'];
$confirm = $_POST['pass2'];

if ($password != $confirm) {
    echo "<script>
            alert('Passwords do not match!');
            window.history.back();
          </script>";
    exit();
}
$sql = "INSERT INTO user (username, password, role) 
        VALUES ('$username', '$password', 'USER')";

echo json_encode(["status" => "success"]);

if ($conn->query($sql) === TRUE) {
    echo "<script>
            alert('Account created successfully! Please log in.');
            window.location.href='../XHTML/log_in.xml'; // Redirect to login
          </script>";

} else {
    echo "Error: " . $conn->error;
}
$conn->close();
?>