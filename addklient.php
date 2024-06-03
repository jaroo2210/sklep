<?php
$f_nazwa = $_POST["f_nazwa"];
$f_adres = $_POST["f_adres"];
$f_telefon = $_POST["f_telefon"];
$f_user_id = $_POST["user_id"]; // New field for user ID

include 'dbconfig.php';
$baza = mysqli_connect($server, $user, $pass, $base) or die('cos nie tak z polaczenie z BD');

$zapytanie = "INSERT INTO `klienci` (`nazwa`, `adres`, `telefon`, `user_id`) VALUES ('$f_nazwa', '$f_adres', '$f_telefon', '$f_user_id')";
$result = $baza->query($zapytanie) or die('bledne zapytanie: ' . $baza->error);

$baza->close();
echo "<br><center> Klient dodany</center>";
?>
<script>
setTimeout(function() {
    window.location.href = "/sklep-main/?page=showklienci";
}, 2000); 
</script>
