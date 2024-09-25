<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input data
    $fotoID = isset($_POST['foto_id']) ? intval($_POST['foto_id']) : 0;
    $isiKomentar = isset($_POST['isi_komentar']) ? trim($_POST['isi_komentar']) : '';
    $userID = $_SESSION['userid'];
    $tanggalKomentar = date("Y-m-d");

    // Check if comment text is not empty
    if (!empty($isiKomentar) && $fotoID > 0) {
        // Prepare and bind the SQL statement
        $sql = "INSERT INTO komentarfoto (FotoID, UserID, IsiKomentar, TanggalKomentar) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiss", $fotoID, $userID, $isiKomentar, $tanggalKomentar);

        if ($stmt->execute()) {
            // Redirect back to the album view or handle success response
            header("Location: view_album.php");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Invalid comment or photo ID.";
    }
}
?>
