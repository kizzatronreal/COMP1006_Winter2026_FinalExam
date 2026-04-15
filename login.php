<?php
require "includes/auth.php";
require "includes/connect.php";
redirectIfLoggedIn();

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username_or_email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($usernameOrEmail === '' || $password === '') {
        $error = "Username/email and password are required.";
    } else {
        $sql = "SELECT id, username, email, password FROM user WHERE username = :login OR email = :login LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':login', $usernameOrEmail);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];

            header("Location: gallery.php");
            exit;
        } else {
            $error = "Invalid credentials. Please try again!";
        }
    }
}
?>

<?php require "includes/header.php"; ?>

<h2>Login</h2>

<?php if ($error !== ""): ?>
    <div>
        <p><?= htmlspecialchars($error); ?></p>
    </div>
<?php endif; ?>

<form method="post">
    <div>
        <label for="username_or_email">Username or Email</label>
        <input type="text" id="username_or_email" name="username_or_email" required>
    </div>

    <div>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>

    <button type="submit">Login</button>
</form>

<p>Don't have an account? <a href="register.php">Register here</a></p>

<?php require "includes/footer.php"; ?>