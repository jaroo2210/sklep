<?php

// Sprawdź, czy użytkownik jest zalogowany
if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php'; // Include your database connection file

// Połączenie z bazą danych
$baza = mysqli_connect($server, $user, $pass, $base) or die('cos nie tak z polaczeniem z BD');

// Przetwarzanie danych z formularza infoklient.php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['imie_klienta'], $_POST['adres_klienta'], $_POST['telefon_klienta'])) {
    $imie_klienta = mysqli_real_escape_string($baza, $_POST['imie_klienta']);
    $adres_klienta = mysqli_real_escape_string($baza, $_POST['adres_klienta']);
    $telefon_klienta = mysqli_real_escape_string($baza, $_POST['telefon_klienta']);
    $user_id = $_SESSION['user_id'];

    $insertQuery = "INSERT INTO klienci (nazwa, adres, telefon, user_id) VALUES ('$imie_klienta', '$adres_klienta', '$telefon_klienta', '$user_id')";
    if ($baza->query($insertQuery)) {
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
        header("Location: $baseUrl/?page=showtowary");
        exit();
    } else {
        echo "Błąd przy dodawaniu klienta: " . $baza->error . "</p>";
    }
}

// Process deletion request
if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    $deleteQuery = "DELETE FROM klienci WHERE id={$deleteId}";
    if ($baza->query($deleteQuery)) {
        echo "<p>DEBUG: Pomyślnie usunięto klienta o ID: $deleteId</p>";
    } else {
        echo "<p>DEBUG: Błąd przy usuwaniu klienta: " . $baza->error . "</p>";
    }
    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
    header("Location: $baseUrl/?page=showklienci");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Klientów</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h3>Lista Klientów</h3>
        <hr>
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Lp.</th>
                    <th>Imię i Nazwisko</th>
                    <th>Adres firmy</th>
                    <th>Telefon</th>
                    <th>User</th>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <th>Opcje</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                // Join klienci table with users table to get the username
                $zapytanie = "SELECT klienci.*, users.username FROM klienci LEFT JOIN users ON klienci.user_id = users.id ORDER BY nazwa ASC";
                $result = $baza->query($zapytanie) or die('bledne zapytanie');

                $n = 0;

                while ($wiersz = $result->fetch_assoc()) {
                    $n++;
                    echo "<tr>
                            <td>$n</td>
                            <td>{$wiersz['nazwa']}</td>
                            <td>{$wiersz['adres']}</td>
                            <td>{$wiersz['telefon']}</td>
                            <td>{$wiersz['username']}</td>";
                    if ($_SESSION['user_type'] === 'admin') {
                        echo "<td>
                                <a href='edit_klient.php?id={$wiersz['id']}'>Edytuj</a> |
                                <a href='?page=showklienci&delete={$wiersz['id']}'>Usuń</a>
                              </td>";
                    }
                    echo "</tr>";
                }

                $baza->close();
                ?>
            </tbody>
        </table>
        <hr>
        <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <h4>Dodaj klienta</h4>
            <form method="POST" action="showklienci.php">
                Imię i Nazwisko klienta: <input type="text" name="imie_klienta" placeholder="imie" autocomplete="off"><br>
                Adres klienta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="adres_klienta" placeholder="wpisz adres" autocomplete="off"><br>
                Telefon klienta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="telefon_klienta" placeholder="telefon do klienta" autocomplete="off"><br>
                <button type="submit" id="button1">DODAJ</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
