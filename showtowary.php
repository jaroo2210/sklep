<?php


if (!isset($_SESSION['user_type'])) {
    header("Location: login.php");
    exit();
}

require_once "functions.php";

// Przetwarzanie formularza usuwania zaznaczonych towarów
if (isset($_POST['delete_selected'])) {
    if (!empty($_POST['delete_ids'])) {
        deleteSelectedTowary($_POST['delete_ids']);
        $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
        header("Location: $baseUrl/?page=showtowary");
        exit();
    } else {
        $deleteError = "Nie wybrano żadnych towarów do usunięcia";
    }
}

?>

<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista Towarów</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
</head>

<body>
    <div class="container">
        <h3>Lista Towarów</h3>
        <hr>
        <?php if (isset($deleteError)) echo "<p>DEBUG: $deleteError</p>"; ?>
        <form method="POST" action="">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <th><input type="checkbox" id="select-all"></th>
                        <?php endif; ?>
                        <th>Lp.</th>
                        <th>Nazwa</th>
                        <th>Ilość</th>
                        <th>Kolor</th>
                        <th>Rozmiar</th>
                        <th>Cena</th>
                        <th>User</th>
                        <?php if ($_SESSION['user_type'] === 'admin'): ?>
                            <th>Opcje</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    if ($_SESSION['user_type'] == "admin") {
                        $result = showTowaryAdmin();
                    } else {
                        $result = showTowary();  
                    }    
                    $n = 0;

                    while ($wiersz = $result->fetch_assoc()) {
                        $n++;
                        echo "<tr>";
                        if ($_SESSION['user_type'] === 'admin') {
                            echo "<td><input type='checkbox' name='delete_ids[]' value='{$wiersz['id']}'></td>";
                        }
                        echo "<td>$n</td>
                                <td>{$wiersz['nazwa']}</td>
                                <td>{$wiersz['ilosc']}</td>
                                <td>{$wiersz['kolor']}</td>
                                <td>{$wiersz['rozmiar']}</td>";

                        if (isset($wiersz['Cena'])) {
                            echo "<td>{$wiersz['Cena']}</td>";
                        } elseif (isset($wiersz['cena'])) {
                            echo "<td>{$wiersz['cena']}</td>";
                        } else {
                            echo "<td>Nieokreślona</td>";
                        }
                        echo "<td>{$wiersz['username']}</td>";

                        if ($_SESSION['user_type'] === 'admin') {
                            echo "<td>
                                    <a href='edit_towar.php?id={$wiersz['id']}'>Edytuj</a> 
                                    
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
            <h4>Dodawanie towaru</h4>
            <form method="POST" action="?page=addtowar">
                Nazwa produktu:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_nazwa" placeholder="nazwa" autocomplete="off"><br>
                Ilość produktu:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_ilosc" placeholder="wpisz ilość" autocomplete="off"><br>
                Kolor produktu:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_kolor" placeholder="kolor" autocomplete="off"><br>
                Rozmiar produktu:&nbsp;&nbsp;&nbsp; <input type="text" name="f_rozmiar" placeholder="rozmiar" autocomplete="off"><br>
                Cena produktu:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_cena" placeholder="cena" autocomplete="off"><br>
                ID użytkownika:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_user_id" placeholder="ID użytkownika" autocomplete="off"><br>
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
