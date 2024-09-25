<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php"); // Redirect to login if not logged in
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .logout {
            text-align: center;
            margin-top: 20px;
        }
        .logout a {
            color: white;
            background-color: #dc3545;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
        }
        .logout a:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome to "Hoffnungslos und Verzweifelt", <?php echo $_SESSION['username']; ?>!</h2>
    <p>This is your dashboard where you can manage your account, view content, etc.</p>

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>
</div>


<!-- <h1>Your Photo Albums</h1>
<a href="create_album.php">Create New Album</a>
<h2>Existing Albums</h2>
<ul id="album-list">
     This will be populated with album links dynamically
</ul> -->

<?php
include 'db.php';

if (!isset($_SESSION['userid'])) {
    header("Location: login.php");
}

$userID = $_SESSION['userid'];

// Fetch albums for the logged-in user
$sqlAlbums = "SELECT * FROM album WHERE UserID='$userID'";
$resultAlbums = $conn->query($sqlAlbums);
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
            margin: 0;
            padding: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        h2 {
            color: #007bff;
        }
        a {
            text-decoration: none;
            color: #007bff;
        }
        a:hover {
            text-decoration: underline;
        }
        .album {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px 15px;
            margin: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .album p {
            color: #666;
        }
        .button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<h1>Your Albums</h1>

<a class="button" href="create_album.php">Create New Album</a>

<?php
if ($resultAlbums->num_rows > 0) {
    while ($album = $resultAlbums->fetch_assoc()) {
        echo "<div class='album'>";
        echo "<h2><a href='view_album.php?album_id=" . $album['AlbumID'] . "'>" . htmlspecialchars($album['NamaAlbum']) . "</a></h2>";
        echo "<p>" . htmlspecialchars($album['Deskripsi']) . "</p>";
        echo "<p>Created on: " . htmlspecialchars($album['TanggalDibuat']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>You have no albums.</p>";
}
?>

<a class="button" href="uploads\upload.php">Upload New Photo</a> <!--| <a class="button" href="logout.php">Logout</a>-->

</body>
</html>


</body>
</html>
