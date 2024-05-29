<form method="post">
    <input type="text" name="username" placeholder="Nazwa użytkownika" required>
    <input type="email" name="email" placeholder="Adres email" required>
    <input type="password" name="password" placeholder="Hasło" required>
    <button type="submit">Zarejestruj</button>
</form>
<?php
if($_POST) {
    include 'functions.php';
    global $baza;
    // Sprawdzanie, czy formularz został przesłany
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Dane rejestracji
        $username = $baza->real_escape_string( (string) $_POST['username'] );
        $password = $baza->real_escape_string((string) $_POST['password'] );
        $email = $baza->real_escape_string( (string) $_POST['email'] );
        // Przetwarzanie innych danych rejestracji
    
        // Polecenie SQL do sprawdzenia, czy użytkownik o danej nazwie już istnieje
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = $baza->query($query);
        // Sprawdzanie, czy użytkownik już istnieje w bazie danych
        if ($result->num_rows > 0) {
            echo "Użytkownik o tej nazwie już istnieje";
        } else {
            // Dodawanie nowego użytkownika do bazy danych
            $insertQuery = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
            if ($baza->query($insertQuery)) {
                echo "Rejestracja zakończona sukcesem";
                header("Location: http://localhost/sklep-main/index.php?page=StronaGlowna");
                    exit();
            } else {
                echo "Błąd podczas rejestracji: " . mysqli_error($baza);
            }
        }
    }
    
    // Zamykanie połączenia z bazą danych
    mysqli_close($baza); 
}