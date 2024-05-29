<?php
    $f_nazwa = $_POST["f_nazwa"];
    $f_ilosc = $_POST["f_ilosc"];
    $f_kolor = $_POST["f_kolor"];
    $f_cena = $_POST["f_cena"];
    include 'dbconfig.php';
    $baza = mysqli_connect($server, $user, $pass, $base) or die ('cos nie tak z polaczenie z BD');

    $zapytanie = "INSERT INTO `towary` (`nazwa`, `ilosc`, `kolor`, `cena`) VALUES ('$f_nazwa', '$f_ilosc', '$f_kolor', '$f_cena')";
    $result = $baza->query($zapytanie) or die ('bledne zapytanie: ' . $baza->error);

    $baza->close();
    echo "<br><center> Towar dodany</center>";
?>
