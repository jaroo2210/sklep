<?php
$f_nazwa = $_POST["f_nazwa"];
$f_ilosc = $_POST["f_ilosc"];
$f_kolor = $_POST["f_kolor"];
$f_cena = $_POST["f_cena"];
$f_user_id = $_POST["f_user_id"]; // New field for user ID

include 'dbconfig.php';
$baza = mysqli_connect($server, $user, $pass, $base) or die('cos nie tak z polaczenie z BD');

$zapytanie = "INSERT INTO `towary` (`nazwa`, `ilosc`, `kolor`, `cena`, `user_id`) VALUES ('$f_nazwa', '$f_ilosc', '$f_kolor', '$f_cena', '$f_user_id')";
$result = $baza->query($zapytanie) or die('bledne zapytanie: ' . $baza->error);

$baza->close();
echo "<br><center> Towar dodany</center>";
?>
<script>
setTimeout(function() {
    window.location.href = "/sklep-main/?page=showtowary";
}, 2000); 
</script>