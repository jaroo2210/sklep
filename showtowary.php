<?php

// Check if the user is logged in
if (!isset($_SESSION['user_type'])) {
    // If the user is not logged in, redirect to the login page or show an access error message
    header("Location: login.php");
    exit(); // Ensure that the redirection is done and no further code is executed
}

// Code for displaying the product list or customer list goes here
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
        <table class="table table-condensed">
            <thead>
                <tr>
                    <th>Lp.</th>
                    <th>Nazwa</th>
                    <th>Ilość</th>
                    <th>Kolor</th>
                    <th>Cena</th>
                    <th>User</th>
                    <?php if ($_SESSION['user_type'] === 'admin'): ?>
                        <th>Opcje</th>
                    <?php endif; ?>
                </tr>
            </thead>
            <tbody>
                <?php
                
                // $zapytanie = "SELECT * FROM towary ORDER BY nazwa ASC";

                // $result = $baza->query($zapytanie) or die('bledne zapytanie');
                if($_SESSION['user_type'] == "admin") {
                    $result = showTowaryAdmin();  
                } else {
                    $result = showTowary();  
                }    
                $n = 0;

                while ($wiersz = $result->fetch_assoc()) {
                    $n++;
                    echo "<tr>
                            <td>$n</td>
                            <td>{$wiersz['nazwa']}</td>
                            <td>{$wiersz['ilosc']}</td>
                            <td>{$wiersz['kolor']}</td>";

                    // Check if the price column is named "Cena" or "cena" - case insensitive
                    // Use the appropriate column name from the database
                    if (isset($wiersz['Cena'])) {
                        echo "<td>{$wiersz['Cena']}</td>";
                    } elseif (isset($wiersz['cena'])) {
                        echo "<td>{$wiersz['cena']}</td>";
                    } else {
                        echo "<td>Nieokreślona</td>"; // If price information is missing
                    }
                    echo "<td>{$wiersz['username']}</td>";

                    // Add delete and edit options for admin only
                    if ($_SESSION['user_type'] === 'admin') {
                        echo "<td>
                                <a href='edit_towar.php?id={$wiersz['id']}'>Edytuj</a> |
                                <a href='?page=showtowary&delete={$wiersz['id']}'>Usuń</a>
                              </td>";
                    }
                    echo "</tr>";
                }

                // Handle the deletion of a product
                if (isset($_GET['delete'])) {
                    $deleteId = intval($_GET['delete']);
                    $deleteQuery = "DELETE FROM towary WHERE id={$deleteId}";
                    if ($baza->query($deleteQuery)) {
                        echo "<p>DEBUG: Pomyślnie usunięto towar o ID: $deleteId</p>";
                    } else {
                        echo "<p>DEBUG: Błąd przy usuwaniu towaru: " . $baza->error . "</p>";
                    }
                    $baseUrl = "http://" . $_SERVER['HTTP_HOST'] . "/sklep-main";
                    header("Location: $baseUrl/?page=showtowary");
                    exit();
                }

                $baza->close();
                ?>
            </tbody>
        </table>
        <hr>
        <?php if ($_SESSION['user_type'] === 'admin'): ?>
            <h4>Dodawanie towaru</h4>
            <form method="POST" action="addtowar.php">
                Nazwa produktu: <input type="text" name="f_nazwa" placeholder="nazwa" autocomplete="off"><br>
                Ilość produktu:&nbsp;&nbsp;&nbsp;&nbsp; <input type="text" name="f_ilosc" placeholder="wpisz ilość" autocomplete="off"><br>
                Kolor produktu:&nbsp;&nbsp;&nbsp; <input type="text" name="f_kolor" placeholder="kolor" autocomplete="off"><br>
                Cena produktu:&nbsp;&nbsp;&nbsp; <input type="text" name="f_cena" placeholder="cena" autocomplete="off"><br>
                <button type="submit" id="button1">DODAJ</button>
            </form>
        <?php endif; ?>
    </div>
</body>

</html>
