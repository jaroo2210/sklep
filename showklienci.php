<?php

if ($_SESSION['user_type'] == "admin"):

if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

include 'dbconfig.php';

$baza = mysqli_connect($server, $user, $pass, $base) or die('cos nie tak z polaczeniem z BD');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['imie_klienta'], $_POST['adres_klienta'], $_POST['telefon_klienta'], $_POST['user_id'])) {
    $imie_klienta = mysqli_real_escape_string($baza, $_POST['imie_klienta']);
    $adres_klienta = mysqli_real_escape_string($baza, $_POST['adres_klienta']);
    $telefon_klienta = mysqli_real_escape_string($baza, $_POST['telefon_klienta']);
    $user_id = mysqli_real_escape_string($baza, $_POST['user_id']); // New field for user ID

    $insertQuery = "INSERT INTO klienci (nazwa, adres, telefon, user_id) VALUES ('$imie_klienta', '$adres_klienta', '$telefon_klienta', '$user_id')";
    if ($baza->query($insertQuery)) {
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
        header("Location: $baseUrl/?page=showklienci");
        exit();
    } else {
        echo "Błąd przy dodawaniu klienta: " . $baza->error . "</p>";
    }
}

if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['delete_ids'])) {
        $deleteIds = implode(',', array_map('intval', $_POST['delete_ids']));
        $deleteQuery = "DELETE FROM klienci WHERE id IN ($deleteIds)";
        if ($baza->query($deleteQuery)) {
            echo "<p>DEBUG: Pomyślnie usunięto klientów o ID: $deleteIds</p>";
        } else {
            echo "<p>DEBUG: Błąd przy usuwaniu klientów: " . $baza->error . "</p>";
        }
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
        header("Location: $baseUrl/?page=showklienci");
        exit();
    } else {
        echo "<p>DEBUG: Nie wybrano żadnych klientów do usunięcia</p>";
    }
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
        <form method="POST" action="">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <th><input type="checkbox" id="select-all"></th>
                        <?php endif; ?>
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
                    $zapytanie = "SELECT klienci.*, users.username FROM klienci LEFT JOIN users ON klienci.user_id = users.id ORDER BY nazwa ASC";
                    $result = $baza->query($zapytanie) or die('bledne zapytanie');

                    $n = 0;

                    while ($wiersz = $result->fetch_assoc()) {
                        $n++;
                        echo "<tr>";
                        if ($_SESSION['user_type'] === 'admin') {
                            echo "<td><input type='checkbox' name='delete_ids[]' value='{$wiersz['id']}'></td>";
                        }
                        echo "<td>$n</td>
                                <td>{$wiersz['nazwa']}</td>
                                <td>{$wiersz['adres']}</td>
                                <td>{$wiersz['telefon']}</td>
                                <td>{$wiersz['username']}</td>";
                        if ($_SESSION['user_type'] === 'admin') {
                            echo "<td>
                                    <a href='edit_klient.php?id={$wiersz['id']}'>Edytuj</a> 
                                  </td>";
                        }
                        echo "</tr>";
                    }

                    $baza->close();
                    ?>
                </tbody>
            </table>
            <?php if ($_SESSION['user_type'] === 'admin'): ?>
                <button type="submit" name="delete_selected" class="btn btn-danger">Usuń zaznaczone</button>
            <?php endif; ?>
        </form>
        <hr>
        <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <h4>Dodaj klienta</h4>
            <form method="POST" action="?page=showklienci">
                Imię i Nazwisko klienta:&nbsp;&nbsp; <input type="text" name="imie_klienta" placeholder="imie" autocomplete="off"><br>
                Adres klienta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="adres_klienta" placeholder="wpisz adres" autocomplete="off"><br>
                Telefon klienta:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="telefon_klienta" placeholder="telefon do klienta" autocomplete="off"><br>
                ID użytkownika:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="user_id" placeholder="ID użytkownika" autocomplete="off"><br>
                <button type="submit" id="button1">DODAJ</button>
            </form>
        <?php endif; ?>
    </div>
    <script>
        document.getElementById('select-all').onclick = function() {
            var checkboxes = document.getElementsByName('delete_ids[]');
            for (var checkbox of checkboxes) {
                checkbox.checked = this.checked;
            }
        }
    </script>
</body>

</html>
<?php endif; ?>