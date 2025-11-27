<?php
session_start();
include 'db.php';


$username = $_POST['nama'];
$password = $_POST['pass1'];


$sql = "SELECT * FROM user WHERE username = '$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verify password
    if ($password == $row['password']) {

        // Save login session
        $_SESSION['username'] = $row['username'];
        $_SESSION['role'] = $row['role'];

        echo "Login successful! Welcome, " . $row['username'];
        echo "<br/><a href='../XHTML/home.xml'>Go to Home</a>";
    } else {
        echo "Incorrect password!";
    }
} else {
    echo "Username not found!";
}

$conn->close();
?>