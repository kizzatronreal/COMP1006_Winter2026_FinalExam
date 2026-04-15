<?php
require "includes/auth.php";
require "includes/connect.php";
requireLogin();

$imageId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($imageId === false) {
    header("Location: gallery.php");
    exit;
}

$sql = "SELECT id, file_path, user_id FROM image WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
$stmt->execute();
$image = $stmt->fetch();

if (!$image || $image['user_id'] != $_SESSION['user_id']) {
    header("Location: gallery.php");
    exit;
}

if (file_exists($image['file_path'])) {
    unlink($image['file_path']);
}

$sql = "DELETE FROM image WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $imageId, PDO::PARAM_INT);
$stmt->execute();

header("Location: gallery.php");
exit;
