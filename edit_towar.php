<?php
session_start();
if ($_SESSION['user_type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php'; // Include your database connection file

$towar = [];
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM towary WHERE id=$id";
    $result = $baza->query($query);

    if ($result->num_rows == 1) {
        $towar = $result->fetch_assoc();
    } else {
        echo "Towar nie znaleziony.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id']);
    $nazwa = $_POST['nazwa'];
    $ilosc = $_POST['ilosc'];
    $kolor = $_POST['kolor'];
    $cena = $_POST['cena'];

    $updateQuery = "UPDATE towary SET nazwa='$nazwa', ilosc='$ilosc', kolor='$kolor', cena='$cena' WHERE id=$id";
    if ($baza->query($updateQuery)) {
        // Redirect to the specified page after a successful update
        header("Location: http://localhost/sklep-main/index.php?page=showtowary");
        exit();
    } else {
        echo "Błąd przy aktualizacji towaru: " . $baza->error;
    }
}

$baza->close();
?>

<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Towar</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h3>Edytuj Towar</h3>
        <form method="POST" action="edit_towar.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($towar['id'] ?? ''); ?>">
            Nazwa produktu: <input type="text" name="nazwa" value="<?php echo htmlspecialchars($towar['nazwa'] ?? ''); ?>" autocomplete="off"><br>
            Ilość produktu:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="ilosc" value="<?php echo htmlspecialchars($towar['ilosc'] ?? ''); ?>" autocomplete="off"><br>
            Kolor produktu:&nbsp;&nbsp;&nbsp; <input type="text" name="kolor" value="<?php echo htmlspecialchars($towar['kolor'] ?? ''); ?>" autocomplete="off"><br>
            Cena produktu:&nbsp;&nbsp;&nbsp; <input type="text" name="cena" value="<?php echo htmlspecialchars($towar['cena'] ?? ''); ?>" autocomplete="off"><br>
            <button type="submit" id="button1">ZAPISZ</button>
        </form>
    </div>
</body>

</html>
