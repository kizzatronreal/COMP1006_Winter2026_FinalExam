<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery Admin</title>
</head>
<body>
    <h1>Image Gallery Admin</h1>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php">Home</a>
            <a href="gallery.php">Gallery</a>
            <a href="upload.php">Upload Image</a>
            <a href="logout.php">Logout (<?= htmlspecialchars($_SESSION['username']) ?>)</a>
        <?php else: ?>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
    <hr>

