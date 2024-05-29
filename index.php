<?php 
session_start(); 
include 'functions.php';
?>

<!DOCTYPE html>
<html lang="pl-PL">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bazy Danych - sklep</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/style.css">
    <style>
        body {
            padding-top: 56px;
        }
        #menu {
            background-color: #f8f9fa;
            height: 100vh;
        }
        #strona {
            padding: 20px;
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Hamazon</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
            <ul class="navbar-nav">
                <?php
                if (isset($_SESSION['user_type'])) {
                    echo '<li class="nav-item"><a class="nav-link" href="logout.php">Wyloguj</a></li>';
                } else {
                    echo '
                    <li class="nav-item"><a class="nav-link" href="login.php">Zaloguj</a></li>
                    <li class="nav-item"><a class="nav-link" href="register.php">Rejestracja</a></li>
                    ';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-2" id="menu">
            <h3>Menu</h3>
            <div class="btn-group-vertical w-100" role="group">
                <a class="menu btn btn-secondary w-100 mb-1" href="?page=StronaGlowna">Strona Główna</a>
                <a class="menu btn btn-secondary w-100 mb-1" href="?page=Produkty">Produkty</a>
                <?php 
                if (isset($_SESSION['user_type'])) {
                    echo '<a class="menu btn btn-secondary w-100 mb-1" href="?page=showtowary">Lista towarów</a>';
                    if ($_SESSION['user_type'] === 'admin') {
                        echo '<a class="menu btn btn-secondary w-100 mb-1" href="?page=showklienci">Lista klientów</a>';
                    }
                }
                ?>
            </div>
        </div>

        <div class="col-md-10" id="strona">
            <?php
            if (isset($_GET['page'])) {
                include $_GET['page'] . ".php";
            }
            ?>
        </div>
    </div>
</div>

<script src="./js/bootstrap.bundle.min.js"></script>
<script src="./js/jq.js"></script>
<script src="./js/app.js"></script>
</body>

</html>
