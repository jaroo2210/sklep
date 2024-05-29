<?php
session_start();
session_destroy(); // Zniszcz sesję, aby wylogować użytkownika

// Przekieruj użytkownika na stronę główną lub inną
header('Location: index.php');
?>
