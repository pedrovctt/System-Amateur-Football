<?php
/*
    @author: Pedro Donato
    @project: Ranking-System für Amateur-Fußballteams
    @version: 1.0
*/

// Verbindungsskript wird hier ein mal verknüpft
include 'connectionDB.php';

// Methodenskript wird hier ein mal verknüpft
include 'functions.php';

// Warnungen/Fehlermeldungen nicht anzeigen
error_reporting(E_ERROR | E_PARSE);

// Datenfelder der Tabelle 'teams'
$teamName = '';
$teamStadt = '';
$teamGruendungsjahr = '';

// Datenfelder der Tabelle 'spiele'
$teamHeim = '';
$toreHeim = '';
$teamAuswaerts = '';
$toreAuswaerts = '';

$formularAnzeigen = true;

// Feedback-Meldungen, je nach Formular eine Meldung per POST Sendung
$meldungTeamRegistrierung = '';
$meldungSpielRegistrierung = '';

// Wenn keine Daten per POST gesendet wurden
if(isset($_POST['DatenSendenTeams']))
{
    // Daten vom Formular 'Neues Team registrieren'
    $teamName = $_POST['TeamName'];
    $teamName = (string) $teamName;

    $teamStadt = $_POST['TeamStadt'];
    $teamStadt = (string) $teamStadt;

    $teamGruendungsjahr = $_POST['TeamGruendungsjahr'];
    $teamGruendungsjahr = (int) $teamGruendungsjahr;

    // DATENVALIDIERUNG
    // Existiert die Stadt vom Team?
    if (!stadtExistiert($teamStadt)) 
    {
        $meldungTeamRegistrierung = '<br><span class = "error">Stadt/Region existiert nicht!</span><br>';
        // die();
    }
    // Wenn das Jahr nicht gültig ist
    else if (!istJahrGueltig($teamGruendungsjahr)) 
    {
        $meldungTeamRegistrierung = '<br><span class = "error">Ungültiges Jahr!</span><br>';
        // die();
    } 
    // Wenn alles gültig ist
    else
    {
        // Hinzufügen der schon geprüften Felder in die Datenbank
        $sql_teamRegistrieren = "INSERT INTO teams (idTeam,NAME,STADT,GRUENDUNGSJAHR) VALUES (NULL,'$teamName','$teamStadt','$teamGruendungsjahr')";
        
        $result = mysqli_query($verbindung, $sql_teamRegistrieren)
            or die("Fehler beim Einfügen der Daten: " . mysqli_error($verbindung));

        $meldungTeamRegistrierung = '<br><span class="success">Team wurde erfolgreich registriert!</span><br>';
    }
}
else if(isset($_POST['DatenSendenSpiel']))
{
    $teamHeim = $_POST['TeamHeim'];
    $teamHeim = (string)$teamHeim;

    $teamAuswaerts = $_POST['TeamAuswaerts'];
    $teamAuswaerts = (string)$teamAuswaerts;

    $toreHeim = $_POST['ToreHeim'];
    $toreHeim = (int)$toreHeim;

    $toreAuswaerts = $_POST['ToreAuswaerts'];
    $toreAuswaerts = (int)$toreAuswaerts;

    $datumSpiel = $_POST['DatumSpiel'];
    $datumSpiel = (string)$datumSpiel;

    // DATENVALIDIERUNG
    // Sind die Teams schon in der Datenbank registriert?
    if((!istTeamExistent($teamHeim) || !istTeamExistent($teamAuswaerts)))
    {
        $meldungSpielRegistrierung = '<br><span class="error">Einer oder beide der angegebenen Teams sind nicht registriert!</span><br>';
        // die();
    }
    // Sind es überhaupt 2 verschiedene Teams?
    else if($teamHeim == $teamAuswaerts)
    {
        $meldungSpielRegistrierung = '<br><span class="error">Ein Team kann nicht gegen sich selbst spielen!</span><br>';
        // die();
    }
    // Ist die Anzahl der Tore gültig?
    else if(!istAnzahlToreGueltig($toreHeim, $toreAuswaerts))
    {
        $meldungSpielRegistrierung = '<br><span class="error">Ungültige Anzahl der Tore!</span><br>';
        // die();
    }
    // Wenn alles gültig ist
    else
    {
        $meldungSpielRegistrierung = '<br><span class="success">Das Spiel wurde erfolgreich registriert!</span><br>';

        $sql_spielRegistrieren = "INSERT INTO spiele (
        idSpiel, TEAM_HEIM_NAME, TEAM_HEIM_ID, TEAM_GAST_NAME, TEAM_GAST_ID, TORE_HEIM, TORE_GAST, DATUM)
        VALUES (NULL, '$teamHeim', NULL, '$teamAuswaerts', NULL, '$toreHeim', '$toreAuswaerts', '$datumSpiel')";

        $result = mysqli_query($verbindung, $sql_spielRegistrieren);
    }
}

// Formular anzeigen
if ($formularAnzeigen) {
?>
    <!DOCTYPE html>
    <html lang="de">

    <head>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="../css/styleRankingSystem.css">
        <meta name=" viewport" content="width=device-width, initial-scale=1.0">
        <title>Ranking Amateur Fußballteams</title>
        <style></style>

        <script>
            // JavaScript
        </script>

    </head>
    <body>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div>
                <h4>Eingeloggter User: <?php echo $db_username; ?></h4>
                <h2>Tabelle:</h2>
                ToDo
                <br>
                <table border = "1">
                    <tr>
                        <th>Team</th>
                        <th>Punkte</th>
                        <th>Spiele</th>
                        <th>Siegen</th>
                        <th>Unenschieden</th>
                        <th>Niederlagen</th>
                        <th>Tore</th>
                        <th>Tordifferenz</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>2</td>
                    </tr>
                </table>
            </div>

            <div>
                <h2 id='titelRegistrierungTeam'>Neues Team registrieren:</h2>

                <input type="text" name="TeamName" placeholder="Name des Teams" required>
                <span class="pflichtfeld">*</span>
                <br>
                <input type="text" name="TeamStadt" placeholder="Standort" required>
                <span class="pflichtfeld">*</span>
                <br>
                <input type="number" name="TeamGruendungsjahr" placeholder="Gründungsjahr" required>
                <span class="pflichtfeld">*</span>
                <br>
                <?php if ($meldungTeamRegistrierung != '') { echo $meldungTeamRegistrierung; } ?>
                <br>
                <input type="submit" name="DatenSendenTeams" value="Team Registrieren">
                <input type="reset" value="Zurücksetzen">
            </div>
        </form>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div>
                <h2 id="titelRegistrierungSpiele">Neues Spiel registrieren:</h2>
                <div>
                    Heim: <input type="text" name="TeamHeim" placeholder="Name des Teams" required>
                    <span class="pflichtfeld">*</span>

                    <input type="number" name="ToreHeim" placeholder="Tore" required>
                    <span class="pflichtfeld">*</span>
                </div>
                <div>
                    Auswärts: <input type="text" name="TeamAuswaerts" placeholder="Name des Teams" required>
                    <span class="pflichtfeld">*</span>

                    <input type="number" name="ToreAuswaerts" placeholder="Tore" required>
                    <span class="pflichtfeld">*</span>

                    <br>

                    Datum: <input type = "date" name = "DatumSpiel" required>
                    <span class="pflichtfeld">*</span>
                </div>
                <?php if ($meldungSpielRegistrierung != '') { echo $meldungSpielRegistrierung; } ?>
                <br>
                <input type="submit" name = "DatenSendenSpiel" value = "Spiel registrieren">
                <input type="reset" value = "Zurücksetzen">
            </div>
        </form>
        <div>
            <br><br><br><br><br>
            ToDo: Liste der Teams und Spiele <br>
        </div>
    </body>
    </html>
<?php
}