<?php
require "includes/auth.php";
require "includes/connect.php";
redirectIfLoggedIn();

$errors = [];
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS));
    $email = trim(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL));
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($username === '') {
        $errors[] = "Username is required.";
    }

    if ($email === '') {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email must be a valid email address.";
    }

    if ($password === '') {
        $errors[] = "Password is required.";
    }

    if ($confirmPassword === '') {
        $errors[] = "Please confirm your password.";
    }

    if ($password !== $confirmPassword) {
        $errors[] = "Passwords do not match.";
    }

    if (strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    if (empty($errors)) {
        $sql = "SELECT id FROM user WHERE username = :username OR email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        if ($stmt->fetch()) {
            $errors[] = "That username or email has already been used!";
        }
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO user (username, email, password) VALUES (:username, :email, :password)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        $success = "Account created successfully! Please log in.";
    }
}
?>

<?php require "includes/header.php"; ?>

<h2>Register</h2>

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
        <a href="login.php">Go to Login</a>
    </div>
<?php endif; ?>

<form method="post">
    <div>
        <label for="username">Username</label>
        <input type="text" id="username" name="username" required>
    </div>

    <div>
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>

    <div>
        <label for="password">Password (minimum 8 characters)</label>
        <input type="password" id="password" name="password" required>
    </div>

    <div>
        <label for="confirm_password">Confirm Password</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
    </div>

    <button type="submit">Register</button>
</form>

<p>Already have an account? <a href="login.php">Login here</a></p>

<?php require "includes/footer.php"; ?>