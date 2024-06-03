<?php
require_once "dbconfig.php";

function addTowary() { 
    global $baza;
    $cartData = json_decode(file_get_contents('php://input'), true);
    if ($cartData !== null) {
        foreach ($cartData as $item) {
            $nazwa = $item['name'];
            $ilosc = $item['quantity'];
            $kolor = $item['color'];
            $rozmiar = $item['size']; // Pobierz rozmiar z danych
            $cena = $item['totalPrice']; // Pobierz cenę jednostkową
            $user_id = $item['userId'];

            $insertQuery = "INSERT INTO `towary` (`nazwa`, `ilosc`, `kolor`, `rozmiar`, `cena`, `user_id`) VALUES ('$nazwa', '$ilosc', '$kolor', '$rozmiar', '$cena', $user_id);";
            $result = $baza->query($insertQuery) or die ('bledne zapytanie: ' . $baza->error);
        }
    }
}

function showTowary() {
    global $baza;
    $zapytanie = "SELECT towary.id, towary.nazwa, towary.ilosc, towary.kolor, towary.rozmiar, towary.cena, users.username FROM towary INNER JOIN users ON towary.user_id=users.id WHERE towary.user_id=".$_SESSION['user_id'].";";
    $result = $baza->query($zapytanie) or die('bledne zapytanie');
    return $result;
}

function showTowaryAdmin() {
    global $baza;
    $zapytanie = "SELECT towary.id, towary.nazwa, towary.ilosc, towary.kolor, towary.rozmiar, towary.cena, users.username FROM towary INNER JOIN users ON towary.user_id=users.id";
    $result = $baza->query($zapytanie) or die('bledne zapytanie');
    return $result;
}

function deleteSelectedTowary($ids) {
    global $baza;
    $ids = implode(",", array_map('intval', $ids));
    $deleteQuery = "DELETE FROM towary WHERE id IN ($ids)";
    if ($baza->query($deleteQuery)) {
        echo "<p>DEBUG: Pomyślnie usunięto wybrane towary</p>";
    } else {
        echo "<p>DEBUG: Błąd przy usuwaniu towarów: " . $baza->error . "</p>";
    }
}
?>
