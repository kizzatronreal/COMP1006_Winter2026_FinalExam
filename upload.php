<?php
require "includes/auth.php";
require "includes/connect.php";
requireLogin();

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim(filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS));

    if ($title === '') {
        $errors[] = "Image title is required.";
    }

    if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Please select an image file to upload.";
    }

    if (empty($errors) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $file = $_FILES['image'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $errors[] = "Only JPEG, PNG, GIF, and WebP images are allowed.";
        }

        if ($file['size'] > $maxSize) {
            $errors[] = "Image size must not exceed 5MB.";
        }

        if (empty($errors)) {
            $uploadsDir = "uploads/";
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            $filename = uniqid() . '_' . basename($file['name']);
            $filepath = $uploadsDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $filepath)) {
                $sql = "INSERT INTO image (user_id, title, file_path) VALUES (:user_id, :title, :file_path)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
                $stmt->bindParam(':title', $title);
                $stmt->bindParam(':file_path', $filepath);
                $stmt->execute();

                $success = "Image uploaded successfully!";
            } else {
                $errors[] = "Failed to upload image.";
            }
        }
    }
}
?>

<?php require "includes/header.php"; ?>

<h2>Upload Image</h2>

<?php if (!empty($errors)): ?>
    <div>
        <h3>Errors:</h3>
        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success !== ""): ?>
    <div>
        <p><?= htmlspecialchars($success); ?></p>
        <a href="gallery.php">View Gallery</a>
    </div>
<?php endif; ?>

<form method="post" enctype="multipart/form-data">
    <div>
        <label for="title">Image Title</label>
        <input type="text" id="title" name="title" required>
    </div>

    <div>
        <label for="image">Select Image (JPEG, PNG, GIF, WebP - Max 5MB)</label>
        <input type="file" id="image" name="image" accept="image/*" required>
    </div>

    <button type="submit">Upload Image</button>
    <a href="gallery.php">Back to Gallery</a>
</form>

<?php require "includes/footer.php"; ?>