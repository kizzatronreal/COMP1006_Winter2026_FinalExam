<?php
require "includes/auth.php";
require "includes/connect.php";
requireLogin();

$sql = "SELECT id, title, file_path, uploaded_at FROM image WHERE user_id = :user_id ORDER BY uploaded_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
$stmt->execute();
$images = $stmt->fetchAll();
?>

<?php require "includes/header.php"; ?>

<h2>My Image Gallery</h2>

<?php if (empty($images)): ?>
    <p>No images uploaded yet.</p>
    <a href="upload.php">Upload Image</a>
<?php else: ?>
    <p><?= count($images); ?> image in your gallery</p>
    
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>Title</th>
            <th>Image</th>
            <th>Uploaded</th>
            <th>Action</th>
        </tr>
        <?php foreach ($images as $image): ?>
            <tr>
                <td><?= htmlspecialchars($image['title']); ?></td>
                <td>
                    <?php if (file_exists($image['file_path'])): ?>
                        <img src="<?= htmlspecialchars($image['file_path']); ?>" alt="<?= htmlspecialchars($image['title']); ?>" width="100" height="100" style="object-fit: cover;">
                    <?php else: ?>
                        <p>Image not found</p>
                    <?php endif; ?>
                </td>
                <td><?= date('M d, Y', strtotime($image['uploaded_at'])); ?></td>
                <td>
                    <a href="delete.php?id=<?= urlencode($image['id']); ?>" onclick="return confirm('Are you sure?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <br>
    <a href="upload.php">Upload Another Image</a>
<?php endif; ?>

<?php require "includes/footer.php"; ?>