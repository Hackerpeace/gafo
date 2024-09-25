<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fotoID = $_POST['foto_id'];
    $userID = $_SESSION['userid'];
    $tanggalLike = date("Y-m-d H:i:s");

    // Prepare and execute SQL statement to insert the like
    $sql = "INSERT INTO likefoto (FotoID, UserID, TanggalLike) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $fotoID, $userID, $tanggalLike);

    if ($stmt->execute()) {
        // Redirect back to the album view or handle success response
        header("Location: view_album.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "Invalid request.";
}
?>
