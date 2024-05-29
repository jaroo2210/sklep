<?php
require_once "dbconfig.php";
function addTowary() { 
    global $baza;
    $cartData = json_decode(file_get_contents('php://input'), true);
    // Sprawdź, czy dane z koszyka nie są nullem
    print_r($cartData);
    if ($cartData !== null) {
        // Iteruj po danych koszyka
        foreach ($cartData as $item) {
            // Przetwarzaj dane koszyka i zapisuj do bazy danych
            $nazwa = $item['name'];
            $ilosc = $item['quantity'];
            $kolor = $item['color'];
            $cena = $item['totalPrice']; // Pobierz cenę jednostkową
            $user_id = $item['userId'];

            // Zapisz dane do bazy danych
            $insertQuery = "INSERT INTO `towary` (`nazwa`, `ilosc`, `kolor`, `cena`, `user_id`) VALUES ('$nazwa', '$ilosc', '$kolor', '$cena', $user_id);";
            $result = $baza->query($insertQuery) or die ('bledne zapytanie: ' . $baza->error);
        }
    }
}
function showTowary() {
    global $baza;
    $zapytanie = "SELECT towary.nazwa, towary.ilosc, towary.kolor, towary.cena, users.username FROM towary INNER JOIN users ON towary.user_id=users.id WHERE towary.user_id=".$_SESSION['user_id'].";";
    $result = $baza->query($zapytanie) or die('bledne zapytanie');
    // echo'<pre>';print_r($result->fetch_assoc());echo '</pre>';
    return $result;
}
function showTowaryAdmin() {
    global $baza;
    $zapytanie = "SELECT towary.id, towary.nazwa, towary.ilosc, towary.kolor, towary.cena, users.username FROM towary INNER JOIN users ON towary.user_id=users.id";
    $result = $baza->query($zapytanie) or die('bledne zapytanie');
    // echo'<pre>';print_r($result->fetch_assoc());echo '</pre>';
    return $result;
}
?>