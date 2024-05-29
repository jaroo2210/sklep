<?php
include 'dbconfig.php';

// Nawiązanie połączenia z bazą danych
$baza = mysqli_connect($server, $user, $pass, $base);

// Sprawdzenie poprawności połączenia
if (!$baza) {
    die("Błąd połączenia z bazą danych: " . mysqli_connect_error());
}

// Sprawdzanie, czy formularz został przesłany
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dane logowania
    $username = mysqli_real_escape_string($baza, $_POST['username']);
    $password = mysqli_real_escape_string($baza, $_POST['password']);

    // Sprawdzenie, czy użytkownik próbuje zalogować się jako admin
    if ($username === 'admin' && $password === 'admin') {
        // Ustawienie sesji lub innych zmiennych sesyjnych w celu oznaczenia użytkownika jako admina
        session_start();
        $_SESSION['user_type'] = 'admin';

        // Przekierowanie użytkownika do odpowiedniej strony po zalogowaniu
        header("Location: index.php");
        exit();
    } else {
        // Polecenie SQL do sprawdzenia poprawności danych logowania dla zwykłych użytkowników
        $query = "SELECT id, username FROM users WHERE username='$username' AND password='$password'";
        $result = mysqli_query($baza, $query);
        $id = $result->fetch_assoc()['id'];

        // Sprawdzanie, czy użytkownik istnieje w bazie danych
        if (mysqli_num_rows($result) > 0) {
            // Ustawienie sesji lub innych zmiennych sesyjnych w celu oznaczenia użytkownika jako zalogowanego
            session_start();
            $_SESSION['user_type'] = 'user';
            $_SESSION['user_id'] = $id; 

            // Przekierowanie użytkownika na stronę główną lub inną stronę po zalogowaniu
            header("Location: index.php");
            exit();
        } else {
            // Jeśli dane logowania są nieprawidłowe, przekieruj użytkownika z powrotem do formularza logowania z komunikatem
            header("Location: login.php?error=invalid_credentials");
            exit();
        }
    }
}

// Zamykanie połączenia z bazą danych
mysqli_close($baza);
?>
