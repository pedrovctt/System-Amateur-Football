<?php

/**
 * Prüft, ob die angegebene Stadt/Region existiert.
 * @param string $teamStadt
 * @return bool wenn etwas gefunden wird: true, wenn nicht: false
 */

function stadtExistiert(string $teamStadt): bool
{
    // URL der API mit dem Städtenamen aus dem Formular
    $url = 'https://nominatim.openstreetmap.org/search?city=' . urlencode($teamStadt) . '&format=json';

    // Nominatim API verlangt einen User-Agent HTTP Header der informiert wer die Request macht.
    $contextOptions =
        [
            "http" => [
                "header" => "User-Agent: Ranking-System-für-Amateur-Fußballteams/1.0\r\n"
            ]
        ];

    $context = stream_context_create($contextOptions);

    // API Request wird ausgeführt und die Antwort in $antwort gespeichert.
    $antwort = file_get_contents($url, false, $context);

    // JSON Inhalt wird über $antwort 'gegeben' und übersetzt. Wird in $daten gespeichert.
    $daten = json_decode($antwort, true);

    if (!empty($daten)) 
    {
        return true;
    }
    return false;
}

/**
 * Prüft, ob das Jahr gültig ist.
 * @param int $jahr;
 * @return bool Wenn Jahr gültig: true, wenn nicht: false
 */
function istJahrGueltig(int $jahr): bool
{
    // (Aktuelles Jahr ermitteln)
    $aktJahr = date("Y");
    $aktJahr = (int) $aktJahr;

    if (($jahr > $aktJahr || $jahr < 1800) || strlen((string)$jahr) != 4) 
    {
        return false;
    }
    return true;
}

/**
 * Prüft ob die Anzahl Tore in einem Spiel gültig ist
 * @param int $anzahlToreHeim
 * @param int $anzahlToreAuswaerts
 */

function istAnzahlToreGueltig(int $anzahlToreHeim, int $anzahlToreAuswaerts): bool
{
    if(($anzahlToreHeim < 0 || $anzahlToreHeim > 30) || ($anzahlToreAuswaerts < 0 || $anzahlToreAuswaerts > 30))
    {
        return false;
    }
    return true;

    /* Da es sich um Amateurfußball handelt, können Spiele auch mal viele Tore haben. 
       TODO In Zukunft könnte hier aber eine manuelle Überprüfung eingeführt werden, 
       wenn ein Team mehr als 12–15 Tore erzielt. Dann müsste ein Admin das Ergebnis 
       bestätigen oder korrigieren. */

}

/**
 * Prüft, ob das eingebene Team in Tabelle teams existiert.
 * @param string $team
 * @return bool Wenn eingegebens Team in der Tabelle existiert: true, wenn nicht: false
 */

function istTeamExistent(string $team): bool
{
    global $verbindung;

    $sql_abfrage = "SELECT NAME FROM teams WHERE NAME LIKE '%" . $team . "%'";
    $result = mysqli_query($verbindung, $sql_abfrage)
              or die("Fehler bei der Teamexistenzüberprüfung!" . mysqli_error($verbindung));

    if(mysqli_num_rows($result) == 0)
    {
        return false;
        mysqli_free_result($result);
    }
    return true;
    mysqli_free_result($result);
}
?>