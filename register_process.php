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
    // Dane rejestracji
    $username = mysqli_real_escape_string($baza, $_POST['username']);
    $password = mysqli_real_escape_string($baza, $_POST['password']);
    // Przetwarzanie innych danych rejestracji

    // Polecenie SQL do sprawdzenia, czy użytkownik o danej nazwie już istnieje
    $query = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($baza, $query);

    // Sprawdzanie, czy użytkownik już istnieje w bazie danych
    if (mysqli_num_rows($result) > 0) {
        echo "Użytkownik o tej nazwie już istnieje";
    } else {
        // Dodawanie nowego użytkownika do bazy danych
        $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
        if (mysqli_query($baza, $insertQuery)) {
            echo "Rejestracja zakończona sukcesem";
        } else {
            echo "Błąd podczas rejestracji: " . mysqli_error($baza);
        }
    }
}

// Zamykanie połączenia z bazą danych
mysqli_close($baza);
?>
