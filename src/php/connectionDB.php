<?php
// Login Daten für die Datenbankverbindung
$db_host = 'localhost';
$db_username = 'pedro';
$db_passwort = 'password';
$database = 'ranking_system_fussball';

// Verbindungsherstellung
$verbindung = mysqli_connect($db_host, $db_username, $db_passwort, $database)
    or die("Verbindungsfehler: ".  mysqli_connect_error());
?>