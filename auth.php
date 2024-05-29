<?php
session_start();

// Funkcja sprawdzająca, czy użytkownik jest zalogowany
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Funkcja sprawdzająca, czy zalogowany użytkownik jest administratorem
function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

// Funkcja przekierowująca na stronę logowania, jeśli użytkownik nie jest zalogowany
function redirectToLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit();
    }
}

// Funkcja przekierowująca na stronę główną po zalogowaniu
function redirectToHome() {
    header('Location: index.php');
    exit();
}
?>
