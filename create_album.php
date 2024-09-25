<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaAlbum = $_POST['nama_album'];
    $deskripsi = $_POST['deskripsi'];
    $tanggalDibuat = date("Y-m-d");
    $userID = $_SESSION['userid'];

    $sql = "INSERT INTO album (NamaAlbum, Deskripsi, TanggalDibuat, UserID) VALUES ('$namaAlbum', '$deskripsi', '$tanggalDibuat', '$userID')";
    
    if ($conn->query($sql) === TRUE) {
        echo "<p>Album created successfully!</p>";
    } else {
        echo "<p>Error: " . $sql . "<br>" . $conn->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Album</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: white;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #666;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 4px;
            transition: background-color 0.3s;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #218838;
        }
        p {
            text-align: center;
            color: #333;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Create Album</h1>
    <form method="POST">
        <label for="nama_album">Nama Album:</label>
        <input type="text" id="nama_album" name="nama_album" required>
        
        <label for="deskripsi">Deskripsi:</label>
        <textarea id="deskripsi" name="deskripsi" rows="4"></textarea>
        
        <input type="submit" value="Create Album">
    </form>
</div>

<div style="position: absolute; top: 20px; right: 20px;">
    <a href="dashboard.php" style="display: inline-block; background-color: #4CAF50; color: white; border: none; border-radius: 5px; padding: 10px 15px; text-align: center; text-decoration: none; margin: 5px;">Go Back to Dashboard</a>
</div>

</body>
</html>
