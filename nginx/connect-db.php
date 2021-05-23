<?php
$mysqli = new mysqli('mysql', 'user', 'password', 'PRENOTAZIONI');
mysqli_set_charset($mysqli, "utf8");
if ($mysqli->connect_errno) {
    die('Connessione al db fallita: '. $mysqli->connect_error);
}
?>

