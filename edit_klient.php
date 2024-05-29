<?php
session_start();
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php'; // Include your database connection file

$klient = [];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM klienci WHERE id=$id";
    $result = $baza->query($query);

    if ($result->num_rows == 1) {
        $klient = $result->fetch_assoc();
    } else {
        echo "Klient nie znaleziony.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nazwa = $_POST['nazwa_firmy'];
    $adres = $_POST['adres_firmy'];
    $telefon = $_POST['telefon_firmy'];

    $updateQuery = "UPDATE klienci SET nazwa='$nazwa', adres='$adres', telefon='$telefon' WHERE id=$id";
    if ($baza->query($updateQuery)) {
        // Redirect to the specified page after a successful update
        header("Location: http://localhost/sklep-main/index.php?page=showklienci");
        exit();
    } else {
        echo "Błąd przy aktualizacji klienta: " . $baza->error;
    }
}

$baza->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Klienta</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h3>Edytuj Klienta</h3>
        <form method="POST" action="edit_klient.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($klient['id'] ?? ''); ?>">
            Nazwa firmy: <input type="text" name="nazwa_firmy" value="<?php echo htmlspecialchars($klient['nazwa'] ?? ''); ?>" autocomplete="off"><br>
            Adres firmy: <input type="text" name="adres_firmy" value="<?php echo htmlspecialchars($klient['adres'] ?? ''); ?>" autocomplete="off"><br>
            Telefon: <input type="text" name="telefon_firmy" value="<?php echo htmlspecialchars($klient['telefon'] ?? ''); ?>" autocomplete="off"><br>
            <button type="submit" id="button1">ZAPISZ</button>
        </form>
    </div>
</body>

</html>
