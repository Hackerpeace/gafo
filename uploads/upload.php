<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Photo</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        form {
            background: white;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            margin: auto;
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        input[type="text"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="file"] {
            margin-bottom: 20px;
        }

        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 10px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        .error {
            color: red;
            text-align: center;
        }
    </style>
</head>
<body>

<?php
session_start();
include 'C:\xampp\htdocs\gafo\db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $judulFoto = $_POST['judul_foto'];
    $deskripsiFoto = $_POST['deskripsi_foto'];
    $albumID = $_POST['album_id'];
    $userID = $_SESSION['userid'];
    $tanggalUnggah = date("Y-m-d");

    // Define the upload directory
    $uploadDir = 'C:/xampp/htdocs/gafo/uploads/'; 
    
    // Sanitize the file name to avoid directory traversal attacks
    $fileName = basename($_FILES['file']['name']);
    $lokasiFile = $uploadDir . $fileName;

    // Check file upload errors
    if ($_FILES['file']['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded file to the designated directory
        if (move_uploaded_file($_FILES['file']['tmp_name'], $lokasiFile)) {
            // Use prepared statements to prevent SQL injection
            $sql = "INSERT INTO foto (JudulFoto, DeskripsiFoto, TanggalUnggah, LokasiFile, AlbumID, UserID) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssss", $judulFoto, $deskripsiFoto, $tanggalUnggah, $lokasiFile, $albumID, $userID);

            if ($stmt->execute()) {
                echo "<p class='success'>Photo uploaded successfully!</p>"; 
            } else {
                echo "<p class='error'>Error: " . $stmt->error . "</p>";
            }
            $stmt->close();
        } else {
            echo "<p class='error'>Error moving uploaded file.</p>";
        }
    } else {
        echo "<p class='error'>File upload error: " . $_FILES['file']['error'] . "</p>";
    }
}
?>

<h2>Upload Photo</h2>
<form method="POST" enctype="multipart/form-data">
    <label for="judul_foto">Judul Foto:</label>
    <input type="text" name="judul_foto" required>

    <label for="deskripsi_foto">Deskripsi:</label>
    <textarea name="deskripsi_foto"></textarea>

    <label for="album_id">Album:</label>
    <select name="album_id" required>
        <?php
        $albums = $conn->query("SELECT * FROM album WHERE UserID=" . $_SESSION['userid']);
        while ($album = $albums->fetch_assoc()) {
            echo "<option value='{$album['AlbumID']}'>{$album['NamaAlbum']}</option>";
        }
        ?>
    </select>

    <label for="file">Foto:</label>
    <input type="file" name="file" required>

    <input type="submit" value="Upload Photo">
</form>

</body>
</html>
