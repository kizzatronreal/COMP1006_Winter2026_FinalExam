<?php
require "includes/auth.php";
require "includes/connect.php";

redirectIfLoggedIn();
?>
<?php require "includes/header.php"; ?>

<h2>Welcome to Image Gallery Admin</h2>

<?php if (!isset($_SESSION['user_id'])): ?>
    <p>Please log in or register.</p>
    <p>
        <a href="login.php">Login</a> | 
        <a href="register.php">Register</a>
    </p>
<?php else: ?>
    <p>Welcome, <?= htmlspecialchars($_SESSION['username']); ?>!</p>
    <p>
        <a href="gallery.php">View Gallery</a> | 
        <a href="upload.php">Upload Image</a>
    </p>
<?php endif; ?>

<?php require "includes/footer.php"; ?>