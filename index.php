<?php

 * Diese Datei legt einige Header für die Webseite fest, zum Beispiel den Etag-Header.
require 'funktionen-neu.php';

$file_last_mod_time = filemtime(__FILE__);
$content_last_mod_time = 7;
$etag = '"'.$file_last_mod_time.'.'.$content_last_mod_time.'"';
// fügt Header mit Datum und Uhrzeit mit der letzten Änderung des Dokuments hinzu
echo header("Last-Modified: ".date("D, d M Y H:i:s")."GMT");
/* fügt Header hinzu: Antworten vom Server müssen im Cache aufgehoben werden und können für
* die nächsten 24 Stunden wiederverwendet werden
*/
echo header("Cache-control: max-age=84600");
/*
* fügt Header mit eTag hinzu: ein unverwechselbares Merkmal für den Inhalt der Seite.
*/
echo header('ETag: '.$etag);

echo <<<EOT
<head> 
<style type="text/css"> @import "./css/normalize.css";</style>
<style type="text/css"> @import "./css/stylingcss.css";</style>
</head>
EOT;


/**
 * wenn in der Variable $_SERVER das Feld 'HTTP_IF_NONE_MATCH' existiert und
 * identisch mit dem Etag der Anfrage ist, wird der Header der Antwort auf 304 Not Modified gesetzt,
 * die Daten haben sich nicht verändert und müssen nicht noch neu geschickt werden.
 * Die Seite kann aus dem Cache geladen werden.
 */
if(isset($_SERVER['HTTP_IF_NONE_MATCH'])) {
    if($_SERVER['HTTP_IF_NONE_MATCH'] == $etag) {
        header('HTTP/1.1 304 Not Modified', true, 304);
        //exit();
    }
}


/**
 * diese Abfrage schaut in die Variable $_POST. Wenn dort noch keine Daten drin sind, bedeutet das, dass die Seite
 * zum ersten Mal aufgerufen wird. Es wird ein Array mit Html-Tags für die Formularfelder erzeugt und der Funktion
 * übergeben, die die Html-Seite mit dem Formular für die erste Seite erzeugt, übergeben.
 */
if (count($_POST) == 0) {

    $leereFelder = [
        '<label for="urlaubsziel-1">Urlaubsziel 1:</label><input type="text" name="urlaubsziel-1."/>'.'<br>',
        '<label for="urlaubsziel-2">Urlaubsziel 2:</label><input type="text" name="urlaubsziel-2."/>'.'<br>',
        '<label for="urlaubsziel-2">Urlaubsziel 3:</label><input type="text" name="urlaubsziel-3."/>'.'<br>',
    ];

    erstelleFormularSeite1($leereFelder);

}


/** im Array $_POST sind schon Daten drin, vielleicht existiert sogar das Feld 'name' schon.
 * die Funktion, die die Daten prüft und bei Korrektheit die dritte Seite des Formulars darstellt,
 * wird aufgerufen
 * ist das Feld 'name' noch nicht vorhanden, aber im Array $_POST ist schon etwas drin, wird die Funktion, die diese Daten
 * überprüft und bei Korrektheit die zweite Seite des Formulars aufruft, ausgeführt
*/
else if (!isset($_POST['name'])) {
    pruefeReiseziele($_POST);
} else {
    pruefeKontaktdaten($_POST);
}




