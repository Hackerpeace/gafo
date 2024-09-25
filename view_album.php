<?php
session_start();
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
    exit();
}

$userID = $_SESSION['userid'];

// Fetch albums for the logged-in user
$sqlAlbums = "SELECT * FROM album WHERE UserID=?";
$stmtAlbums = $conn->prepare($sqlAlbums);
$stmtAlbums->bind_param("s", $userID);
$stmtAlbums->execute();
$resultAlbums = $stmtAlbums->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Albums</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        h1 { text-align: center; color: #333; }
        h2 { color: #4CAF50; margin-top: 20px; }
        .photos { display: flex; flex-wrap: wrap; gap: 20px; justify-content: center; }
        .photo { background: white; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); padding: 10px; width: 200px; text-align: center; }
        img { max-width: 100%; border-radius: 8px; }
        p { color: #666; }
        textarea { width: 100%; margin-top: 10px; padding: 5px; }
        form { margin: 10px 0; }
        input[type="submit"] { background-color: #4CAF50; color: white; border: none; border-radius: 5px; padding: 8px 12px; cursor: pointer; }
        input[type="submit"]:hover { background-color: #45a049; }
    </style>
</head>
<body>

<h1>Your Albums</h1>

<?php
if ($resultAlbums->num_rows > 0) {
    while ($album = $resultAlbums->fetch_assoc()) {
        echo "<div style='background: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); margin-top:20px; margin-bottom: 20px; padding: 15px;'>";
        echo "<h2 style='color: #4CAF50;'>" . htmlspecialchars($album['NamaAlbum']) . "</h2>";
        echo "<p style='font-size: 14px; color: #555;'>" . htmlspecialchars($album['Deskripsi']) . "</p>";
        echo "<p style='font-size: 12px; color: #999;'>Created on: " . htmlspecialchars($album['TanggalDibuat']) . "</p>";
        echo "</div>";
  


        // Fetch photos in the current album
        $sqlPhotos = "SELECT * FROM foto WHERE AlbumID=?";
        $stmtPhotos = $conn->prepare($sqlPhotos);
        $stmtPhotos->bind_param("s", $album['AlbumID']);
        $stmtPhotos->execute();
        $resultPhotos = $stmtPhotos->get_result();

        if ($resultPhotos->num_rows > 0) {
            echo "<div class='photos'>";
            while ($photo = $resultPhotos->fetch_assoc()) {
                // Use a relative path for images
                $photoPath = 'uploads/' . basename($photo['LokasiFile']);

                echo "<div class='photo'>";
                echo "<h3>" . htmlspecialchars($photo['JudulFoto']) . "</h3>";
                echo "<img src='" . htmlspecialchars($photoPath) . "' alt='" . htmlspecialchars($photo['JudulFoto']) . "'>";
                echo "<p>" . htmlspecialchars($photo['DeskripsiFoto']) . "</p>";
                echo "<p>Uploaded on: " . htmlspecialchars($photo['TanggalUnggah']) . "</p>";

                // Fetch the number of likes for the current photo
                $sqlLikesCount = "SELECT COUNT(*) as like_count FROM likefoto WHERE FotoID=?";
                $stmtLikesCount = $conn->prepare($sqlLikesCount);
                $stmtLikesCount->bind_param("s", $photo['FotoID']);
                $stmtLikesCount->execute();
                $resultLikesCount = $stmtLikesCount->get_result();
                $likes = $resultLikesCount->fetch_assoc();
                $likeCount = $likes['like_count'];

                echo "<p>Likes: " . htmlspecialchars($likeCount) . "</p>"; // Display like count

                // Fetch comments for the current photo
                $sqlComments = "SELECT * FROM komentarfoto WHERE FotoID=?";
                $stmtComments = $conn->prepare($sqlComments);
                $stmtComments->bind_param("s", $photo['FotoID']);
                $stmtComments->execute();
                $resultComments = $stmtComments->get_result();

                echo "<h4>Comments:</h4>";
                if ($resultComments->num_rows > 0) {
                    while ($comment = $resultComments->fetch_assoc()) {
                        echo "<p><strong>" . htmlspecialchars($comment['IsiKomentar']) . "</strong> - " . htmlspecialchars($comment['TanggalKomentar']) . "</p>";
                    }
                } else {
                    echo "<p>No comments yet.</p>";
                }

                // Comment submission form
                echo "<form method='POST' action='comment.php'>";
                echo "<input type='hidden' name='foto_id' value='" . htmlspecialchars($photo['FotoID']) . "'>";
                echo "<textarea name='isi_komentar' placeholder='Add a comment' required></textarea>";
                echo "<input type='submit' value='Post Comment'>";
                echo "</form>";

                // Like button
                echo "<form method='POST' action='like.php'>";
                echo "<input type='hidden' name='foto_id' value='" . htmlspecialchars($photo['FotoID']) . "'>";
                echo "<input type='submit' value='Like'>";
                echo "</form>";

                echo "</div>"; // End photo div
            }
            echo "</div>"; // End photos div
        } else {
            echo "<p>No photos in this album.</p>";
        }
    }
} else {
    echo "<p>You have no albums.</p>";
}
?>

<div style="position: absolute; top: 20px; right: 20px;">
    <a href="uploads/upload.php" style="display: inline-block; background-color: #4CAF50; color: white; border: none; border-radius: 5px; padding: 10px 15px; text-align: center; text-decoration: none; margin: 5px;">Upload New Photo</a>
    <a href="create_album.php" style="display: inline-block; background-color: #4CAF50; color: white; border: none; border-radius: 5px; padding: 10px 15px; text-align: center; text-decoration: none; margin: 5px;">Create New Album</a>
    <a href="dashboard.php" style="display: inline-block; background-color: #4CAF50; color: white; border: none; border-radius: 5px; padding: 10px 15px; text-align: center; text-decoration: none; margin: 5px;">Go Back to Dashboard</a>
</div>



</body>
</html>
