<!-- login.php -->

<?php
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany
if (isset($_SESSION['user_type'])) {
    // Jeśli użytkownik jest zalogowany, wyświetl przycisk wylogowania
    echo '<a href="logout.php">Wyloguj</a>';
} else {
    // Jeśli użytkownik nie jest zalogowany, wyświetl formularz logowania
    echo '
    <form method="post" action="login_process.php">
        <!-- Tutaj dodaj pola formularza logowania (np. nazwa użytkownika i hasło) -->
        <input type="text" name="username" placeholder="Nazwa użytkownika">
        <input type="password" name="password" placeholder="Hasło">
        <button type="submit">Zaloguj</button>
    </form>
    ';
}
?>
