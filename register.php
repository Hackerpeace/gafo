<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $namaLengkap = $_POST['nama_lengkap'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO user (Username, Password, Email, NamaLengkap, Alamat) VALUES ('$username', '$password', '$email', '$namaLengkap', '$alamat')";
    
    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<form method="POST">
    Username: <input type="text" name="username" required>
    Password: <input type="password" name="password" required>
    Email: <input type="email" name="email" required>
    Nama Lengkap: <input type="text" name="nama_lengkap">
    Alamat: <textarea name="alamat"></textarea>
    <input type="submit" value="Register">
</form>
