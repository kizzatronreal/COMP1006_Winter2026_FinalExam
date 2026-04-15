<?php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

//check if user logged in redirext to login if not
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

// check if user already logged in if so redirect to gallery
function redirectIfLoggedIn() {
    if (isset($_SESSION['user_id'])) {
        header('Location: gallery.php');
        exit;
    }
}

//log out and destroy session
function logoutUser() {
    session_destroy();
    header('Location: index.php');
    exit;
}
