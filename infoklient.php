<?php

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

    $insertQuery = "INSERT INTO klienci (nazwa, adres, telefon) VALUES ('$imie_klienta', '$adres_klienta', '$telefon_klienta')";
    if ($baza->query($insertQuery)) {
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
        header("Location: $baseUrl/?page=showtowary");
        exit();
    } else {
        echo "Błąd przy dodawaniu klienta: " . $baza->error . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nowa Strona Zamówienia</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h2>Wprowadź dane klienta</h2>
       
        <?php addTowary(); ?>
        <form action="?page=showklienci" method="POST">
            Imię i Nazwisko: <input type="text" name="imie_klienta"><br>
            Adres:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="adres_klienta"><br>
            Telefon:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="telefon_klienta"><br>
            <button type="submit">Zamów</button>
        </form>
    </div>
</body>

</html>
